<x-mobile-layout>
    <x-slot name="header">
        Transactions
    </x-slot>

    <!-- Action Buttons -->
    <div class="row g-2 mb-3">
        <div class="col-8">
            <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter me-2"></i> Filters
                @if(request()->hasAny(['type', 'account_id', 'category_id', 'start_date', 'end_date']))
                    <span class="badge bg-success ms-2">Active</span>
                @endif
            </button>
        </div>
        <div class="col-4">
            <a href="{{ route('transactions.export', request()->query()) }}" class="btn btn-outline-success w-100">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </div>
    </div>

    <!-- Filter Form (Collapsible) -->
    <div class="collapse {{ request()->hasAny(['type', 'account_id', 'category_id', 'start_date', 'end_date']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card-dark mb-3">
            <form method="GET" action="{{ route('transactions.index') }}">
                <div class="row g-3">
                    <!-- Type Filter -->
                    <div class="col-12">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="transfer" {{ request('type') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>

                    <!-- Account Filter -->
                    <div class="col-12">
                        <label class="form-label">Account</label>
                        <select name="account_id" class="form-select">
                            <option value="">All Accounts</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-12">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-6">
                        <label class="form-label">From Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">To Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Apply
                        </button>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Transaction List -->
    @forelse($transactions as $transaction)
        <div class="card-dark mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center flex-grow-1">
                    <!-- Category Icon -->
                    <div class="me-3" style="width: 45px; height: 45px; border-radius: 50%; background-color: {{ $transaction->category->color ?? '#888' }}20; display: flex; align-items: center; justify-content: center;">
                        @if($transaction->type === 'transfer')
                            <i class="fas fa-exchange-alt" style="color: var(--transfer-color);"></i>
                        @else
                            <i class="fas fa-{{ $transaction->category->icon ?? 'question' }}" style="color: {{ $transaction->category->color ?? '#888' }};"></i>
                        @endif
                    </div>

                    <!-- Transaction Details -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">
                                    {{ $transaction->category->name ?? 'Transfer' }}
                                </div>
                                <small class="text-muted">
                                    {{ $transaction->account->name }}
                                    @if($transaction->type === 'transfer' && $transaction->toAccount)
                                        → {{ $transaction->toAccount->name }}
                                    @endif
                                </small>
                                @if($transaction->description)
                                    <div><small class="text-muted">{{ Str::limit($transaction->description, 40) }}</small></div>
                                @endif
                                <div><small class="text-muted">{{ $transaction->date->format('d M Y') }}</small></div>
                            </div>

                            <!-- Amount -->
                            <div class="text-end ms-3">
                                <div class="fw-bold {{ $transaction->type === 'income' ? 'transaction-income' : ($transaction->type === 'expense' ? 'transaction-expense' : 'transaction-transfer') }}">
                                    {{ $transaction->type === 'expense' ? '-' : '+' }}₹{{ number_format($transaction->amount, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card-dark text-center text-muted">
            <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
            <p class="mb-2">No transactions found</p>
            <small>Try adjusting your filters or add a new transaction</small>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($transactions->hasPages())
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @endif

</x-mobile-layout>
