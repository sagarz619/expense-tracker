<x-mobile-layout>
    <x-slot name="header">
        Reports
    </x-slot>

    <!-- Month Selector and Export -->
    <div class="row g-2 mb-3">
        <div class="col-8">
            <form method="GET" action="{{ route('reports.index') }}">
                <div class="input-group">
                    <span class="input-group-text" style="background-color: var(--bg-tertiary); border-color: var(--border-color);">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>
        <div class="col-4">
            <a href="{{ route('reports.export', ['month' => $month]) }}" class="btn btn-outline-success w-100">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </div>
    </div>

    <!-- Monthly Summary Cards -->
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="card-dark text-center">
                <small class="text-muted d-block mb-1">Income</small>
                <div class="fw-bold transaction-income">₹{{ number_format($monthlyIncome, 2) }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card-dark text-center">
                <small class="text-muted d-block mb-1">Expense</small>
                <div class="fw-bold transaction-expense">₹{{ number_format($monthlyExpense, 2) }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card-dark text-center">
                <small class="text-muted d-block mb-1">Net</small>
                <div class="fw-bold {{ $monthlyNet >= 0 ? 'transaction-income' : 'transaction-expense' }}">
                    ₹{{ number_format(abs($monthlyNet), 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Breakdown Pie Chart -->
    @if($expenseByCategory->count() > 0)
        <div class="card-dark mb-3">
            <h6 class="mb-3">Expense Breakdown</h6>
            <div style="position: relative; height: 250px;">
                <canvas id="expensePieChart"></canvas>
            </div>
        </div>
    @endif

    <!-- Top Expense Categories -->
    @if($topExpenseCategories->count() > 0)
        <div class="card-dark mb-3">
            <h6 class="mb-3">Top Expense Categories</h6>
            @foreach($topExpenseCategories as $item)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $item->category->color ?? '#888' }}20; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $item->category->icon ?? 'question' }}" style="color: {{ $item->category->color ?? '#888' }};"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $item->category->name ?? 'Unknown' }}</div>
                            <small class="text-muted">{{ number_format(($item->total / $monthlyExpense) * 100, 1) }}%</small>
                        </div>
                    </div>
                    <div class="fw-bold transaction-expense">₹{{ number_format($item->total, 2) }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Daily Expense Trend -->
    @if($dailyExpense->count() > 0)
        <div class="card-dark mb-3">
            <h6 class="mb-3">Daily Expense Trend</h6>
            <div style="position: relative; height: 200px;">
                <canvas id="dailyExpenseChart"></canvas>
            </div>
        </div>
    @endif

    <!-- Income Breakdown -->
    @if($incomeByCategory->count() > 0)
        <div class="card-dark mb-3">
            <h6 class="mb-3">Income Breakdown</h6>
            <div style="position: relative; height: 250px;">
                <canvas id="incomePieChart"></canvas>
            </div>
        </div>
    @endif

    <!-- Account Balances -->
    <div class="card-dark mb-3">
        <h6 class="mb-3">Account Balances</h6>
        @foreach($accounts as $account)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center">
                    <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $account->color }}20; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-{{ $account->icon ?? 'wallet' }}" style="color: {{ $account->color }};"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $account->name }}</div>
                        <small class="text-muted">{{ ucfirst($account->type) }}</small>
                    </div>
                </div>
                <div class="fw-bold">₹{{ number_format($account->current_balance, 2) }}</div>
            </div>
        @endforeach
    </div>

    @if($expenseByCategory->count() === 0 && $incomeByCategory->count() === 0)
        <div class="card-dark text-center text-muted">
            <i class="fas fa-chart-pie fa-3x mb-3 opacity-25"></i>
            <p class="mb-2">No data for this month</p>
            <small>Start adding transactions to see your reports</small>
        </div>
    @endif

    <script>
    console.log('Reports page script executing...');
    console.log('Chart available:', typeof Chart);

// Wait for Chart.js to load
function initCharts() {
    if (typeof Chart === 'undefined') {
        console.log('Waiting for Chart.js to load...');
        setTimeout(initCharts, 100);
        return;
    }

    console.log('Chart.js loaded successfully! Initializing charts...');

    // Expense Pie Chart
    @if($expenseByCategory->count() > 0)
    const expenseCtx = document.getElementById('expensePieChart');
    if (expenseCtx) {
        new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($expenseByCategory as $item)
                        '{!! addslashes($item->category->name ?? "Unknown") !!}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($expenseByCategory as $item)
                            {{ $item->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($expenseByCategory as $item)
                            '{{ $item->category->color ?? "#888" }}',
                        @endforeach
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94A3B8',
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return percentage + '%';
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleColor: '#F1F5F9',
                        bodyColor: '#F1F5F9',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ₹' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
    @endif

    // Daily Expense Trend Chart
    @if($dailyExpense->count() > 0)
    const dailyCtx = document.getElementById('dailyExpenseChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($dailyExpense as $item)
                        '{{ \Carbon\Carbon::parse($item->day)->format("d M") }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Daily Expense',
                    data: [
                        @foreach($dailyExpense as $item)
                            {{ $item->total }},
                        @endforeach
                    ],
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#EF4444'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleColor: '#F1F5F9',
                        bodyColor: '#F1F5F9',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return '₹' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#334155'
                        },
                        ticks: {
                            color: '#94A3B8',
                            callback: function(value) {
                                return '₹' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#334155'
                        },
                        ticks: {
                            color: '#94A3B8',
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
    @endif

    // Income Pie Chart
    @if($incomeByCategory->count() > 0)
    const incomeCtx = document.getElementById('incomePieChart');
    if (incomeCtx) {
        new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($incomeByCategory as $item)
                        '{!! addslashes($item->category->name ?? "Unknown") !!}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($incomeByCategory as $item)
                            {{ $item->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($incomeByCategory as $item)
                            '{{ $item->category->color ?? "#888" }}',
                        @endforeach
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94A3B8',
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return percentage + '%';
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleColor: '#F1F5F9',
                        bodyColor: '#F1F5F9',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ₹' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
    @endif
    }

    // Initialize charts when page loads
    initCharts();
    </script>

</x-mobile-layout>
