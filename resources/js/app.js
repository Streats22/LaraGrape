import './bootstrap';
import Alpine from 'alpinejs';

// Register Alpine components
// Alpine.data('grapesJsEditor', grapesJsEditor); // Removed - using direct JS in Blade view

// Start Alpine
Alpine.start();

// Make Alpine available globally
window.Alpine = Alpine;
