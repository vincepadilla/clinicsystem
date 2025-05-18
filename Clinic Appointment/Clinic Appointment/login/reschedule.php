<?php
include_once("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointment_id = $_POST['appointment_id'] ?? null;
    $new_date = $_POST['new_date'] ?? null;
    $new_time = $_POST['new_time'] ?? null;

    if ($appointment_id && $new_date && $new_time) {
        $stmt = $con->prepare("
            UPDATE tbl_appointments 
            SET appointment_date = ?, appointment_time = ?, status = 'scheduled' 
            WHERE appointment_id = ?
        ");
        $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // JavaScript alert before redirect
            echo '<script>
                alert("Appointment rescheduled successfully!");
                window.location.href = "account.php?reschedule=success";
            </script>';
        } else {
            echo '<script>
                alert("Failed to reschedule the appointment. Please try again.");
                window.location.href = "account.php?reschedule=failed";
            </script>';
        }

        $stmt->close();
    } else {
        echo '<script>
            alert("Invalid input. Please fill out all required fields.");
            window.location.href = "account.php?reschedule=invalid";
        </script>';
    }
} else {
    header("Location: account.php");
    exit();
}
?>
