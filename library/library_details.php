<?php
$page_css = '/KnowledgeGrid-Libraries/library/css/library_details.css';
require_once '../includes/header.php';
?>
<main>
    <section class="page-content container">
        
        <!-- AMAZON-STYLE IMAGE GALLERY -->
        <div class="gallery-container">
            <div class="main-image-view">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&auto=format&fit=crop" alt="Spacious library reading area" id="main-gallery-image">
            </div>
            <div class="thumbnail-strip">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&auto=format&fit=crop" alt="Thumbnail of spacious library reading area" class="thumbnail-image active">
                <img src="https://images.unsplash.com/photo-1533669958342-d62c64b638a2?w=800&auto=format&fit=crop" alt="Thumbnail of close-up of bookshelves" class="thumbnail-image">
                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&auto=format&fit=crop" alt="Thumbnail of person browsing books in an aisle" class="thumbnail-image">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=800&auto=format&fit=crop" alt="Thumbnail of cozy corner of a library with a lamp" class="thumbnail-image">
                <img src="https://images.unsplash.com/photo-1544415449-74e5ce6c9cf1?w=500&auto=format&fit=crop" alt="Thumbnail of library shelves" class="thumbnail-image">
            </div>
        </div>

        <!-- DETAILS & LOCATION GRID -->
        <div class="details-grid">
            
            <!-- Left Column: Details -->
            <div class="library-details">
                <h1>Central City Library</h1>
                <div class="library-description">
                    <p>Our flagship branch in Bengaluru, offering a serene environment for reading and research. Features a vast collection, digital access points, and dedicated study areas.</p>
                </div>

                <h2>Information</h2>
                <ul class="info-list">
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span>123 Tech Park Road, Electronic City, Bengaluru, Karnataka</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-clock"></i>
                        <span>Mon - Sat: 9:00 AM - 8:00 PM | Sun: Closed</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <span>(080) 1234 5678</span>
                    </li>
                        <li>
                        <i class="fa-solid fa-envelope"></i>
                        <span>contact.bengaluru@knowledgegrid.com</span>
                    </li>
                </ul>
                <br>
                <a href="explore.html" class="btn btn-primary">View Books Available</a>
            </div>

            <!-- Right Column: Location Map -->
            <div class="location-map">
                <h2>Location</h2>
                <div class="map-container">
                    <!-- Embed OpenStreetMap centered on Manipal, Karnataka -->
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=74.781%2C13.345%2C74.791%2C13.355&amp;layer=mapnik&amp;marker=13.35,74.786" title="Map showing library location in Manipal"></iframe>
                </div>
            </div>

        </div>

    </section>
</main>
<?php
$page_script = '/KnowledgeGrid-Libraries/library/js/library_details.js';
 require_once '../includes/footer.php'; ?>