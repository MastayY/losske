<?php 
session_start();
session_unset();
session_destroy();

setcookie('id', '', time() - 90000);
setcookie('key', '', time() - 90000);
header("Location: index.php")
?>