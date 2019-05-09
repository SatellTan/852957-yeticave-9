<?php
require_once('init.php');

if (!isset($_GET['lot_id'])) {
    http_response_code(404);
    $page_content = renderTemplate('404.php');

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => $lot['name']
    ]);

    print($layout_content);
    exit;
}

$lot = null;

$sql = "SELECT l.name, l.start_price, l.bid_step, l.description, l.img_URL, coalesce(MAX(b.price), l.start_price) AS current_price, l.finish_date, c.name AS category
    FROM lots l
    LEFT JOIN bids b ON b.lot_id = l.id
    INNER JOIN categories c ON l.category_id = c.id
    WHERE l.id = ?
    GROUP BY l.id";

$lot = db_fetch_data($link, $sql, [$_GET['lot_id']]);

if ($lot) {
    $lot = $lot[0];
    $page_content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot
    ]);
} else {
    http_response_code(404);
    $page_content = include_template('404.php');
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $lot['name']
]);

print($layout_content);
