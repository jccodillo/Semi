@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Summary Cards - Combined in One Row -->
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="font-weight-bold">Dashboard Overview</h4>
        </div>
        
        <!-- Stock Management Cards -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white pb-0 border-0">
                    <h5 class="mb-0">Stock Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <p class="mb-1 text-sm text-muted">Total Items</p>
                                <h3 class="font-weight-bolder">{{ $totalStockItems }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <p class="mb-1 text-sm text-muted">Low Stock</p>
                                <h3 class="font-weight-bolder">{{ $lowStockCount }}</h3>
                                @if($lowStockCount > 0)
                                    <span class="badge bg-light text-danger">Needs Restock</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <p class="mb-1 text-sm text-muted">Total Value</p>
                                <h3 class="font-weight-bolder">₱{{ number_format($totalStockValue, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Supplies Inventory Cards -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white pb-0 border-0">
                    <h5 class="mb-0">Supplies Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <p class="mb-1 text-sm text-muted">Total Items</p>
                                <h3 class="font-weight-bolder">{{ $totalSupplyItems }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <p class="mb-1 text-sm text-muted">Low Stock</p>
                                <h3 class="font-weight-bolder">{{ $lowSupplyCount }}</h3>
                                @if($lowSupplyCount > 0)
                                    <span class="badge bg-light text-danger">Needs Restock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="font-weight-bold">Analytics</h4>
        </div>
        
        <!-- Period Selector -->
        <div class="col-12 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="periodForm" class="mb-0 d-flex align-items-center">
                        <label for="period-selector" class="me-3 mb-0">Time Period:</label>
                        <select class="form-select form-select-sm" style="max-width: 200px;" name="period" id="period-selector">
                            <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="annually" {{ $period === 'annually' ? 'selected' : '' }}>Annually</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Stock Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white pb-0 border-0">
                    <h5 class="mb-0">Stock Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="stocks-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Supplies Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white pb-0 border-0">
                    <h5 class="mb-0">Supplies Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="supplies-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Statistics -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white pb-0 border-0">
                    <h5 class="mb-0">User Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Metric</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Count</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Last Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex px-3">
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">{{ $userStats['metric'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $userStats['count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">
                                            {{ $userStats['last_joined'] ? Carbon\Carbon::parse($userStats['last_joined'])->format('M d, Y') : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Movements Section -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="font-weight-bold">Recent Activities</h4>
        </div>
        
        <!-- Recent Stock Movements -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center pb-0 border-0">
                    <h5 class="mb-0">Recent Stock Movements</h5>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Department</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentStocks as $stock)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3">
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">{{ $stock->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $stock->department }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $stock->category }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $stock->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">₱{{ number_format($stock->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">₱{{ number_format($stock->price * $stock->quantity, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $stock->created_at->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Supply Movements -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center pb-0 border-0">
                    <h5 class="mb-0">Recent Supplies Inventory Movements</h5>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSupplies as $supply)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3">
                                            <div class="my-auto">
                                                <h6 class="mb-0 text-sm">{{ $supply->product_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $supply->unit_type }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $supply->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">{{ $supply->created_at->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('dashboard-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Stock Chart
        var stockCtx = document.getElementById("stocks-chart").getContext("2d");
        var stockChart;
        
        function initStockChart() {
            if (stockChart) {
                stockChart.destroy();
            }

            stockChart = new Chart(stockCtx, {
                type: "bar",
                data: {
                    labels: {!! json_encode($stockData->pluck('label')) !!},
                    datasets: [{
                        label: "Stocks",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#821131",
                        data: {!! json_encode($stockData->pluck('count')) !!},
                        maxBarThickness: 6
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: "#9ca2b7"
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false
                            },
                            ticks: {
                                display: true,
                                color: "#9ca2b7",
                                padding: 10
                            }
                        },
                    },
                },
            });
        }
        
        // Supplies Chart
        var supplyCtx = document.getElementById("supplies-chart").getContext("2d");
        var supplyChart;
        
        function initSupplyChart() {
            if (supplyChart) {
                supplyChart.destroy();
            }

            supplyChart = new Chart(supplyCtx, {
                type: "bar",
                data: {
                    labels: {!! json_encode($supplyData->pluck('label')) !!},
                    datasets: [{
                        label: "Supplies",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#666",
                        data: {!! json_encode($supplyData->pluck('count')) !!},
                        maxBarThickness: 6
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: "#9ca2b7"
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false
                            },
                            ticks: {
                                display: true,
                                color: "#9ca2b7",
                                padding: 10
                            }
                        },
                    },
                },
            });
        }

        // Initialize charts
        initStockChart();
        initSupplyChart();

        // Handle period change
        document.getElementById('period-selector').addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

<style>
.card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    padding: 1.25rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}

.table thead th {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge {
    padding: 0.35em 0.65em;
    font-weight: 600;
}
</style>
