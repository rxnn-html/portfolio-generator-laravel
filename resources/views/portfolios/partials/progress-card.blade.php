<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="relative h-16 w-16 shrink-0">
            <svg viewBox="0 0 64 64" class="h-16 w-16 -rotate-90" aria-hidden="true">
                <defs>
                    <linearGradient id="ringGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#6366f1"/>
                        <stop offset="1" stop-color="#d946ef"/>
                    </linearGradient>
                </defs>
                <circle cx="32" cy="32" r="28" fill="none" stroke-width="6" class="stroke-gray-200"/>
                <circle cx="32" cy="32" r="28" fill="none" stroke-width="6" stroke-linecap="round" stroke="url(#ringGrad)"
                        stroke-dasharray="175.9" :stroke-dashoffset="ring" class="transition-all duration-500"/>
            </svg>
            <span class="absolute inset-0 flex items-center justify-center text-sm font-extrabold text-gray-900" x-text="percent + '%'"></span>
        </div>
        <div class="min-w-0">
            <p class="font-bold text-gray-900">Profile strength</p>
            <p class="text-xs text-gray-500" x-show="percent < 100">Only name and email are required.</p>
            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-300" x-show="percent === 100">All set. Ready to shine!</p>
        </div>
    </div>

    <ul class="mt-4 space-y-0.5">
        <template x-for="section in sections" :key="section.id">
            <li>
                <button type="button" x-on:click="go(section.id)"
                        class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-left text-sm font-medium transition"
                        :class="active === section.id ? 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[11px] transition-colors"
                          :class="done[section.id] ? 'bg-emerald-500 text-white' : 'border border-gray-300 text-transparent'">&#10003;</span>
                    <span x-text="section.label"></span>
                </button>
            </li>
        </template>
    </ul>
</div>