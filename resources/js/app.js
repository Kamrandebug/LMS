import confetti from 'canvas-confetti';
import './bootstrap';

// Livewire 3 bundles its own Alpine instance. We register our Alpine data
// against Livewire's Alpine once Livewire initializes. Calling Alpine.start()
// ourselves would start a SECOND Alpine instance that fights Livewire's,
// leaving Livewire components (wire:click etc.) unregistered.
document.addEventListener('livewire:init', () => {
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
