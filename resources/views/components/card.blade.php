@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'footer' => null,
    'variant' => 'default',
    'class' => ''
])

<div {{ $attributes->merge([
    'class' => 'bg-white border-radius-16 shadow-md overflow-hidden ' . $class
]) }}>
    @if($title || $subtitle || $actions)
        <div class="px-6 py-4 border-b border-gray-200">
            @if($title || $subtitle)
                <div class="flex justify-between items-start">
                    <div>
                        @if($title)
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $title }}</h3>
                        @endif
                        @if($subtitle)
                            <p class="text-sm text-gray-500">{{ $subtitle }}</p>
                        @endif
                    </div>
                    @if($actions)
                        <div class="flex gap-2">
                            {{ $actions }}
                        </div>
                    @endif
                </div>
            @elseif($actions)
                <div class="flex justify-end">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
.border-radius-16 {
    border-radius: 16px;
}
.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>