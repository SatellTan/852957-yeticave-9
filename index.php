<?php
require_once('init.php');

$user_name = 'Татьяна';

$sql = "SELECT l.id, l.name, l.start_price, l.img_URL, coalesce(MAX(b.price), l.start_price) AS current_price, l.finish_date, c.name AS category
    FROM lots l
    LEFT JOIN bids b ON b.lot_id = l.id
    INNER JOIN categories c ON l.category_id = c.id
    WHERE l.finish_date > CURRENT_TIMESTAMP
    GROUP BY l.id
    ORDER BY l.creation_date DESC";
$lots = db_fetch_data($link, $sql);


$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'is_auth' => rand(0, 1),
	'content' => $page_content,
    'user_name' => $user_name,
    'categories' => $categories,
	'title' => 'Главная'
]);

print($layout_content);
