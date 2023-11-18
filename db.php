<?php
$servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "FormData";

try {
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>