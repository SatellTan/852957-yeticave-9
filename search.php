<?php
require_once('init.php');

$search = '';
$lots = [];
$current_page = 1;
$pages_count = 0;


if (isset($_GET['search']) && trim($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search) {
    $current_page = intval($_GET['page'] ?? 1);

    //Найти общее количество лотов для пагинации
    $sql = "SELECT COUNT(*) as cnt
        FROM lots
        WHERE MATCH(name, description) AGAINST(?) && finish_date > CURRENT_TIMESTAMP";

    $items_count = db_fetch_data($link, $sql, [$search])[0]['cnt'];
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
            COUNT(b.price) AS count_bids, l.finish_date, c.name AS category
        FROM lots l
        LEFT JOIN bids b ON b.lot_id = l.id
        INNER JOIN categories c ON l.category_id = c.id
        WHERE MATCH(l.name, l.description) AGAINST(?) && l.finish_date > CURRENT_TIMESTAMP
        GROUP BY l.id
        ORDER BY l.creation_date DESC
        LIMIT " . $page_items . " OFFSET " . $offset;

    $lots = db_fetch_data($link, $sql, [$search]);
    if ($lots) {
        $message = 'Результаты поиска по запросу ';
    } else {
        $message = 'Ничего не найдено по вашему запросу ';
    }
}

$page_link = '/search.php?search=' . $search . '&page=';

$pagination = include_template('pagination.php', [
    'pages' => $pages,
    'pages_count' => $pages_count,
    'current_page' => $current_page,
    'page_link' => $page_link
]);

$page_content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search,
    'message' => $message,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => $search . ' - Yeticave',
    'search' => $search
]);

print($layout_content);
