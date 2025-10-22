<?php
$page_css = '/KnowledgeGrid-Libraries/book/css/book_details.css';
include_once '../includes/header.php';
?>
<main>
    <section class="page-content container">
        <div class="book-details-grid">
            <!-- Image Carousel -->
            <div class="image-carousel-container">
                <img src="https://images.unsplash.com/photo-1588666301433-1428a49c4033?w=500&auto=format&fit=crop" alt="Book cover for Mystery of the Old Mansion" class="main-book-image" id="main-book-image">
                <button class="carousel-btn prev-btn" id="prev-btn" aria-label="Previous book cover image"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="carousel-btn next-btn" id="next-btn" aria-label="Next book cover image"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

            <!-- Book Info -->
            <div class="book-info">
                <span class="genre-tag">Mystery</span>
                <h1>Mystery of the Old Mansion</h1>
                <p class="author">by Jane Doe</p>
                <p class="description">
                    When a reclusive billionaire is found dead in his locked study, Detective Miles Corbin is called to the eerie, fog-shrouded Blackwood Mansion. With a house full of eccentric relatives, each with a motive, Corbin must navigate a labyrinth of family secrets, hidden passages, and cryptic clues. The storm outside traps everyone inside, turning the investigation into a tense race against time before the killer can strike again.
                </p>
                <div class="action-buttons">
                    <button class="btn btn-primary">Borrow Book</button>
                    <button class="btn btn-secondary">Purchase Book</button>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
    $page_script = '/KnowledgeGrid-Libraries/book/js/book_details.js';
    require_once '../includes/footer.php';
?>