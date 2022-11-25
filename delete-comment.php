<?php
session_start();
if(!isset($_SERVER['HTTP_REFERER'])){
    header('location: index.php');
    exit;
}
require 'functions.php';
$id = $_GET["id"];

if( deleteComment($id) > 0 ) {
    header("Location:index.php");
} else {
    header("Location:index.php");
}
?>