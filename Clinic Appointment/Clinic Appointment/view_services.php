<?php
include_once('./login/config.php'); 
define("TITLE", "View Services");
include_once('header.php');

// Fetch services data
$sql = "SELECT service_id, service_name, description, price FROM tbl_services";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Koulen&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_forward"/>

    <title>Available Dental Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #48A6A7;
            --secondary-color: #264653;
            --accent-color:rgb(255, 226, 151);
            --light-color: #F2EFE7;
            --dark-color: #343a40;
            --text-color: #333;
            --white: #fff;
            --shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Metropolis';
            background-color: var(--light-color);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
        }

        h2 {
            margin-top: 20px;
            color: var(--secondary-color);
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: var(--white);
            box-shadow: var(--shadow);
            margin-bottom: 12em;
        }

        th, td {
            border: 1px solid var(--dark-color);
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: var(--primary-color);
            color: var(--white);
        }

        tr:nth-child(even) {
            background-color: var(--light-color);
        }

        tr:hover {
            background-color: var(--accent-color);
            transition: var(--transition);
        }
    </style>
</head>

<body>

    <h2>AVAILABLE SERVICES</h2>

    <div class="container">
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price (PHP)</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["service_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["service_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                    echo "<td>₱" . number_format($row["price"], 2) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No services available at the moment.</td></tr>";
            }
            ?>
        </table>
    </div>

<?php include_once('footer.php');?>

</body>
</html>
