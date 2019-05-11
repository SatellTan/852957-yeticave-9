<?php
require_once('functions.php');

session_start();

$str_max_length = 128;

$link = mysqli_connect("localhost", "root", "", "yeticave_852957");

if ($link === false) {
    print("Ведутся технические работы");
    exit();
}

mysqli_set_charset($link, "utf8");

$user = null;
if (isset($_SESSION['user'])) {

    $sql = "SELECT * FROM users WHERE id = ?";
    $res = db_fetch_data($link, $sql, [$_SESSION['user']['id']]);
    if (!$res) {
        print('Что-то пошло не так. Попробуйте позднее');
        exit;
    }
    $user = $res[0];
}

$sql = "SELECT * FROM categories";
$categories = db_fetch_data($link, $sql);
