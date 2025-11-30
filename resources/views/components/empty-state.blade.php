@props([
    'icon' => 'fas fa-inbox',
    'title' => 'Tidak ada data',
    'description' => null,
    'action' => null,
    'class' => ''
])

<div class="empty-state {{ $class }}">
    @if($icon)
        <div class="empty-state-icon">
            <i class="{{ $icon }}"></i>
        </div>
    @endif

    @if($title)
        <h3 class="empty-state-title">{{ $title }}</h3>
    @endif

    @if($description)
        <p class="empty-state-description">{{ $description }}</p>
    @endif

    @if($action)
        <div class="empty-state-action">
            {{ $action }}
        </div>
    @endif
</div>

<style>
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    text-align: center;
}

.empty-state-icon {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.empty-state-title {
    font-size: 18px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.empty-state-description {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 24px;
    max-width: 400px;
}

.empty-state-action {
    /* Action buttons or links */
}

/* Mobile responsive */
@media (max-width: 768px) {
    .empty-state {
        padding: 32px 16px;
    }

    .empty-state-icon {
        font-size: 36px;
    }

    .empty-state-title {
        font-size: 16px;
    }

    .empty-state-description {
        font-size: 13px;
    }
}
</style>