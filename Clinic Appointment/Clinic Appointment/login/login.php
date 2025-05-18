<?php
session_start();
include_once("config.php"); 

if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $name = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = mysqli_real_escape_string($con, trim($_POST['password']));

    if (!empty($name) && !empty($password)) {
        // Query to check if user exists
        $query = "SELECT * FROM users WHERE name='$name'"; 
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $row['password_hash'])) { 
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['userID'] = $row['user_id']; 
                $_SESSION['valid'] = true;  
                $_SESSION['username'] = $row['name'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['email'] = $row['email'];

                // Redirect to the homepage or dashboard after successful login
                header("Location: ../index.php");
                exit();
            } else {
                $error = "Wrong password. Please try again.";
            }
        } else {
            $error = "No account found with that username. Please sign up.";
        }
    } else {
        $error = "Please fill in all fields.";
    }

    if($name == "admin" && $password == "admin") {
        header("Location: admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginpagestyle.css">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container">
        <div class="left">
            <div class="logo">
                <img src="../logo.png">
            </div>
        </div>
        <div class="right">
            <div class="form-box">
                <header>Login</header>
                <!-- Error message display -->
                <?php if (isset($error) && !empty($error)) { ?>
                    <div class="message"><p><?php echo $error; ?></p></div><br>
                <?php } ?>
                <form action="" method="post">
                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" autocomplete="off" required>
                    </div>
                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" required>
                    </div>
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Login">
                    </div>
                    <div class="links">
                        Don't have an account? <a href="register.php">Sign Up Now</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
