<?php
require_once('init.php');

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit;
}

$sql = "SELECT b.*, c.name AS category, l.name, l.id AS lot_id, l.img_URL, l.finish_date, l.winner_id, u.contacts
    FROM bids b
    LEFT JOIN lots l ON b.lot_id = l.id
    LEFT JOIN users u ON l.author_id = u.id
    LEFT JOIN categories c ON l.category_id = c.id
    WHERE b.user_id = ?
    ORDER BY b.bid_date DESC";

$bids = db_fetch_data($link, $sql, [$user['id']]);

$page_content = include_template('my-bets.php', [
    'categories' => $categories,
    'bids' => $bids,
    'user_id' => $user['id']
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => $lot['name']
]);

print($layout_content);
