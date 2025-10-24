<?php
$page_css = '/KnowledgeGrid-Libraries/assets/css/login.css';
include dirname(__DIR__, 1) . '/includes/header.php';
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 1) . '/includes/db_connect.php';


// Define the function directly in this file
if (!function_exists('get_nearest_library_by_city')) {
  function get_nearest_library_by_city(mysqli $conn, string $city): ?array {
    $q = $conn->prepare('SELECT id, name, city FROM libraries WHERE LOWER(city)=LOWER(?) LIMIT 1');
    if (!$q) return null; // Prevent fatal error on prepare failure
    $q->bind_param('s', $city);
    $q->execute();
    $r = $q->get_result();
    if ($r && $r->num_rows > 0) {
      return $r->fetch_assoc();
    }
    // Fallback if no library is found in the user's city
    $fallback = $conn->query('SELECT id, name, city FROM libraries ORDER BY id ASC LIMIT 1');
    return $fallback && $fallback->num_rows > 0 ? $fallback->fetch_assoc() : null;
  }
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    $error_message = 'Please enter a valid email and password (min 6 chars).';
  } else {
    // Check for admin
    $stmt = $conn->prepare('SELECT id, name, password_hash FROM admins WHERE email=?');
    if ($stmt) {
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($admin = $res->fetch_assoc()) {
        if (password_verify($password, $admin['password_hash'])) {
          $_SESSION['user_id'] = (int)$admin['id'];  // Changed from admin_id
          $_SESSION['user_name'] = $admin['name'];    // Changed from admin_name
          $_SESSION['is_admin'] = true;               // Add this line to set admin flag
          header('Location: /KnowledgeGrid-Libraries/admin/manage_libraries.php');
          exit;
        }
      }
    }

    // Check for user
    $stmt = $conn->prepare('SELECT id, name, password_hash, location_city FROM users WHERE email=?');
    if ($stmt) {
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($user = $res->fetch_assoc()) {
        if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
          $city = $user['location_city'] ?? '';
          $lib = $city ? get_nearest_library_by_city($conn, $city) : null;
          $_SESSION['user_id'] = (int)$user['id'];
          $_SESSION['user_name'] = $user['name'];
          $_SESSION['nearest_library_id'] = $lib['id'] ?? null;
          header('Location: /knowledgegrid-libraries/user/explore.php');
          exit;
        }
      }
    }
    
    // Generic error if no match was found
    $error_message = 'Invalid email or password.';
  }
}
?>
    <!-- MAIN CONTENT -->
    <main class="form-page-container">
        <div class="auth-form">
            <h2>Login to Your Account</h2>
            
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>

                <p class="register-link">
                    New user? <a href="knowledgegrid-libraries/auth/register.php">Register here</a>.
                </p>
            </form>
        </div>
    </main>
<?php include_once '../includes/footer.php'; ?>