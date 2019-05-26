<?php

require_once('init.php');
require_once('vendor/autoload.php');

$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 2525);
$transport->setUsername('712949fbbcd334');
$transport->setPassword('c9225308ba1c1c');

$mailer = new Swift_Mailer($transport);
$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

// Найти все лоты без победителей, но хотя бы с одной ставкой, дата истечения которых меньше или равна текущей дате.
$sql = "SELECT b.lot_id, l.name
        FROM bids b
        INNER JOIN lots l ON b.lot_id = l.id
        WHERE l.finish_date <= CURRENT_TIMESTAMP && l.winner_id IS NULL
        GROUP BY b.lot_id";

$lots = db_fetch_data($link, $sql);

foreach ($lots as $lot) {
    // Получить все данные автора наибольшей ставки для лота
    $sql = "SELECT b.user_id, u.name, u.email
        FROM bids b
        INNER JOIN users u ON b.user_id = u.id
        WHERE b.lot_id = ?
        ORDER BY b.price DESC LIMIT 1";

    $winner = db_fetch_data($link, $sql, [$lot['lot_id']]);
    if ($winner) {
        $winner = $winner[0];

        // Записать в лот победителем id автора наибольшей ставки
        $sql = "UPDATE lots SET winner_id = ? WHERE id = ?";
        $res = db_insert_data($link, $sql, [$winner['user_id'], $lot['lot_id']]);

        // Отправить победителю на email письмо-поздравление с победой
        $message = new Swift_Message();
        $message->setSubject('Ваша ставка победила!');
        $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
        $message->addTo($winner['email'], $winner['name']);
        $msg_content = include_template('email.php',
            [
                'user' => $winner,
                'lot' => $lot,
                'my_bets_link' => $project_link . '/my-bets.php',
                'lot_link'  => $project_link . '/lot.php?lot_id=' . $lot['lot_id']
            ]);

        $message->setBody($msg_content, 'text/html');
        $mailer->send($message);
    }
}
