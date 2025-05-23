@extends('layouts.master')

@section('title', 'Customer Service Analytics')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Customer Service Analytics
                </h1>
                <a href="{{ route('customer-service.dashboard') }}" class="btn btn-outline-primary">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>

            <!-- Performance Overview -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Average Response Time</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-info">{{ $avgTimes && $avgTimes->avg_response ? round($avgTimes->avg_response, 1) . ' hours' : 'N/A' }}</h2>
                            <p class="text-muted">Time to first response</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Average Resolution Time</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-success">{{ $avgTimes && $avgTimes->avg_resolution ? round($avgTimes->avg_resolution, 1) . ' hours' : 'N/A' }}</h2>
                            <p class="text-muted">Time to resolution</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Cases by Status -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Cases by Status</h5>
                        </div>
                        <div class="card-body">
                            @if($casesByStatus->count() > 0)
                                @foreach($casesByStatus as $status => $count)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                                        <div>
                                            <span class="badge bg-primary">{{ $count }}</span>
                                            <div class="progress mt-1" style="width: 100px; height: 6px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $casesByStatus->sum() > 0 ? ($count / $casesByStatus->sum()) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cases by Priority -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Cases by Priority</h5>
                        </div>
                        <div class="card-body">
                            @if($casesByPriority->count() > 0)
                                @foreach($casesByPriority as $priority => $count)
                                    @php
                                        $badgeClass = match($priority) {
                                            'urgent' => 'bg-danger',
                                            'high' => 'bg-warning',
                                            'medium' => 'bg-info',
                                            'low' => 'bg-secondary',
                                            default => 'bg-primary'
                                        };
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">{{ ucfirst($priority) }}</span>
                                        <div>
                                            <span class="badge {{ $badgeClass }}">{{ $count }}</span>
                                            <div class="progress mt-1" style="width: 100px; height: 6px;">
                                                <div class="progress-bar {{ str_replace('bg-', 'bg-', $badgeClass) }}" role="progressbar" 
                                                     style="width: {{ $casesByPriority->sum() > 0 ? ($count / $casesByPriority->sum()) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cases Trend -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Cases Created (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            @if($casesPerDay->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Cases Created</th>
                                                <th>Visual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($casesPerDay as $dayData)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($dayData->date)->format('M d, Y') }}</td>
                                                    <td><span class="badge bg-primary">{{ $dayData->count }}</span></td>
                                                    <td>
                                                        <div class="progress" style="width: 200px; height: 20px;">
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: {{ $casesPerDay->max('count') > 0 ? ($dayData->count / $casesPerDay->max('count')) * 100 : 0 }}%">
                                                                {{ $dayData->count }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">No data for the last 30 days</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-trophy text-warning me-2"></i>
                                Top Customer Service Representatives
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($topReps->count() > 0)
                                <div class="row">
                                    @foreach($topReps as $index => $rep)
                                        <div class="col-md-6 mb-3">
                                            <div class="card {{ $index < 3 ? 'border-warning' : '' }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="card-title mb-1">
                                                                @if($index === 0)
                                                                    <i class="bi bi-trophy-fill text-warning me-1"></i>
                                                                @elseif($index === 1)
                                                                    <i class="bi bi-award-fill text-secondary me-1"></i>
                                                                @elseif($index === 2)
                                                                    <i class="bi bi-award text-warning me-1"></i>
                                                                @endif
                                                                {{ $rep->name }}
                                                            </h6>
                                                            <small class="text-muted">{{ $rep->email }}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <h5 class="mb-0 text-success">{{ $rep->resolved_cases_count }}</h5>
                                                            <small class="text-muted">Cases Resolved</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-3">No representatives found</h5>
                                    <p class="text-muted">No customer service representatives have resolved cases yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 