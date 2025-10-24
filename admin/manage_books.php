<?php
$page_css = '/KnowledgeGrid-Libraries/admin/css/manage_books.css';
include_once '../includes/header.php';
if (!is_admin_logged_in()): header('Location: /KnowledgeGrid-Libraries/auth/login.php'); exit; endif;

// Initialize variables
$error_message = '';
$success_message = '';
$edit = null;

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $bid = (int)$_GET['delete'];
    try {
        $conn->begin_transaction();
        
        // Delete from library_books first
        $stmt = $conn->prepare('DELETE FROM library_books WHERE book_id = ?');
        $stmt->bind_param('i', $bid);
        $stmt->execute();

        // Then delete the book
        $stmt = $conn->prepare('DELETE FROM books WHERE id = ?');
        $stmt->bind_param('i', $bid);
        $stmt->execute();

        $conn->commit();
        header('Location: manage_books.php?success=2');
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Error deleting book: " . $e->getMessage();
    }
}

// Handle Edit Fetch
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM books WHERE id = ?');
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit = $result->fetch_assoc();
}

// Handle Create/Update Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (empty($title) || empty($author)) {
        $error_message = "Title and Author are required.";
    } else {
        try {
            if ($id) {
                $stmt = $conn->prepare('UPDATE books SET title=?, author=?, genre=?, isbn=?, description=? WHERE id=?');
                $stmt->bind_param('sssssi', $title, $author, $genre, $isbn, $description, $id);
            } else {
                $stmt = $conn->prepare('INSERT INTO books (title, author, genre, isbn, description) VALUES (?,?,?,?,?)');
                $stmt->bind_param('sssss', $title, $author, $genre, $isbn, $description);
            }
            $stmt->execute();
            header('Location: manage_books.php?success=1');
            exit;
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Handle Library Mapping
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form']) && $_POST['form'] === 'map') {
    $book_id = (int)($_POST['book_id'] ?? 0);
    $library_id = (int)($_POST['library_id'] ?? 0);
    $available = (int)($_POST['available'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);

    if ($book_id > 0 && $library_id > 0) {
        try {
            // Check if mapping already exists
            $check_stmt = $conn->prepare('SELECT id FROM library_books WHERE library_id = ? AND book_id = ?');
            $check_stmt->bind_param('ii', $library_id, $book_id);
            $check_stmt->execute();
            $existing = $check_stmt->get_result()->fetch_assoc();

            if ($existing) {
                // Update existing mapping
                $stmt = $conn->prepare('UPDATE library_books SET available_count = ?, price = ? WHERE id = ?');
                $stmt->bind_param('idi', $available, $price, $existing['id']);
                $stmt->execute();
                $success_message = "Library mapping updated successfully!";
            } else {
                // Create new mapping
                $stmt = $conn->prepare('INSERT INTO library_books (library_id, book_id, available_count, price) VALUES (?, ?, ?, ?)');
                $stmt->bind_param('iiid', $library_id, $book_id, $available, $price);
                $stmt->execute();
                $success_message = "Book mapped to library successfully!";
            }
            header('Location: manage_books.php?success=3');
            exit;
        } catch (Exception $e) {
            $error_message = "Error mapping book to library: " . $e->getMessage();
        }
    } else {
        $error_message = "Invalid book or library selected.";
    }
}

// Fetch all libraries for the mapping dropdown
$libraries = [];
$stmt = $conn->prepare('SELECT id, name, city FROM libraries ORDER BY name');
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $libraries[] = $row;
}

// Success message handling
if (isset($_GET['success'])) {
    $success_message = match ($_GET['success']) {
        '1' => "Book operation completed successfully!",
        '2' => "Book deleted successfully!",
        '3' => "Book mapped to library successfully!",
        default => "Operation completed successfully!"
    };
}

// Get current mappings
$mappings = [];
$mapping_stmt = $conn->prepare('SELECT * FROM library_books');
$mapping_stmt->execute();
$mapping_result = $mapping_stmt->get_result();
while ($mapping = $mapping_result->fetch_assoc()) {
    $key = $mapping['book_id'] . '-' . $mapping['library_id'];
    $mappings[$key] = $mapping;
}
?>

<main class="container" aria-labelledby="books-manage-title">
    <h1 id="books-manage-title">Manage Books</h1>

    <?php if ($error_message): ?>
        <div class="alert alert-error" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?php 
            if ($_GET['success'] == 1) {
                echo "Book operation completed successfully!";
            } else if ($_GET['success'] == 2) {
                echo "Book deleted successfully!";
            } else if ($_GET['success'] == 3) {
                echo "Book mapped to library successfully!";
            }
            ?>
        </div>
    <?php endif; ?>

    <section aria-labelledby="book-form-title" class="form-section">
        <h2 id="book-form-title"><?php echo $edit ? 'Edit Book' : 'Add Book'; ?></h2>
        <form method="POST" novalidate class="book-form">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           required 
                           value="<?php echo htmlspecialchars($edit['title'] ?? ''); ?>"
                           placeholder="Enter book title">
                </div>

                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" 
                           id="author" 
                           name="author" 
                           required 
                           value="<?php echo htmlspecialchars($edit['author'] ?? ''); ?>"
                           placeholder="Enter author name">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" 
                           id="genre" 
                           name="genre" 
                           value="<?php echo htmlspecialchars($edit['genre'] ?? ''); ?>"
                           placeholder="Enter book genre">
                </div>

                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" 
                           id="isbn" 
                           name="isbn" 
                           value="<?php echo htmlspecialchars($edit['isbn'] ?? ''); ?>"
                           placeholder="Enter ISBN">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description" 
                         name="description" 
                         rows="4" 
                         placeholder="Enter book description"><?php echo htmlspecialchars($edit['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas <?php echo $edit ? 'fa-save' : 'fa-plus'; ?>"></i>
                    <?php echo $edit ? 'Update Book' : 'Add Book'; ?>
                </button>
                <?php if ($edit): ?>
                    <a href="manage_books.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section aria-labelledby="books-list-title" class="books-section">
        <h2 id="books-list-title">Books Collection</h2>
        
        <div class="books-grid">
            <?php 
            $stmt = $conn->prepare('SELECT * FROM books ORDER BY title');
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0): ?>
                <p class="no-books">No books found. Add your first book above.</p>
            <?php endif;

            while($book = $result->fetch_assoc()): ?>
                <div class="book-card">
                    <div class="book-card-content">
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <div class="book-details">
                            <p class="book-author">
                                <i class="fas fa-user-edit"></i>
                                <?php echo htmlspecialchars($book['author']); ?>
                            </p>
                            <?php if (!empty($book['genre'])): ?>
                                <p class="book-genre">
                                    <i class="fas fa-bookmark"></i>
                                    <?php echo htmlspecialchars($book['genre']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($book['isbn'])): ?>
                                <p class="book-isbn">
                                    <i class="fas fa-barcode"></i>
                                    ISBN: <?php echo htmlspecialchars($book['isbn']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($book['description'])): ?>
                            <p class="book-description">
                                <?php echo htmlspecialchars($book['description']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="book-actions">
                        <div class="primary-actions">
                            <a href="manage_books.php?edit=<?php echo (int)$book['id']; ?>" 
                               class="btn btn-edit" 
                               title="Edit book">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="manage_books.php?delete=<?php echo (int)$book['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this book?');"
                               title="Delete book">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>

                        <div class="library-mapping">
                            <form method="POST" class="mapping-form">
                                <input type="hidden" name="form" value="map">
                                <input type="hidden" name="book_id" value="<?php echo (int)$book['id']; ?>">
                                
                                <div class="mapping-inputs">
                                    <div class="form-group">
                                        <select name="library_id" required class="library-select">
                                            <option value="">Select Library</option>
                                            <?php foreach ($libraries as $lib): 
                                                $mapping_key = $book['id'] . '-' . $lib['id'];
                                                $is_mapped = isset($mappings[$mapping_key]);
                                            ?>
                                                <option value="<?php echo (int)$lib['id']; ?>" 
                                                        <?php if ($is_mapped) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($lib['name'] . ' (' . $lib['city'] . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" 
                                               name="available" 
                                               placeholder="Copies" 
                                               min="0" 
                                               value="<?php 
                                                   if (isset($mappings[$mapping_key])) {
                                                       echo (int)$mappings[$mapping_key]['available_count'];
                                                   } else {
                                                       echo '1';
                                                   }
                                               ?>" 
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" 
                                               name="price" 
                                               placeholder="Price" 
                                               min="0" 
                                               step="0.01" 
                                               value="<?php 
                                                   if (isset($mappings[$mapping_key])) {
                                                       echo number_format($mappings[$mapping_key]['price'], 2);
                                                   } else {
                                                       echo '0.00';
                                                   }
                                               ?>" 
                                               required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-map">
                                    <i class="fas <?php echo $is_mapped ? 'fa-sync' : 'fa-link'; ?>"></i>
                                    <?php echo $is_mapped ? 'Update Mapping' : 'Map to Library'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>