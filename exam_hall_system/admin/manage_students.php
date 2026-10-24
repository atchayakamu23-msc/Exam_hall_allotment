<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";
$edit_mode = false;
$student_id = "";
$reg_no = "";
$name_val = "";
$dept_val = "";
// 1. Handle Delete Action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM students WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
// 2. Handle Edit Load
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $sql = "SELECT * FROM students WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $student_id = $row['id'];
    $reg_no = $row['register_number'];
    $name_val = $row['name'];
    $dept_val = $row['department'];
}
// 3. Handle Form Submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $reg = $_POST['register_number'];
        $name = $_POST['name'];
        $dept = $_POST['department'];
        $sql = "UPDATE students SET register_number=?, name=?, department=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $reg, $name, $dept, $id);
        mysqli_stmt_execute($stmt);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
if (isset($_POST['add'])) {
        $reg = $_POST['register_number'];
        $name = $_POST['name'];
        $dept = $_POST['department'];
        
        $sql = "INSERT INTO students (register_number, name, department) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $reg, $name, $dept);
        mysqli_stmt_execute($stmt);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$students = mysqli_query($conn, "SELECT * FROM students");
?>
<?php include '../includes/header.php'; ?>

<div class="container">
<h2 class="mb-4 fw-bold">Manage Students</h2>
    
<!-- Add/Edit Form -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-white">
<h5 class="mb-0"><?php echo $edit_mode ? 'Edit Student Details' : 'Add New Student'; ?></h5>
</div>
<div class="card-body">
<form method="post" class="row g-3">
    <?php if ($edit_mode): ?>
        <input type="hidden" name="id" value="<?php echo $student_id; ?>">
    <?php endif; ?>
<div class="col-md-3">
    <input type="text" name="register_number" class="form-control" placeholder="Register No" value="<?php echo htmlspecialchars($reg_no); ?>" required>
</div>
<div class="col-md-3">
    <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($name_val); ?>" required>
</div>
<div class="col-md-3">
    <input type="text" name="department" class="form-control" placeholder="Department" value="<?php echo htmlspecialchars($dept_val); ?>" required>
</div>
<div class="col-md-3 d-flex gap-2">
    <?php if ($edit_mode): ?>
<button type="submit" name="update" class="btn btn-warning flex-grow-1">Update</button>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Cancel</a>
<?php else: ?>
<button type="submit" name="add" class="btn btn-primary w-100">Add Student</button>
<?php endif; ?>
</div>
</form>
</div>
</div>
<!-- List -->
 <div class="table-container">
     <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                <th>ID</th>
                <th>Register No</th>
                <th>Name</th>
                <th>Department</th>
                <th width="150">Actions</th>
                </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($students)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['register_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                        <td>
                            <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-pen"></i></a>
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

