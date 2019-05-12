<?php
/*
    Найти все лоты без победителей, дата истечения которых меньше или равна текущей дате.
    Получить id автора максимальной ставки для лота.
*/
$sql = "SELECT l.id, l.name, (SELECT b.user_id FROM bids b WHERE b.lot_id = l.id ORDER BY b.price DESC LIMIT 1) AS win_user_id
        FROM lots l
        WHERE l.winner_id IS NULL && l.finish_date <= CURRENT_TIMESTAMP";

$lots = db_fetch_data($link, $sql);

// Записать в лот победителем id автора последней ставки.
foreach ($lots as $lot) {
    if (isset($lot['win_user_id'])) {

        $sql = "UPDATE lots SET winner_id = ? WHERE id = ?";

        $res = db_insert_data($link, $sql, [$lot['win_user_id'], $lot['id']]);
    }
}

// Отправить победителю на email письмо-поздравление с победой.
