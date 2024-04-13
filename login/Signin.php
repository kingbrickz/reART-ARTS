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
                    <header>Login</header>
                    <form action="../actions/signin_user.php" method="POST">
                        <div class="field input-field">
                            <input type="email" placeholder="Email" name="email" class="input">
                        </div>

                        <div class="field input-field">
                            <input type="password" placeholder="Password" name="password" class="password">
                            <i class='bx bx-hide eye-icon'></i>
                        </div>

                        <div class="form-link">
                            <a href="#" class="forgot-pass">Forgot password?</a>
                        </div>

                        <div class="field button-field">
                            <button  type="submit" id="signinButton">Login</button>
    
                        </div>
                        
                    </form>

                    <div class="form-link">
                        <span>Don't have an account? <a href="../login/signup.php" class="link signup-link">Signup</a></span>
                    </div>
                </div>
            </div>
        </section>

    </body>
</html>