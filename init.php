<?php
require_once('functions.php');
require_once('vendor/autoload.php');

session_start();

$str_max_length = 128;
$page_items = 9;
$project_link = 'http://';
$timezone = '+03:00';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);

$link = mysqli_connect("localhost", "root", "", "yeticave_852957");
if ($link) {
    $sql = "SET time_zone = '" . $timezone ."'";
    $set_time_zone = mysqli_query($link, $sql);
}

if (!$link || !$set_time_zone) {
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
