<?php
require_once "db.php";

try {
    // Fetch data from the database
    $sql = "SELECT id, FirstName, LastName, Email, Gender, File, City FROM form";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error fetching data: " . $conn->error);
    }

    // Display the fetched data in a table
    if ($result->num_rows > 0) {
        echo "<h2>Form Data</h2>". "<h2><a href='index.php'>Add New Entry</a></h2>";
        echo "<table border='1'>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Gender</th><th>File</th><th>City</th><th>Actions</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['FirstName']}</td>";
            echo "<td>{$row['LastName']}</td>";
            echo "<td>{$row['Email']}</td>";
            echo "<td>{$row['Gender']}</td>";
            echo "<td>{$row['File']}</td>";
            echo "<td>{$row['City']}</td>";
            echo "<td><a href='index.php?id={$row['id']}'>Edit</a> | <a href='delete.php?id={$row['id']}'>Delete</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No data available";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>