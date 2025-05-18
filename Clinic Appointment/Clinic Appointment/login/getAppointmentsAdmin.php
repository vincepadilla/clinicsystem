<?php
include_once('config.php');
if (isset($_GET['prefer_date'])) {
    $selectedDate = $_GET['prefer_date'];

    $bookedSlots = array();
    $query = "SELECT time_slot FROM tbl_appointments WHERE appointment_date = ?";
    $stmt  = $con->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $bookedSlots[] = $row['time_slot'];
        }
        $stmt->close(); 
    }
    header('Content-Type: application/json');
    echo json_encode($bookedSlots);
}
?>
