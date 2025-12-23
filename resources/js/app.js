import './bootstrap';

// Import Alpine plugins
import intersect from '@alpinejs/intersect';

// Register plugins before Alpine starts via Livewire's event
document.addEventListener('livewire:init', () => {
    // Alpine is available via Livewire
    if (window.Alpine) {
        window.Alpine.plugin(intersect);
    }
});

// Also handle case where Alpine is initialized before Livewire
document.addEventListener('alpine:init', () => {
    if (window.Alpine) {
        window.Alpine.plugin(intersect);
    }
});
