<?php
/* Initialize the session */
session_start();

/* Check if the user is logged in, if not then redirect him to login page */
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

/* Include config file */
require_once "config.php";

/* Define the logged-in username */
$loggedInUsername = $_SESSION["username"];

/* Attempt to select the logged-in user */
$sql = "SELECT * FROM users WHERE username = ?";

if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $loggedInUsername);

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                // Display user information
                echo "ID: " . $row["id"] . " - Username: " . $row["username"] . "<br>";
            }
        } else{
            echo "No user found.";
        }
    } else{
        echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else{
    echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
}

/* Close connection */
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="assets/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome</h1>
    </div>
    <p>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>
