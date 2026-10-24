<?php
require_once "includes/config.php";
if (isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true) {
    header("location: admin/dashboard.php");
    exit;
}
$body_class = "login-page"; // ✅ ADD THIS
$username = $password = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
$sql = "SELECT id, username, password FROM admin WHERE username = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username; 
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    // Plain text comparison as requested
                    if ($password == $hashed_password) {
                        $_SESSION["admin_loggedin"] = true;
                        $_SESSION["admin_id"] = $id;
                        $_SESSION["admin_username"] = $username; 
                        header("location: admin/dashboard.php");
                    } else {
                        $error = "Invalid password.";
                    }
                }
            } else {
                $error = "Invalid username.";
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<?php require_once "includes/header.php"; ?>
<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="login-card w-100">
        <div class="text-center mb-4">
            <i class="fa-solid fa-user-shield fa-3x text-primary mb-3"></i>
            <h3>Admin Login</h3>
            <p class="text-muted">Enter your credentials to continue</p>
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 text-center"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none text-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>
</div>

