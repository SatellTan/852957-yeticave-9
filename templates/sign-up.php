<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="/lots-by-category.php?category=<?=$val['id'];?>"><?=$val['name'];?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<form class="form container <?= count($errors)? 'form--invalid' : ''?>" action="/sign-up.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '';?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= isset($user['email']) ? $user['email'] : '';?>" placeholder="Введите e-mail">
        <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : '';?></span>
    </div>

    <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '';?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= isset($user['password']) ? $user['password'] : '';?>" placeholder="Введите пароль">
        <span class="form__error"><?= isset($errors['password']) ? $errors['password'] : '';?></span>
    </div>

    <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '';?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?= isset($user['name']) ? $user['name'] : '';?>" placeholder="Введите имя">
        <span class="form__error"><?= isset($errors['name']) ? $errors['name'] : '';?></span>
    </div>

    <div class="form__item <?= isset($errors['message']) ? 'form__item--invalid' : '';?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите, как с вами связаться"><?= isset($user['message']) ? $user['message'] : '';?></textarea>
        <span class="form__error"><?= isset($errors['message']) ? $errors['message'] : '';?></span>
    </div>

    <div class="form__item form__item--file form__item--last <?= isset($errors['file']) ? 'form__item--invalid' : ''; ?>">
        <label>Аватар</label>
        <div class="form__input-file">
            <input class="visually-hidden" id="avatar" type="file" name="avatar" value="">
            <label for="avatar">
            <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error"><?= isset($errors['file']) ? $errors['file'] : ''; ?></span>
    </div>

    <?php if (isset($errors)): ?>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>
