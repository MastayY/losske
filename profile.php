<?php 
session_start();
if(!isset($_SERVER['HTTP_REFERER'])){
    header('location: index.php');
    exit;
}
require('functions.php');

$usernameProfile = $_GET['username'];

$user = query("SELECT * FROM users WHERE username = '$usernameProfile'");
$posts = query("SELECT * FROM posts WHERE postedby = '$usernameProfile' ORDER BY id DESC");
$num = count($posts);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f760262075.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/profile.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="icon.png" type="image">
    <title>Losske | Profil</title>
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
                    <?php if( $usernameProfile == $_SESSION['username'] ): ?>
                    <a href="edit-profile.php" class="edit-profile"><i class="fa-solid fa-pen-to-square"></i> Edit Profil</a>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <p class="title">Nama Lengkap</p>
                    <p><?= $user[0]['fullname'] ?></p>
                    <p class="title">Username</p>
                    <p><?= $user[0]['username'] ?></p>
                    <p class="title">Email</p>
                    <p><?= $user[0]['email'] ?></p>
                    <p class="title">Dibuat Tanggal</p>
                    <p><?= $user[0]['createddate'] ?></p>
                    <p class="title">Status</p>
                    <div class="user">
                        <?php if( $user[0]['status'] == 'verified' ): ?>
                        <p style="color: #07c4d9;">Verified</p>
                        <img src="assets/img/badge/verified.gif" alt="">
                        <?php endif; ?>
                        <?php if( $user[0]['status'] == 'amogus' ): ?>
                        <p style="color: #ffb700;">Suspicious</p>
                        <img src="assets/img/badge/amogus.gif" alt="">
                        <?php endif; ?>
                        <?php if( $user[0]['status'] == 'admin' ): ?>
                        <p style="color: #ff0000; font-weight: bold;">Admin</p>
                        <img src="assets/img/badge/admin.gif" alt="">
                        <?php endif; ?>
                        <?php if( $user[0]['status'] == 'None' ): ?>
                        <p>Pengguna Biasa</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <h1><span><?= $num; ?></span> Postingan</h1>
            <?php foreach($posts as $post): ?>
            <div id="postingan">
                <div class="content">
                    <div class="post-header">
                        <img src="assets/img/profile/<?= $post['userpic'] ?>" alt="" height="50px" width="50px">
                        <div class="post-info">
                        <div class="user">
                                <?php if( $user[0]['status'] == 'verified' ): ?>
                                <p class="username"><?= $post['postedby'] ?></p>
                                <img src="assets/img/badge/verified.gif" alt="">
                                <?php endif; ?>
                                <?php if( $user[0]['status'] == 'amogus' ): ?>
                                <p class="username"><?= $post['postedby'] ?></p>
                                <img src="assets/img/badge/amogus.gif" alt="">
                                <?php endif; ?>
                                <?php if( $user[0]['status'] == 'admin' ): ?>
                                <p class="username" style="color: #ff0000; font-weight: bold;"><?= $post['postedby'] ?></p>
                                <img src="assets/img/badge/admin.gif" alt="">
                                <?php endif; ?>
                                <?php if( $user[0]['status'] == 'None' ): ?>
                                <p class="username"><?= $post['postedby'] ?></p>
                                <?php endif; ?>
                        </div>
                            <p class="timestamp"><?= $post['postdate'] ?></p>
                        </div>
                    </div>
                    <div class="post-content">
                        <p><?= $post['postcontent'] ?></p>
                        <?php if( isset($post['postimg']) ): ?>
                        <img src="assets/img/post-img/<?= $post['postimg'] ?>" alt="" class="post-img">
                        <?php endif; ?>
                    </div>
                    <!-- <div class="post-footer">
                        <button><i class="fa-solid fa-comment"></i> Komentar</button>
                    </div> -->
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <footer>
            <p>© 2022 LossKe | Dibuat dengan ❤️ dan ☕ oleh MastayCode</p>
        </footer>
    </section>
</body>
</html>