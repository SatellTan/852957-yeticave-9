<?php
require_once('init.php');

if (isset($_SESSION['user'])) {
    header("Location: /");
    exit;
}

$user = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$required = ['email', 'password', 'name', 'message'];

	foreach ($required as $key) {
		if (isset($_POST[$key]) && empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле необходимо заполнить';
		} else {
            $user[$key] = trim($_POST[$key]);
        }
    }

    if (!isset($errors['email'])) {
        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Неверный формат e-mail';
        }
    }

    if (!isset($errors['email'])) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $user_id = db_fetch_data($link, $sql, [$_POST['email']]);
        if ($user_id) {
            $errors['email'] = 'Пользователь с таким e-mail уже зарегистрирован';
        }
    }

    if (!isset($errors['name']) && (strlen($_POST['name']) > ($str_max_length / 2))) {
        $errors['name'] = 'Имя больше допустимой длины в 64 символа';
    }

    if (!isset($errors['password']) && (strlen($_POST['password']) > ($str_max_length / 2))) {
        $errors['password'] = 'Пароль больше допустимой длины в 64 символа';
    }

    if (!empty($_FILES['avatar']['name'])) {
		$tmp_name = $_FILES['avatar']['tmp_name'];
        $file_type = mime_content_type($tmp_name);
		if (($file_type !== 'image/jpeg') && ($file_type !== 'image/png')) {
			$errors['file'] = 'Загрузите файл в формате jpg/jpeg/png';
		}
	}

    if (!count($errors)) {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        $user['path'] = '';
        if (!empty($_FILES['avatar']['name'])) {
            //файл изображения с новым уникальным именем перенести в публичную директорию и сохранить ссылку
            $tmp_name = $_FILES['avatar']['tmp_name'];
            $file_type = mime_content_type($tmp_name);
            $ext = 'jpg';
            if ($file_type === 'image/png') {
                $ext = 'png';
            }

            $path = '/uploads/' . uniqid() . '.' . $ext;
            move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'] . $path);
            $user['path'] = $path;
        }

        //Сохранить нового пользователя в БД
        $sql = "INSERT INTO users
            (registration_date, email, name, password, avatar_url, contacts)
            VALUES
            (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?)";

        $user_id = db_insert_data($link, $sql, [$user['email'], $user['name'], $password, $user['path'], $user['message']]);

        //Редирект на страницу входа пользователя
        if ($user_id) {
            header("Location: /login.php");
            exit;
        }

        print('Что-то пошло не так. Попробуйте позднее');
        exit;
	}
}

$page_content = include_template('sign-up.php', [
    'user' => $user,
    'categories' => $categories,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => 'Yeticave - Регистрация'
]);

print($layout_content);
