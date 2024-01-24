<?php
    ob_start();
    session_start();

    // Check previous session until it is destroyed
    if (isset($_SESSION['username'])) {
        // logged in
        header('Location: settings.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Credit Card</title>
    <!-- Load all static files -->
    <link rel="stylesheet" type="text/css" href="assets/BS/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-signin {
            max-width: 530px;
            padding: 15px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-signin .form-signin-heading {
            text-align: center;
            color: #333;
        }

        .form-signin .form-control {
            position: relative;
            height: 45px;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .form-signin input[type="email"],
        .form-signin input[type="password"] {
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 10px;
        }

        .form-signin button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .form-signin button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #d9534f;
            text-align: center;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Config included -->
    <?php include 'helper/config.php' ?>

    <!-- Here will be checking for login -->
    <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $get_login_sql = "SELECT * FROM users WHERE email='".$email."' AND password='".$password."'";

            $login_success = $conn->query($get_login_sql);
            if($login_success->num_rows == 1){
                // Check the session and add into session
                $_SESSION['valid'] = true;
                $_SESSION['timeout'] = time();
                $_SESSION['username'] = $email;

                // Redirect to settings page
                header('Location: settings.php');
            } else {
                echo '<p class="error-message">Credentials are not correct!!</p>';
            }
        }
    ?>

    <!-- Login view -->
    <form class="form-signin" method="POST" action="">
        <div class="logo">
            <img src="./assets/images/logo.png" alt="Logo">
        </div>
        <h2 class="form-signin-heading">SIGN IN</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

    <footer>
        <!-- All the JavaScript will be loaded here... -->
        <script type="text/javascript" src="assets/JS/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="assets/JS/main.js"></script>
        <script type="text/javascript" src="assets/BS/js/bootstrap.min.js"></script>
    </footer>
</body>
</html>
