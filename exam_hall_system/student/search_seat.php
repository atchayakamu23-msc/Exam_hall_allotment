<?php
require_once "../includes/config.php";

 $result = null;
 $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_no = trim($_POST['register_number']);
    
    $sql = "SELECT s.name, s.department, h.hall_name, a.seat_number 
            FROM seating_allotment a 
            JOIN students s ON a.student_id = s.id 
            JOIN halls h ON a.hall_id = h.id 
            WHERE s.register_number = ?";
            
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $reg_no);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($res) == 1) {
            $result = mysqli_fetch_assoc($res);
        } else {
            $error = "No seating allotment found for Register Number: " . htmlspecialchars($reg_no);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="search-card">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-ticket fa-3x text-primary mb-3"></i>
                    <h3>Find Your Exam Seat</h3>
                    <p class="text-muted">Enter your register number to view your hall and seat details.</p>
                </div>

                <form method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="register_number" class="form-control form-control-lg" placeholder="Enter Register Number" required>
                        <button class="btn btn-primary px-4" type="submit"><i class="fa-solid fa-search me-2"></i>Search</button>
                    </div>
                </form>

                <?php if ($result): ?>
                    <div class="alert alert-success mt-4 p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-circle-check fa-2x me-3"></i>
                            <h4 class="mb-0">Allotment Found</h4>
                        </div>
                        <hr>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="ps-0 text-muted">Name:</th>
                                <td class="fw-bold"><?php echo htmlspecialchars($result['name']); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-muted">Department:</th>
                                <td><?php echo htmlspecialchars($result['department']); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-muted">Hall Assigned:</th>
                                <td class="text-primary fw-bold fs-5"><?php echo htmlspecialchars($result['hall_name']); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-muted">Seat Number:</th>
                                <td class="text-danger fw-bold fs-5"><?php echo htmlspecialchars($result['seat_number']); ?></td>
                            </tr>
                        </table>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger mt-4 text-center"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="text-center mt-4">
                    <a href="../index.php" class="btn btn-link text-decoration-none"><i class="fa-solid fa-arrow-left me-2"></i>Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

