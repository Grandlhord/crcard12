<?php
    ob_start();
    session_start();

    // Check previous session untill is destroyed
    if (isset($_SESSION['username'])) {
        // logged in
        header('Location: settings.php');
    }
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Credit Card</title>

	<!-- Load all static files -->
	<link rel="stylesheet" type="text/css" href="assets/BS/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<body class="container">
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
            }else {
                echo '<p class="error-message">Credientials are not correct!!</p>';
            }
        }
     ?>

    <!-- Login view -->
    <form class="form-signin"method="POST" action="">
        <h2 class="form-signin-heading">SIGN IN</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
</body>
<footer>
	<!-- All the Javascript will be load here... -->
	<script type="text/javascript" src="assets/JS/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="assets/JS/main.js"></script>
	<script type="text/javascript" src="assets/BS/js/bootstrap.min.js"></script>
</footer>
</html>