<x-mobile-layout>
    <x-slot name="header">
        Settings
    </x-slot>

    <!-- Navigation Tabs -->
    <div class="mb-3">
        <div class="btn-group w-100" role="group">
            <a href="{{ route('accounts.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-wallet me-1"></i> Accounts
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary active">
                <i class="fas fa-tags me-1"></i> Categories
            </a>
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

    <!-- Expense Categories -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-danger"><i class="fas fa-arrow-down me-2"></i>Expense Categories</h6>
            <a href="{{ route('categories.create', ['type' => 'expense']) }}" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-plus"></i> Add
            </a>
        </div>

        @forelse($expenseCategories as $category)
            <div class="card-dark mb-2 {{ !$category->is_active ? 'opacity-50' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $category->color }}20; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $category->icon }}" style="color: {{ $category->color }};"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">
                                {{ $category->name }}
                                @if(!$category->is_active)
                                    <span class="badge bg-secondary ms-2">Inactive</span>
                                @endif
                            </div>
                            @if($category->description)
                                <small class="text-muted">{{ $category->description }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <form action="{{ route('categories.toggle', $category) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                <i class="fas fa-{{ $category->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($category->transactions()->count() === 0)
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark text-center text-muted">
                <p class="mb-0 small">No expense categories</p>
            </div>
        @endforelse
    </div>

    <!-- Income Categories -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-success"><i class="fas fa-arrow-up me-2"></i>Income Categories</h6>
            <a href="{{ route('categories.create', ['type' => 'income']) }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-plus"></i> Add
            </a>
        </div>

        @forelse($incomeCategories as $category)
            <div class="card-dark mb-2 {{ !$category->is_active ? 'opacity-50' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $category->color }}20; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $category->icon }}" style="color: {{ $category->color }};"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">
                                {{ $category->name }}
                                @if(!$category->is_active)
                                    <span class="badge bg-secondary ms-2">Inactive</span>
                                @endif
                            </div>
                            @if($category->description)
                                <small class="text-muted">{{ $category->description }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <form action="{{ route('categories.toggle', $category) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                <i class="fas fa-{{ $category->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($category->transactions()->count() === 0)
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark text-center text-muted">
                <p class="mb-0 small">No income categories</p>
            </div>
        @endforelse
    </div>

</x-mobile-layout>
