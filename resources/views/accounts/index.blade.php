<x-mobile-layout>
    <x-slot name="header">
        Settings
    </x-slot>

    <!-- Navigation Tabs -->
    <div class="mb-3">
        <div class="btn-group w-100" role="group">
            <a href="{{ route('accounts.index') }}" class="btn btn-outline-primary active">
                <i class="fas fa-wallet me-1"></i> Accounts
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-tags me-1"></i> Categories
            </a>
        </div>
    </div>

    <!-- Add New Button -->
    <div class="mb-3">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary w-100">
            <i class="fas fa-plus me-2"></i> Add New Account
        </a>
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

    <!-- Accounts List -->
    @forelse($accounts as $account)
        <div class="card-dark mb-2 {{ !$account->is_active ? 'opacity-50' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center flex-grow-1">
                    <!-- Account Icon -->
                    <div class="me-3" style="width: 50px; height: 50px; border-radius: 50%; background-color: {{ $account->color }}20; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-{{ $account->icon ?? 'wallet' }} fa-lg" style="color: {{ $account->color }};"></i>
                    </div>

                    <!-- Account Details -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">
                                    {{ $account->name }}
                                    @if(!$account->is_active)
                                        <span class="badge bg-secondary ms-2">Inactive</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ ucfirst($account->type) }}
                                </small>
                            </div>

                            <!-- Balance -->
                            <div class="text-end ms-3">
                                <div class="fw-bold" style="color: {{ $account->color }};">
                                    ₹{{ number_format($account->current_balance, 2) }}
                                </div>
                                <small class="text-muted">
                                    Opening: ₹{{ number_format($account->opening_balance, 2) }}
                                </small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-2">
                            <form action="{{ route('accounts.toggle', $account) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $account->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    <i class="fas fa-{{ $account->is_active ? 'ban' : 'check' }}"></i>
                                    {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @if($account->transactions()->count() === 0)
                                <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card-dark text-center text-muted">
            <i class="fas fa-wallet fa-3x mb-3 opacity-25"></i>
            <p class="mb-2">No accounts found</p>
            <small>Create your first account to start tracking finances</small>
        </div>
    @endforelse

</x-mobile-layout>
