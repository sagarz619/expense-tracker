<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Monthly Summary
        $monthlyIncome = Transaction::income()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $monthlyExpense = Transaction::expense()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $monthlyNet = $monthlyIncome - $monthlyExpense;

        // Category-wise Expense Breakdown
        $expenseByCategory = Transaction::expense()
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->get();

        // Category-wise Income Breakdown
        $incomeByCategory = Transaction::income()
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->get();

        // Daily Expense Trend (Last 30 days)
        $dailyExpense = Transaction::expense()
            ->whereBetween('date', [$startDate, $endDate])
            ->select(DB::raw('DATE(date) as day'), DB::raw('SUM(amount) as total'))
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();

        // Account-wise Balance
        $accounts = Account::active()->get();

        // Top 5 Expense Categories
        $topExpenseCategories = $expenseByCategory->take(5);

        return view('reports.index', compact(
            'monthlyIncome',
            'monthlyExpense',
            'monthlyNet',
            'expenseByCategory',
            'incomeByCategory',
            'dailyExpense',
            'accounts',
            'topExpenseCategories',
            'month'
        ));
    }

    public function export(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        $query = Transaction::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $filename = 'report_' . $month . '_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(new TransactionsExport($query), $filename);
    }
}
