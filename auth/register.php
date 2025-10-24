<?php
$page_css = '/KnowledgeGrid-Libraries/assets/css/register.css';
include_once '../includes/header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize error message
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/db_connect.php';
    
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $location = trim($_POST['location'] ?? '');

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($location) || strlen($password) < 6) {
        $error_message = 'Please fill all fields correctly. Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error_message = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email=?');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error_message = 'Email already registered. Please login.';
            } else {
                // Extract city and state
                $parts = explode(',', $location);
                $city = $parts[0] ?? $location;
                $state = $parts[1] ?? '';
                $hash = password_hash($password, PASSWORD_BCRYPT);
                
                // Create user
                $stmt = $conn->prepare('INSERT INTO users (name,email,password_hash,location_city,location_state) VALUES (?,?,?,?,?)');
                if ($stmt) {
                    $stmt->bind_param('sssss', $name, $email, $hash, $city, $state);
                    if ($stmt->execute()) {
                        $uid = $conn->insert_id;
                        
                        // Get nearest library
                        $lib_stmt = $conn->prepare('SELECT id FROM libraries WHERE LOWER(city)=LOWER(?) LIMIT 1');
                        if ($lib_stmt) {
                            $lib_stmt->bind_param('s', $city);
                            $lib_stmt->execute();
                            $result = $lib_stmt->get_result();
                            $lib_id = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['id'] : null;
                            
                            // Set session and redirect
                            $_SESSION['user_id'] = $uid;
                            $_SESSION['user_name'] = $name;
                            $_SESSION['nearest_library_id'] = $lib_id;
                            header('Location: /KnowledgeGrid-Libraries/user/explore.php');
                            exit();
                        }
                    }
                }
                $error_message = 'Failed to create account. Please try again.';
            }
        } else {
            $error_message = 'System error. Please try again later.';
        }
    }
}


?>

<main class="form-page-container">
    <div class="registration-form">
        <h2>Create Your Account</h2>
        <form id="registerForm" action="#" method="POST">

            <!-- Client-side error area -->
            <p id="clientError" class="muted" style="color: red; display: none;"></p>

            <div class="input-group">
                <label for="fullname">Full Name</label>
                <i class="fa-solid fa-user"></i>
                <input type="text" id="fullname" name="fullname" placeholder="e.g. Ananya Sharma" required>
            </div>

            <div class="input-group">
                <label for="email">Email Address</label>
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
            </div>

            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="confirm-password" name="confirm" placeholder="Re-enter your password"
                    required>
            </div>

            <div class="input-group">
                <label for="location">Location</label>
                <i class="fa-solid fa-building-columns"></i>
                <select id="location" name="location" required>
                    <option value="" disabled selected>Select a location</option>
                    <option value="bangalore">Bengaluru, Karnataka</option>
                    <option value="mumbai">Mumbai, Maharashtra</option>
                    <option value="delhi">Delhi</option>
                    <option value="chennai">Chennai, Tamil Nadu</option>
                    <option value="bhopal">Bhopal, Madhya Pradesh</option>
                    <option value="kolkata">Kolkata, West Bengal</option>
                    <option value="manipal">Manipal, Karnataka</option>
                    <option value="tiruchirappalli">Tiruchirappalli, Kerala</option>
                </select>
            </div>

            <div class="terms-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>

            <p class="login-link">
                Already have an account? <a href="login.html">Login here</a>
            </p>
        </form>
    </div>
</main>

<!-- Client-side validation script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    if (!form) return;

    const fullname = form.elements['fullname'];
    const email = form.elements['email'];
    const password = form.elements['password'];
    const confirm = form.elements['confirm'];
    const locationEl = form.elements['location'];
    const terms = form.elements['terms'];
    const clientError = document.getElementById('clientError');

    function showError(msg) {
        clientError.textContent = msg;
        clientError.style.display = 'block';
    }
    function clearError() {
        clientError.textContent = '';
        clientError.style.display = 'none';
    }

    form.addEventListener('submit', function (e) {
        clearError();

        if (!fullname || fullname.value.trim().length < 2) {
            showError('Please enter your full name (at least 2 characters).');
            fullname.focus();
            e.preventDefault();
            return;
        }

        const emailVal = (email && email.value || '').trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailVal || !emailPattern.test(emailVal)) {
            showError('Please enter a valid email address.');
            email.focus();
            e.preventDefault();
            return;
        }

        if (!password || password.value.length < 6) {
            showError('Password must be at least 6 characters.');
            password.focus();
            e.preventDefault();
            return;
        }

        if (!confirm || password.value !== confirm.value) {
            showError('Passwords do not match.');
            confirm.focus();
            e.preventDefault();
            return;
        }

        if (!locationEl || !locationEl.value) {
            showError('Please select a location.');
            locationEl.focus();
            e.preventDefault();
            return;
        }

        if (!terms || !terms.checked) {
            showError('You must agree to the Terms & Conditions.');
            terms.focus();
            e.preventDefault();
            return;
        }
        // All checks passed: allow submit (server side will still validate)
    });

    // Clear client error on input/change for better UX
    [fullname, email, password, confirm, locationEl, terms].forEach(function (el) {
        if (!el) return;
        el.addEventListener('input', clearError);
        el.addEventListener('change', clearError);
    });
});
</script>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Define the function here, as it was originally
if (!function_exists('get_nearest_library_by_city')) {
    function get_nearest_library_by_city(mysqli $conn, string $city): ?array
    {
        $q = $conn->prepare('SELECT id, name, city FROM libraries WHERE LOWER(city)=LOWER(?) LIMIT 1');
        if (!$q)
            return null; // Prevent fatal error on prepare failure
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
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $location = trim($_POST['location'] ?? '');

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($location) || strlen($password) < 6) {
        $error_message = 'Please fill all fields correctly. Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error_message = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email=?');
        if ($stmt === false) {
            $error_message = 'Database error. Please try again later.';
        } else {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $error_message = 'Email already registered. Please login.';
            } else {
                $parts = array_map('trim', explode(',', $location));
                $city = $parts[0] ?? $location;
                $state = $parts[1] ?? '';
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare('INSERT INTO users (name,email,password_hash,location_city,location_state) VALUES (?,?,?,?,?)');
                if ($stmt) {
                    $stmt->bind_param('sssss', $name, $email, $hash, $city, $state);
                    $stmt->execute();
                    $uid = $conn->insert_id;
                    $lib = get_nearest_library_by_city($conn, $city);
                    $_SESSION['user_id'] = $uid;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['nearest_library_id'] = $lib['id'] ?? null;

                    header('Location: C:/KnowledgeGrid-Libraries/user/explore.php');
                    exit;
                } else {
                    $error_message = 'Could not create user account.';
                }
            }
        }
    }
}
?>


<?php
include_once '../includes/footer.php';
?>