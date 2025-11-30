@if($logs->count() > 0)
    <div class="table-responsive">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipe</th>
                    <th>Nomor WhatsApp</th>
                    <th>Status</th>
                    <th>Pesan</th>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr class="log-row {{ $log->status }}">
                        <td class="log-id">{{ $log->id }}</td>
                        <td>
                            <span class="type-badge {{ $log->report_type }}">
                                {{ ucfirst($log->report_type) }}
                            </span>
                        </td>
                        <td class="phone-number">{{ $log->phone_number }}</td>
                        <td>
                            <span class="status-badge {{ $log->status }}">
                                @if($log->status === 'success')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Berhasil
                                @elseif($log->status === 'failed')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                    Gagal
                                @elseif($log->status === 'pending')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Pending
                                @else
                                    {{ ucfirst($log->status) }}
                                @endif
                            </span>
                        </td>
                        <td class="message-cell">
                            <div class="message-preview">
                                @if($log->message)
                                    {{ Str::limit($log->message, 50) }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="time-cell">
                            <div class="time-info">
                                <div class="created-time">{{ $log->created_at->format('d/m H:i') }}</div>
                                @if($log->sent_at)
                                    <div class="sent-time">{{ $log->sent_at->diffForHumans() }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="user-cell">
                            @if($log->user)
                                <div class="user-info">
                                    <div class="user-name">{{ $log->user->name }}</div>
                                    @if($log->branch)
                                        <div class="user-branch">{{ $log->branch->name }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="no-user">-</span>
                            @endif
                        </td>
                        <td class="actions-cell">
                            <button class="action-btn detail log-detail-btn"
                                    data-log-id="{{ $log->id }}"
                                    title="Lihat Detail">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>

                            @if($log->status === 'failed' && $log->retry_count < 3)
                                <button class="action-btn retry"
                                        data-log-id="{{ $log->id }}"
                                        title="Coba Lagi">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 4 23 10 17 10"></polyline>
                                        <polyline points="1 20 1 14 7 14"></polyline>
                                        <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="pagination-container">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h3 class="empty-title">Belum ada log pengiriman</h3>
        <p class="empty-description">
            Log pengiriman WhatsApp akan muncul di sini setelah ada aktivitas pengiriman laporan.
        </p>
    </div>
@endif