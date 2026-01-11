<x-app-layout>
    @section('title', 'My Health Progress')
    <x-toast-notification />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Health Progress') }}
        </h2>
    </x-slot>

<style>
    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 10px 0;
    }

    .page-subtitle {
        font-size: 14px;
        color: #666;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
    }

    .stat-card.total .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-card.completed .stat-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .stat-card.pending .stat-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-card.accepted .stat-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 10px 0 5px;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 30px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .appointment-timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -28px;
        top: 8px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #0084ff;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #0084ff;
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        left: -23px;
        top: 20px;
        width: 2px;
        height: calc(100% - 8px);
        background: #e0e0e0;
    }

    .timeline-item:last-child::after {
        display: none;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 3px solid #0084ff;
    }

    .timeline-date {
        font-size: 12px;
        color: #999;
        margin-bottom: 5px;
    }

    .timeline-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 5px 0;
    }

    .timeline-procedure {
        font-size: 14px;
        color: #666;
    }

    .timeline-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 8px;
    }

    .timeline-status.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .timeline-status.accepted {
        background: #dbeafe;
        color: #1e40af;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-top: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #ddd;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #666;
        margin: 0 0 10px 0;
    }

    .empty-state p {
        font-size: 14px;
        color: #999;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0084ff 0%, #0073e6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .info-content h4 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .info-content p {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

<div class="page-header">
    <h1 class="page-title">My Health Progress</h1>
    <p class="page-subtitle">Track your dental health journey and treatment progress</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-value">{{ $totalAppointments }}</div>
        <div class="stat-label">Total Appointments</div>
    </div>

    <div class="stat-card completed">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $completedTreatments }}</div>
        <div class="stat-label">Completed Treatments</div>
    </div>

    <div class="stat-card accepted">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $acceptedTreatments }}</div>
        <div class="stat-label">Accepted Appointments</div>
    </div>

    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-value">{{ $pendingTreatments }}</div>
        <div class="stat-label">Pending Appointments</div>
    </div>
</div>

<!-- Additional Information -->
<div class="content-card">
    <h2 class="card-title">Health Overview</h2>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-tooth"></i>
            </div>
            <div class="info-content">
                <h4>Dental Records</h4>
                <p>{{ $totalTeethRecorded }} Teeth</p>
            </div>
        </div>

        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="info-content">
                <h4>Treatment Progress</h4>
                <p>{{ $totalAppointments > 0 ? round(($completedTreatments / $totalAppointments) * 100) : 0 }}%</p>
            </div>
        </div>

        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="info-content">
                <h4>Last 6 Months</h4>
                <p>{{ $monthlyData->sum('count') }} Visits</p>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trends -->
@if($monthlyData->isNotEmpty())
<div class="content-card">
    <h2 class="card-title">Monthly Appointment Trends (Last 6 Months)</h2>
    <div class="chart-container">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>
@endif

<!-- Treatment Timeline -->
<div class="content-card">
    <h2 class="card-title">Treatment Timeline</h2>
    
    <!-- Search and Filter for Timeline -->
    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600">
        <form method="GET" action="{{ route('health-progress') }}" class="flex flex-wrap gap-3">
            <input type="text" name="timeline_search" value="{{ request('timeline_search') }}" 
                   placeholder="Search treatments..." 
                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 flex-1 min-w-[200px]">
            
            <select name="timeline_status" 
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">All Status</option>
                <option value="accepted" {{ request('timeline_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="completed" {{ request('timeline_status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            
            <input type="date" name="timeline_from" value="{{ request('timeline_from') }}" 
                   placeholder="From Date"
                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                   
            <input type="date" name="timeline_to" value="{{ request('timeline_to') }}" 
                   placeholder="To Date"
                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-search mr-1"></i>Search
            </button>
            <a href="{{ route('health-progress') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                <i class="fas fa-redo mr-1"></i>Reset
            </a>
        </form>
    </div>
    
    @php
        // Build query with filters for timeline
        $timelineQuery = App\Models\Appointment::where('user_id', auth()->id())
            ->whereIn('status', ['accepted', 'completed']);
            
        // Apply search
        if (request('timeline_search')) {
            $search = request('timeline_search');
            $timelineQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('procedure', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if (request('timeline_status')) {
            $timelineQuery->where('status', request('timeline_status'));
        }
        
        // Apply date range
        if (request('timeline_from')) {
            $timelineQuery->whereDate('start', '>=', request('timeline_from'));
        }
        if (request('timeline_to')) {
            $timelineQuery->whereDate('start', '<=', request('timeline_to'));
        }
        
        $paginatedAppointments = $timelineQuery->orderBy('start', 'desc')->paginate(10)->appends(request()->query());
    @endphp
    
    @if($paginatedAppointments->isEmpty())
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>No Treatment History Yet</h3>
            <p>Your completed and accepted treatments will appear here.</p>
        </div>
    @else
        <div class="appointment-timeline">
            @foreach($paginatedAppointments as $appointment)
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($appointment->start)->format('F d, Y') }} at {{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }}
                        </div>
                        <h3 class="timeline-title">{{ $appointment->title }}</h3>
                        <p class="timeline-procedure">
                            <i class="fas fa-stethoscope"></i> {{ $appointment->procedure }}
                        </p>
                        <span class="timeline-status {{ $appointment->status }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination for Timeline -->
        <div class="mt-6">
            {{ $paginatedAppointments->appends(request()->query())->links('vendor.pagination.compact-bootstrap-5') }}
        </div>
    @endif
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($monthlyData->isNotEmpty())
    // Monthly Trends Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlyData as $data)
                    '{{ date("M Y", mktime(0, 0, 0, $data->month, 1, $data->year)) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Appointments',
                data: [
                    @foreach($monthlyData as $data)
                        {{ $data->count }},
                    @endforeach
                ],
                borderColor: '#0084ff',
                backgroundColor: 'rgba(0, 132, 255, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#0084ff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
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
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#666'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: '#666'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    @endif
</script>

            </div>
        </div>
    </div>
</div>
</x-app-layout>
