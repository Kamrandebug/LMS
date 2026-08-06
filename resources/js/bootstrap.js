import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Quiz localStorage helpers
 */
window.QuizStorage = {
    save(key, data) {
        try {
            localStorage.setItem(key, JSON.stringify(data));
        } catch (e) {
            console.warn('QuizStorage: Could not save', e);
        }
    },
    load(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        } catch (e) {
            console.warn('QuizStorage: Could not load', e);
            return null;
        }
    },
    remove(key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.warn('QuizStorage: Could not remove', e);
        }
    },
    getCompletedSets() {
        return JSON.parse(localStorage.getItem('completed_sets') || '[]');
    },
    addCompletedSet(setId) {
        const sets = this.getCompletedSets();
        if (!sets.includes(setId)) {
            sets.push(setId);
            localStorage.setItem('completed_sets', JSON.stringify(sets));
        }
    },
    getStreak() {
        return parseInt(localStorage.getItem('streak') || '0');
    },
    updateStreak(count) {
        localStorage.setItem('streak', count.toString());
    },
};
