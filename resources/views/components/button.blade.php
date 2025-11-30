@props([
    'variant' => 'primary', // primary, secondary, success, danger, warning, info, light, dark
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'class' => '',
    'attributes' => []
])

@php
    $baseClasses = 'btn btn-' . $variant . ' btn-' . $size;
    $allClasses = $baseClasses . ($class ? ' ' . $class : '');
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $allClasses }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($loading)
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @endif
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ms-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" class="{{ $allClasses }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($loading)
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @endif
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ms-2"></i>
        @endif
    </button>
@endif

<style>
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    line-height: 1.5;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Size variants */
.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 16px;
}

/* Color variants */
.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
    color: white;
}

.btn-secondary:hover:not(:disabled) {
    background-color: #4b5563;
    border-color: #4b5563;
}

.btn-success {
    background-color: #10b981;
    border-color: #10b981;
    color: white;
}

.btn-success:hover:not(:disabled) {
    background-color: #059669;
    border-color: #059669;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
    color: white;
}

.btn-danger:hover:not(:disabled) {
    background-color: #dc2626;
    border-color: #dc2626;
}

.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.btn-warning:hover:not(:disabled) {
    background-color: #d97706;
    border-color: #d97706;
}

.btn-info {
    background-color: #06b6d4;
    border-color: #06b6d4;
    color: white;
}

.btn-info:hover:not(:disabled) {
    background-color: #0891b2;
    border-color: #0891b2;
}

.btn-light {
    background-color: #f3f4f6;
    border-color: #f3f4f6;
    color: #374151;
}

.btn-light:hover:not(:disabled) {
    background-color: #e5e7eb;
    border-color: #e5e7eb;
}

.btn-dark {
    background-color: #1f2937;
    border-color: #1f2937;
    color: white;
}

.btn-dark:hover:not(:disabled) {
    background-color: #111827;
    border-color: #111827;
}

/* Focus states */
.btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-primary:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.btn-success:focus {
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
}

.btn-danger:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
}

/* Loading spinner */
.spinner-border {
    width: 1rem;
    height: 1rem;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

.spinner-border-sm {
    width: 0.8rem;
    height: 0.8rem;
    border-width: 0.2em;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}
</style>