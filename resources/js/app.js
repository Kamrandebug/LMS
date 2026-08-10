import confetti from 'canvas-confetti';
import Alpine from 'alpinejs';
import './bootstrap';

// Livewire 3 bundles its own Alpine instance. When Livewire is loaded we
// register our Alpine data against its instance and let it call Alpine.start().
// When Livewire is NOT loaded (auth pages, static pages), we call Alpine.start()
// ourselves after a short grace period so directives like x-data/@click work.
let alpineStarted = false;

document.addEventListener('livewire:init', () => {
    if (alpineStarted) return;
    alpineStarted = true;

    const Alpine = window.Alpine;

    // Theme Manager
    Alpine.data('themeManager', () => ({
        isDark: document.documentElement.classList.contains('dark'),
        init() {
            this.isDark = document.documentElement.classList.contains('dark');
        },
        toggle() {
            this.isDark = !this.isDark;
            document.documentElement.classList.toggle('dark', this.isDark);
            const val = this.isDark ? 'dark' : 'light';
            localStorage.setItem('theme', val);
            document.cookie = `theme=${val};path=/;max-age=31536000;SameSite=Lax`;
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('theme-changed', { theme: val });
            }
        }
    }));
});

// Fallback: on pages without Livewire (e.g. auth), manually start Alpine so
// x-data, @click, :class etc. work. We wait a tick to give Livewire a chance,
// then start if it hasn't already.
setTimeout(() => {
    if (!alpineStarted && typeof Alpine !== 'undefined') {
        alpineStarted = true;
        Alpine.start();
    }
}, 100);

// Quiz confetti + animations
window.addEventListener('quiz-complete', (e) => {
    const { percentage } = e.detail;
    if (percentage === 100) {
        confetti({ particleCount: 150, spread: 80, origin: { y: 0.6 } });
        setTimeout(() => confetti({ particleCount: 100, angle: 60, spread: 55, origin: { x: 0 } }), 250);
        setTimeout(() => confetti({ particleCount: 100, angle: 120, spread: 55, origin: { x: 1 } }), 400);
    }
});

window.addEventListener('answer-wrong', () => {
    const card = document.querySelector('.current-question-card');
    if (card) {
        card.classList.add('animate-wiggle');
        setTimeout(() => card.classList.remove('animate-wiggle'), 500);
    }
});

window.addEventListener('answer-correct', () => {
    const card = document.querySelector('.current-question-card');
    if (card) {
        card.classList.add('animate-pop');
        setTimeout(() => card.classList.remove('animate-pop'), 300);
    }
});

window.addEventListener('share-whatsapp', (e) => {
    const { score, total } = e.detail;
    const text = `🎯 I scored ${score}/${total} on LearnUp! Can you beat me?`;
    const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(text + ' ' + window.location.href)}`;
    window.open(url, '_blank');
});

// GA4 Safe Wrapper
window.trackEvent = function (eventName, params) {
    if (typeof gtag === 'function') {
        gtag('event', eventName, params);
    }
};
