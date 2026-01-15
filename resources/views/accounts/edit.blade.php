<x-mobile-layout>
    <x-slot name="header">
        Edit Account
    </x-slot>

    <div class="card-dark">
        <form action="{{ route('accounts.update', $account) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Account Name -->
            <div class="mb-4">
                <label for="name" class="form-label">
                    Account Name <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $account->name) }}"
                    placeholder="e.g., Cash Wallet, HDFC Bank, Credit Card"
                    required
                    autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Account Type -->
            <div class="mb-4">
                <label for="type" class="form-label">
                    Account Type <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="cash" {{ old('type', $account->type) === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank" {{ old('type', $account->type) === 'bank' ? 'selected' : '' }}>Bank Account</option>
                    <option value="card" {{ old('type', $account->type) === 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Current Balance (Read-only) -->
            <div class="mb-4">
                <label class="form-label">Current Balance</label>
                <div class="input-group">
                    <span class="input-group-text" style="background-color: var(--bg-tertiary); border-color: var(--border-color);">₹</span>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ number_format($account->current_balance, 2) }}"
                        readonly
                        disabled>
                </div>
                <small class="text-muted">Balance is automatically updated by transactions</small>
            </div>

            <!-- Icon Selection -->
            <div class="mb-4">
                <label class="form-label">Icon <span class="text-muted">(Optional)</span></label>
                <div class="row g-2">
                    @php
                        $icons = ['wallet', 'money-bill-wave', 'university', 'credit-card', 'piggy-bank', 'hand-holding-usd'];
                    @endphp
                    @foreach($icons as $iconName)
                        <div class="col-4">
                            <input
                                type="radio"
                                class="btn-check"
                                name="icon"
                                id="icon_{{ $iconName }}"
                                value="{{ $iconName }}"
                                {{ old('icon', $account->icon) === $iconName ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center p-3"
                                for="icon_{{ $iconName }}"
                                style="border-color: var(--border-color);">
                                <i class="fas fa-{{ $iconName }} fa-2x mb-2"></i>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Color Selection -->
            <div class="mb-4">
                <label class="form-label">Color <span class="text-muted">(Optional)</span></label>
                <div class="row g-2">
                    @php
                        $colors = ['#3bb54a', '#3B82F6', '#EF4444', '#F59E0B', '#8B5CF6', '#EC4899'];
                    @endphp
                    @foreach($colors as $colorCode)
                        <div class="col-4">
                            <input
                                type="radio"
                                class="btn-check"
                                name="color"
                                id="color_{{ str_replace('#', '', $colorCode) }}"
                                value="{{ $colorCode }}"
                                {{ old('color', $account->color) === $colorCode ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-secondary w-100 p-3"
                                for="color_{{ str_replace('#', '', $colorCode) }}"
                                style="border-color: var(--border-color);">
                                <div style="width: 30px; height: 30px; background-color: {{ $colorCode }}; border-radius: 50%; margin: 0 auto;"></div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Active Status -->
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (Show in transaction forms)
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check me-2"></i> Update Account
                </button>
                <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

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
