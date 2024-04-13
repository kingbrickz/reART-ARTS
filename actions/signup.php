<?php
// Include the database connection file
include('../settings/connection.php');

// Initialize variables
$email = $password = $confirm_password = "";
$error_array = array();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $error_array[] = "Please enter an email.";
    } else {
        // Prepare a select statement to check if the email already exists
        $sql = "SELECT id FROM USERS WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error_array[] = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                $error_array[] = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $error_array[] = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $error_array[] = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check password confirmation
    if (empty(trim($_POST["confirm_password"]))) {
        $error_array[] = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $error_array[] = "Passwords did not match.";
        }
    }

    // Check if there are no errors, then insert into database
    if (empty($error_array)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password_hash, Created_at) VALUES (?, ?, NOW())";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_email, $param_password);

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header("Location: ../login/Signin.php");
                exit();
            } else {
                $error_array[] = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}

// Redirect to signup page with error messages
$_SESSION['errors'] = $error_array;
$_SESSION['signup_data'] = $_POST;

?>
