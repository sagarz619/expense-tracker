<x-mobile-layout>
    <x-slot name="header">
        Recurring Transactions
    </x-slot>

    <!-- Add New Button -->
    <div class="mb-3">
        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addRecurringModal">
            <i class="fas fa-plus me-2"></i> Add Recurring Transaction
        </button>
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

    <!-- Recurring Transactions List -->
    @forelse($recurringTransactions as $recurring)
        <div class="card-dark mb-2 {{ !$recurring->is_active ? 'opacity-50' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center flex-grow-1">
                    <!-- Category Icon -->
                    <div class="me-3" style="width: 45px; height: 45px; border-radius: 50%; background-color: {{ $recurring->category->color ?? '#888' }}20; display: flex; align-items: center; justify-content: center;">
                        @if($recurring->type === 'transfer')
                            <i class="fas fa-exchange-alt" style="color: var(--transfer-color);"></i>
                        @else
                            <i class="fas fa-{{ $recurring->category->icon ?? 'question' }}" style="color: {{ $recurring->category->color ?? '#888' }};"></i>
                        @endif
                    </div>

                    <!-- Transaction Details -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">
                                    {{ $recurring->category->name ?? 'Transfer' }}
                                    @if(!$recurring->is_active)
                                        <span class="badge bg-secondary ms-2">Paused</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ $recurring->account->name }}
                                    @if($recurring->type === 'transfer' && $recurring->toAccount)
                                        → {{ $recurring->toAccount->name }}
                                    @endif
                                </small>
                                @if($recurring->description)
                                    <div><small class="text-muted">{{ Str::limit($recurring->description, 40) }}</small></div>
                                @endif
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-repeat me-1"></i>
                                        {{ ucfirst($recurring->frequency) }} | Next: {{ $recurring->next_occurrence->format('d M Y') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="text-end ms-3">
                                <div class="fw-bold {{ $recurring->type === 'income' ? 'transaction-income' : ($recurring->type === 'expense' ? 'transaction-expense' : 'transaction-transfer') }}">
                                    {{ $recurring->type === 'expense' ? '-' : '+' }}₹{{ number_format($recurring->amount, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-2">
                            <form action="{{ route('recurring-transactions.toggle', $recurring) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $recurring->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    <i class="fas fa-{{ $recurring->is_active ? 'pause' : 'play' }}"></i>
                                    {{ $recurring->is_active ? 'Pause' : 'Resume' }}
                                </button>
                            </form>
                            <a href="{{ route('recurring-transactions.edit', $recurring) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('recurring-transactions.destroy', $recurring) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this recurring transaction?');">
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
            <i class="fas fa-repeat fa-3x mb-3 opacity-25"></i>
            <p class="mb-2">No recurring transactions found</p>
            <small>Set up automatic recurring expenses and income</small>
        </div>
    @endforelse

</x-mobile-layout>

<!-- Add Recurring Transaction Modal -->
<div class="modal fade" id="addRecurringModal" tabindex="-1" aria-labelledby="addRecurringModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light" style="background-color: var(--bg-secondary) !important; border: 1px solid var(--border-color);">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="addRecurringModalLabel">Add Recurring Transaction</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('recurring-transactions.create', ['type' => 'expense']) }}" class="btn btn-lg btn-outline-danger">
                        <i class="fas fa-minus-circle me-2"></i> Recurring Expense
                    </a>
                    <a href="{{ route('recurring-transactions.create', ['type' => 'income']) }}" class="btn btn-lg btn-outline-success">
                        <i class="fas fa-plus-circle me-2"></i> Recurring Income
                    </a>
                    <a href="{{ route('recurring-transactions.create', ['type' => 'transfer']) }}" class="btn btn-lg btn-outline-primary">
                        <i class="fas fa-exchange-alt me-2"></i> Recurring Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
