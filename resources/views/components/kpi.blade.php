@props([
    'label' => '',
    'value' => '',
    'change' => null,
    'changeType' => 'positive', // positive, negative, neutral
    'icon' => null,
    'variant' => 'default', // primary, success, danger, warning
    'sparkline' => null
])

<div {{ $attributes->merge([
    'class' => 'kpi-card kpi-card-' . $variant
]) }}>
    <div class="kpi-content">
        <div class="kpi-header">
            <div class="kpi-info">
                <h3 class="kpi-label">{{ $label }}</h3>
                <p class="kpi-value">{{ $value }}</p>
                @if($change !== null)
                    <span class="kpi-change kpi-change-{{ $changeType }}">
                        <i class="fas fa-arrow-{{ $change >= 0 ? 'up' : 'down' }}"></i>
                        {{ abs($change) }}%
                    </span>
                @endif
            </div>
            @if($icon)
                <div class="kpi-icon">
                    <i class="{{ $icon }}"></i>
                </div>
            @endif
        </div>
        @if($sparkline)
            <div class="kpi-chart">
                {{ $sparkline }}
            </div>
        @endif
    </div>
</div>

<style>
.kpi-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
}

.kpi-card-primary::before {
    background: #3b82f6;
}

.kpi-card-success::before {
    background: #10b981;
}

.kpi-card-danger::before {
    background: #ef4444;
}

.kpi-card-warning::before {
    background: #f59e0b;
}

.kpi-content {
    padding: 24px;
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.kpi-info {
    flex: 1;
}

.kpi-label {
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.kpi-value {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.kpi-change {
    display: inline-flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
}

.kpi-change-positive {
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
}

.kpi-change-negative {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

.kpi-change i {
    margin-right: 4px;
}

.kpi-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 20px;
}

.kpi-card-primary .kpi-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.kpi-card-success .kpi-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.kpi-card-danger .kpi-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.kpi-card-warning .kpi-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.kpi-chart {
    height: 60px;
}
</style>