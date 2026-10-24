<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";
$edit_mode = false;
$hall_id = "";
$hall_name_val = "";
$capacity_val = "";

// Logic similar to manage_students
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM halls WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM halls WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    $hall_id = $row['id'];
    $hall_name_val = $row['hall_name'];
    $capacity_val = $row['capacity'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['hall_name'];
        $cap = $_POST['capacity'];
        mysqli_query($conn, "UPDATE halls SET hall_name='$name', capacity='$cap' WHERE id=$id");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['add'])) {
        $name = $_POST['hall_name'];
        $cap = $_POST['capacity'];
        mysqli_query($conn, "INSERT INTO halls (hall_name, capacity) VALUES ('$name', '$cap')");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$halls = mysqli_query($conn, "SELECT * FROM halls");
?>

<?php include '../includes/header.php'; ?>
<div class="container">
<h2 class="mb-4 fw-bold">Manage Exam Halls</h2>
<div class="card shadow-sm mb-4">
<div class="card-header bg-white">
<h5 class="mb-0"><?php echo $edit_mode ? 'Edit Hall Details' : 'Add New Hall'; ?></h5>
</div>
<div class="card-body">
<form method="post" class="row g-3">
<?php if ($edit_mode): ?>
<input type="hidden" name="id" value="<?php echo $hall_id; ?>">
<?php endif; ?>
<div class="col-md-5">
<input type="text" name="hall_name" class="form-control" placeholder="Hall Name (e.g., Hall A)" value="<?php echo htmlspecialchars($hall_name_val); ?>" required>
</div>
<div class="col-md-3">
     <input type="number" name="capacity" class="form-control" placeholder="Capacity" value="<?php echo htmlspecialchars($capacity_val); ?>" required>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <?php if ($edit_mode): ?>
                <button type="submit" name="update" class="btn btn-warning flex-grow-1">Update</button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add" class="btn btn-primary w-100">Add Hall</button>
            <?php endif; ?>
</div>
</form>
</div>
</div

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                <th>ID</th>
                <th>Hall Name</th>
                <th>Capacity</th>
                <th width="150">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($halls)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['hall_name']); ?></td>
                    <td><?php echo $row['capacity']; ?></td>
                    <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-pen"></i></a>
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this hall?');" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
</div>
