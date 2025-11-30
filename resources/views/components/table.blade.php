@props([
    'headers' => [],
    'data' => [],
    'actions' => null,
    'emptyMessage' => 'Tidak ada data',
    'class' => '',
    'responsive' => true
])

@if($responsive)
    <div class="table-responsive">
@endif

<table {{ $attributes->merge([
    'class' => 'data-table ' . $class
]) }}>
    @if(count($headers) > 0)
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
                @if($actions)
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
    @endif

    <tbody>
        @if(count($data) > 0)
            @foreach($data as $row)
                <tr>
                    @foreach($headers as $key => $header)
                        <td>
                            @if(isset($row[$key]))
                                {{ $row[$key] }}
                            @endif
                        </td>
                    @endforeach
                    @if($actions)
                        <td>
                            <div class="table-actions">
                                {{ $actions($row) }}
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-8">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <span>{{ $emptyMessage }}</span>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>

@if($responsive)
    </div>
@endif

<style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.data-table thead {
    background: #f9fafb;
}

.data-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e5e7eb;
}

.data-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease;
}

.data-table tbody tr:hover {
    background: #f9fafb;
}

.data-table td {
    padding: 16px;
    font-size: 14px;
    color: #374151;
}

.table-actions {
    display: flex;
    gap: 8px;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 16px;
    color: #9ca3af;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state span {
    font-size: 16px;
    font-weight: 500;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .data-table th,
    .data-table td {
        padding: 12px 8px;
        font-size: 12px;
    }

    .table-actions {
        flex-direction: column;
        gap: 4px;
    }
}
</style>