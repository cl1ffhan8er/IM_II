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
        /* Styles for server messages */
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* Styles for form validation */
        .form-control { position: relative; margin-bottom: 25px; }
        .form-control:last-of-type { margin-bottom: 10px; }
        .form-control small {
            color: #e74c3c;
            position: absolute;
            bottom: -13px;
            left: 0;
            font-size: 0.6em;
            margin-bottom: 2px;
            visibility: hidden;
        }
        .form-control.error small { visibility: visible; }
        .form-control.error input { border-color: #e74c3c; }
        .form-control.success input { border-color: #2ecc71; }
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
                <form id="registerForm" method="post" action="loginform.php" novalidate>
                    <div class="form-control">
                        <label for="reg_username">Username:</label>
                        <input type="text" name="username" id="reg_username" placeholder="Username">
                        <small>Error message</small>
                    </div>
                    <div class="form-control">
                        <label for="reg_password">Password:</label>
                        <input type="password" name="password" id="reg_password" placeholder="Password">
                        <small>Error message</small>
                    </div>
                    <div class="form-control">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="Email">
                        <small>Error message</small>
                    </div>
                    <div class="form-control">
                        <label for="contact">Contact Number:</label>
                        <input type="text" name="contact" id="contact" placeholder="09xxxxxxxxx">
                        <small>Error message</small>
                    </div>
                    <input type="submit" value="Register" name="register" style = "margin-top: 5px">
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
                <form id="loginForm" method="post" action="loginform.php" novalidate>
                    <div class="form-control">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="Username">
                        <small>Error message</small>
                    </div>
                    <div class="form-control">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" placeholder="Password">
                        <small>Error message</small>
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
                <form id="forgotPasswordForm" method="post" action="forgotpassword.php" novalidate>
                    <div class="form-control">
                        <label for="email_reset">Email:</label>
                        <input type="email" name="email_reset" id="email_reset" placeholder="yourname@example.com">
                        <small>Error message</small>
                    </div>
                    <div class="form-control">
                        <label for="new_password">New Password:</label>
                        <input type="password" name="new_password" id="new_password" placeholder="New Password">
                        <small>Error message</small>
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
                <div class="carousel-slide active" style="background-image: url('../images/bg10.jpg');"></div>
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
        const registerContainer = document.getElementById('register');
        const loginContainer = document.getElementById('login');
        const forgotPasswordContainer = document.getElementById('forgot-password');
        registerBtn.addEventListener('click', () => {
            registerContainer.style.display = 'block';
            loginContainer.style.display = 'none';
            forgotPasswordContainer.style.display = 'none';
        });
        function showLogin() {
            registerContainer.style.display = 'none';
            loginContainer.style.display = 'block';
            forgotPasswordContainer.style.display = 'none';
        }
        loginBtn.addEventListener('click', showLogin);
        backToLoginBtn.addEventListener('click', showLogin);
        forgotPasswordBtn.addEventListener('click', (e) => {
            e.preventDefault();
            registerContainer.style.display = 'none';
            loginContainer.style.display = 'none';
            forgotPasswordContainer.style.display = 'block';
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
        
        const showError = (input, message) => {
            const formControl = input.parentElement;
            formControl.classList.add('error');
            formControl.classList.remove('success');
            const small = formControl.querySelector('small');
            small.innerText = message;
        };
        const showSuccess = (input) => {
            const formControl = input.parentElement;
            formControl.classList.remove('error');
            formControl.classList.add('success');
        };
        const isValidEmail = (email) => {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        };

        const registerFormEl = document.getElementById('registerForm');
        const loginFormEl = document.getElementById('loginForm');
        const forgotPasswordFormEl = document.getElementById('forgotPasswordForm');

        registerFormEl.addEventListener('submit', function(event) {
            let isFormValid = true;
            const username = document.getElementById('reg_username');
            const password = document.getElementById('reg_password');
            const email = document.getElementById('email');
            const contact = document.getElementById('contact');

            if (username.value.trim() === '') { isFormValid = false; showError(username, 'Username is required'); }
            else { showSuccess(username); }

            if (password.value.trim() === '') { isFormValid = false; showError(password, 'Password is required'); }
            else { showSuccess(password); }

            if (email.value.trim() === '') { isFormValid = false; showError(email, 'Email is required'); }
            else if (!isValidEmail(email.value.trim())) { isFormValid = false; showError(email, 'Provide a valid email address'); }
            else { showSuccess(email); }
            
            const contactValue = contact.value.trim();
            if (contactValue === '') {
                isFormValid = false;
                showError(contact, 'Contact number is required');
            } else if (!/^\d+$/.test(contactValue)) {
                isFormValid = false;
                showError(contact, 'Must only contain numbers');
            } else if (contactValue.length !== 11) {
                isFormValid = false;
                showError(contact, 'Contact number must be 11 digits');
            } else if (!contactValue.startsWith('09')) {
                isFormValid = false;
                showError(contact, 'Must start with "09"');
            } else {
                showSuccess(contact);
            }

            if (!isFormValid) { event.preventDefault(); }
        });

        loginFormEl.addEventListener('submit', function(event) {
            let isFormValid = true;
            const username = document.getElementById('username');
            const password = document.getElementById('password');

            if (username.value.trim() === '') { isFormValid = false; showError(username, 'Username is required'); }
            else { showSuccess(username); }

            if (password.value.trim() === '') { isFormValid = false; showError(password, 'Password is required'); }
            else { showSuccess(password); }
            
            if (!isFormValid) { event.preventDefault(); }
        });
        
        forgotPasswordFormEl.addEventListener('submit', function(event) {
            let isFormValid = true;
            const email = document.getElementById('email_reset');
            const newPassword = document.getElementById('new_password');

            if (email.value.trim() === '') { isFormValid = false; showError(email, 'Email is required'); }
            else if (!isValidEmail(email.value.trim())) { isFormValid = false; showError(email, 'Provide a valid email address'); }
            else { showSuccess(email); }

            if (newPassword.value.trim() === '') { isFormValid = false; showError(newPassword, 'New password is required'); }
            else { showSuccess(newPassword); }

            if (!isFormValid) { event.preventDefault(); }
        });
    </script>
</body>
</html>
