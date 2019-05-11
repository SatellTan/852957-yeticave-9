<?php
require_once('init.php');

if (!isset($_SESSION['user'])) {
    display_error_code_block (403, $categories, 'Yeticave - Добавление лота');
    exit;
}

$lot = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];

	foreach ($required as $key) {
		if (isset($_POST[$key])) {
            if (empty(trim($_POST[$key]))) {
                $errors[$key] = 'Это поле необходимо заполнить';
            } else {
                $lot[$key] = trim($_POST[$key]);
            }
        } else {
            $errors[$key] = 'Поле ' . $key . ' отсутствует в форме';
        }
    }

    //Проверка, выбрана ли категория (передан id категории, являющийся числом)
    if (!isset($errors['category']) && (intval($_POST['category']) === 0)) {
        $errors['category'] = 'Значение категории необходимо выбрать';
    } else {
        $sql = "SELECT c.name
            FROM categories c
            WHERE c.id = ?
            GROUP BY c.id";

        $category = db_fetch_data($link, $sql, [$_POST['category']]);

        if (!$category) {
            $errors['category'] = 'Значение категории не найдено в базе данных';
        }
    }

    if (!isset($errors['lot-name']) && (strlen($_POST['lot-name']) > $str_max_length)) {
        $errors['lot-name'] = 'Наименование больше допустимой длины в 128 символов';
    }

    if (!isset($errors['lot-date'])) {
        if (!is_date_valid($_POST['lot-date'])) {
            $errors['lot-date'] = 'Дата не соответствует формату ГГГГ-ММ-ДД';
        } elseif ($_POST['lot-date'] <= date("Y-m-d")) {
            $errors['lot-date'] = 'Дата должна быть больше текущей';
        }
    }

    if (!isset($errors['lot-rate'])) {
        if (!(intval($_POST['lot-rate']) > 0)) {
            $errors['lot-rate'] = 'Значение должно быть больше 0';
        }
    }

    if (!isset($errors['lot-step'])) {
        if (!(intval($_POST['lot-step']) > 0)) {
            $errors['lot-step'] = 'Значение должно быть больше 0';
        }
    }

    if (!empty($_FILES['lot-img']['name'])) {
		$tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_type = mime_content_type($tmp_name);
		if (($file_type !== 'image/jpeg') && ($file_type !== 'image/png')) {
			$errors['file'] = 'Загрузите картинку в формате jpg/jpeg/png';
		}
	}
	else {
		$errors['file'] = 'Вы не загрузили файл';
	}

    if (!count($errors)) {
        //файл изображения с новым уникальным именем перенести в публичную директорию и сохранить ссылку
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $ext = 'jpg';
        if ($file_type === 'image/png') {
            $ext = 'png';
        }

		$path = '/uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'] . $path);
        $lot['path'] = $path;

        //сохранить новый лот в таблице лотов
        $sql = "INSERT INTO lots
            (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
            VALUES
            (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?)";

        $lot_id = db_insert_data($link, $sql, [$lot['lot-name'], $lot['message'], $lot['path'], $lot['lot-rate'], $lot['lot-date'], $lot['lot-step'], $user['id'], $lot['category']]);

        //Редирект на страницу с описанием нового лота
        if ($lot_id) {
            header("Location: /lot.php?lot_id=".$lot_id);
            exit;
        }

        print('Что-то пошло не так. Попробуйте позднее');
        exit;
	}
}

$page_content = include_template('add.php', [
    'lot' => $lot,
    'categories' => $categories,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => 'Yeticave - Добавление лота'
]);

print($layout_content);
