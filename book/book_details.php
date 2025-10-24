<?php
$page_css = '/KnowledgeGrid-Libraries/book/css/book_details.css';
include_once '../includes/header.php';

// Get and validate book ID
$bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($bookId <= 0) {
    echo '<main class="error-container"><p class="error-message">Invalid book ID.</p></main>';
    include_once '../includes/footer.php';
    exit;
}

// Fetch book details
$stmt = $conn->prepare('SELECT id, title, author, genre, isbn, description FROM books WHERE id = ?');
$stmt->bind_param('i', $bookId);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    echo '<main class="error-container"><p class="error-message">Book not found.</p></main>';
    include_once '../includes/footer.php';
    exit;
}
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
                <span class="genre-tag"><?php echo htmlspecialchars($book['genre']); ?></span>
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>
                <p class="author">by <?php echo htmlspecialchars($book['author']); ?></p>
                <p class="description">
                    <?php echo nl2br(htmlspecialchars($book['description'])); ?>
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