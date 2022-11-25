<?php 
error_reporting(E_ALL); 
session_start();
require('functions.php');

//cek session
if( isset($_SESSION["login"]) ) {
    header('Location: index.php');
    exit;
}

if( isset($_POST["submit"]) ) {
    $username = $_POST["username"];
    $pass = $_POST["pass"];

    $result = mysqli_query($dbcon, "SELECT * FROM users WHERE username = '$username'");
    $result1 = mysqli_query($dbcon, "SELECT * FROM users WHERE email = '$username'");

    if( mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);

        if( password_verify($pass, $row["password"]) ) {
            // Set session
            $_SESSION["login"] = true;
            $_SESSION["username"] = $_POST["username"];
            // cek remember me
            if( isset($_POST['remember']) ) {
                // Set cookie
                setcookie('num', $row['id'], time() + 86400);
                setcookie('key', hash('sha256', $row['username']), time() + 86400);
            }
            header('Location: index.php');
            exit;
        } else {
            $passwordWrong = true;
        }
    } else {
        $usernameNotFound = true;
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
    <title>Losske | Login</title>
</head>
<body>
    <div class="login-form">
        <h1>LOGIN</h1>
        <form action="" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" id="username">
            <?php if( isset($usernameNotFound) ): ?>
            <p class="error-msg">* Username Tidak ditemukan</p>
            <?php endif; ?>
            <label for="pass">Password</label>
            <input type="password" name="pass" placeholder="Password" id="pass">
            <?php if( isset($passwordWrong) ): ?>
            <p class="error-msg">* Password Salah</p>
            <?php endif; ?>
            <div class="btn-sec">
                <div class="btn-info">
                    <a href="register.php">Belum punya akun?</a>
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">remember me</label>
                    </div>
                </div>
                <button type="submit" name="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>