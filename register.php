<?php 
require('functions.php');

if( isset($_POST["submit"]) ) {
    if( register($_POST) > 0 ) {
        header("Location:login.php");
    } else {
        mysqli_error($dbcon);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="icon.png" type="image">
    <title>Losske | Register</title>
</head>
<body>
    <div class="login-form">
        <h1>Register</h1>
        <form action="" method="post">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="example@gmail.com" required>
            <input type="password" name="pass" placeholder="Password" required>
            <div class="btn-sec">
                <div class="btn-info">
                    <a href="login.php">Sudah punya akun?</a>
                </div>
                <button type="submit" name="submit">Register</button>
            </div>
        </form>
    </div>

<script src="assets/js/script.js"></script>
</body>
</html>