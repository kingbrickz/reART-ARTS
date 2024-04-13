<?php

// Include the database connection file
include('../settings/connection.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email and password from the request body
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate email and password (you may add more validation as needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Invalid email format
       echo "Invalid email format";
    }

    

    // Check connection
    if ($conn->connect_error) {
       echo "Connection error";
    }

    // Prepare SQL statement to retrieve user data by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the given email exists
    if ($result->num_rows > 0) {
        // Retrieve the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user["password_hash"])) {
            session_start();
            $_SESSION['ID'] = $user['ID'];
            // Password is correct, login successful
            // You can customize the response as needed
           header("Location: ../index.php");
        } else {
            // Incorrect password
            echo "Invalid email or password.";
        }
    } else {
        // User with the given email not found
        echo "User with the given email not found.";
    }

    // Close database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    echo "Invalid request method";
}

?>
