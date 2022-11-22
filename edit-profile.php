<?php 
session_start();
// if(!isset($_SERVER['HTTP_REFERER'])){
//     header('location: index.php');
//     exit;
// }
require('functions.php');

$usernameProfile = $_SESSION['username'];

$user = query("SELECT * FROM users WHERE username = '$usernameProfile'");

if( isset($_POST["submit"]) ) {
    if( editProfile($_POST) > 0 ) {
        echo "<script>
                alert('Data berhasil diubah');
                window.location = 'index.php';
            </script>";
    } else {
        echo "<script>
                alert('Data tidak berhasil diubah');
            </script>";
    }
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f760262075.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/edit-profile.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="icon.png" type="image">
    <title>Edit Profil</title>
</head>
<body>
    <section>
        <div class="profile-content">
            <nav>
                <a href="">
                    <img src="assets/img/logo.png" alt="" height="70px">
                </a>
                <div class="btn-group">
                    <a href="index.php" class="login-btn">Beranda</a>
                </div>
            </nav>
            <div class="profile-section">
                <div class="img">
                    <img src="assets/img/profile/<?= $user[0]['userpicture'] ?>" alt="">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="profile-info">
                    <input type="hidden" name="id" value="<?= $user[0]["id"] ?>">
                    <p class="title">Nama Lengkap</p>
                    <input type="text" name="fullname" id="fullname" value="<?= $user[0]['fullname'] ?>">
                    <p class="title">Username</p>
                    <input type="text" value="<?= $user[0]['username'] ?>" disabled>
                    <p class="title">Email</p>
                    <input type="email" name="email" id="email" value="<?= $user[0]['email'] ?>">
                    <p class="title">Dibuat Tanggal</p>
                    <input type="text" value="<?= $user[0]['createddate'] ?>" disabled>
                    <button type="submit" name="submit" id="button">Edit</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>