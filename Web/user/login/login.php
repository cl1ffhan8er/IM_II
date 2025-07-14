<?php
    session_start();

    $message = '';
    $message_type = '';
    if (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        $message_type = 'error';
        unset($_SESSION['error_message']);
    } else if (isset($_SESSION['success_message'])) {
        $message = $_SESSION['success_message'];
        $message_type = 'success';
        unset($_SESSION['success_message']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/log-in.css">
    <title>Log In</title>
    <style>
        /* Styles for error/success messages */
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-section">

            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div id="register" style="display: none;">
                <h1>WELCOME TO SR TRAVEL VANS!</h1>
                <hr>
                <h1>REGISTER AN ACCOUNT</h1>
                <form method="post" action="loginform.php">
                    <div>
                        <label for="reg_username">Username:</label>
                        <input type="text" name="username" id="reg_username" placeholder="Username" required>
                    </div>
                    <div>
                        <label for="reg_password">Password:</label>
                        <input type="password" name="password" id="reg_password" placeholder="Password" required>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>
                    <div>
                        <label for="contact">Contact Number:</label>
                        <input type="text" name="contact" id="contact" placeholder="Contact Number" required>
                    </div>
                    <input type="submit" value="Register" name="register">
                </form>
                <div>
                    <p>Already have an account?</p>
                    <button id="loginBtn">Login to your account</button>
                </div>
            </div>

            <div id="login">
                <h1>WELCOME TO SR TRAVEL VANS!</h1>
                <hr>
                <h1>LOGIN TO YOUR ACCOUNT</h1>
                <form method="post" action="loginform.php">
                    <div>
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="Username" required>
                    </div>
                    <div>
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" placeholder="Password" required>
                    </div>
                    <p style="text-align: right; margin-top: 5px;"><a href="#" id="forgotPasswordBtn" style="font-size: 0.9em; color: #3498db; text-decoration: none;">Forgot Password?</a></p>
                    <input type="submit" value="Log In" name="login">
                </form>
                <div>
                    <p>Don't have an account?</p>
                    <button id="registerBtn">Register an account</button>
                </div>
            </div>
            
            <div id="forgot-password" style="display: none;">
                <h1>RESET YOUR PASSWORD</h1>
                <hr>
                <p>Enter your email address and your new password</p>
                <form method="post" action="forgotpassword.php">
                    <div>
                        <label for="email_reset">Email:</label>
                        <input type="email" name="email_reset" id="email_reset" placeholder="yourname@example.com" required>
                    </div>
                    <div>
                        <label for="new_password">New Password:</label>
                        <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
                    </div>
                    <input type="submit" value="Change Password" name="change-password">
                </form>
                <div>
                    <p>Remembered your password?</p>
                    <button id="backToLoginBtn">Back to Login</button>
                </div>
            </div>
        </div>

        <div class="carousel-section">
            <div class="carousel-wrapper">
                <div class="carousel-slide" style="background-image: url('../images/bg10.jpg');"></div>
                <div class="carousel-slide" style="background-image: url('../images/bg2.jpg');"></div>
                <div class="carousel-slide" style="background-image: url('../images/bg3.jpg');"></div>
            </div>
        </div>
    </div>

    <script>
        const loginBtn = document.getElementById('loginBtn');
        const registerBtn = document.getElementById('registerBtn');
        const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
        const backToLoginBtn = document.getElementById('backToLoginBtn');

        const registerForm = document.getElementById('register');
        const loginForm = document.getElementById('login');
        const forgotPasswordForm = document.getElementById('forgot-password');

        registerBtn.addEventListener('click', () => {
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
            forgotPasswordForm.style.display = 'none';
        });

        function showLogin() {
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
            forgotPasswordForm.style.display = 'none';
        }

        loginBtn.addEventListener('click', showLogin);
        backToLoginBtn.addEventListener('click', showLogin);

        forgotPasswordBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            registerForm.style.display = 'none';
            loginForm.style.display = 'none';
            forgotPasswordForm.style.display = 'block';
        });

        const slides = document.querySelectorAll('.carousel-slide');
        let current = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            current = (current + 1) % slides.length;
            showSlide(current);
        }

        showSlide(current);
        setInterval(nextSlide, 5000);
    </script>
</body>
</html>