<?php
require_once('init.php');

if ( isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$lots = [];

if (($_SERVER['REQUEST_METHOD'] === 'GET') && $search) {

    $sql = "SELECT l.id, l.name, l.start_price, l.img_URL, coalesce(MAX(b.price), l.start_price) AS current_price, l.finish_date, c.name AS category
        FROM lots l
        LEFT JOIN bids b ON b.lot_id = l.id
        INNER JOIN categories c ON l.category_id = c.id
        WHERE MATCH(l.name, l.description) AGAINST(?) && l.finish_date > CURRENT_TIMESTAMP
        GROUP BY l.id
        ORDER BY l.creation_date DESC";

    $lots = db_fetch_data($link, $sql, [$search]);

}

$page_content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => $lot['name'],
    'search' => $search
]);

print($layout_content);