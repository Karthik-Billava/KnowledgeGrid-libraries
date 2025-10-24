// Mobile navigation toggle
function toggleNav() {
    const nav = document.getElementById('main-nav');
    nav.classList.toggle('active');
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const nav = document.getElementById('main-nav');
    const toggle = document.querySelector('.nav-toggle');
    if (nav.classList.contains('active') && 
        !nav.contains(event.target) && 
        !toggle.contains(event.target)) {
        nav.classList.remove('active');
    }
});

// Hide header on scroll down, show on scroll up
let lastScroll = 0;
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll && currentScroll > 100) {
        header.style.transform = 'translateY(-100%)';
    } else {
        header.style.transform = 'translateY(0)';
    }
    lastScroll = currentScroll;
});