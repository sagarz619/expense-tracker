<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'recurring:process';
    protected $description = 'Process due recurring transactions and create actual transactions';

    public function handle()
    {
        $this->info('Processing recurring transactions...');

        $dueRecurring = RecurringTransaction::dueForProcessing()->get();

        if ($dueRecurring->isEmpty()) {
            $this->info('No recurring transactions due for processing.');
            return 0;
        }

        $processed = 0;
        $failed = 0;

        foreach ($dueRecurring as $recurring) {
            try {
                DB::beginTransaction();

                // Create the actual transaction
                $transaction = Transaction::create([
                    'type' => $recurring->type,
                    'amount' => $recurring->amount,
                    'date' => now(),
                    'description' => $recurring->description . ' (Auto-generated)',
                    'account_id' => $recurring->account_id,
                    'category_id' => $recurring->category_id,
                    'to_account_id' => $recurring->to_account_id,
                    'recurring_transaction_id' => $recurring->id,
                ]);

                // Update account balances
                $this->updateAccountBalances($transaction);

                // Calculate and update next occurrence
                $nextOccurrence = $this->calculateNextOccurrence(
                    $recurring->next_occurrence,
                    $recurring->frequency
                );

                $recurring->update([
                    'next_occurrence' => $nextOccurrence
                ]);

                // Check if this was the last occurrence
                if ($recurring->end_date && $nextOccurrence->greaterThan($recurring->end_date)) {
                    $recurring->update(['is_active' => false]);
                    $this->info("Recurring transaction #{$recurring->id} has reached its end date and was deactivated.");
                }

                DB::commit();
                $processed++;
                $this->info("Processed: {$recurring->category->name ?? 'Transfer'} - ₹{$recurring->amount}");
            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                $this->error("Failed to process recurring transaction #{$recurring->id}: " . $e->getMessage());
            }
        }

        $this->info("Processed: {$processed} | Failed: {$failed}");
        return 0;
    }

    private function updateAccountBalances(Transaction $transaction)
    {
        $account = Account::find($transaction->account_id);

        switch ($transaction->type) {
            case 'expense':
                $account->decrement('current_balance', $transaction->amount);
                break;

            case 'income':
                $account->increment('current_balance', $transaction->amount);
                break;

            case 'transfer':
                $account->decrement('current_balance', $transaction->amount);
                $toAccount = Account::find($transaction->to_account_id);
                $toAccount->increment('current_balance', $transaction->amount);
                break;
        }
    }

    private function calculateNextOccurrence($currentDate, $frequency)
    {
        $current = Carbon::parse($currentDate);

        switch ($frequency) {
            case 'daily':
                return $current->addDay();
            case 'weekly':
                return $current->addWeek();
            case 'monthly':
                return $current->addMonth();
            case 'yearly':
                return $current->addYear();
            default:
                return $current->addDay();
        }
    }
}
