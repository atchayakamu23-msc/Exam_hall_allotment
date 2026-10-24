<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

$message = "";

if (isset($_POST['generate'])) {
    // 1. Clear previous seating
    mysqli_query($conn, "TRUNCATE TABLE seating_allotment");

    // 2. Fetch Halls
    $halls_result = mysqli_query($conn, "SELECT * FROM halls ORDER BY id ASC");
    $halls = [];
    while ($row = mysqli_fetch_assoc($halls_result)) {
        $halls[] = $row;
    }

    if (count($halls) == 0) {
        $message = "Error: No halls available.";
    } else {
        // 3. Fetch Students grouped by department
        $students_result = mysqli_query($conn, "SELECT * FROM students ORDER BY department ASC, id ASC");
        $students_by_department = [];
        while ($student = mysqli_fetch_assoc($students_result)) {
            $dept = $student['department'];
            $students_by_department[$dept][] = $student;
        }

        // 4. Interleave students from departments
        $interleaved_students = [];
        while (count($students_by_department) > 0) {
            foreach ($students_by_department as $dept => $students) {
                if (!empty($students)) {
                    $interleaved_students[] = array_shift($students_by_department[$dept]);
                }
                if (empty($students_by_department[$dept])) {
                    unset($students_by_department[$dept]);
                }
            }
        }

        // 5. Assign seats hall by hall
        $total_halls = count($halls);
        $current_hall_index = 0;
        $current_seat_number = 1;
        $current_hall_capacity = $halls[$current_hall_index]['capacity'];
        $current_hall_id = $halls[$current_hall_index]['id'];

        foreach ($interleaved_students as $student) {
            $student_id = $student['id'];

            if ($current_seat_number > $current_hall_capacity) {
                $current_hall_index++;
                if ($current_hall_index >= $total_halls) {
                    $message = "Warning: Out of capacity. Some students not seated.";
                    break; 
                }
                $current_hall_id = $halls[$current_hall_index]['id'];
                $current_hall_capacity = $halls[$current_hall_index]['capacity'];
                $current_seat_number = 1;
            }

            $insert_sql = "INSERT INTO seating_allotment (student_id, hall_id, seat_number) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($stmt, "iii", $student_id, $current_hall_id, $current_seat_number);
            mysqli_stmt_execute($stmt);

            $current_seat_number++;
        }

        if (empty($message)) {
            $message = "Success! Seating arrangement generated with department mixing.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg text-center p-5">
                <div class="card-body">
                    <i class="fa-solid fa-wand-magic-sparkles fa-4x text-primary mb-4"></i>
                    <h2>Generate Seating</h2>
                    <p class="text-muted">
                        This action will <strong>remove</strong> all existing arrangements and create new ones 
                        based on current student and hall data, mixing departments across halls.
                    </p>
                    
                    <form method="post" class="d-inline-block mt-4">
                        <button type="submit" name="generate" class="btn btn-lg btn-success shadow">
                            <i class="fa-solid fa-check me-2"></i> Confirm & Generate
                        </button>
                    </form>

                    <?php if ($message): ?>
                        <div class="alert alert-info mt-4"><?php echo $message; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
