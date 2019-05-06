<?php
require_once('functions.php');

$user_name = 'Татьяна';
$user_id = 1;
$str_max_length = 128;

$link = mysqli_connect("localhost", "root", "", "yeticave_852957");

if ($link == false) {
    print("Ведутся технические работы");
    exit();
}

mysqli_set_charset($link, "utf8");
$sql = "SELECT * FROM categories";
$categories = db_fetch_data($link, $sql);
