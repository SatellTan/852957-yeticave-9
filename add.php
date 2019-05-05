<?php
require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$lot = $_POST;
	$required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];

	foreach ($required as $key) {
		if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле необходимо заполнить';
		}
    }

    //Проверка, выбрана ли категория (передан id категории, являющийся числом)
    if (intval($_POST['category']) === 0) {
        $errors['category'] = 'Значение категории необходимо выбрать';
    }

    if (!isset($errors['lot-date'])) {
        if (!is_date_valid($_POST['lot-date'])) {
            $errors['lot-date'] = 'Дата не соответствует формату ГГГГ-ММ-ДД';
        } elseif ($_POST['lot-date'] <= date("Y-m-d")) {
            $errors['lot-date'] = 'Дата должна быть больше текущей';
        }
    }

    if (!(intval($_POST['lot-rate']) > 0)) {
        $errors['lot-rate'] = 'Значение должно быть больше 0';
    }

    if (!(intval($_POST['lot-step']) > 0)) {
        $errors['lot-step'] = 'Значение должно быть больше 0';
    }

	if (isset($_FILES['lot-img']['name'])) {
		$tmp_name = $_FILES['lot-img']['tmp_name'];
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        //$file_type = mime_content_type ($tmp_name); //В ТЗ прописано использование этой функции
		if (($file_type !== "image/jpeg") && ($file_type !== "image/png")) {
			$errors['file'] = 'Загрузите картинку в формате jpg/jpeg/png';
		}
	}
	else {
		$errors['file'] = 'Вы не загрузили файл';
	}

	if (count($errors)) {
		$page_content = include_template('add.php', [
            'lot' => $lot,
            'categories' => $categories,
            'errors' => $errors
        ]);
	}
	else {
        //файл изображения перенести в публичную директорию и сохранить ссылку
        $tmp_name = $_FILES['lot-img']['tmp_name'];
		$path = 'uploads/' . $_FILES['lot-img']['name'];
        move_uploaded_file($tmp_name, $path);
        $lot['path'] = $path;

        //сохранить новый лот в таблице лотов
        $sql = "INSERT INTO lots
        (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
        VALUES
        (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, 1, ?)";

        $lot_id = db_insert_data($link, $sql, [esc($lot['lot-name']), esc($lot['message']), $lot['path'], esc($lot['lot-rate']), esc($lot['lot-date']), esc($lot['lot-step']), esc($lot['category'])]);

        //Редирект на страницу с описанием нового лота
        header("Location: /lot.php?lot_id=".$lot_id);
	}
}
else {
    $page_content = include_template('add.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'is_auth' => rand(0, 1),
    'content' => $page_content,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => 'Yeticave - Добавление лота'
]);

print($layout_content);
