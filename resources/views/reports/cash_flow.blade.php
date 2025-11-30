@extends('layouts.app')

@section('title', 'Laporan Arus Kas')
@section('page-title', 'Laporan Arus Kas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>Laporan Arus Kas</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
                            <li class="breadcrumb-item active">Arus Kas</li>
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

    @if(count($cashFlowData) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Arus Kas Bulanan</h5>
                <small class="text-muted">Periode: {{ \Carbon\Carbon::parse($startDate)->format('M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M Y') }}</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Pendapatan Operasional</th>
                                <th>Pengeluaran Operasional</th>
                                <th>Arus Kas Operasional</th>
                                <th>Transfer</th>
                                <th>Arus Kas Bersih</th>
                                <th>Arus Kas Kumulatif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashFlowData as $data)
                                <tr>
                                    <td><strong>{{ $data['period'] }}</strong></td>
                                    <td class="text-success">+Rp{{ number_format($data['operating_income'], 0, ',', '.') }}</td>
                                    <td class="text-danger">-Rp{{ number_format($data['operating_expenses'], 0, ',', '.') }}</td>
                                    <td class="{{ $data['net_operating'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $data['net_operating'] >= 0 ? '+' : '' }}Rp{{ number_format($data['net_operating'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-warning">
                                        {{ $data['transfers'] >= 0 ? '+' : '' }}Rp{{ number_format($data['transfers'], 0, ',', '.') }}
                                    </td>
                                    <td class="{{ $data['net_cash_flow'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        <strong>{{ $data['net_cash_flow'] >= 0 ? '+' : '' }}Rp{{ number_format($data['net_cash_flow'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="{{ $data['cumulative'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        <strong>{{ $data['cumulative'] >= 0 ? '+' : '' }}Rp{{ number_format($data['cumulative'], 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h6>Grafik Arus Kas Kumulatif</h6>
                    <div style="height: 300px;">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                    <h3>Tidak ada data arus kas</h3>
                    <p class="text-muted">Belum ada transaksi dalam periode yang dipilih.</p>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($cashFlowData) > 0)
    const ctx = document.getElementById('cashFlowChart');
    if (ctx) {
        const cashFlowData = @json($cashFlowData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: cashFlowData.map(item => item.period),
                datasets: [{
                    label: 'Arus Kas Kumulatif',
                    data: cashFlowData.map(item => item.cumulative),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Arus Kas Kumulatif: Rp' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endsection