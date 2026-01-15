<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0F172A">

        <title>{{ $title ?? config('app.name', 'Expense Tracker') }}</title>

        <!-- Font Awesome for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body>
        <div class="app-container">
            <!-- Page Header (Optional) -->
            @isset($header)
                <div class="app-header">
                    <h1>{{ $header }}</h1>
                </div>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Sticky Bottom Navigation -->
        <nav class="bottom-nav">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <!-- Transactions -->
            <a href="{{ route('transactions.index') }}" class="bottom-nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>History</span>
            </a>

            <!-- Add Button (FAB) -->
            <a href="#" class="bottom-nav-item" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <div class="bottom-nav-fab">
                    <i class="fas fa-plus"></i>
                </div>
            </a>

            <!-- Reports -->
            <a href="{{ route('reports.index') }}" class="bottom-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Reports</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('settings.index') }}" class="bottom-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>

        <!-- Add Transaction Modal -->
        <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content bg-dark text-light" style="background-color: var(--bg-secondary) !important; border: 1px solid var(--border-color);">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="addTransactionModalLabel">Add Transaction</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn btn-lg btn-outline-danger">
                                <i class="fas fa-minus-circle me-2"></i> Add Expense
                            </a>
                            <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn btn-lg btn-outline-success">
                                <i class="fas fa-plus-circle me-2"></i> Add Income
                            </a>
                            <a href="{{ route('transactions.create', ['type' => 'transfer']) }}" class="btn btn-lg btn-outline-primary">
                                <i class="fas fa-exchange-alt me-2"></i> Transfer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
