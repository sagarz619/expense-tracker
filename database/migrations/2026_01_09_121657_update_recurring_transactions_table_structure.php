<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['name', 'type', 'transaction_type', 'next_due_date', 'auto_generate']);

            // Add new columns
            $table->enum('type', ['expense', 'income', 'transfer'])->default('expense')->after('id');
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->onDelete('set null')->after('account_id');
            $table->text('description')->nullable()->after('amount');
            $table->date('next_occurrence')->after('end_date');

            // Update frequency enum to match controller
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn(['type', 'to_account_id', 'description', 'next_occurrence']);

            // Add back old columns
            $table->string('name');
            $table->enum('type', ['emi', 'subscription', 'investment'])->default('subscription');
            $table->enum('transaction_type', ['expense', 'income'])->default('expense');
            $table->date('next_due_date');
            $table->boolean('auto_generate')->default(false);

            // Revert frequency enum
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly')->change();
        });
    }
};
