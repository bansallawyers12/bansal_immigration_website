@extends('layouts.adminnew')

@section('title', 'Contact Management Dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contact Management Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contact-management.index') }}">Contact Management</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Overview Statistics -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total_queries'] }}</h3>
                            <p>Total Queries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <a href="{{ route('admin.contact-management.index') }}" class="small-box-footer">
                            View All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['unread_queries'] }}</h3>
                            <p>Unread Queries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <a href="{{ route('admin.contact-management.index') }}?status=unread" class="small-box-footer">
                            View Unread <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $stats['queries_today'] }}</h3>
                            <p>Today's Queries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <a href="{{ route('admin.contact-management.index') }}?date_from={{ date('Y-m-d') }}" class="small-box-footer">
                            View Today's <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['forwarded_queries'] }}</h3>
                            <p>Forwarded Queries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-share"></i>
                        </div>
                        <a href="{{ route('admin.contact-management.index') }}" class="small-box-footer">
                            View Forwarded <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Queries -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-2"></i>
                                Recent Queries
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.contact-management.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-list mr-1"></i> View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($recentQueries->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentQueries as $query)
                                        <tr class="{{ $query->status === 'unread' ? 'font-weight-bold' : '' }}">
                                            <td>
                                                {{ $query->name ?: 'N/A' }}
                                                @if($query->status === 'unread')
                                                    <span class="badge badge-warning badge-sm ml-1">New</span>
                                                @endif
                                            </td>
                                            <td>{{ $query->contact_email }}</td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $query->subject }}">
                                                    {{ $query->subject ?: 'No subject' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $query->status === 'unread' ? 'warning' : ($query->status === 'resolved' ? 'success' : ($query->status === 'archived' ? 'secondary' : 'info')) }}">
                                                    {{ ucfirst($query->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $query->created_at->format('M d, H:i') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.contact-management.show', $query->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <p class="text-muted">No queries found.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics Charts -->
                <div class="col-md-4">
                    <!-- Status Distribution -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Status Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(!empty($statusStats))
                            <div class="progress-group">
                                @foreach($statusStats as $status => $count)
                                @php
                                    $percentage = $stats['total_queries'] > 0 ? round(($count / $stats['total_queries']) * 100, 1) : 0;
                                    $colorClass = $status === 'unread' ? 'warning' : ($status === 'resolved' ? 'success' : ($status === 'archived' ? 'secondary' : 'info'));
                                @endphp
                                <div class="mb-3">
                                    <span class="float-right"><b>{{ $count }}/{{ $stats['total_queries'] }}</b></span>
                                    <span>{{ ucfirst($status) }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-{{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $percentage }}%</small>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted text-center">No data available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Form Sources -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Form Sources
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(!empty($sourceStats))
                            <div class="progress-group">
                                @foreach($sourceStats as $source => $count)
                                @php
                                    $percentage = $stats['total_queries'] > 0 ? round(($count / $stats['total_queries']) * 100, 1) : 0;
                                @endphp
                                <div class="mb-3">
                                    <span class="float-right"><b>{{ $count }}</b></span>
                                    <span>{{ ucfirst(str_replace('-', ' ', $source)) }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $percentage }}%</small>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted text-center">No source data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time-based Statistics -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar mr-2"></i>
                                Time-based Statistics
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-calendar-day"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Today</span>
                                            <span class="info-box-number">{{ $stats['queries_today'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-calendar-week"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">This Week</span>
                                            <span class="info-box-number">{{ $stats['queries_this_week'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">This Month</span>
                                            <span class="info-box-number">{{ $stats['queries_this_month'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Unread</span>
                                            <span class="info-box-number">{{ $stats['unread_queries'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-2"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.contact-management.index') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-list mr-2"></i>
                                        View All Queries
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.contact-management.index') }}?status=unread" class="btn btn-warning btn-block">
                                        <i class="fas fa-envelope-open mr-2"></i>
                                        View Unread
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.contact-management.export') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-download mr-2"></i>
                                        Export All
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="/contact-form-test" target="_blank" class="btn btn-info btn-block">
                                        <i class="fas fa-vial mr-2"></i>
                                        Test Form
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: .25rem;
    background-color: #fff;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
    position: relative;
    width: 100%;
}

.info-box .info-box-icon {
    border-radius: .25rem;
    -ms-flex-align: center;
    align-items: center;
    display: -ms-flexbox;
    display: flex;
    font-size: 1.875rem;
    -ms-flex-pack: center;
    justify-content: center;
    text-align: center;
    width: 70px;
    color: rgba(255,255,255,.8);
    text-shadow: 0 1px 1px rgba(0,0,0,.5);
}

.info-box .info-box-content {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    -ms-flex-pack: center;
    justify-content: center;
    line-height: 1.8;
    -ms-flex: 1;
    flex: 1;
    padding: 0 10px;
}

.progress-group {
    margin-bottom: 0;
}

.small-box {
    border-radius: .25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 20px;
    position: relative;
}

.small-box > .inner {
    padding: 10px;
}

.small-box .icon {
    color: rgba(0,0,0,.15);
    z-index: 0;
}

.small-box .icon > i {
    font-size: 90px;
    position: absolute;
    right: 15px;
    top: 15px;
    transition: all .3s linear;
}

.small-box .small-box-footer {
    background-color: rgba(0,0,0,.1);
    color: rgba(255,255,255,.8);
    display: block;
    padding: 3px 0;
    position: relative;
    text-align: center;
    text-decoration: none;
    z-index: 10;
}

.small-box .small-box-footer:hover {
    color: #fff;
    background-color: rgba(0,0,0,.15);
}
</style>
@endpush
