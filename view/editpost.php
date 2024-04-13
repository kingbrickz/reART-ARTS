<?php 
 include '../settings/connection.php';
 session_start();
 $user_id = $_SESSION['ID'];
if (isset($_POST['post_id'])) {
    // Sanitize the post ID to prevent SQL injection
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $sql = "SELECT * FROM post WHERE user_id = $user_id AND id = $post_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Output data of the row
        $row = $result->fetch_assoc();
        // Store the content and image path in variables
        $content = $row["content"];
        $image = $row["picture_path"];
    } else {
        // Handle the case where the post is not found or multiple posts are returned
        echo "Error: Post not found or multiple posts found.";
    }
} else {
    // Handle the case where post_id is not set
    echo "Error: Post ID not provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row py-5 px-4">
      <div class="col-md-5 mx-auto">
        <!-- New post form -->
        <div class="bg-white shadow rounded overflow-hidden">
          <div class="px-4 py-3">
            <h5 class="mb-0">Edit Post</h5>
            <form action="../actions/edit_user_post.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="postImage">Update post</label>
                <img src="<?php echo $image; ?>" class="card-img-top" alt="Post Image">
                <input type="file" class="form-control-file" id="postImage" name="image" accept="image/*">
              </div>
              <div class="form-group">
                <label for="postCaption">Caption</label>
                <textarea class="form-control" id="postCaption" name="caption" rows="3"><?php echo $content; ?></textarea>
              </div>
              <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
              <button type="submit" class="btn btn-primary">Update Post</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
