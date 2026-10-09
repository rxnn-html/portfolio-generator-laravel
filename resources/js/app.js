import Alpine from 'alpinejs';

// A blank row for each repeating section
const blank = {
    education: { school: '', degree: '', year: '', description: '' },
    projects: { project_name: '', description: '', technologies: '', project_link: '' },
    experiences: { job_title: '', company: '', start_date: '', end_date: '', description: '' },
    social_links: { platform: 'GitHub', url: '' },
};
const limits = { education: 10, skills: 30, projects: 20, experiences: 20, social_links: 10 };

// The form's brain. Blade passes the starting data in ("init").
Alpine.data('portfolioForm', (init) => ({
    f: init.f,
    education: init.education,
    skills: init.skills,
    projects: init.projects,
    experiences: init.experiences,
    social_links: init.social_links,

    draft: '',
    submitting: false,
    existingImage: init.image,
    preview: init.image,
    removeImage: false,
    imageError: '',
    suggestions: ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'React', 'Python', 'Java', 'SQL', 'Git'],

    init() {
        // Always show at least one blank row in each repeating section
        Object.keys(blank).forEach((key) => {
            if (this[key].length === 0) this.add(key);
        });
    },

    add(key) {
        if (this[key].length < limits[key]) this[key].push({ ...blank[key] });
    },
    remove(key, index) {
        this[key].splice(index, 1);
    },

    // "HTML, CSS" -> two skills (skips duplicates, blanks, and very long names)
    addSkill(raw) {
        String(raw).split(',').map((s) => s.trim()).filter(Boolean).forEach((name) => {
            const exists = this.skills.some((s) => s.toLowerCase() === name.toLowerCase());
            if (!exists && name.length <= 50 && this.skills.length < limits.skills) this.skills.push(name);
        });
        this.draft = '';
    },
    removeSkill(index) {
        this.skills.splice(index, 1);
    },
    get freeSuggestions() {
        return this.suggestions.filter((n) => !this.skills.some((s) => s.toLowerCase() === n.toLowerCase()));
    },

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
    },
    clearImage() {
        this.$refs.file.value = '';
        this.preview = null;
        this.imageError = '';
        this.removeImage = Boolean(this.existingImage); // tell the server to delete the saved one
    },
}));

window.Alpine = Alpine;
Alpine.start();