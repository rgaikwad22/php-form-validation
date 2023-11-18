<?php
require_once "db.php";
// Check if the form is submitted for updating data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process the updated form data...

    $id = $_POST['id'];
    $newFirstName = $_POST['firstname'];
    $newLastName = $_POST['lastname'];
    $newEmail = $_POST['email'];
    $newGender = $_POST['gender'];
    $newCity = $_POST['city'];

    $sql = "UPDATE form SET FirstName='$newFirstName', LastName='$newLastName', Email='$newEmail', Gender='$newGender', City='$newCity' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: display.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing data based on the provided ID
    $sql = "SELECT id, FirstName, LastName, Email, Gender, City FROM form WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $existingFirstName = $row['FirstName'];
        $existingLastName = $row['LastName'];
        $existingEmail = $row['Email'];
        $existingGender = $row['Gender'];
        $existingCity = $row['City'];
    } else {
        echo "Record not found";
        exit();
    }
} else {
    echo "ID not provided";
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Form Data</title>
</head>
<body>

<h2>Edit Form Data</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div>
        <label for="newFirstName">New First Name:</label>
        <input type="text" id="newFirstName" name="newFirstName" value="<?php echo $existingFirstName; ?>">
    </div>
    <div>
        <label for="newLastName">New Last Name:</label>
        <input type="text" id="newLastName" name="newLastName" value="<?php echo $existingLastName; ?>">
    </div>
    <div>
        <label for="newEmail">New Email:</label>
        <input type="email" id="newEmail" name="newEmail" value="<?php echo $existingEmail; ?>">
    </div>
    <div>
        <label for="newGender">New Gender:</label>
        <input type="text" id="newGender" name="newGender" value="<?php echo $existingGender; ?>">
    </div>
    <div>
        <label for="newCity">New City:</label>
        <input type="text" id="newCity" name="newCity" value="<?php echo $existingCity; ?>">
    </div>
    <input type="submit" value="Update">
</form>

</body>
</html>

?>