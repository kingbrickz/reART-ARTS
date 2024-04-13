<?php
  include '../settings/connection.php';
  session_start();
  $user_id = $_SESSION['ID'];
// SQL query to get the total likes for the user
$sql = "SELECT COUNT(userId) as total_likes FROM likes WHERE userId = ?";

// Prepare and bind the SQL statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming $userId contains the ID of the user

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch the total likes
if ($row = $result->fetch_assoc()) {
    $totalLikes = $row['total_likes'];
} else {
    $totalLikes = 0;
}

// Close the statement
$stmt->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./css/profile.css"> <!-- Added profile.css -->
  <link rel="stylesheet" href="assets/css/main.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
  <title>User Profile</title>
  

<body>
  
  <!-- Header -->
  <header id="header">
        <h1><a href="../index.php">REART<br>
          </a></h1>
        <nav class="links">
          <ul>
            <li> <a href="profile.php">PROFILE</a><br>
            </li>
            <li><a href="../login/Signin.php" moz-do-not-send="true">LOGIN</a><br>
            </li>
          </ul>
        </nav>
    
<div class="row py-5 px-4">
  <div class="col-md-5 mx-auto">
    <!-- Profile widget -->
    <div class="bg-white shadow rounded overflow-hidden">
      <div class="px-4 pt-0 pb-4 cover">
        <div class="media align-items-end profile-head">
          <div class="profile mr-3">
            <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80" alt="..." width="130" class="rounded mb-2 img-thumbnail">
            <button class="btn btn-outline-dark btn-sm btn-block" onclick="openProfilePictureModal()">Edit profile</button>

          </div>
          <div class="media-body mb-5 text-white">
            <h4 class="mt-0 mb-0">Mark Williams</h4>
            <p class="small mb-4">
              <i class="fas fa-map-marker-alt mr-2"></i>New York
            </p>
          </div>
          <p>Total Likes: <?php echo $totalLikes; ?></p>

        </div>
      </div>


      <div class="bg-light p-4 d-flex justify-content-end text-center">
        <ul class="list-inline mb-0">
          <li class="list-inline-item">
          
            </small>
          
        </ul>
      </div>
     

     <!-- New post form -->
     <div class="px-4 py-3">
        <h5 class="mb-0">Create a New Post</h5>
        <form action="../actions/create_post.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="postImage">Upload Image</label>
            <input type="file" class="form-control-file" id="postImage" name="image" accept="image/*" required>
          </div>
          <div class="form-group">
            <label for="postCaption">Caption</label>
            <textarea class="form-control" id="postCaption" name="caption" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Post</button>
        </form>
      </div>
</div>
<h5 class="mb-0">Recent photos</h5>

        <!-- Post -->
        <?php

// Fetch all posts from the database
$sql = "SELECT * FROM post WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if there are any posts
if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
      // Display each post
      echo '<div class="card" style="width: 18rem;">
              <input type="hidden" name="id" value="' . $row['id'] . '" />
              <img src="' . $row['picture_path'] . '" class="card-img-top" alt="...">
              <div class="card-body">
                  <h5 class="card-title">' . $row['content'] . '</h5>
                  <p class="card-text">Created at ' . $row['created_at'] . '</p>
                  <div class="btn-group" role="group" aria-label="Post Actions">
                      <form action="../view/editpost.php" method="post" style="margin-right: 5px;">
                          <input type="hidden" name="post_id" value="' . $row['id'] . '">
                          <button type="submit" class="btn btn-primary">Edit</button>
                      </form>
                      <form action="../actions/deletepost.php" method="post">
                          <input type="hidden" name="post_id" value="' . $row['id'] . '">
                          <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
                  </div>
              </div>
            </div>';
  }


} else {
  // Display a message if there are no posts
  echo "No posts found.";
}

// Close the database connection
$conn->close();
?>
 

