<?php

require_once('init.php');
require_once('vendor/autoload.php');
/*
    Найти все лоты без победителей, дата истечения которых меньше или равна текущей дате.
    Получить id автора максимальной ставки для лота.
*/
$sql = "SELECT l.id, l.name, l.img_URL, (SELECT b.user_id FROM bids b WHERE b.lot_id = l.id ORDER BY b.price DESC LIMIT 1) AS win_user_id
        FROM lots l
        WHERE l.winner_id IS NULL && l.finish_date <= CURRENT_TIMESTAMP";

$lots = db_fetch_data($link, $sql);

foreach ($lots as $lot) {
    if ($lot['win_user_id']) {
        // Записать в лот победителем id автора наибольшей ставки
        $sql = "UPDATE lots SET winner_id = ? WHERE id = ?";
        $res = db_insert_data($link, $sql, [$lot['win_user_id'], $lot['id']]);

        // Отправить победителю на email письмо-поздравление с победой
        $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
        $transport->setUsername('keks@phpdemo.ru');
        $transport->setPassword('htmlacademy');
        $mailer = new Swift_Mailer($transport);
        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

        $sql = "SELECT id, name, email FROM users WHERE id = ?";
        $user = db_fetch_data($link, $sql, [$lot['win_user_id']]);
        $user = $user[0];

        $message = new Swift_Message();
        $message->setSubject('Ваша ставка победила!');
        $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
        $message->addTo($user['email'], $user['name']);
        $msg_content = include_template('email.php',
            [
                'user' => $user,
                'lot' => $lot,
                'my_bets_link' => $project_link . '/my-bets.php',
                'lot_link'  => $project_link . '/lot.php?lot_id=' . $lot['id']
            ]);
        $message->setBody($msg_content, 'text/html');
        $mailer->send($message);
    }
}
