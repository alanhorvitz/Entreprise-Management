import './bootstrap';
import { initializeTheme } from './theme';

// Initialize theme system as soon as possible
initializeTheme();

// Backup initialization in case the DOM isn't ready
document.addEventListener('DOMContentLoaded', () => {
    initializeTheme();
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Theme toggle functionality
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Set initial theme based on user preference
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const defaultTheme = savedTheme || (prefersDark ? 'dark' : 'light');
    
    document.documentElement.setAttribute('data-theme', defaultTheme);
});
