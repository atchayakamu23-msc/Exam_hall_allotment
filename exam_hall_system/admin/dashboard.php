<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";
// Count Statistics
 $student_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
 $hall_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM halls")->fetch_assoc()['count'];
 $allotted_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM seating_allotment")->fetch_assoc()['count'];
?>
<?php include '../includes/header.php'; ?>
<div class="container">
<h2 class="mb-4 fw-bold">Admin Dashboard</h2>
<p class="text-muted">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>!</p>
<!-- Stats Cards -->
<div class="row g-4 mb-5 mt-3">
<!-- Student Card -->
<div class="col-md-4">
<div class="card dashboard-card text-white bg-primary h-100">
<div class="card-body position-relative">
<div class="card-icon"><i class="fa-solid fa-user-graduate"></i></div>
<h3><?php echo $student_count; ?></h3>
<p class="card-text">Total Students</p>
<a href="manage_students.php" class="text-white text-decoration-none small">View Details &rarr;</a>
</div>
</div>
</div>
 <!-- Hall Card -->
<div class="col-md-4">
<div class="card dashboard-card text-white bg-success h-100">
<div class="card-body position-relative">
<div class="card-icon"><i class="fa-solid fa-school"></i></div>
<h3><?php echo $hall_count; ?></h3>
<p class="card-text">Total Halls</p>
<a href="manage_halls.php" class="text-white text-decoration-none small">Manage Halls &rarr;</a>
</div>
</div>
</div>
<!-- Allotment Card -->
<div class="col-md-4">
<div class="card dashboard-card text-white bg-info h-100">
<div class="card-body position-relative">
<div class="card-icon"><i class="fa-solid fa-chair"></i></div>
<h3><?php echo $allotted_count; ?></h3>
<p class="card-text">Seats Allotted</p><a href="view_allotment.php" class="text-white text-decoration-none small">View Arrangement &rarr;</a>
</div>
</div>
</div>
</div>
<!-- Quick Actions -->
 <div class="table-container p-4 text-center">
<h4 class="mb-3">Quick Actions</h4>
<p class="text-muted mb-4">Automatically generate seating arrangements. The system fills Hall 1 first, then Hall 2, etc., based on capacity.</p>
<a href="generate_seating.php" class="btn btn-lg btn-primary shadow">
<i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate Seating Arrangement
</a>
</div>
</div>

