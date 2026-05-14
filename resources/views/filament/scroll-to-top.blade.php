@php
    $visible = \Illuminate\Support\Js::from(__('filament-panels::global_search.field.locator'));
@endphp

<div
    x-data="{
        visible: false,
        init() {
            window.addEventListener('scroll', () => {
                this.visible = window.scrollY > 300;
            });
        }
    }"
    x-show="visible"
    x-transition
    style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;"
>
    <button
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        style="
            background: #d97706;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 12px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.2s;
        "
        onmouseover="this.style.background='#b45309'"
        onmouseout="this.style.background='#d97706'"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>
</div>
