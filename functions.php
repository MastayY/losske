<?php 
$dbcon = mysqli_connect('localhost', 'root', '', 'losske');

date_default_timezone_set("Asia/Jakarta");

function query($query) {
    global $dbcon; 
    $result = mysqli_query($dbcon, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }

    return $rows;
}

function addPost($post) {
    global $dbcon;

    $content = mysqli_real_escape_string($dbcon, strip_tags($_POST["teks"], '<br><strong><i>'));
    // $postImg = $_POST["postimage"];
    $time = date('d F Y H:i');
    $postedby = $_POST["username"];
    $userpic = $_POST["userpic"];

    $query = "INSERT INTO posts
                VALUES
                ('', '$postedby', '$userpic', '$time', '', '$content')
            ";

    mysqli_query($dbcon, $query);

    return mysqli_affected_rows($dbcon);
}

function register($data) {
    global $dbcon;

    $fullname = htmlspecialchars($data["fullname"]);
    $username = stripslashes($data["username"]);
    $email = $data["email"];
    $password = mysqli_real_escape_string($dbcon, $data["pass"]);
    $create = date('d F Y H:i');
    // cek username
    $check = mysqli_query($dbcon, "SELECT username FROM users WHERE username = '$username'");
    if( mysqli_fetch_assoc($check) ) {
        echo "<script>
                alert('Username sudah terdaftar');
             </script>";
        return false;
    }

    // encrypt password
    $password = password_hash($password, PASSWORD_DEFAULT);
    // tambahkan ke database
    mysqli_query($dbcon, "INSERT INTO users VALUES('','$fullname', '$username','$email', '$password', 'default.png', '$create')");

    return mysqli_affected_rows($dbcon);
}

function delete($id) {
    global $dbcon;
    mysqli_query($dbcon, "DELETE FROM posts WHERE id = $id");

    return mysqli_affected_rows($dbcon);
}

function editProfile($data) {
    global $dbcon;

    $id = $data["id"];
    $fullname = htmlspecialchars($data["fullname"]);
    $email = $data["email"];

    // insert query
    $query = "UPDATE users SET
                fullname = '$fullname',
                email = '$email'
             WHERE id = $id
             "; 
    mysqli_query($dbcon, $query);

    return mysqli_affected_rows($dbcon);
}

?>