<?php
session_start();

if(isset($_GET['bio'])) {
    $_SESSION['bio'] = $_GET['bio'];
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}