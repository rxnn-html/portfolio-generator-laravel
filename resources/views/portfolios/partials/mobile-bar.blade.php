<div class="sticky top-16 z-30 -mx-4 mb-5 border-b border-gray-200 bg-gray-50/90 px-4 py-2 backdrop-blur lg:hidden">
    <div class="flex items-center gap-3">
        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-300" x-text="percent + '%'"></span>
        <div class="h-1.5 w-14 shrink-0 overflow-hidden rounded-full bg-gray-200">
            <div class="h-full rounded-full bg-linear-to-r from-indigo-500 to-fuchsia-500 transition-all duration-500" :style="`width: ${percent}%`"></div>
        </div>
        <div class="flex gap-1 overflow-x-auto">
            <template x-for="section in sections" :key="section.id">
                <button type="button" x-on:click="go(section.id)"
                        class="flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-semibold transition"
                        :class="active === section.id ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600'">
                    <span x-show="done[section.id]">&#10003;</span><span x-text="section.label"></span>
                </button>
            </template>
        </div>
    </div>
</div>