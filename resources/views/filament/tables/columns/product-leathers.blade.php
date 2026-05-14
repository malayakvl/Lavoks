@php
    $record = $getRecord();
    $leathers = $record->leathers ?? collect();
@endphp

<div style="display: flex; flex-wrap: wrap; gap: 4px;">
    @forelse($leathers as $leather)
        <span style="
            display: inline-block;
            padding: 4px 8px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 12px;
            color: #374151;
        ">
            {{ $leather->title ?? 'No title' }}
        </span>
    @empty
        <span style="color: #9ca3af; font-size: 12px;">—</span>
    @endforelse
</div>
