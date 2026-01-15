<x-mobile-layout>
    <x-slot name="header">
        Add {{ ucfirst($type) }} Category
    </x-slot>

    <div class="card-dark">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Category Name -->
            <div class="mb-4">
                <label for="name" class="form-label">
                    Category Name <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g., Groceries, Rent, Salary"
                    required
                    autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label">
                    Description <span class="text-muted">(Optional)</span>
                </label>
                <textarea
                    class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description"
                    rows="2"
                    placeholder="e.g., Fruits, Vegetables, Groceries">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Brief description of what this category includes</small>
            </div>

            <!-- Icon Selection -->
            <div class="mb-4">
                <label class="form-label">Icon <span class="text-muted">(Optional)</span></label>
                <div class="row g-2">
                    @php
                        $icons = $type === 'expense'
                            ? ['utensils', 'car', 'home', 'shopping-cart', 'heartbeat', 'graduation-cap', 'film', 'bolt', 'tshirt', 'coffee', 'bus', 'plane']
                            : ['briefcase', 'wallet', 'hand-holding-usd', 'dollar-sign', 'chart-line', 'gift'];
                    @endphp
                    @foreach($icons as $iconName)
                        <div class="col-3">
                            <input
                                type="radio"
                                class="btn-check"
                                name="icon"
                                id="icon_{{ $iconName }}"
                                value="{{ $iconName }}"
                                {{ old('icon', $icons[0]) === $iconName ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center p-3"
                                for="icon_{{ $iconName }}"
                                style="border-color: var(--border-color);">
                                <i class="fas fa-{{ $iconName }} fa-lg"></i>
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
                        $colors = $type === 'expense'
                            ? ['#EF4444', '#F59E0B', '#8B5CF6', '#EC4899', '#F97316', '#DC2626']
                            : ['#10B981', '#3B82F6', '#14B8A6', '#06B6D4', '#22C55E', '#0EA5E9'];
                    @endphp
                    @foreach($colors as $colorCode)
                        <div class="col-4">
                            <input
                                type="radio"
                                class="btn-check"
                                name="color"
                                id="color_{{ str_replace('#', '', $colorCode) }}"
                                value="{{ $colorCode }}"
                                {{ old('color', $colors[0]) === $colorCode ? 'checked' : '' }}>
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
                    <i class="fas fa-check me-2"></i> Create Category
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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
