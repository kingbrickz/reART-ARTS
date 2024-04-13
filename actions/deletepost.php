<?php
// Include database connection
include '../settings/connection.php';

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the post ID is provided in the request parameters
    if (isset($_POST['post_id'])) {
        // Sanitize the post ID to prevent SQL injection
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

        // SQL query to delete the post from the database
        $sql = "DELETE FROM post WHERE id = $post_id";
        $sqli = "DELETE FROM likes WHERE postId = $post_id";


        // Execute the delete query
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn, $sqli);
            
            // Post deleted successfully
            header("Location: ../view/profile.php");


        } else {
            // Error deleting post
            echo "Error deleting post: " . mysqli_error($conn);
        }
    } else {
        // Post ID is not provided in the request parameters
        echo "Post ID is required.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}

// Close database connection
mysqli_close($conn);
?>
