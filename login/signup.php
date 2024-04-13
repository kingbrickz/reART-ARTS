<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login and Signup Form </title>

        <!-- CSS -->
        <link rel="stylesheet" href="../css/signin.css">
                
        <!-- Boxicons CSS -->
        <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
                        
    </head>
    <body>
        <section class="container forms">
            <div class="form login">
                <div class="form-content">
                    <header>Sign Up</header>
                    <form action="../actions/signup.php" method="POST">
                        <div class="field input-field">
                            <input type="email" placeholder="Email" name="email" class="input">
                        </div>

                        <div class="field input-field">
                            <input type="password" placeholder="Password" name="password" class="password">
                            <i class='bx bx-hide eye-icon'></i>
                        </div>

                        <div class="field input-field">
                            <input type="password" placeholder="Confirm Password" name="confirm_password" class="password">
                            <i class='bx bx-hide eye-icon'></i>
                        </div>

                        <div class="field button-field">
                            <button type="submit" id="signupButton">Sign Up</button>
                        </div>
                        
                    </form>

                    <div class="form-link">
                        <span>Already have an account? <a href="../login/Signin.php" class="link signup-link">Login</a></span>
                    </div>
                </div>
            </div>
        </section>

    </body>
</html>