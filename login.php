<?php 
session_start();
require('functions.php');

if( isset($_COOKIE["num"]) && isset($_COOKIE["key"]) ) {
    $id = $_COOKIE["num"];
    $key = $_COOKIE["key"];
    //ambil username berdasarkan id
    $result = mysqli_query($dbcon, "SELECT username FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    //cek cookie dan username
    if( $key === hash('sha256', $row['username']) ) {
        $_SESSION['login'] = true;
    }
}
//cek session
if( isset($_SESSION["login"]) ) {
    header('Location: index.php');
    exit;
}

if( isset($_POST["submit"]) ) {
    $username = $_POST["username"];
    $pass = $_POST["pass"];

    $result = mysqli_query($dbcon, "SELECT * FROM users WHERE username = '$username'");

    if( mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);

        if( password_verify($pass, $row["password"]) ) {
            // Set session
            $_SESSION["login"] = true;
            $_SESSION["username"] = $_POST["username"];
            // cek remember me
            if( isset($_POST['remember']) ) {
                // Set cookie
                setcookie('num', $row['id'], time() + 60);
                setcookie('key', hash('sha256', $row['username']), time() + 60);
            }
            header('Location: index.php');
            exit;
        }
    }

    $error = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>
<body>
    <div class="login-form">
        <h1>LOGIN</h1>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="pass" placeholder="Password">
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