@php
    $record = $getRecord();
    $colors = $record->colors ?? collect();

    // Отладка
    \Log::info('Product colors debug', [
        'product_id' => $record->id,
        'colors_count' => $colors->count(),
        'colors' => $colors->toArray()
    ]);
@endphp

<div style="display: flex; flex-wrap: wrap; gap: 6px;">
    @forelse($colors as $color)
        <div
            title="{{ $color->title ?? 'No title' }}"
            style="
                display: inline-block;
                width: 24px;
                height: 24px;
                border-radius: 6px;
                border: 2px solid #9ca3af;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                background-color: {{ $color->code ?? '#ccc' }};
                cursor: pointer;
            "
        ></div>
    @empty
        <span style="color: #9ca3af; font-size: 14px;">— (0 colors)</span>
    @endforelse
</div>

<!-- Debug Info -->
{{--<div style="font-size: 12px; color: #6b7280; margin-top: 4px;">--}}
{{--    Product ID: {{ $record->id }}, Colors: {{ $colors->count() }}--}}
{{--</div>--}}
