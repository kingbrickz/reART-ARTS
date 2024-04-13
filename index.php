<?php
// Include the connection settings file
session_start();
include("settings/connection.php");

 ?>
<!DOCTYPE html>


<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>REART</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,
      user-scalable=no">
    <link rel="stylesheet" href="assets/css/main.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </head>
  <body class="is-preload">
    <!-- Wrapper -->
    <div id="wrapper">
      <!-- Header -->
      <header id="header">
        <h1><a href="index.html">REART<br>
          </a></h1>
        <nav class="links">
          <ul>
            <li> <a href="view/profile.php">PROFILE</a><br>
            </li>
            <li><a href="login/Signin.php" moz-do-not-send="true">LOGIN</a><br>
            </li>
          </ul>
        </nav>
    
		
		  
        </article>
    
        <!-- Pagination -->

        <ul class="actions pagination">

        <div> 

        <?php

// Fetch all posts from the database
$sql = "SELECT * FROM post ORDER BY created_at DESC ";
$result = $conn->query($sql);

// Check if there are any posts
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Display each post
        echo '<div class="card" style="width: 18rem;">
        <a href="actions/likes.php?post_id=' . $row['id'] . '&user_id=' . $row['user_id'] . '"> <!-- Link to likes.php with post_id and user_id as parameters -->
        <img src="' . $row['picture_path'] . '" class="card-img-top" alt="...">
    </a>
                    <img src="' . $row['picture_path'] . '" class="card-img-top" alt="...">
                </a>
                <div class="card-body">
                    <h5 class="card-title">' . $row['content'] . '</h5>
                    <p class="card-text">Created at ' . $row['created_at'] . '</p>
                </div>
            </div>';
    }
} else {
    // Display a message if there are no posts
    echo "No posts available.";
}

?>

        </div>
             <!-- Post -->
	
        </ul>
      </div>
      <!-- Sidebar -->
      <section id="sidebar">
        <!-- Intro -->
        <section id="intro"> <a href="#" class="logo"><img
              src="images/logo.jpg" alt=""></a>
          <header>
            <h2>REART<br>
            </h2>
            <p>A WEBSITE THAT REDEFINES HOW ART IS CONSUMED <br>
            </p>
          </header>

        </section>
       
        <!-- About -->
        <section class="blurb">
          <h2>About</h2>
          <p>Mauris neque quam, fermentum ut nisl vitae, convallis
            maximus nisl. Sed mattis nunc id lorem euismod amet
            placerat. Vivamus porttitor magna enim, ac accumsan tortor
            cursus at phasellus sed ultricies.</p>


       
        </section>
       
        </section>
      </section>
    </div>
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
    // Add event listener to all images with class 'card-img-top'
    var images = document.querySelectorAll('.card-img-top');
    images.forEach(function(image) {
        image.addEventListener('dblclick', function() {
            var postId = this.id.split('_')[1]; // Extract postId from the image id
            var userId = <?php echo $user_Id; ?>; // Assuming you have the logged in user's ID stored in $loggedInUserId

            // Send AJAX request to update likes
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_likes.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Update UI or handle response
                        console.log(xhr.responseText);
                    } else {
                        // Handle error
                        console.error('Error occurred:', xhr.status);
                    }
                }
            };
            xhr.send('postId=' + postId + '&userId=' + userId);
        });
    });
</script>
  </body>
</html>
