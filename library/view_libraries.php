<?php
$page_css = '/KnowledgeGrid-Libraries/library/css/view_libraries.css';
include_once '../includes/header.php';
?>
<section class="page-content container">
    <div class="page-header">
        <h1>Library Branches</h1>
        <p>Find KnowledgeGrid libraries across India. Sign in to get your nearest branch.</p>
    </div>

    <div class="filter-section">
        <div class="search-bar">
            <i class="fa-solid fa-search"></i>
            <input type="search" placeholder="Filter libraries by name or city">
        </div>
    </div>

    <div class="library-grid">
        <!-- Library Card Template (like Image 2) --><div class="library-card">
            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bGlicmFyeXxlbnwwfHwwfHx8MA%3D%3D" alt="Library interior" class="library-cover-image">
            <div class="library-info">
                <h3>Central City Library</h3>
                <p class="library-location-details">
                    <span>City: Bengaluru</span>
                    <span>State: Karnataka</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>

        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8bGlicmFyeXxlbnwwfHwwfHx8MA%3D%3D" alt="Library interior with bookshelves" class="library-cover-image">
            <div class="library-info">
                <h3>Coastal Reads Library</h3>
                <p class="library-location-details">
                    <span>City: Chennai</span>
                    <span>State: Tamil Nadu</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>

        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1531988042231-E09b3cc4c92a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8bGlicmFyeXxlbnwwfHwwfHx8MA%3D%3D" alt="Student studying in a library" class="library-cover-image">
            <div class="library-info">
                <h3>Downtown Knowledge Hub</h3>
                <p class="library-location-details">
                    <span>City: Dharwad</span>
                    <span>State: Karnataka</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>

        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1596495578065-6f6854e4f20e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8bGliYXJ5JTIwaW5kaWF8ZW58MHx8MHx8fDA%3D" alt="Old library interior" class="library-cover-image">
            <div class="library-info">
                <h3>Historic Town Library</h3>
                <p class="library-location-details">
                    <span>City: Hyderabad</span>
                    <span>State: Telangana</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>

        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1506880018603-a8365fd07a01?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGxpYnJhcnklMjBpbmRpYXxlbnwwfHwwfHx8MA%3D%3D" alt="Modern library with large windows" class="library-cover-image">
            <div class="library-info">
                <h3>Lakeside Community Library</h3>
                <p class="library-location-details">
                    <span>City: Manipal</span>
                    <span>State: Karnataka</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>
        
        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1627889700543-980b1e4c3d4a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGxpYnJhcnklMjBpbmRpYXxlbnwwfHwwfHx8MA%3D%3D" alt="Bright and airy library" class="library-cover-image">
            <div class="library-info">
                <h3>Mountainview Library</h3>
                <p class="library-location-details">
                    <span>City: Kolkata</span>
                    <span>State: West Bengal</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>

        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1544415449-74e5ce6c9cf1?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGxpYnJhcnklMjBpbmRpYXxlbnwwfHwwfHx8MA%3D%3D" alt="Library shelves" class="library-cover-image">
            <div class="library-info">
                <h3>Riverside Public Library</h3>
                <p class="library-location-details">
                    <span>City: Mumbai</span>
                    <span>State: Maharashtra</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>

        <div class="library-card">
            <img src="https://images.unsplash.com/photo-1549414002-39c0f991f861?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjh8fGxpYnJhcnklMjBpbmRpYXxlbnwwfHwwfHx8MA%3D%3D" alt="Reading area in a library" class="library-cover-image">
            <div class="library-info">
                <h3>Suburban Book Haven</h3>
                <p class="library-location-details">
                    <span>City: Shivamogga</span>
                    <span>State: Karnataka</span>
                </p>
                <a href="#" class="btn btn-secondary">View Details</a>
            </div>
        </div>
    </div>
</section>
</main>
<?php include_once '../includes/footer.php'; ?>