
<?php
include("../settings/connection.php");

// Check if the request contains postId and userId
if (isset($_GET['post_id']) && isset($_GET['user_id'])) {
    // Extract postId and userId from the request
    $postId = $_GET['post_id'];
    $userId = $_GET['user_id'];



    // Check if the user already liked the post
    $checkQuery = "SELECT * FROM likes WHERE postId = '$postId' AND userId = '$userId'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows == 0) {
        // If the user hasn't liked the post yet, insert a new record in the likes table
        $insertQuery = "INSERT INTO likes (postId, userId) VALUES ('$postId', '$userId')";
        if ($conn->query($insertQuery) === TRUE) {
            // Update the likes count in the posts table
            $updateQuery = "UPDATE post SET likes = likes + 1 WHERE id = '$postId'";
            if ($conn->query($updateQuery) === TRUE) {
                echo "Likes updated successfully.";
            } else {
                echo "Error updating likes: " . $conn->error;
            }
        } else {
            echo "Error updating likes: " . $conn->error;
        }
    } else {
        // If the user already liked the post, do nothing
        echo "User already liked this post.";
    }

    // Close database connection
    $conn->close();
} else {
    // If postId or userId is not provided in the request, return an error message
    echo "Error: postId and userId are required.";
}

?>