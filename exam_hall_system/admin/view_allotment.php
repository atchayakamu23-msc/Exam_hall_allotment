<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

// 1. Fetch all halls for the dropdown filter
 $hall_list_result = mysqli_query($conn, "SELECT id, hall_name FROM halls ORDER BY hall_name ASC");

// 2. Check if a filter is applied
 $filter_hall_id = isset($_GET['hall_id']) ? (int)$_GET['hall_id'] : 0;

// 3. Build the SQL query dynamically
 $where_clause = "";
if ($filter_hall_id > 0) {
    // Use prepared statement logic or careful escaping since ID is integer
    $where_clause = " AND h.id = " . $filter_hall_id;
}

 $sql = "SELECT s.register_number, s.name, s.department, h.hall_name, a.seat_number 
        FROM seating_allotment a 
        JOIN students s ON a.student_id = s.id 
        JOIN halls h ON a.hall_id = h.id 
        WHERE 1=1 $where_clause
        ORDER BY h.hall_name ASC, a.seat_number ASC";

 $result = mysqli_query($conn, $sql);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Seating Allotment List</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filter by Hall:</label>
                    <select name="hall_id" class="form-select">
                        <option value="0">-- All Halls --</option>
                        <?php 
                        // Reset pointer to loop through halls again
                        mysqli_data_seek($hall_list_result, 0);
                        while ($hall = mysqli_fetch_assoc($hall_list_result)): 
                        ?>
                            <option value="<?php echo $hall['id']; ?>" 
                                <?php echo ($filter_hall_id == $hall['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($hall['hall_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Apply Filter
                    </button>
                    <a href="view_allotment.php" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Hall Name</th>
                        <th>Seat No</th>
                        <th>Register No</th>
                        <th>Name</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($row['hall_name']); ?></span></td>
                            <td><strong><?php echo htmlspecialchars($row['seat_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['register_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-solid fa-chair fa-2x mb-3 d-block"></i>
                                No allotments found. 
                                <?php if($filter_hall_id > 0): ?>
                                    Try selecting a different hall or generate seating.
                                <?php else: ?>
                                    Please generate seating first.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

