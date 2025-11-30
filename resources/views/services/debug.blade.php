@extends('layouts.app')

@section('title', 'Debug - All Services')

@section('content')
<div class="dashboard-layout">
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7"></path>
                    </svg>
                    <span>DEBUG - All Services</span>
                </div>
                <h1 class="page-title">All Services (Debug Mode)</h1>
                <p class="page-subtitle">Showing all services without branch filtering</p>
            </div>
        </div>
    </header>

    <main class="dashboard-main">
        <div class="content-grid">
            <div class="services-section">
                <div class="services-container">
                    <div class="services-body">
                        @if($allServices->count() > 0)
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Branch ID</th>
                                            <th>User ID</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allServices as $service)
                                            <tr>
                                                <td>{{ $service->id }}</td>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ $service->productCategory->name ?? 'N/A' }}</td>
                                                <td>Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                                                <td>{{ $service->branch_id }}</td>
                                                <td>{{ $service->user_id }}</td>
                                                <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                    </svg>
                                </div>
                                <div class="empty-title">No Services Found</div>
                                <div class="empty-description">There are no services in the database at all.</div>
                            </div>
                        @endif

                        <div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 5px;">
                            <h4>Debug Information:</h4>
                            <p><strong>Total Services in Database:</strong> {{ $allServices->count() }}</p>
                            <p><strong>Current User ID:</strong> {{ auth()->id() }}</p>
                            <p><strong>Current User Branch ID:</strong> {{ auth()->user()->branch_id ?? 'null' }}</p>
                            <a href="{{ route('services.index') }}" class="btn btn-primary" style="margin-top: 10px;">Back to Normal Services Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection