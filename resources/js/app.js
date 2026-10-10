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