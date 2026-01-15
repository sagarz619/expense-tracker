<x-mobile-layout>
    <x-slot name="header">
        Edit Recurring {{ ucfirst($recurringTransaction->type) }}
    </x-slot>

    <div class="card-dark">
        <form action="{{ route('recurring-transactions.update', $recurringTransaction) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="type" value="{{ $recurringTransaction->type }}">

            <!-- Amount Input -->
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
                        value="{{ old('amount', $recurringTransaction->amount) }}"
                        placeholder="0.00"
                        required
                        autofocus
                        style="font-size: 2rem; font-weight: 600;">
                </div>
                @error('amount')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            @if($recurringTransaction->type !== 'transfer')
                <!-- Category Picker -->
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
                                    {{ old('category_id', $recurringTransaction->category_id) == $category->id ? 'checked' : '' }}
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

            <!-- Frequency -->
            <div class="mb-4">
                <label for="frequency" class="form-label">
                    Frequency <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('frequency') is-invalid @enderror" id="frequency" name="frequency" required>
                    <option value="">Select Frequency</option>
                    <option value="daily" {{ old('frequency', $recurringTransaction->frequency) === 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ old('frequency', $recurringTransaction->frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ old('frequency', $recurringTransaction->frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('frequency', $recurringTransaction->frequency) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
                @error('frequency')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Account Selection -->
            <div class="mb-4">
                <label for="account_id" class="form-label">
                    {{ $recurringTransaction->type === 'transfer' ? 'From Account' : 'Account' }} <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('account_id') is-invalid @enderror" id="account_id" name="account_id" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id', $recurringTransaction->account_id) == $account->id ? 'selected' : '' }}>
                            {{ $account->name }} (₹{{ number_format($account->current_balance, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($recurringTransaction->type === 'transfer')
                <!-- To Account -->
                <div class="mb-4">
                    <label for="to_account_id" class="form-label">
                        To Account <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('to_account_id') is-invalid @enderror" id="to_account_id" name="to_account_id" required>
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('to_account_id', $recurringTransaction->to_account_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->name }} (₹{{ number_format($account->current_balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <!-- Start Date -->
            <div class="mb-4">
                <label for="start_date" class="form-label">
                    Start Date <span class="text-danger">*</span>
                </label>
                <input
                    type="date"
                    class="form-control @error('start_date') is-invalid @enderror"
                    id="start_date"
                    name="start_date"
                    value="{{ old('start_date', $recurringTransaction->start_date->format('Y-m-d')) }}"
                    required>
                @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- End Date (Optional) -->
            <div class="mb-4">
                <label for="end_date" class="form-label">
                    End Date <span class="text-muted">(Optional)</span>
                </label>
                <input
                    type="date"
                    class="form-control @error('end_date') is-invalid @enderror"
                    id="end_date"
                    name="end_date"
                    value="{{ old('end_date', $recurringTransaction->end_date?->format('Y-m-d')) }}">
                <small class="text-muted">Leave empty for indefinite recurring</small>
                @error('end_date')
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
                    placeholder="Add a note...">{{ old('description', $recurringTransaction->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $recurringTransaction->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (Process automatically)
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check me-2"></i> Update Recurring {{ ucfirst($recurringTransaction->type) }}
                </button>
                <a href="{{ route('recurring-transactions.index') }}" class="btn btn-outline-secondary">
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
