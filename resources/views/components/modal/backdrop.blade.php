<div
    x-cloak
    @click="modelOpen = false"
    x-show="modelOpen"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-70"
    aria-hidden="true"
></div>
