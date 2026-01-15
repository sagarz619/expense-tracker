<x-mobile-layout>
    <x-slot name="header">
        Add New Account
    </x-slot>

    <div class="card-dark">
        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf

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
                    value="{{ old('name') }}"
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
                    <option value="cash" {{ old('type') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank" {{ old('type') === 'bank' ? 'selected' : '' }}>Bank Account</option>
                    <option value="card" {{ old('type') === 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Opening Balance -->
            <div class="mb-4">
                <label for="opening_balance" class="form-label">
                    Opening Balance <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background-color: var(--bg-tertiary); border-color: var(--border-color);">₹</span>
                    <input
                        type="number"
                        step="0.01"
                        class="form-control @error('opening_balance') is-invalid @enderror"
                        id="opening_balance"
                        name="opening_balance"
                        value="{{ old('opening_balance', '0') }}"
                        placeholder="0.00"
                        required>
                    @error('opening_balance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted">Current balance of this account</small>
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
                                {{ old('icon', 'wallet') === $iconName ? 'checked' : '' }}>
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
                                {{ old('color', '#3bb54a') === $colorCode ? 'checked' : '' }}>
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

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check me-2"></i> Create Account
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
