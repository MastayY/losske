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
        $_SESSION['username'] = $row['username'];
    }
}

if( isset($_SESSION["login"]) ) {
    $username = $_SESSION["username"];
    $pic = query("SELECT * FROM users WHERE username = '$username'");
};

$posts = query("SELECT * FROM posts ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f760262075.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="icon.png" type="image">
    <title>Losske | Beranda</title>
</head>
<body>
    <div class="container">
        <nav>
            <a href="">
                <img src="assets/img/logo.png" alt="" height="70px">
            </a>
            <?php if( !isset($_SESSION['login']) ): ?>
            <div class="btn-group">
                <a href="login.php" class="login-btn"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            </div>
            <?php else: ?>
            <div class="user-profile">
                <div class="profil">
                    <img src="assets/img/profile/<?= $pic[0]["userpicture"] ?>" alt="">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="profile-card">
                    <div class="profile">
                        <img src="assets/img/profile/<?= $pic[0]["userpicture"] ?>">
                        <h1><?= $pic[0]["fullname"] ?></h1>
                        <p>@<?= $pic[0]["username"] ?></p>
                    </div>
                    <ul>
                        <li><a href="profile.php?username=<?= $pic[0]["username"] ?>"><i class="fa-solid fa-user"></i> Profile</a></li>
                        <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </nav>
        <?php if( isset($_POST["post"]) ): ?>
            <?php if( !isset($_SESSION["login"]) ) {
                header("Location: login.php");
                exit;
            } ?>
            <?php if( addPost($_POST) > 0): ?>
            <div class="toast">
                <div class="toast-content">
                    <i class="fas fa-solid fa-check check"></i>

                    <div class="message">
                    <span class="text text-1">Berhasil</span>
                    <span class="text text-2">Pikiran anda berhasil dibagi ke orang lain</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <h1>Selamat Datang di website <span>LossKe</span><br>Luapkan seluruh keresahan, kekesalan, dan kemarahan anda disini. <br>Curhat? O tentu juga bisa.</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="content">
                <div class="posting">
                    <img src="assets/img/profile/default.png" alt="" height="50px">
                    <input type="hidden" value="<?= $pic[0]["username"]?>" name="username">
                    <input type="hidden" value="<?= $pic[0]["userpicture"]?>" name="userpic">
                    <?php if( isset($_SESSION['login']) ): ?>
                    <textarea name="teks" class="form-control" placeholder="Apa yang ada di pikiran mu saat ini <?= $pic[0]['username'] ?>?" required></textarea>
                    <?php else : ?>
                    <textarea name="teks" class="form-control" placeholder="Apa yang ada di pikiran mu saat ini?" required></textarea>
                    <?php endif; ?>
                </div>
                <div class="posting2">
                    <!-- <input type="file" class="custom-file-input" name="postimage" disabled> -->
                    <button id="post" name="post">Posting</button>
                </div>
            </div>
        </form>
        <h1 class="public-post">Postingan Terbaru</h1>
        <?php foreach($posts as $post): ?>
        <?php 
            $userlosske = $post['postedby'];
            $status = query("SELECT status FROM users WHERE username = '$userlosske'"); 
        ?>
        <div id="postingan">
            <div class="content">
                <div class="post-header">
                    <img src="assets/img/profile/<?= $post['userpic'] ?>" alt="" height="50px" width="50px">
                    <div class="post-info">
                        <div class="user-info">
                            <div class="user">
                                <?php if( $status[0]['status'] == 'verified' ): ?>
                                <a href="profile.php?username=<?= $post['postedby'] ?>" class="username"><?= $post['postedby'] ?></a>
                                <img src="assets/img/badge/verified.gif" alt="">
                                <?php endif; ?>
                                <?php if( $status[0]['status'] == 'amogus' ): ?>
                                <a href="profile.php?username=<?= $post['postedby'] ?>" class="username"><?= $post['postedby'] ?></a>
                                <img src="assets/img/badge/amogus.gif" alt="">
                                <?php endif; ?>
                                <?php if( $status[0]['status'] == 'admin' ): ?>
                                <a href="profile.php?username=<?= $post['postedby'] ?>" class="username" style="color: #ff0000; font-weight: bold;"><?= $post['postedby'] ?></a>
                                <img src="assets/img/badge/admin.gif" alt="">
                                <?php endif; ?>
                                <?php if( $status[0]['status'] == 'None' ): ?>
                                <a href="profile.php?username=<?= $post['postedby'] ?>" class="username"><?= $post['postedby'] ?></a>
                                <?php endif; ?>
                            </div>
                            <p class="timestamp"><?= $post['postdate'] ?></p>
                        </div>
                        <?php if( isset($_SESSION['login']) ): ?>
                            <?php 
                                $userlogin = $_SESSION['username'];
                                $isAdmin = query("SELECT status FROM users WHERE username = '$userlogin'");    
                            ?>
                            <?php if( $_SESSION['username'] == $post['postedby'] || $isAdmin[0]['status'] == 'admin' ): ?>
                            <input type="checkbox" id="check">
                            <i class="fa-solid fa-ellipsis-vertical option" id="option"></i>
                            <div class="option-list" id="option-list">
                                <ul>
                                    <!-- <li><a href="editpost.php">Edit</a></li> -->
                                    <li><a href="delete.php?id=<?= $post['id'] ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?');"><i class="fa-solid fa-trash-can"></i> Hapus</a></li>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="post-content">
                    <div class="post-text">
                        <?= $post['postcontent'] ?>
                    </div>
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

<script src="assets/js/script.js"></script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    const profil = document.querySelector('.profil')
    const profileCard = document.querySelector('.profile-card')

    profil.addEventListener('click', function () {
        profileCard.classList.toggle('active');
    });
    
</script>
</body>
</html>