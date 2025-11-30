@extends('layouts.app')

@section('title', 'Laporan Harian - ' . \Carbon\Carbon::parse($date)->format('d M Y'))

@section('content')
<div class="page-wrapper">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Laporan Harian</h1>
                    <p class="page-subtitle">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" class="text-white-50">Laporan</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Harian</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Date Selector -->
        <div class="content-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.daily') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Pilih Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="content-card text-center">
                    <div class="card-body">
                        <div class="display-6 text-success mb-2">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                        <h6 class="text-muted">Total Pemasukan</h6>
                        <i class="fas fa-arrow-trend-up fa-2x text-success mt-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <div class="card-body">
                        <div class="display-6 text-danger mb-2">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </div>
                        <h6 class="text-muted">Total Pengeluaran</h6>
                        <i class="fas fa-arrow-trend-down fa-2x text-danger mt-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <div class="card-body">
                        <div class="display-6 text-info mb-2">
                            Rp {{ number_format($totalTransfer, 0, ',', '.') }}
                        </div>
                        <h6 class="text-muted">Total Transfer</h6>
                        <i class="fas fa-exchange-alt fa-2x text-info mt-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <div class="card-body">
                        <div class="display-6 {{ $netCashFlow >= 0 ? 'text-success' : 'text-danger' }} mb-2">
                            Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                        </div>
                        <h6 class="text-muted">Arus Kas Netto</h6>
                        <i class="fas fa-{{ $netCashFlow >= 0 ? 'plus' : 'minus' }}-circle fa-2x {{ $netCashFlow >= 0 ? 'text-success' : 'text-danger' }} mt-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="content-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Detail Transaksi Hari Ini
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Waktu</th>
                                <th>Jenis</th>
                                <th>Rekening</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('H:i:s') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : ($transaction->type === 'expense' ? 'danger' : 'info') }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->account->name }}</td>
                                    <td>
                                        @if($transaction->category)
                                            <span class="badge bg-secondary">{{ $transaction->category->name }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $transaction->description ?: '-' }}</td>
                                    <td class="fw-bold text-{{ $transaction->type === 'income' ? 'success' : ($transaction->type === 'expense' ? 'danger' : 'info') }}">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($transaction->reconciled)
                                            <span class="badge bg-success">Rekonsiliasi</span>
                                        @else
                                            <span class="badge bg-warning">Belum</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5>Tidak ada transaksi</h5>
                                        <p class="text-muted">Belum ada transaksi pada tanggal yang dipilih.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        @if($transactions->count() > 0)
        <div class="d-flex justify-content-end mt-3">
            <div class="btn-group">
                <button class="btn btn-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </button>
                <button class="btn btn-danger" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function exportToExcel() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("reports.export") }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const type = document.createElement('input');
    type.type = 'hidden';
    type.name = 'type';
    type.value = 'daily';
    form.appendChild(type);

    const date = document.createElement('input');
    date.type = 'hidden';
    date.name = 'date';
    date.value = '{{ $date }}';
    form.appendChild(date);

    const format = document.createElement('input');
    format.type = 'hidden';
    format.name = 'format';
    format.value = 'excel';
    form.appendChild(format);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function exportToPDF() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("reports.export") }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const type = document.createElement('input');
    type.type = 'hidden';
    type.name = 'type';
    type.value = 'daily';
    form.appendChild(type);

    const date = document.createElement('input');
    date.type = 'hidden';
    date.name = 'date';
    date.value = '{{ $date }}';
    form.appendChild(date);

    const format = document.createElement('input');
    format.type = 'hidden';
    format.name = 'format';
    format.value = 'pdf';
    form.appendChild(format);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>

<style>
.page-wrapper {
    background-color: #f8f9fc;
    min-height: 100vh;
    padding: 20px;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.page-subtitle {
    font-size: 16px;
    opacity: 0.9;
    margin: 0;
}

.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    overflow: hidden;
    margin-bottom: 30px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 24px;
    border-bottom: none;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 30px;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.display-6 {
    font-size: 2rem;
    font-weight: 700;
}

@media (max-width: 768px) {
    .page-wrapper {
        padding: 10px;
    }

    .page-header {
        padding: 20px;
    }

    .page-title {
        font-size: 24px;
    }

    .card-body {
        padding: 20px;
    }

    .table-responsive {
        font-size: 0.8rem;
    }
}
</style>
@endsection