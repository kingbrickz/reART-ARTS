<?php
session_start();
include("../settings/connection.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if the required fields are not empty
    if (empty($_POST['caption']) || empty($_FILES["image"]["name"])) {
        // Handle the case where required fields are empty
        echo "Error: Please provide both an image and a caption.";
        exit();
    } else {
        // Get the user ID from the session
        $user_id = $_SESSION["ID"];
        
        // Get the caption from the form
        $caption = $_POST['caption'];
        
        // Generate a unique filename for the image to avoid conflicts
        $image_filename = uniqid() . "_" . $_FILES["image"]["name"];
        
        // Specify the target directory where the image will be stored
        $target_directory = "../assets/images/";
        
        // Specify the full path to the uploaded image file
        $target_path = $target_directory . $image_filename;
        
        // Move the uploaded image file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            // If the image is successfully uploaded, insert the post into the database
            $sql = "INSERT INTO post (user_id, content, picture_path, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iss", $user_id, $caption, $target_path);
                if ($stmt->execute()) {
                    // If the post is successfully inserted into the database, redirect the user
                    header("location: ..//index.php");
                    exit();
                } else {
                    // Handle the case where the post insertion fails
                    echo "Error: Unable to insert post into database.";
                }
                $stmt->close();
            } else {
                // Handle the case where the prepared statement fails
                echo "Error: Unable to prepare statement.";
            }
        } else {
            // Handle the case where the image upload fails
            echo "Error: Unable to upload image.";
        }
    }
}

// Close the database connection
$conn->close();
?>
