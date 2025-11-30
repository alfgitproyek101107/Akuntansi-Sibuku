@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')
@section('page-title', 'Laporan Laba Rugi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Company Header -->
            @if(App\Models\AppSetting::getValue('company_name'))
                <div class="text-center mb-4">
                    <h2 class="text-primary">{{ App\Models\AppSetting::getValue('company_name') }}</h2>
                    <p class="text-muted mb-1">Laporan Laba Rugi</p>
                    <p class="text-muted small">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>Laporan Laba Rugi</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
                            <li class="breadcrumb-item active">Laba Rugi</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <form method="GET" class="d-flex gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">Rp{{ number_format($totalIncome, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Pendapatan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">Rp{{ number_format($totalExpense, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Pengeluaran</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp{{ number_format($netProfit, 0, ',', '.') }}
                    </h3>
                    <p class="text-muted mb-0">Laba/Rugi Bersih</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $profitMargin }}%</h3>
                    <p class="text-muted mb-0">Margin Laba</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pendapatan per Kategori</h5>
                </div>
                <div class="card-body">
                    @if($incomeByCategory->count() > 0)
                        @foreach($incomeByCategory as $category)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $category['category'] }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">{{ $category['percentage'] }}%</small>
                                    <strong>Rp{{ number_format($category['amount'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $category['percentage'] }}%"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Tidak ada data pendapatan</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pengeluaran per Kategori</h5>
                </div>
                <div class="card-body">
                    @if($expenseByCategory->count() > 0)
                        @foreach($expenseByCategory as $category)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $category['category'] }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">{{ $category['percentage'] }}%</small>
                                    <strong>Rp{{ number_format($category['amount'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ $category['percentage'] }}%"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Tidak ada data pengeluaran</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection