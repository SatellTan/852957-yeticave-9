<?php
require_once('functions.php');

$link = mysqli_connect("localhost", "root", "", "yeticave_852957");

if ($link == false) {
    print("Ведутся технические работы");
    exit();
} else {
    mysqli_set_charset($link, "utf8");
    $sql = "SELECT * FROM categories";
    $categories = db_fetch_data($link, $sql);
};
