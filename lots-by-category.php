<?php
require_once('init.php');

$category_id = '';
$category = '';
$lots = [];
$message = 'Все лоты в категории ';
$current_page = 1;
$pages_count = 0;

if (isset($_GET['category']) && trim($_GET['category'])) {
    $category_id = trim($_GET['category']);
}

$sql = "SELECT c.name
        FROM categories c
        WHERE c.id = ?
        GROUP BY c.id";

$res = db_fetch_data($link, $sql, [$category_id]);

if (!$res) {
    display_error_code_block (404, $categories, 'Категория с таким номером не существует');
    exit;
}

if ($category_id) {
    $category = $res[0]['name'];
    $current_page = intval($_GET['page']) ?? 1;

    //Найти общее количество лотов для пагинации
    $sql = "SELECT COUNT(*) as cnt
        FROM lots
        WHERE category_id = ? && finish_date > CURRENT_TIMESTAMP";

    $items_count = db_fetch_data($link, $sql, [$category_id])[0]['cnt'];
    $pages_count = ceil($items_count / $page_items);

    //Проверка номера текущей страницы, исправление в случае некорректности номера
    if ($current_page < 1) {
        $current_page = 1;
    }
    if (($current_page > $pages_count) && $pages_count) {
        $current_page = $pages_count;
    }

    $offset = ($current_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = "SELECT l.id, l.name, l.start_price, l.img_URL, coalesce(MAX(b.price), l.start_price) AS current_price,
            COUNT(b.price) AS count_bids, l.finish_date, c.name AS category, l.category_id
        FROM lots l
        LEFT JOIN bids b ON b.lot_id = l.id
        INNER JOIN categories c ON l.category_id = c.id
        WHERE l.category_id = ? && l.finish_date > CURRENT_TIMESTAMP
        GROUP BY l.id
        ORDER BY l.creation_date DESC
        LIMIT " . $page_items . " OFFSET " . $offset;

    $lots = db_fetch_data($link, $sql, [$category_id]);
}

$page_link = '/lots-by-category.php?category=' . $category_id . '&page=';

$pagination = include_template('pagination.php', [
    'pages' => $pages,
    'pages_count' => $pages_count,
    'current_page' => $current_page,
    'page_link' => $page_link
]);

$page_content = include_template('lots-by-category.php', [
    'categories' => $categories,
    'lots' => $lots,
    'category_id' => $category_id,
    'category' => $category,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => $category . ' - Yeticave'
]);

print($layout_content);
