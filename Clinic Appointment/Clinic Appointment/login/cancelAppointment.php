<?php
include_once('config.php');

$appointment_id = $_GET['id'];

// Cancel appointment
$updateAppointment = $con->prepare("UPDATE tbl_appointments SET status = 'Cancelled' WHERE appointment_id = ?");
$updateAppointment->bind_param("i", $appointment_id);

if ($updateAppointment->execute()) {
    // Find and update payment status if already paid
    $paymentQuery = $con->prepare("SELECT status FROM tbl_payment WHERE appointment_id = ?");
    $paymentQuery->bind_param("i", $appointment_id);
    $paymentQuery->execute();
    $paymentResult = $paymentQuery->get_result();

    if ($paymentResult->num_rows > 0) {
        $payment = $paymentResult->fetch_assoc();
        
        if ($payment['status'] === 'paid') {
            $refund = $con->prepare("UPDATE tbl_payment SET status = 'refund' WHERE appointment_id = ?");
            $refund->bind_param("i", $appointment_id);
            $refund->execute();
            
            echo "<script>alert('Appointment cancelled and payment refunded.'); window.location.href='account.php';</script>";
        } else {
            echo "<script>alert('Appointment cancelled.'); window.location.href='account.php';</script>";
        }
    } else {
        echo "<script>alert('Appointment cancelled.'); window.location.href='account.php';</script>";
    }
} else {
    echo "<script>alert('Error cancelling appointment.'); window.history.back();</script>";
}
?>
