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

    $content = mysqli_real_escape_string($dbcon, strip_tags($_POST["teks"], '<br><strong><i><a>'));
    $time = date('d F Y H:i');
    $postedby = $_POST["username"];
    $userpic = $_POST["userpic"];
    $postimg = uploadPhoto();

    if( strlen($content) > 500 ) {
        echo "<script>
                alert('Postingan tidak boleh lebih dari 500 karakter');
            </script>";
        return false;
    }

    $query = "INSERT INTO posts
                VALUES
                ('', '$postedby', '$userpic', '$time', '$postimg', '$content')
            ";

    mysqli_query($dbcon, $query);

    return mysqli_affected_rows($dbcon);
}
function uploadPhoto() {
    // mengambil data foto
    $fileName = $_FILES['postimage']['name'];
    $fileSize = $_FILES['postimage']['size'];
    $error = $_FILES['postimage']['error'];
    $tmpName = $_FILES['postimage']['tmp_name'];
    // check file ekstensi valid
    $validExtension = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = explode('.', $fileName);
    $extension = strtolower(end($extension));

    if( !in_array($extension, $validExtension) ) {
        echo "<script>
                alert('Tolong upload dengan ekstensi jpg, png, jpeg, atau gif');
              </script>";
        return false;
    }
    //check file size
    if( $fileSize > 6000000 ) {
        echo "<script>
                alert('Ukuran gambar tidak boleh lebih dari 5 MB');
              </script>";
        return false;
    }
    //upload database
    // generate new photo name
    $newFileName = uniqid();
    $newFileName .= '.';
    $newFileName .= $extension;
    move_uploaded_file($tmpName, 'assets/img/post-img/' . $newFileName);

    return $newFileName;
}
function addComment($comment) {
    global $dbcon;

    $content = mysqli_real_escape_string($dbcon, htmlspecialchars($_POST["comment"]));
    // $postImg = $_POST["postimage"];
    $time = date('d F Y H:i');
    $commentTo = $_POST['comment_to'];
    $commentBy = $_POST['comment_by'];

    if( strlen($content) > 300 ) {
        echo "<script>
                alert('Komen tidak boleh lebih dari 300 karakter');
            </script>";
        return false;
    }

    $query = "INSERT INTO comments
                VALUES
                ('', '$commentTo', '$commentBy', '$time', '$content', '')
            ";

    mysqli_query($dbcon, $query);

    return mysqli_affected_rows($dbcon);
}

function register($data) {
    global $dbcon;

    $fullname = htmlspecialchars($data["fullname"]);
    $username = htmlspecialchars(stripslashes($data["username"]));
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
    if( strlen($fullname) > 24 ) {
        echo "<script>
                alert('Nama Lengkap tidak boleh lebih dari 24 karakter');
            </script>";
        return false;
    }

    // encrypt password
    $password = password_hash($password, PASSWORD_DEFAULT);
    // tambahkan ke database
    mysqli_query($dbcon, "INSERT INTO users VALUES('','$fullname', '$username','$email', '$password', 'default.png', '$create', 'None')");

    return mysqli_affected_rows($dbcon);
}

function delete($id) {
    global $dbcon;
    mysqli_query($dbcon, "DELETE FROM posts WHERE id = $id");

    return mysqli_affected_rows($dbcon);
}
function deleteComment($id) {
    global $dbcon;
    mysqli_query($dbcon, "DELETE FROM comments WHERE id = $id");

    return mysqli_affected_rows($dbcon);
}

function editProfile($data) {
    global $dbcon;

    $id = $data["id"];
    $fullname = htmlspecialchars($data["fullname"]);
    $email = $data["email"];

    if( strlen($fullname) > 24 ) {
        echo "<script>
                alert('Nama Lengkap tidak boleh lebih dari 24 karakter');
            </script>";
        return false;
    }

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