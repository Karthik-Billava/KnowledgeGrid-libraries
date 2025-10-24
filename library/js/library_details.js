document.addEventListener('DOMContentLoaded', () => {
// Image gallery logic
const mainImage = document.getElementById('main-gallery-image');
const thumbnails = document.querySelectorAll('.thumbnail-image');

if (mainImage && thumbnails.length > 0) {
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(innerThumb => innerThumb.classList.remove('active'));
            
            // Add active class to the clicked one
            this.classList.add('active');
            
            // Change the main image src
            mainImage.style.opacity = 0; // Fade out
            setTimeout(() => {
                mainImage.src = this.src;
                mainImage.alt = this.alt.replace('Thumbnail of', 'View of'); // Update alt text
                mainImage.style.opacity = 1; // Fade in
            }, 300);
        });
    });
}
});