<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In</title>
    </head>
    <body>
        <div>
            <div id = "register" style = "display: none;">
                <h1>REGISTER AN ACCOUNT</h1> 
                 <form method = "post" action = "loginform.php">
                    <div>
                        <label for = "username">Username:</label>
                        <input  type = "text" name = "username" id = "username" placeholder = "Username" required>
                    </div>
                    <div>
                        <label for = "password">Password:</label>
                        <input type = "password" name = "password" id = "password" placeholder = "Password" required>
                    </div>
                    <div>
                        <label for = "email">Email:</label>
                        <input type = "email" name = "email" id = "email" placeholder = "Email" required>
                    </div>
                    <div>
                        <label for = "contact">Contact Number:</label>
                        <input type = "text" name = "contact" id = "contact" placeholder = "Contact Number" required>
                    </div>
                    <input type = "submit" value = "Register" name = "register">
                </form>
                <div>
                    <p>Already have an account?</p>
                    <button id = "loginBtn">Login to your account</button>
                </div>
            </div>

            <div id = "login">
                <h1>LOGIN TO YOUR ACCOUNT</h1> 
                 <form method = "post" action = "loginform.php">
                    <div>
                        <label for = "username">Username:</label>
                        <input  type = "text" name = "username" id = "username" placeholder = "Username" required>
                    </div>
                    <div>
                        <label for = "password">Password:</label>
                        <input type = "password" name = "password" id = "password" placeholder = "Password" required>
                    </div>
                    <input type = "submit" value = "Log In" name = "login">
                </form>
                <div>
                    <p>Don't have an account?</p>
                    <button id = "registerBtn">Register an account</button>
                </div>
            </div>

            <p id = "message"></p> <!-- error message will be displayed here -->
        </div>
        <script>
            const loginBtn = document.getElementById('loginBtn');
            const registerBtn = document.getElementById('registerBtn');
            const registerForm = document.getElementById('register');
            const loginForm = document.getElementById('login');

            registerBtn.addEventListener('click', () => {
                registerForm.style.display = 'block';
                loginForm.style.display = 'none';
            });

            loginBtn.addEventListener('click', () => {
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
            });
        </script>
    </body>
</html>