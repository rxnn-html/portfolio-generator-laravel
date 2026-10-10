$utf8 = New-Object System.Text.UTF8Encoding($false)
function SaveF($p, $t) {
    $full = Join-Path (Get-Location) $p
    $dir = Split-Path $full -Parent
    if (-not (Test-Path $dir)) { New-Item -ItemType Directory -Force $dir | Out-Null }
    [System.IO.File]::WriteAllText($full, $t.Replace("`r`n", "`n"), $utf8)
    Write-Host "created $p"
}
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

# ============================================================ the form's brain
SaveF "resources/js/app.js" @'
import Alpine from 'alpinejs';

// A blank row for each repeating section
const blank = {
    education: { school: '', degree: '', year: '', description: '' },
    projects: { project_name: '', description: '', technologies: '', project_link: '' },
    experiences: { job_title: '', company: '', start_date: '', end_date: '', description: '' },
    social_links: { platform: 'GitHub', url: '' },
};
const limits = { education: 10, skills: 30, projects: 20, experiences: 20, social_links: 10 };

// Turns anything (even null) into a trimmed string
const s = (value) => String(value ?? '').trim();

// Starter texts for "About me"
const starters = {
    student: "I'm a passionate student who loves turning ideas into working projects. I'm learning something new every week, from clean code to good design, and I'm looking for opportunities to grow and build things that help people.",
    developer: "I'm a developer who enjoys building clean, reliable web applications from the database all the way to the interface. I care about readable code, thoughtful design, and finishing what I start. I'm open to new projects and collaborations.",
    designer: "I'm a designer who believes good design should feel effortless. I focus on clear layouts, thoughtful colour, and interfaces people actually enjoy using. I'm always exploring new ideas and looking for projects where I can make things better.",
};

