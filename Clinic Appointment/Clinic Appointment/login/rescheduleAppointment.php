<?php
include_once("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointment_id = $_POST['appointment_id'] ?? null;
    $new_date = $_POST['new_date'] ?? null;
    $new_time = $_POST['new_time'] ?? null;

    // Validate input
    if ($appointment_id && $new_date && $new_time) {

        // Optional: check if date is in the past
        $today = date("Y-m-d");
        if ($new_date < $today) {
            echo '<script>
                alert("New date cannot be in the past.");
                window.location.href = "admin.php?reschedule=invalid_date";
            </script>';
            exit();
        }

        // Perform update
        $stmt = $con->prepare("
            UPDATE tbl_appointments 
            SET appointment_date = ?, 
                appointment_time = ?, 
                status = 'Reschedule'
            WHERE appointment_id = ?
        ");
        $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo '<script>
                alert("Appointment has been successfully rescheduled!");
                window.location.href = "admin.php?reschedule=success";
            </script>';
        } else {
            echo '<script>
                alert("No changes were made. The data may be identical or appointment not found.");
                window.location.href = "admin.php?reschedule=failed";
            </script>';
        }

        $stmt->close();
        $con->close();

    } else {
        echo '<script>
            alert("Please complete all required fields.");
            window.location.href = "admin.php?reschedule=missing_fields";
        </script>';
    }
} else {
    header("Location: admin.php");
    exit();
}
?>
