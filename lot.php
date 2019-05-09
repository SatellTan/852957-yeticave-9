<?php
require_once('init.php');

if (!isset($_GET['lot_id'])) {
    http_response_code(404);
    $page_content = include_template('404.php');

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'user' => $user,
        'categories' => $categories,
        'title' => $lot['name']
    ]);

    print($layout_content);
    exit;
}

$lot = null;
$errors = [];
$form = [];

$sql = "SELECT l.id, l.name, l.start_price, l.bid_step, l.description, l.img_URL, coalesce(MAX(b.price), l.start_price) AS current_price, l.finish_date, c.name AS category
    FROM lots l
    LEFT JOIN bids b ON b.lot_id = l.id
    INNER JOIN categories c ON l.category_id = c.id
    WHERE l.id = ?
    GROUP BY l.id";

$lot = db_fetch_data($link, $sql, [$_GET['lot_id']]);
if (!$lot) {
    print('Что-то пошло не так. Попробуйте позднее');
    exit;
}
$lot = $lot[0];

/* Признак false отображения блока со ставкой, если одно из условий:
    пользователь не авторизован;
    последняя ставка сделана текущим пользователем;
    лот создан текущим пользователем.
*/
$display = true;
if (empty($user)) {
    $display = false;
} else {
    $sql = "SELECT user_id, bid_date FROM bids WHERE lot_id = ? ORDER BY bid_date DESC";
    $last_bid = db_fetch_data($link, $sql, [$_GET['lot_id']]);
    if ($last_bid) {
        $last_bid = $last_bid[0];
        if ($last_bid['user_id'] === $user_id) {
            $display = false;
        }
    }

    if ($user_id === $lot['id']) {
        $display = false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['cost'];
	foreach ($required as $key) {
		if (isset($_POST[$key]) && empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле необходимо заполнить';
        } else {
            $form[$key] = trim($_POST[$key]);
        }
    }

    if (!isset($errors['cost'])) {
        if (!(intval($_POST['cost']) > 0)) {
            $errors['cost'] = 'Ставка должна быть больше 0';
        }
    }

    if (!isset($errors['cost'])) {
        if (!($_POST['cost'] >= ($lot['current_price'] + $lot['bid_step']))) {
            $errors['cost'] = 'Ставка меньше допустимой';
        }
    }

    if (!count($errors)) {
        //добавить новую ставку в таблицу ставок
        $sql = "INSERT INTO bids
        (bid_date, price, user_id, lot_id)
        VALUES
        (CURRENT_TIMESTAMP, ?, $user_id, ?)";

        $bid_id = db_insert_data($link, $sql, [$form['cost'], $lot['id']]);
        if ($bid_id) {
            header("Location: /lot.php?lot_id=".$lot['id']);
            exit;
        } else {
            print('Что-то пошло не так. Попробуйте позднее');
            exit;
        }
    }
}
$bids = [];
if (!empty($user)) {
    $sql = "SELECT b.*, u.name
    FROM bids b
    LEFT JOIN users u ON b.user_id = u.id
    WHERE b.lot_id = ?";

    $bids = db_fetch_data($link, $sql, [$lot['id']]);
}

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot,
    'user' => $user,
    'errors' => $errors,
    'form' => $form,
    'display' => $display,
    'bids' => $bids
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => $lot['name']
]);

print($layout_content);
