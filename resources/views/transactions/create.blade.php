<x-mobile-layout>
    <x-slot name="header">
        Add {{ ucfirst($type) }}
    </x-slot>

    <div class="card-dark">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Amount Input (Auto-focused, Large) -->
            <div class="mb-4">
                <label for="amount" class="form-label">
                    Amount <span class="text-danger">*</span>
                </label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text" style="background-color: var(--bg-tertiary); border-color: var(--border-color); color: var(--text-primary);">₹</span>
                    <input
                        type="number"
                        step="0.01"
                        class="form-control form-control-lg @error('amount') is-invalid @enderror"
                        id="amount"
                        name="amount"
                        value="{{ old('amount') }}"
                        placeholder="0.00"
                        required
                        autofocus
                        style="font-size: 2rem; font-weight: 600;">
                </div>
                @error('amount')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            @if($type !== 'transfer')
                <!-- Category Picker (Icon Grid) -->
                <div class="mb-4">
                    <label class="form-label">
                        Category <span class="text-danger">*</span>
                    </label>
                    <div class="row g-2">
                        @foreach($categories as $category)
                            <div class="col-4">
                                <input
                                    type="radio"
                                    class="btn-check"
                                    name="category_id"
                                    id="category_{{ $category->id }}"
                                    value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'checked' : '' }}
                                    required>
                                <label
                                    class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center p-3"
                                    for="category_{{ $category->id }}"
                                    style="border-color: var(--border-color);">
                                    <i class="fas fa-{{ $category->icon }} fa-2x mb-2" style="color: {{ $category->color }};"></i>
                                    <small class="text-center">{{ $category->name }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('category_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <!-- Account Selection -->
            <div class="mb-4">
                <label for="account_id" class="form-label">
                    {{ $type === 'transfer' ? 'From Account' : 'Account' }} <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('account_id') is-invalid @enderror" id="account_id" name="account_id" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }} (₹{{ number_format($account->current_balance, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($type === 'transfer')
                <!-- To Account (for transfers only) -->
                <div class="mb-4">
                    <label for="to_account_id" class="form-label">
                        To Account <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('to_account_id') is-invalid @enderror" id="to_account_id" name="to_account_id" required>
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }} (₹{{ number_format($account->current_balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <!-- Date Picker (Default: Today) -->
            <div class="mb-4">
                <label for="date" class="form-label">
                    Date <span class="text-danger">*</span>
                </label>
                <input
                    type="date"
                    class="form-control @error('date') is-invalid @enderror"
                    id="date"
                    name="date"
                    value="{{ old('date', date('Y-m-d')) }}"
                    required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description (Optional) -->
            <div class="mb-4">
                <label for="description" class="form-label">
                    Description <span class="text-muted">(Optional)</span>
                </label>
                <textarea
                    class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Add a note...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check me-2"></i> Save {{ ucfirst($type) }}
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

</x-mobile-layout>

@push('styles')
<style>
    .btn-check:checked + .btn-outline-secondary {
        background-color: var(--accent-green);
        border-color: var(--accent-green);
        color: white;
    }
</style>
@endpush
