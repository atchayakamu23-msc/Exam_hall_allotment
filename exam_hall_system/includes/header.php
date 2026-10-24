<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_admin_page = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
?>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exam Hall Allotment System</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- ✅ CSS FIX -->
<link rel="stylesheet" href="<?php echo ($is_admin_page) ? '../assets/css/style.css' : 'assets/css/style.css'; ?>">
</head>
<body class="<?php echo isset($body_class) ? $body_class : ''; ?>">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top <?php echo (isset($body_class) && $body_class == 'index-bg') ? 'navbar-transparent' : ''; ?>">
<div class="container">
<!-- LOGO -->
<a class="navbar-brand fw-bold" href="<?php echo ($is_admin_page) ? '../index.php' : 'index.php'; ?>">
   <i class="fa-solid fa-building-columns me-2"></i>Exam Hall Allotment System
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto align-items-center">
    <?php if (isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true): ?>
        <!-- ADMIN LINKS -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ($is_admin_page) ? 'dashboard.php' : 'admin/dashboard.php'; ?>">Dashboard</a></li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ($is_admin_page) ? 'manage_students.php' : 'admin/manage_students.php'; ?>">Students</a></li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ($is_admin_page) ? 'manage_halls.php' : 'admin/manage_halls.php'; ?>">Halls</a></li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ($is_admin_page) ? 'view_allotment.php' : 'admin/view_allotment.php'; ?>">Allotment</a> </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ($is_admin_page) ? 'reports.php' : 'admin/reports.php'; ?>">Reports</a></li>
        <li class="nav-item ms-lg-3">
            <a class="btn btn-light btn-sm text-primary fw-bold" href="<?php echo ($is_admin_page) ? '../logout.php' : 'logout.php'; ?>">Logout</a></li>
<?php else: ?>
<!-- PUBLIC LINKS -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ($is_admin_page) ? '../index.php' : 'index.php'; ?>">Home</a></li>
        <li class="nav-item ms-lg-3">
            <a class="btn btn-light btn-sm text-primary fw-bold" href="<?php echo ($is_admin_page) ? '../login.php' : 'login.php'; ?>">
                <i class="fa-solid fa-lock"></i> Admin Login</a></li>
                    <li class="nav-item ms-lg-2">
    <a class="btn btn-outline-light btn-sm fw-bold" href="<?php echo ($is_admin_page) ? '../student/search_seat.php' : 'student/search_seat.php'; ?>">
        <i class="fa-solid fa-user-graduate"></i> Student Login
    </a>
</li>
<?php endif; ?>
</ul>
</div>
</div>
</nav>
<div class="main-content py-4">
