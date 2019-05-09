<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?= esc($val['name']);?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<form class="form container <?= count($errors)? 'form--invalid' : ''?>" action="/login.php" method="post">
    <h2>Вход</h2>
    <?php if (isset($errors)): ?>
    <span class="form__error form__error--bottom">Вы ввели неверный email/пароль</span>
    <?php endif; ?>
    <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '';?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= isset($user['email']) ? $user['email'] : '';?>" placeholder="Введите e-mail">
        <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : 'Введите e-mail';?></span>
    </div>
    <div class="form__item form__item--last <?= isset($errors['password']) ? 'form__item--invalid' : '';?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= isset($user['password']) ? $user['password'] : '';?>" placeholder="Введите пароль">
        <span class="form__error"><?= isset($errors['password']) ? $errors['password'] : 'Введите пароль';?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
