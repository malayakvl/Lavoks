<button
    x-on:click="$store.sidebar.isOpen = !$store.sidebar.isOpen"
    type="button"
    class="fi-sidebar-toggle-btn"
    aria-label="Toggle sidebar"
    style="width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; border-radius: 0.375rem;"
>
    <svg
        x-show="$store.sidebar.isOpen"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        style="width: 1.25rem; height: 1.25rem;"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
    </svg>
    <svg
        x-show="!$store.sidebar.isOpen"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        style="width: 1.25rem; height: 1.25rem;"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
    </svg>
</button>
