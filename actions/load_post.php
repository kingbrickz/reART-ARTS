<!-- Add this PHP code to retrieve and display all posts -->
<?php
// Include the database connection file
include("../settings/connection.php");

// Query to select all posts from the database
$sql = "SELECT * FROM POST ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each row in the result set
    while($row = $result->fetch_assoc()) {
        // Get the post data
        $picture_path = $row['picture_path'];
        $content = $row['content'];

        // Output the post HTML
        echo "<div class='col-lg-6 mb-2 pr-lg-1'>";
        echo "<img src='$picture_path' alt='' class='img-fluid rounded shadow-sm' onclick='displayModal(\"$picture_path\")'>";
        echo "<p class='caption'>$content</p>";
        echo "</div>";
    }
} else {
    // If no posts are found in the database
    echo "No posts found.";
}

// Close the database connection
$conn->close();
?>
