const applyTheme = (theme) => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
};

const savedTheme = localStorage.getItem('theme');
const preferredTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

applyTheme(savedTheme || preferredTheme);

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-theme-toggle]');

    if (! toggle) {
        return;
    }

    const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';

    localStorage.setItem('theme', nextTheme);
    applyTheme(nextTheme);
});