Alpine.data('portfolioForm', (init) => ({
    f: init.f,
    education: init.education,
    skills: init.skills,
    projects: init.projects,
    experiences: init.experiences,
    social_links: init.social_links,

    draft: '',
    submitting: false,
    dirty: false,
    drag: false,
    active: 'personal',
    existingImage: init.image,
    preview: init.image,
    removeImage: false,
    imageError: '',
    suggestions: ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'React', 'Python', 'Java', 'SQL', 'Git'],
    starterKeys: Object.keys(starters),
    sections: [
        { id: 'personal', label: 'You' },
        { id: 'education', label: 'Education' },
        { id: 'skills', label: 'Skills' },
        { id: 'projects', label: 'Projects' },
        { id: 'experience', label: 'Work' },
        { id: 'links', label: 'Links' },
    ],

    init() {
        // Always show at least one blank row in each repeating section
        Object.keys(blank).forEach((key) => {
            if (this[key].length === 0) this.add(key);
        });

        this.$nextTick(() => {
            // Scroll-spy: highlight the section that is on screen
            const sections = document.querySelectorAll('[data-section]');
            if ('IntersectionObserver' in window) {
                const watcher = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => { if (entry.isIntersecting) this.active = entry.target.id; });
                }, { rootMargin: '-25% 0px -65% 0px' });
                sections.forEach((el) => watcher.observe(el));
            }

            // Remember that the user changed something (for the "leave page?" warning)
            ['f', 'education', 'skills', 'projects', 'experiences', 'social_links'].forEach((key) => {
                this.$watch(key, () => { this.dirty = true; });
            });
        });

        window.addEventListener('beforeunload', (event) => {
            if (this.dirty && !this.submitting) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    },

    /* ---------- computed values ---------- */
    get initials() {
        return s(this.f.full_name).split(/\s+/).filter(Boolean).slice(0, 2).map((w) => w[0].toUpperCase()).join('') || 'YN';
    },
    get emailOk() {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(s(this.f.email));
    },
    get counts() {
        return {
            education: this.education.filter((r) => s(r.school)).length,
            projects: this.projects.filter((r) => s(r.project_name)).length,
            experiences: this.experiences.filter((r) => s(r.job_title) && s(r.company)).length,
            links: this.social_links.filter((r) => s(r.url)).length,
        };
    },
    get done() {
        return {
            personal: Boolean(s(this.f.full_name) && this.emailOk),
            education: this.counts.education > 0,
            skills: this.skills.length > 0,
            projects: this.counts.projects > 0,
            experience: this.counts.experiences > 0,
            links: this.counts.links > 0,
        };
    },
    get percent() {
        const flags = Object.values(this.done);
        return Math.round((flags.filter(Boolean).length / flags.length) * 100);
    },
    get ring() {
        return 175.9 * (1 - this.percent / 100); // circle length for r = 28
    },
    get freeSuggestions() {
        return this.suggestions.filter((n) => !this.skills.some((x) => s(x).toLowerCase() === n.toLowerCase()));
    },

    /* ---------- navigation ---------- */
    go(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        this.active = id;
    },

    /* ---------- repeating rows ---------- */
    add(key) {
        if (this[key].length < limits[key]) this[key].push({ ...blank[key] });
    },
    remove(key, index) {
        this[key].splice(index, 1);
    },

    /* ---------- skills ---------- */
    addSkill(raw) {
        String(raw).split(',').map((x) => x.trim()).filter(Boolean).forEach((name) => {
            const exists = this.skills.some((x) => s(x).toLowerCase() === name.toLowerCase());
            if (!exists && name.length <= 50 && this.skills.length < limits.skills) this.skills.push(name);
        });
        this.draft = '';
    },
    removeSkill(index) {
        this.skills.splice(index, 1);
    },

    /* ---------- about me ---------- */
    useStarter(key) {
        if (s(this.f.about_me) && !confirm('Replace what you wrote with this starter text?')) return;
        this.f.about_me = starters[key];
    },

    /* ---------- profile picture ---------- */
    pickImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            this.imageError = 'Profile picture must be a JPG, PNG, or WebP image.';
            event.target.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            this.imageError = 'Profile picture must be 2 MB or smaller.';
            event.target.value = '';
            return;
        }

        this.imageError = '';
        this.preview = URL.createObjectURL(file);
        this.removeImage = false;
        this.dirty = true;
    },
    dropImage(event) {
        this.drag = false;
        const file = event.dataTransfer.files[0];
        if (!file) return;
        const transfer = new DataTransfer();
        transfer.items.add(file);
        this.$refs.file.files = transfer.files; // put the dropped file into the real input
        this.pickImage({ target: this.$refs.file });
    },
    clearImage() {
        this.$refs.file.value = '';
        this.preview = null;
        this.imageError = '';
        this.removeImage = Boolean(this.existingImage); // tell the server to delete the saved one
        this.dirty = true;
    },
}));

window.Alpine = Alpine;
Alpine.start();
'@

# ============================================================ section card (with completion check)
SaveF "resources/views/components/form-section.blade.php" @'
@props(['number', 'title', 'description' => null, 'section'])

<section id="{{ $section }}" data-section class="relative scroll-mt-32 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
    {{-- green edge appears when the section is complete --}}
    <span aria-hidden="true" class="absolute inset-y-6 left-0 w-1 rounded-r bg-emerald-500 transition-opacity duration-500"
          :class="done.{{ $section }} ? 'opacity-100' : 'opacity-0'"></span>

    <header class="flex items-start gap-4">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl font-mono text-sm font-bold transition-colors duration-500"
              :class="done.{{ $section }} ? 'bg-emerald-500 text-white' : 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-300'">
            <span x-show="!done.{{ $section }}">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
            <span x-show="done.{{ $section }}">&#10003;</span>
        </span>
        <div class="min-w-0 flex-1">
            <h2 class="text-lg font-bold text-gray-900">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
        <span x-show="done.{{ $section }}" class="hidden rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-300 sm:inline">Complete</span>
    </header>

    <div class="mt-6 space-y-5">
        {{ $slot }}
    </div>
</section>
'@

# ============================================================ live preview card
SaveF "resources/views/portfolios/partials/live-card.blade.php" @'
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
'@

# ============================================================ progress ring + checklist
SaveF "resources/views/portfolios/partials/progress-card.blade.php" @'
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
'@

# ============================================================ mobile sticky bar
SaveF "resources/views/portfolios/partials/mobile-bar.blade.php" @'
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
'@

Write-Host ""
Write-Host "Done with 1!"