<script>
    function editAbout() {
        // Get the existing about text
        const aboutText = document.getElementById('about-text').innerText.trim();

        // Create a textarea element
        const textarea = document.createElement('textarea');
        textarea.classList.add('form-control');
        textarea.value = aboutText;

        // Replace the existing about text with the textarea
        const aboutSection = document.getElementById('about-section');
        aboutSection.innerHTML = '';
        aboutSection.appendChild(textarea);

        // Focus on the textarea
        textarea.focus();

        // Add event listener to handle saving the edited about text
        textarea.addEventListener('blur', function () {
            saveAbout(textarea.value);
        });
    }

    function saveAbout(newAbout) {
        // Here you can send an AJAX request to save the new about text to the server
        // For demonstration purposes, let's just update the UI with the new text
        const aboutSection = document.getElementById('about-section');
        aboutSection.innerHTML = `<p class="font-italic mb-0">${newAbout}</p>`;
        // Add the "Edit" button again after saving
        aboutSection.innerHTML += '<button class="btn btn-link text-muted" onclick="editAbout()">Edit</button>';
    }
</script>

      </div>
      <div class="py-4 px-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <a href="#" class="btn btn-link text-muted">Show all</a>

</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<!-- Custom JS -->
<script src="./js/profile.js"></script>

<script>
    function openProfilePictureModal() {
      const fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.accept = 'image/*';
  
      fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function () {
            const profilePicture = document.getElementById('profile-picture');
            profilePicture.src = reader.result;
          };
          reader.readAsDataURL(file);
        }
      });
  
      fileInput.click();
    }
    
  </script>
<script>
    // Function to display modal with enlarged image
    function displayModal(imageSrc) {
        // Get the modal element
        var modal = document.getElementById('myModal');

        // Get the image element and set its source
        var modalImg = document.getElementById("img01");
        modalImg.src = imageSrc;

        // Display the modal
        modal.style.display = "block";
    }

    // Get all the images with class "img-fluid" (recent photos)
    var images = document.getElementsByClassName("img-fluid");

    // Loop through each image and add a click event listener
    for (var i = 0; i < images.length; i++) {
        images[i].addEventListener('click', function() {
            // When an image is clicked, display the modal with the enlarged image
            displayModal(this.src);
        });
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('myModal').style.display = "none";
    }
</script>

<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="img01">
</div>





<script>
  // Function to handle form submission for new post
  document.addEventListener('DOMContentLoaded', function () {
    const newPostForm = document.querySelector('#newPostForm');

    newPostForm.addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent the default form submission

      // Get the form data
      const formData = new FormData(newPostForm);


      // Send the form data to the server using Fetch API
      fetch('../actions/create_post.php', {
        method: 'POST',
        body: formData,
      })
      .then(response => {
        if (response.ok) {
          // If the request is successful, reload the page to see the new post
          window.location.reload();
        } else {
          // Handle error if needed
          console.error('Error:', response.statusText);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    });
  });
</script>
<script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

<script>
// Function to handle triple-click event
function handleTripleClick(postId) {
    // Prompt the user for confirmation
    if (confirm("Are you sure you want to delete this post?")) {
        // If confirmed, send an AJAX request to delete the post
        fetch('../actions/deletepost.php?post_id=' + postId, {
            method: 'post'
        })
        .then(response => {
            if (response.ok) {
                // If the request is successful, remove the post element from the DOM
                const postElement = document.getElementById('post_' + postId);
                postElement.remove();
                window.location.reload();
            } else {
          // Handle error if needed
          console.error('Error:', response.statusText);
        }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

// Get all post elements
var posts = document.querySelectorAll('.card');

// Loop through each post element and add a triple-click event listener
posts.forEach(function(post) {
    var clickCount = 0;
    post.addEventListener('click', function() {
        clickCount++;
        if (clickCount === 3) {
            handleTripleClick(post.id.split('_')[1]); 
            console.log(post.id.split('_')[1]);// Exct postId from the post id
            clickCount = 0; // Reset click count
        }
    });
});

</script>

</body>
</html>
