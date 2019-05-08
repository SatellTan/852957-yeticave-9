<?php
require_once('functions.php');

session_start();
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
}

$str_max_length = 128;

$link = mysqli_connect("localhost", "root", "", "yeticave_852957");

if ($link == false) {
    print("Ведутся технические работы");
    exit();
}

mysqli_set_charset($link, "utf8");
$sql = "SELECT * FROM categories";
$categories = db_fetch_data($link, $sql);
