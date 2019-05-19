<?php
require_once('init.php');

if (isset($_SESSION['user'])) {
    header("Location: /");
    exit;
}

$form = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['email', 'password'];

    foreach ($required as $key) {
        if (isset($_POST[$key]) && !empty(trim($_POST[$key]))) {
            $form[$key] = trim($_POST[$key]);
        } else {
            $errors[$key] = 'Это поле необходимо заполнить';
        }
    }

    if (!count($errors)) {
        $email = trim($_POST['email']);
        $sql = "SELECT * FROM users WHERE email = ?";
        $res = db_fetch_data($link, $sql, [$email]);
        $user = $res ? $res[0] : null;

        if (!$user) {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if (!count($errors)) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /");
            exit;
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }
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
