<div class="theme-fixed relative overflow-hidden rounded-3xl p-px shadow-2xl shadow-indigo-900/20" role="img" aria-label="Live preview of your portfolio card">
    <div aria-hidden="true" class="absolute -inset-[100%] bg-[conic-gradient(from_0deg,#22d3ee,#6366f1,#d946ef,#fcd34d,#22d3ee)] motion-safe:animate-[spin_10s_linear_infinite]"></div>

    <div class="relative rounded-[calc(1.5rem-1px)] bg-slate-900 p-5 text-white">
        <p class="mb-4 flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-cyan-300">
            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span> Live preview
        </p>

        <div class="flex items-center gap-3">
            <img x-show="preview" :src="preview" alt="" class="h-14 w-14 shrink-0 rounded-2xl object-cover">
            <div x-show="!preview" x-text="initials"
                 class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-cyan-400 to-fuchsia-500 text-lg font-extrabold text-slate-950"></div>
            <div class="min-w-0">
                <p class="truncate text-lg font-extrabold" x-text="(f.full_name || '').trim() || 'Your Name'"></p>
                <p class="truncate text-xs text-slate-400" x-text="(f.email || '').trim() || 'you@email.com'"></p>
                <p x-show="(f.address || '').trim()" class="truncate text-xs text-slate-400" x-text="f.address"></p>
            </div>
        </div>

        <p class="mt-4 text-sm leading-relaxed text-slate-300"
           x-text="(f.about_me || '').trim() ? (f.about_me.trim().slice(0, 120) + (f.about_me.trim().length > 120 ? '...' : '')) : 'Your introduction will appear here as you type.'"></p>

        <div class="mt-4 flex flex-wrap gap-1.5">
            <template x-for="(skill, n) in skills.slice(0, 6)" :key="n">
                <span class="rounded-full bg-indigo-500/25 px-2.5 py-0.5 text-[11px] font-semibold text-indigo-200" x-text="skill"></span>
            </template>
            <span x-show="skills.length > 6" class="rounded-full bg-white/10 px-2.5 py-0.5 text-[11px] text-slate-300" x-text="'+' + (skills.length - 6)"></span>
            <span x-show="skills.length === 0" class="text-[11px] text-slate-500">Your skills will show up here.</span>
        </div>

        <div class="mt-5 grid grid-cols-3 gap-2 text-center">
            <div class="rounded-xl bg-white/5 py-2"><p class="text-lg font-bold" x-text="counts.projects"></p><p class="text-[10px] uppercase tracking-wider text-slate-400">Projects</p></div>
            <div class="rounded-xl bg-white/5 py-2"><p class="text-lg font-bold" x-text="counts.experiences"></p><p class="text-[10px] uppercase tracking-wider text-slate-400">Roles</p></div>
            <div class="rounded-xl bg-white/5 py-2"><p class="text-lg font-bold" x-text="counts.education"></p><p class="text-[10px] uppercase tracking-wider text-slate-400">Schools</p></div>
        </div>
    </div>
</div>