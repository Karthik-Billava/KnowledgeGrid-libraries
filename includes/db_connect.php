<?php
$conn = new mysqli('localhost', 'root', '', 'knowledgegrid');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// Helper functions - define them only once here
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('is_admin_logged_in')) {
    function is_admin_logged_in() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
}

function current_user_name(): string
{
    return $_SESSION['user_name'] ?? '';
}
function nearest_library_id(): ?int
{
    return isset($_SESSION['nearest_library_id']) ? (int) $_SESSION['nearest_library_id'] : null;
}

function get_nearest_library_by_city(mysqli $conn, string $city): ?array
{
    $city = trim($city);
    if ($city === '')
        return null;
    // Exact match 
    $stmt = $conn->prepare('SELECT id, name, city, state FROM libraries WHERE LOWER(city) = LOWER(?) LIMIT 1');
    $stmt->bind_param('s', $city);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc())
            return $row;
    }
    // Fallback: fuzzy match by LIKE
    $like = '%' . $city . '%';
    $stmt = $conn->prepare('SELECT id, name, city, state FROM libraries WHERE city LIKE ? ORDER BY id ASC LIMIT 1');
    $stmt->bind_param('s', $like);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc())
            return $row;
    }
    // Final fallback: first library
    $res = $conn->query('SELECT id, name, city, state FROM libraries ORDER BY id ASC LIMIT 1');
    return $res && $res->num_rows ? $res->fetch_assoc() : null;
}
