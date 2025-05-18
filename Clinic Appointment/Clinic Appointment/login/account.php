<?php
    session_start();

    include_once("config.php");
    define("TITLE", "My Account");
    include_once('../header.php');

    // Redirect to login if not logged in
    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit();
    }

    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'] ?? 'Not available';

    // Fetch the latest appointment using userID instead of patient_name
    $appointment = null;
    $stmt = $con->prepare("
        SELECT * FROM tbl_appointments 
        WHERE userID = ? 
        ORDER BY appointment_date DESC, appointment_time DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $userID);  // "i" for integer type
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
    }
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="accountstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="container">
        <!-- Account action buttons at the top -->
        <div class="account-actions">
            <a href="edit_account.php" class="btn btn-warning">Edit Account</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>

        <div class="card">
            <h2 class="card-title">Account Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Username</strong>
                    <?php echo htmlspecialchars($username); ?>
                </div>
                <div class="info-item">
                    <strong>User ID</strong>
                    <?php echo htmlspecialchars($userID); ?>
                </div>
                <div class="info-item">
                    <strong>Email</strong>
                    <?php echo htmlspecialchars($email); ?>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Your Recent Appointment</h2>
            <?php if ($appointment) { ?>
                <div class="appointment-details">
                <p><strong>Appointment ID:</strong> <?php echo htmlspecialchars($appointment['appointment_id']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                    <p><strong>Time:</strong> <?php echo htmlspecialchars($appointment['appointment_time']); ?></p>
                    <p><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service']); ?></p>
                    <p><strong>Dentist:</strong> <?php echo htmlspecialchars($appointment['dentist']); ?></p>
                    <?php
                        $statusClass = '';
                        switch ($appointment['status']) {
                            case 'Scheduled':
                                $statusClass = 'status-scheduled';
                                break;
                            case 'Confirmed':
                                $statusClass = 'status-confirmed';
                                break;
                            case 'Cancelled':
                                $statusClass = 'status-cancelled';
                                break;
                            default:
                                $statusClass = 'status-default';
                        }
                    ?>
                    <p class="status-badge <?php echo $statusClass; ?>">
                        <strong>Status:</strong> <?php echo htmlspecialchars($appointment['status']); ?>
                    </p>

                    <?php
                        if($appointment['status'] == "Reschedule") {
                            echo "<p><strong>Your preferred date and time have been rescheduled due to some conflict or the dentist's availability. 
                            If you are not available on the new date and time, please click reschedule to select your preferred schedule.</strong></p>";

                        } else if($appointment['status'] == "Scheduled") {
                            echo "<p><strong>Your appointment has been scheduled. Please wait for the confirmation to ensure the date and time are finalized.</strong></p>";


                        } else if($appointment['status'] == "Confirmed") {
                            echo "<p><strong>Your appointment has been confirmed.</strong></p>";
                        }
                    ?>
                </div>
                
                <?php if ($appointment['status'] != 'Cancelled') { ?>
                    <div class="action-buttons">
                        <a href="cancelAppointment.php?id=<?php echo $appointment['appointment_id']; ?>" 
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel your appointment? Your payment will be refunded if already paid.');">
                            Cancel Appointment
                        </a>

                        <a href="reschedule.php?id=<?php echo $appointment['appointment_id']; ?>" 
                            class="btn btn-primary <?php echo ($appointment['status'] == 'Confirmed') ? 'disabled' : ''; ?>" 
                            id="reschedBtn"
                            data-id="<?php echo $appointment['appointment_id']; ?>"
                            <?php echo ($appointment['status'] == 'Confirmed') ? 'onclick="return false;"' : ''; ?>>
                            Reschedule Appointment
                        </a>
                        
                        <a href="printReceipt.php?id=<?php echo $appointment['appointment_id']; ?>" 
                            class="btn btn-print <?php echo ($appointment['status'] == 'Scheduled') ? 'disabled' : ''; ?>" 
                            <?php echo ($appointment['status'] == 'Scheduled') ? 'onclick="return false;"' : ''; ?>>
                            Print Receipt
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="action-buttons" style="opacity: 0.6; pointer-events: none;">
                        <a class="btn btn-danger disabled">Cancel Appointment</a>
                        <a class="btn btn-primary disabled">Reschedule Appointment</a>
                        <a class="btn btn-print disabled">Print Receipt</a>
                    </div>
                    <p><em>This appointment has been cancelled. No further actions are available.</em></p>
                <?php } ?>

            
            <?php } else { ?>
                <div class="no-appointment">
                    <p>You have no recent appointments.</p>
                    <a href="../index.php" class="btn btn-primary">Book an Appointment</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="reschedModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeReschedModal()">&times;</span>
            <h3>Reschedule Appointment</h3>
            <form action="reschedule.php" method="POST">
                <input type="hidden" id="modalAppointmentID" name="appointment_id">

                <label for="new_date">Select New Date:</label>
                <input type="date" id="new_date" name="new_date" required>

                <label for="new_time">Select New Time:</label>
                <select id="new_time" name="new_time" required>
                    <option value="">Select a time</option>
                        <option value="firstBatch">Morning (8AM-9AM)</option>
                        <option value="secondBatch">Morning (9AM-10AM)</option>
                        <option value="thirdBatch">Morning (10AM-11AM)</option>
                        <option value="fourthBatch">Afternoon (11AM-12PM)</option>
                        <option value="fifthBatch">Afternoon (1PM-2PM)</option>
                        <option value="sixthBatch">Afternoon (2PM-3PM)</option>
                        <option value="seventhBatch">Afternoon (3PM-4PM)</option>
                        <option value="eighthBatch">Afternoon (4PM-5PM)</option>
                        <option value="ninethBatch">Afternoon (5PM-6PM)</option>
                        <option value="tenthBatch">Evening (6PM-7PM)</option>
                        <option value="lastBatch">Evening (7PM-8PM)</option>
                </select>

                <button type="submit" class="btn btn-success">CONFIRM SCHEDULE</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
        // Function that polls the backend for the selected date
        function checkAvailability() {
            var selectedDate = $("#new_date").val();
            if (selectedDate) {
                $.ajax({
                    url: 'getAppointmentsAccount.php',
                    type: 'GET',
                    data: { new_date: selectedDate },
                    dataType: 'json',
                    success: function(bookedSlots) {
                        // First, enable all options.
                        $("#new_time option").prop("disabled", false);
                        // Then disable the options that are booked.
                        $.each(bookedSlots, function(index, slot) {
                            $("#new_time option[value='" + slot + "']").prop("disabled", true);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching appointment data:", error);
                    }
                });
            }
        }

        // Trigger checkAvailability when the date changes.
        $("#new_date").on("change", function(){
            checkAvailability();
        });

        setInterval(function(){
            checkAvailability();
        }, 100);
    
        });

        document.getElementById('reschedBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Stop navigation
            const appointmentID = this.getAttribute('data-id');
            document.getElementById('modalAppointmentID').value = appointmentID;

            // Set min date to tomorrow
            const dateInput = document.getElementById('new_date');
            const today = new Date();
            today.setDate(today.getDate() + 1); // add 1 day
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            dateInput.min = `${yyyy}-${mm}-${dd}`;

            openReschedModal();
        });

        function openReschedModal() {
            document.getElementById("reschedModal").style.display = "block";
        }

        function closeReschedModal() {
            document.getElementById("reschedModal").style.display = "none";
        }

        // Optional: Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById("reschedModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };


    </script>

    <?php include_once('../footer.php');?>

</body>
</html>