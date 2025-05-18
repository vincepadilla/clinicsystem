<?php
session_start();
include_once('./login/config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check if user is logged in
        if (!isset($_SESSION['userID'])) {
            echo "<script>alert('Please login to book an appointment');
            window.location.href='login.php';</script>";
            exit();
        }

        $userID = $_SESSION['userID'];

        //Personal Info
        $fname = mysqli_real_escape_string($con, trim($_POST['fname']));
        $lname = mysqli_real_escape_string($con, trim($_POST['lname']));
        $age = (int)$_POST['age']; 
        $gender = mysqli_real_escape_string($con, trim($_POST['gender']));
        $email = mysqli_real_escape_string($con, trim($_POST['email']));
        $phone = mysqli_real_escape_string($con, trim($_POST['phone'])); 

        //Address
        $street = mysqli_real_escape_string($con, trim($_POST['street'])); 
        $brgy = mysqli_real_escape_string($con, trim($_POST['brgy'])); 
        $city = mysqli_real_escape_string($con, trim($_POST['city'])); 
        $zip = mysqli_real_escape_string($con, trim($_POST['zipCode'])); 

        //Appointment Details
        $service = mysqli_real_escape_string($con, trim($_POST['service']));
        $subService = mysqli_real_escape_string($con, trim($_POST['subService']));
        $dentist = mysqli_real_escape_string($con, trim($_POST['dentist']));
        $date = mysqli_real_escape_string($con, trim($_POST['date']));
        $timeSlot = mysqli_real_escape_string($con, trim($_POST['time']));
        $time = '';

        if ($time_slot === 'firstBatch'){
            $time = '8:00AM-9:00AM';
        } elseif ($time_slot === 'secondBatch'){
            $time = '9:00AM-10:00AM';
        }


         // Payment Details
        $paymentMethod = mysqli_real_escape_string($con, trim($_POST['paymentMethod']));
        $paymentNumber = ''; // Will be set later depending on payment method
        $paymentAmount = 0;  // Will be set later depending on payment method
        $paymentRefNum = ''; // Will be set later depending on payment method
        $paymentAccName = '';

        // GCash or PayMaya Payment Data
        if ($paymentMethod == 'GCash') {
            $paymentAccName = mysqli_real_escape_string($con, trim($_POST['gcashaccName']));
            $paymentNumber = mysqli_real_escape_string($con, trim($_POST['gcashNum']));
            $paymentAmount = (float)$_POST['gcashAmount'];
            $paymentRefNum = mysqli_real_escape_string($con, trim($_POST['gcashrefNum']));
        } elseif ($paymentMethod == 'PayMaya') {
            $paymentAccName = mysqli_real_escape_string($con, trim($_POST['mayaaccName']));
            $paymentNumber = mysqli_real_escape_string($con, trim($_POST['mayaNum']));
            $paymentAmount = (float)$_POST['mayaAmount'];
            $paymentRefNum = mysqli_real_escape_string($con, trim($_POST['mayarefNum']));
        }

        // Image Upload Handling
        $proofImagePath = '';
        if (isset($_FILES['proofImage']) && $_FILES['proofImage']['error'] == UPLOAD_ERR_OK) {
            $img = $_FILES['proofImage'];
            $imgName = basename($img['name']);
            $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($imgExt, $allowed)) {
                $safeName = uniqid() . "_" . preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $imgName);
                $uploadDir = "uploads/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); // create folder if not exists
                }

                $proofImagePath = $uploadDir . $safeName;

                if (!move_uploaded_file($img['tmp_name'], $proofImagePath)) {
                    echo "<script>alert('Failed to upload proof of payment image.'); window.location.href='index.php#appointment';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Invalid file type for proof image.'); window.location.href='index.php#appointment';</script>";
                exit();
            }
        }

        // Basic validation
        // if (empty($fname) || empty($lname) || empty($gender) || empty($email) || empty($phone) || empty($street) || empty($brgy) || empty($city) || empty($zip) || empty($date) 
        // || empty($time) || empty($dentist) || empty($subService) || empty($paymentMethod) || empty($proofImagePath)) {
        //     echo "<script>alert('All required fields must be filled');
        //     window.location.href='index.php#appointment';</script>";
        //     exit();
        // }

        if ($age < 1 || $age > 120) {
            echo "<script>alert('Please enter a valid age (1-120)');
            window.location.href='index.php#appointment';</script>";
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Please enter a valid email address');
            window.location.href='index.php#appointment';</script>";
            exit();
        }

        // Convert service name to service ID
        $serviceMap = [
            "general" => 1,
            "cosmetic" => 2,
            "orthodontics" => 3
        ];

        /*$timeRanges = [
            'firstBatch' => '8:00AM-9:00AM',
            'secondBatch' => '9:00AM-10:00AM',
            'thirdBatch' => '10:00AM-11:00AM',
            'fourthBatch' => '11:00AM-12:00PM',
            'fifthBatch' => '1:00PM-2:00PM',
            'sixthBatch' => '2:00PM-3:00PM',
            'sevenBatch' => '3:00PM-4:00PM',
            'eightBatch' => '4:00PM-5:00PM'
        ];*/

        $service_id = isset($serviceMap[$service]) ? $serviceMap[$service] : 5;
        //$time = $timeRanges[$_POST['time']] ?? null;

          // Insert patient details into the `tbl_patients` table
        $patientsquery = "INSERT INTO tbl_patients (first_name, last_name, email, gender, phone, address) 
        VALUES ('$fname', '$lname', '$email', '$gender', '$phone' , '$street $brgy $city $zip')";

        if (mysqli_query($con, $patientsquery)) {
            // Get the last inserted patient_id
            $patient_id = mysqli_insert_id($con);

            // Now insert the appointment details into the `tbl_appointments` table
            $query = "INSERT INTO tbl_appointments (userID, patient_name, service, dentist, appointment_date, appointment_time, time_slot) 
            VALUES ('$userID','$fname $lname', '$service ($subService)', '$dentist', '$date', '$time', '$time_slot')";

            if (mysqli_query($con, $query)) {
                $appointment_id = mysqli_insert_id($con);

                // Insert payment details into the `tbl_payment` table
                $paymentQuery = "INSERT INTO tbl_payment (appointment_id, user_id, method, account_name, account_number, amount, reference_no ,proof_image) 
                    VALUES ('$appointment_id', '$userID', '$paymentMethod', '$paymentAccName', '$paymentNumber', '$paymentAmount', ' $paymentRefNum', '$proofImagePath')";

                if (mysqli_query($con, $paymentQuery)) {
                    echo "<script>alert('Appointment Successfully Booked. Your ID: $appointment_id');
                    window.location.href='../login/account.php';</script>";
                } else {
                    error_log("Payment insertion error: " . mysqli_error($con)); // Log the error
                    echo "<script>alert('Error processing payment. Please try again.');
                    window.location.href='index.php#appointment';</script>";
                }

            } else {
                error_log("Appointment error: " . mysqli_error($con)); // Log the error
                echo "<script>alert('Error booking appointment. Please try again.');
                window.location.href='index.php#appointment';</script>";
            }

        } else {
            error_log("Patient insertion error: " . mysqli_error($con)); // Log the error
            echo "<script>alert('Error adding patient details. Please try again.');
            window.location.href='index.php#appointment';</script>";
        }

    } else {
        header("Location: index.php");
        exit();
    }

mysqli_close($con);
?>