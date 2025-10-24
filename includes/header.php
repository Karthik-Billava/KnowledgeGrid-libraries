<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KnowledgeGrid Libraries</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/KnowledgeGrid-Libraries/assets/images/favicon.ico">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/KnowledgeGrid-Libraries/assets/css/style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($page_css); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="/KnowledgeGrid-Libraries/includes/css/header.css">

    <style>
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/KnowledgeGrid-Libraries/" class="brand">
                <i class="fa-solid fa-book-open-reader logo-icon" class="brand-logo"></i>
                <h1 class="brand-name">KnowledgeGrid</h1>
            </a>

            <button class="nav-toggle" aria-label="Toggle navigation" onclick="toggleNav()">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="navigation" id="main-nav">
                <ul class="nav-links">
                    <li><a href="/KnowledgeGrid-Libraries/" class="nav-link <?php echo empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/KnowledgeGrid-Libraries/' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="/KnowledgeGrid-Libraries/book/view_books.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'view_books.php') !== false ? 'active' : ''; ?>">Books</a></li>
                    <li><a href="/KnowledgeGrid-Libraries/library/view_libraries.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'view_libraries.php') !== false ? 'active' : ''; ?>">Libraries</a></li>
                    <?php if (is_admin_logged_in()): ?>
                        <li><a href="/KnowledgeGrid-Libraries/admin/manage_books.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'manage_books.php') !== false ? 'active' : ''; ?>">Manage Books</a></li>
                        <li><a href="/KnowledgeGrid-Libraries/admin/manage_libraries.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'manage_libraries.php') !== false ? 'active' : ''; ?>">Manage Libraries</a></li>
                    <?php endif; ?>
                </ul>

                <div class="user-actions">
                    <?php if (is_logged_in()): ?>
                        <div class="user-menu">
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                                </div>
                                <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                            </div>
                        </div>
                        <a href="/KnowledgeGrid-Libraries/auth/logout.php" class="btn btn-login">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="/KnowledgeGrid-Libraries/auth/login.php" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="/KnowledgeGrid-Libraries/auth/register.php" class="btn btn-login">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <script src="/KnowledgeGrid-Libraries/includes/js/header.js"></script>