<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

// 1. Fetch all halls for the dropdown list
 $hall_list_result = mysqli_query($conn, "SELECT id, hall_name FROM halls ORDER BY hall_name ASC");

// 2. Initialize variables
 $report_data = null;
 $report_title = "";
 $is_print_mode = false;

// 3. Logic: Check if a report is being requested
if (isset($_GET['hall_id']) && !empty($_GET['hall_id'])) {
    // --- HALL SPECIFIC REPORT ---
    $hall_id = (int)$_GET['hall_id'];
    $is_print_mode = true;

    // Get Hall Name
    $hall_res = mysqli_query($conn, "SELECT hall_name, capacity FROM halls WHERE id = $hall_id");
    $hall_info = mysqli_fetch_assoc($hall_res);
    $report_title = "Seating Arrangement - " . $hall_info['hall_name'];

    // Get Students
    $sql = "SELECT s.register_number, s.name, s.department, a.seat_number 
            FROM seating_allotment a 
            JOIN students s ON a.student_id = s.id 
            WHERE a.hall_id = ? 
            ORDER BY a.seat_number ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $hall_id);
    mysqli_stmt_execute($stmt);
    $report_data = mysqli_stmt_get_result($stmt);

} elseif (isset($_GET['master_list'])) {
    // --- MASTER LIST REPORT ---
    $is_print_mode = true;
    $report_title = "Master Seating List (All Halls)";

    $sql = "SELECT h.hall_name, s.register_number, s.name, s.department, a.seat_number 
            FROM seating_allotment a 
            JOIN students s ON a.student_id = s.id 
            JOIN halls h ON a.hall_id = h.id 
            ORDER BY h.hall_name ASC, a.seat_number ASC";
    $report_data = mysqli_query($conn, $sql);
}
?>

<?php include '../includes/header.php'; ?>

<!-- CSS for Printing -->
<style>
    /* Hide navigation and buttons when printing */
    @media print {
        .navbar, .no-print, footer { display: none !important; }
        body { background-color: white; }
        .main-content { padding: 0 !important; }
        .report-table { box-shadow: none !important; border: 1px solid #000; }
    }

    /* Report Table Styling */
    .report-container {
        background: white;
        padding: 20px;
        border: 1px solid #dee2e6;
    }
    .report-header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #000;
        padding-bottom: 15px;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .report-table th, .report-table td {
        border: 1px solid #333;
        padding: 8px 12px;
        font-size: 14px;
    }
    .report-table th {
        background-color: #f8f9fa;
    }
</style>

<div class="container">

    <?php if ($is_print_mode): ?>
        <!-- ================= VIEW: PRINT MODE ================= -->
        
        <div class="no-print mb-3">
            <a href="reports.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Options</a>
            <button onclick="window.print()" class="btn btn-primary float-end"><i class="fa-solid fa-print"></i> Print / PDF</button>
        </div>

        <div class="report-container">
            <div class="report-header">
                <h3><?php echo $report_title; ?></h3>
                <p class="mb-0"><strong>Date:</strong> <?php echo date('d-m-Y'); ?></p>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <?php if (isset($_GET['master_list'])): ?>
                            <th>Hall</th>
                        <?php endif; ?>
                        <th>Seat No</th>
                        <th>Register No</th>
                        <th>Name</th>
                        <th>Department</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php if ($report_data && mysqli_num_rows($report_data) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($report_data)): ?>
                            <tr>
                                <?php if (isset($_GET['master_list'])): ?>
                                    <td><?php echo $row['hall_name']; ?></td>
                                <?php endif; ?>
                                <td style="text-align:center; font-weight:bold;"><?php echo $row['seat_number']; ?></td>
                                <td><?php echo $row['register_number']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">No data found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <!-- ================= VIEW: SELECTION MODE ================= -->

        <h2 class="mb-4 fw-bold">Reports & Prints</h2>
        <p class="text-muted">Select a report type to generate a printable document.</p>

        <div class="row mt-4">
            <!-- Card 1: Hall Report -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-primary">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-primary"><i class="fa-solid fa-building me-2"></i>Hall Seating Report</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Generates a single hall list with a signature column. Ideal for pasting on doors.</p>
                        <form action="" method="get">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Hall:</label>
                                <select name="hall_id" class="form-select" required>
                                    <option value="" disabled selected>-- Select Hall --</option>
                                    <?php 
                                    // Reset pointer for dropdown
                                    mysqli_data_seek($hall_list_result, 0);
                                    while ($row = mysqli_fetch_assoc($hall_list_result)): 
                                    ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['hall_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-print me-2"></i>Generate
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card 2: Master List -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-success">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-success"><i class="fa-solid fa-list-ol me-2"></i>Master Allotment List</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Generates a combined list of all students across all halls. Ideal for office records.</p>
                        <div class="d-grid">
                            <a href="?master_list=true" class="btn btn-success">
                                <i class="fa-solid fa-file-export me-2"></i>Generate Master List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
