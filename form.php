<!-- php starts here  -->
<?php
require_once "db.php";

// variable created globally
$fnameErr = $lnameErr = $emailErr = $genderErr = $cityErr = $fileErr = $upload = "";
$fname = $lname = $email = $gender = $city = "";
$edit_id = null;

// Check if edit_id is present in the URL
if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];

    // Fetch existing data for editing
    $editDataQuery = "SELECT * FROM form WHERE id = $edit_id";
    $editDataResult = mysqli_query($conn, $editDataQuery);

    try {
        if (!$editDataResult) {
            throw new Exception("Error fetching data for editing: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($editDataResult) > 0) {
            $editData = mysqli_fetch_assoc($editDataResult);
            $fname = $editData['FirstName'];
            $lname = $editData['LastName'];
            $email = $editData['Email'];
            $gender = $editData['Gender'];
            $city = $editData['City'];
        } else {
            throw new Exception("No data found for editing with ID: $edit_id");
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fnameval = $_POST["firstname"];
    $lnameval = $_POST["lastname"];
    $emailval = $_POST["email"];
    $genderval = $_POST["gender"];
    $cityval = $_POST['city'];

    $fname = test_input($fnameval);
    $lname = test_input($lnameval);
    $email = test_input($emailval);
    $gender = test_input($genderval);
    $city = test_input($cityval);

    $chName = checkName($fnameval, $fnameErr, "First name is required.");
    $chLName = checkName($lnameval, $lnameErr, "Last name is required.");
    $chEmail = checkEmail($emailval, $emailErr);
    $chMulty = checkMultiple($genderval, $genderErr, "Please select your gender.");
    $chMultyCity = checkMultiple($cityval, $cityErr, "Please select your city.");
    $chFile = checkFile();

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $edit_id = mysqli_real_escape_string($conn, $_POST['id']);
        try {
            if (validate()) {
                // Update existing entry using prepared statement
                $sql = "UPDATE form SET FirstName=?, LastName=?, Email=?, Gender=?, City=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . mysqli_error($conn));
                }

                // Bind parameters to the prepared statement
                mysqli_stmt_bind_param($stmt, "sssssi", $fname, $lname, $email, $gender, $city, $edit_id);

                // Execute the prepared statement
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    echo "<h2>Data Updated Successfully</h2>";

                    // Redirect to the display page or any other page
                    header("Location: display.php");
                    exit();
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
                // Close the prepared statement
                mysqli_stmt_close($stmt);
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    } else {
        try {
            if (validate()) {
                // Prepare the SQL statement
                $sql = "INSERT INTO form (FirstName, LastName, Email, Gender, File, City) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . mysqli_error($conn));
                }

                // Bind parameters to the prepared statement
                mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $email, $gender, $filename, $city);

                // Execute the prepared statement
                $result = mysqli_stmt_execute($stmt);

                if (!$result) {
                    throw new Exception("Error inserting record: " . mysqli_stmt_error($stmt));
                }

                // Close statement
                mysqli_stmt_close($stmt);

                // Close connection
                mysqli_close($conn);
                header("Location: display.php");
                exit();
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }
} else {
    try {
        // If unauthorized access, throw an exception
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            throw new Exception("Invalid Request Method");
        }
    } catch (Exception $e) {
        // Handle unauthorized access exception
        echo "Error: " . $e->getMessage();
    }
}

// functions stats here 
function validate() {
    global $chName, $chLName, $chEmail, $chMulty, $chMultyCity, $chFile;

    if ($chName && $chLName && $chEmail && $chMulty && $chMultyCity && $chFile) {
        return true;
    }
    return false;
}

function checkName($val, &$error, $errMessage) {
    if (empty($val)) {
        $error = $errMessage;
    } else if (!preg_match("/^[a-zA-Z-']*$/", $val)) {
        $error = "Please enter valid name.";
        return false;
    } else if (strlen($val) < 5 || strlen($val) > 15) {
        $error = "Length of name shouled be greater than 5 and less than 15.";
        return false;
    }
    return true;
}

function checkEmail($emailval, &$error) {
    global $conn;
    $email = mysqli_real_escape_string($conn, $emailval);
    $checkQuery = "SELECT * FROM form WHERE Email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $error = "Error: Email already exists!";
    } else if (empty($emailval)) {
        $error = "Email is required.";
        return false;
    } else if (!preg_match("/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $emailval)) {
        $error = "Please enter valid valid email.";
        return false;
    }
    return true;
}

function checkMultiple($val, &$error, $select) {
    if (empty($val)) {
        $error = $select;
        return false;
    }
    return true;
}

function checkFile() {
    global $filename, $upload;
    // file validation starts 
    $filename = $_FILES["doc"]["name"];
    $tempname = $_FILES["doc"]["tmp_name"];
    $filesize = $_FILES["doc"]["size"];
    $fileext = explode(".", $filename);
    $fileextcheck = strtolower(end($fileext));
    $extensions = array("pdf", "png");

    if (in_array($fileextcheck, $extensions) === true && $filesize < 2097152) {
        move_uploaded_file($tempname, "uploads/" . $filename);
        $upload = "File uploaded successfully";
        return true;
    } else {
        $upload = "File extension should be .png or .pdf or File size less than 2MB";
        return false;
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!-- php ends here  -->