<?php
$page_css = '/KnowledgeGrid-Libraries/admin/css/manage_libraries.css';
include_once '../includes/header.php';
if (!is_admin_logged_in()): header('Location: /KnowledgeGrid-Libraries/auth/login.php'); exit; endif;

// Initialize variables
$error_message = '';
$success_message = '';
$edit = null;

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    try {
        // Start transaction for safe deletion
        $conn->begin_transaction();

        // First delete from library_books
        $stmt = $conn->prepare('DELETE FROM library_books WHERE library_id = ?');
        $stmt->bind_param('i', $did);
        $stmt->execute();

        // Then delete the library
        $stmt = $conn->prepare('DELETE FROM libraries WHERE id = ?');
        $stmt->bind_param('i', $did);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        header('Location: manage_libraries.php?success=2');
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Error deleting library: " . $e->getMessage();
    }
}

// Handle Edit Fetch
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare('SELECT id, name, city, state FROM libraries WHERE id = ?');
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit = $result->fetch_assoc();
}

// Handle Create/Update Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (empty($name) || empty($city) || empty($state)) {
        $error_message = "All fields are required.";
    } else {
        try {
            if ($id) {
                // Update existing library
                $stmt = $conn->prepare('UPDATE libraries SET name = ?, city = ?, state = ? WHERE id = ?');
                $stmt->bind_param('sssi', $name, $city, $state, $id);
                $stmt->execute();
                $success_message = "Library updated successfully!";
            } else {
                // Create new library
                $stmt = $conn->prepare('INSERT INTO libraries (name, city, state) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $name, $city, $state);
                $stmt->execute();
                $success_message = "Library added successfully!";
            }
            header('Location: manage_libraries.php?success=1');
            exit;
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>


<main class="container" aria-labelledby="lib-manage-title">
    <h1 id="lib-manage-title">Manage Libraries</h1>

    <?php if ($error_message): ?>
        <div class="alert alert-error" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?php 
            if ($_GET['success'] == 1) {
                echo "Library operation completed successfully!";
            } else if ($_GET['success'] == 2) {
                echo "Library deleted successfully!";
            }
            ?>
        </div>
    <?php endif; ?>

    <section aria-labelledby="lib-form-title" class="form-section">
        <h2 id="lib-form-title"><?php echo $edit ? 'Edit Library' : 'Add Library'; ?></h2>
        <form method="POST" novalidate class="library-form">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Library Name</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       value="<?php echo htmlspecialchars($edit['name'] ?? ''); ?>"
                       placeholder="Enter library name">
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <input type="text" 
                       id="city" 
                       name="city" 
                       required 
                       value="<?php echo htmlspecialchars($edit['city'] ?? ''); ?>"
                       placeholder="Enter city name">
            </div>

            <div class="form-group">
                <label for="state">State</label>
                <input type="text" 
                       id="state" 
                       name="state" 
                       required 
                       value="<?php echo htmlspecialchars($edit['state'] ?? ''); ?>"
                       placeholder="Enter state name">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="margin: 20px auto;">
                    <?php echo $edit ? 'Update Library' : 'Add Library'; ?>
                </button>
                <?php if ($edit): ?>
                    <a href="manage_libraries.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section aria-labelledby="lib-list-title" class="libraries-section">
        <h2 id="lib-list-title">Libraries</h2>
        
        <div class="libraries-grid">
            <?php 
            $stmt = $conn->prepare('SELECT id, name, city, state FROM libraries ORDER BY name');
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0): ?>
                <p class="no-libraries">No libraries found. Add your first library above.</p>
            <?php endif;

            while($lib = $result->fetch_assoc()): ?>
                <div class="library-card">
                    <div class="library-card-content">
                        <h3 class="library-name"><?php echo htmlspecialchars($lib['name']); ?></h3>
                        <div class="library-details">
                            <p class="library-location">
                                <i class="fas fa-location-dot"></i>
                                <?php echo htmlspecialchars($lib['city'] . ', ' . $lib['state']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="library-card-actions">
                        <a href="manage_libraries.php?edit=<?php echo (int)$lib['id']; ?>" 
                           class="btn btn-edit" 
                           title="Edit library">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="manage_libraries.php?delete=<?php echo (int)$lib['id']; ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this library? This will also remove all book mappings.');"
                           title="Delete library">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>