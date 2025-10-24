<?php
$page_css = '/KnowledgeGrid-Libraries/book/css/view_books.css';
include_once '../includes/header.php';

// Fetch books with availability info
$stmt = $conn->prepare('
    SELECT b.*, 
           GROUP_CONCAT(DISTINCT l.name) as libraries,
           MIN(lb.price) as min_price,
           SUM(lb.available_count) as total_available
    FROM books b
    LEFT JOIN library_books lb ON b.id = lb.book_id
    LEFT JOIN libraries l ON lb.library_id = l.id
    GROUP BY b.id
    ORDER BY b.title
');
$stmt->execute();
$books = $stmt->get_result();
?>

<main>
    <section class="page-content container">
        <div class="search-section">
            <h1>Find Your Next Read</h1>
            <p>Search our entire collection and filter by your favorite genres.</p>
            <div class="search-bar">
                <i class="fa-solid fa-search"></i>
                <input type="search" id="book-search-input" placeholder="Search by title or description...">
            </div>
        </div>

        <!-- GENRE FILTER CAROUSEL -->
        <div class="genre-carousel">
            <button class="genre-btn active" data-genre="all">All</button>
            <button class="genre-btn" data-genre="fiction">Fiction</button>
            <button class="genre-btn" data-genre="mystery">Mystery</button>
            <button class="genre-btn" data-genre="science">Science</button>
            <button class="genre-btn" data-genre="history">History</button>
            <button class="genre-btn" data-genre="biography">Biography</button>
            <button class="genre-btn" data-genre="non-fiction">Non-Fiction</button>
            <button class="genre-btn" data-genre="fantasy">Fantasy</button>
        </div>

        <!-- BOOKS GRID -->
        <div class="books-grid" id="books-grid">
            <?php while($book = $books->fetch_assoc()): ?>
                <div class="book-card" data-genre="<?php echo htmlspecialchars(strtolower($book['genre'] ?? 'uncategorized')); ?>">
                    <img src="<?php echo !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'https://via.placeholder.com/500x750.png?text=No+Cover'; ?>" 
                         alt="Book cover for <?php echo htmlspecialchars($book['title']); ?>" 
                         class="book-cover-image">
                    
                    <div class="book-card-content">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="description">
                            <?php 
                            if (!empty($book['description'])) {
                                echo htmlspecialchars(substr($book['description'], 0, 150)) . '...';
                            } else {
                                echo 'No description available.';
                            }
                            ?>
                        </p>
                        <div class="book-metadata">
                            <?php if ($book['total_available'] > 0): ?>
                                <span class="availability">
                                    <i class="fas fa-check"></i> Available
                                </span>
                                <span class="price">
                                    From ₹<?php echo number_format($book['min_price'], 2); ?>
                                </span>
                            <?php else: ?>
                                <span class="availability unavailable">
                                    <i class="fas fa-times"></i> Currently Unavailable
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="/KnowledgeGrid-Libraries/book/book_details.php?id=<?php echo (int)$book['id']; ?>" 
                           class="btn">View Book</a>
                    </div>
                </div>
            <?php endwhile; ?>

            <?php if ($books->num_rows === 0): ?>
                <div class="no-books-message">
                    <i class="fas fa-books"></i>
                    <p>No books available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
$page_script = '/KnowledgeGrid-Libraries/book/js/view_books.js';
require_once '../includes/footer.php';
?>