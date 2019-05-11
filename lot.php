<?php
require_once('init.php');

if (!isset($_GET['lot_id'])) {
    display_error_code_block (404, $categories, 'Лот не найден');
    exit;
}

$lot = null;
$errors = [];
$form = [];

$sql = "SELECT l.*, coalesce(MAX(b.price), l.start_price) AS current_price, c.name AS category
    FROM lots l
    LEFT JOIN bids b ON b.lot_id = l.id
    INNER JOIN categories c ON l.category_id = c.id
    WHERE l.id = ?
    GROUP BY l.id";

$lot = db_fetch_data($link, $sql, [$_GET['lot_id']]);
if (!$lot) {
    display_error_code_block (404, $categories, 'Лот не найден');
    exit;
}
$lot = $lot[0];

$sql = "SELECT b.*, u.name
        FROM bids b
        LEFT JOIN users u ON b.user_id = u.id
        WHERE b.lot_id = ?
        ORDER BY b.bid_date DESC";
$sorted_bids = db_fetch_data($link, $sql, [$lot['id']]);

/* Признак false отображения блока со ставкой, если одно из условий:
    пользователь не авторизован;
    лот создан текущим пользователем;
    последняя ставка сделана текущим пользователем.
*/
$display_form = false;
if (!empty($user)) {
    if ($user['id'] !== $lot['author_id']) {
        $display_form = true;
    }

    if ($sorted_bids) {
        $last_bid = $sorted_bids[0];
        if ($last_bid['user_id'] === $user['id']) {
            $display_form = false;
        }
    }
}

if ( $display_form && ($_SERVER['REQUEST_METHOD'] === 'POST')) {

    $required = ['cost'];
	foreach ($required as $key) {
        if (isset($_POST[$key])) {
            if (empty(trim($_POST[$key]))) {
                $errors[$key] = 'Это поле необходимо заполнить';
            } else {
                $form[$key] = trim($_POST[$key]);
            }
        } else {
            $errors[$key] = 'Поле ' . $key . ' отсутствует в форме';
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
            (CURRENT_TIMESTAMP, ?, ?, ?)";

        $bid_id = db_insert_data($link, $sql, [$form['cost'], $user['id'], $lot['id']]);
        if ($bid_id) {
            $lot['current_price'] = $form['cost'];

            $new_bid['name'] = $user['name'];
            $new_bid['price'] = $form['cost'];
            $new_bid['bid_date'] = date('Y-m-d H:i:s');
            array_unshift($sorted_bids, $new_bid);

            $display_form = false;
        } else {
            print('Что-то пошло не так. Попробуйте позднее');
            exit;
        }
    }
}

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot,
    'user' => $user,
    'errors' => $errors,
    'form' => $form,
    'display' => $display_form,
    'bids' => $sorted_bids
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => $lot['name']
]);

print($layout_content);
