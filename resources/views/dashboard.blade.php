<x-mobile-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <!-- Total Balance Summary -->
    <div class="card-dark text-center">
        <div class="text-muted mb-2">Total Balance</div>
        <h2 class="display-4 fw-bold mb-0" style="color: var(--accent-green);">
            ₹{{ number_format($totalBalance, 2) }}
        </h2>
        <small class="text-muted">Across all accounts</small>
    </div>

    <!-- Accounts Snapshot -->
    <div class="mb-4">
        <h5 class="mb-3">Accounts</h5>
        <div class="row g-3">
            @forelse($accounts as $account)
                <div class="col-6">
                    <div class="card-dark">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-{{ $account->icon }} me-2" style="color: {{ $account->color }}; font-size: 1.5rem;"></i>
                            <h6 class="mb-0">{{ $account->name }}</h6>
                        </div>
                        <div class="h5 mb-0" style="color: {{ $account->color }};">
                            ₹{{ number_format($account->current_balance, 2) }}
                        </div>
                        <small class="text-muted">{{ ucfirst($account->type) }}</small>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card-dark text-center text-muted">
                        <i class="fas fa-wallet fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">No accounts found</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="mb-4">
        <h5 class="mb-3">This Month</h5>
        <div class="card-dark">
            <div class="row text-center">
                <div class="col-6">
                    <div class="transaction-income">
                        <i class="fas fa-arrow-up fa-2x mb-2"></i>
                        <div class="h5 mb-0">₹{{ number_format($monthlyIncome, 2) }}</div>
                        <small class="text-muted">Income</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="transaction-expense">
                        <i class="fas fa-arrow-down fa-2x mb-2"></i>
                        <div class="h5 mb-0">₹{{ number_format($monthlyExpense, 2) }}</div>
                        <small class="text-muted">Expense</small>
                    </div>
                </div>
            </div>
            <hr style="border-color: var(--border-color);">
            <div class="text-center">
                <div class="text-muted mb-1">Net</div>
                <div class="h4 mb-0 {{ ($monthlyIncome - $monthlyExpense) >= 0 ? 'transaction-income' : 'transaction-expense' }}">
                    ₹{{ number_format($monthlyIncome - $monthlyExpense, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-4">
        <h5 class="mb-3">Quick Actions</h5>
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ route('recurring-transactions.index') }}" class="card-dark d-block text-center text-decoration-none">
                    <i class="fas fa-repeat text-primary fa-2x mb-2"></i>
                    <div class="small">Recurring</div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('reports.index') }}" class="card-dark d-block text-center text-decoration-none">
                    <i class="fas fa-chart-pie text-success fa-2x mb-2"></i>
                    <div class="small">Reports</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Recent Transactions</h5>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>

        @forelse($recentTransactions as $transaction)
            <div class="card-dark mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $transaction->category->color ?? '#888' }}20; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $transaction->category->icon ?? 'question' }}" style="color: {{ $transaction->category->color ?? '#888' }};"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $transaction->category->name ?? 'Transfer' }}</div>
                            <small class="text-muted">
                                {{ $transaction->account->name }} • {{ $transaction->date->format('d M') }}
                            </small>
                            @if($transaction->description)
                                <div><small class="text-muted">{{ Str::limit($transaction->description, 30) }}</small></div>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold {{ $transaction->type === 'income' ? 'transaction-income' : ($transaction->type === 'expense' ? 'transaction-expense' : 'transaction-transfer') }}">
                            {{ $transaction->type === 'expense' ? '-' : '+' }}₹{{ number_format($transaction->amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark text-center text-muted">
                <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                <p class="mb-2">No transactions yet</p>
                <small>Tap the + button to add your first transaction</small>
            </div>
        @endforelse
    </div>

</x-mobile-layout>
