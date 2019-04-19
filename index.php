<?php
require_once('functions.php');

$user_name = 'Татьяна';
$categories = [
    [
        'class' => 'boards',
        'name' => 'Доски и лыжи'
    ],
    [
        'class' => 'attachment',
        'name' => 'Крепления'
    ],
    [
        'class' => 'boots',
        'name' => 'Ботинки'
    ],
    [
        'class' => 'clothing',
        'name' => 'Одежда'
    ],
    [
        'class' => 'tools',
        'name' => 'Инструменты'
    ],
    [
        'class' => 'other',
        'name' => 'Разное'
    ]
];

$lots = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => $categories[0],
        'price' => 10999,
        'img_URL' => 'img/lot-1.jpg'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories[0],
        'price' => 159999,
        'img_URL' => 'img/lot-2.jpg'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories[1],
        'price' => 8000,
        'img_URL' => 'img/lot-3.jpg'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories[2],
        'price' => 10999,
        'img_URL' => 'img/lot-4.jpg'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories[3],
        'price' => 7500,
        'img_URL' => 'img/lot-5.jpg'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => $categories[5],
        'price' => 5400,
        'img_URL' => 'img/lot-6.jpg'
    ]
];

function esc($str) {
	$text = htmlspecialchars($str);
	return $text;
}

function get_str_price($price) {
    if ($price >= 1000) {
        $result_str = number_format(ceil($price), 0, ',', ' ');
    }
    return $result_str;
}

function get_time_count() {
    $current_date_timestamp = time();
    $tomorrow_midnight_timestamp = strtotime('tomorrow');
    $secs_diff = $tomorrow_midnight_timestamp - $current_date_timestamp;
    $hours = floor($secs_diff / 3600);
    $minutes = floor(($secs_diff % 3600) / 60);
    $time_count = [$hours, $minutes];

    return $time_count;
}

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
    'currency' => 'р'
]);

$layout_content = include_template('layout.php', [
    'is_auth' => rand(0, 1),
	'content' => $page_content,
    'user_name' => $user_name,
    'categories' => $categories,
	'title' => 'Главная'
]);

print($layout_content);
