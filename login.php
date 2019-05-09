<?php
require_once('init.php');

if (isset($_SESSION['user'])) {
    header("Location: /");
    exit;
}

$form = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$required = ['email', 'password'];

	foreach ($required as $key) {
		if (isset($_POST[$key]) && empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле необходимо заполнить';
		} else {
            $form[$key] = trim($_POST[$key]);
        }
    }

    if (!count($errors)) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $res = db_fetch_data($link, $sql, [$_POST['email']]);
        $user = $res ? $res[0] : null;
    }

    if (!count($errors) && $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /");
            exit;
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }
    }

    if (!count($errors) && !$user) {
        $errors['email'] = 'Такой пользователь не найден';
    }
}

$page_content = include_template('login.php', [
    'user' => $form,
    'categories' => $categories,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'categories' => $categories,
    'title' => 'Yeticave - Вход'
]);

print($layout_content);
