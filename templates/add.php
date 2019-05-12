<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="/lots-by-category.php?category=<?=$val['id'];?>"><?=$val['name'];?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<form class="form form--add-lot container <?= count($errors)? 'form--invalid' : ''?>" action="/add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($errors['lot-name']) ? 'form__item--invalid' : '';?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" value="<?= isset($lot['lot-name']) ? $lot['lot-name'] : '';?>" placeholder="Введите наименование лота">
            <span class="form__error"><?= isset($errors['lot-name']) ? $errors['lot-name'] : '';?></span>
        </div>

        <div class="form__item <?= isset($errors['category']) ? 'form__item--invalid' : '';?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $key => $val): ?>
                <option value="<?=$val['id']?>" <?= ($val['id']==$lot['category']) ? 'selected' : '';?>><?=esc($val['name']);?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= isset($errors['category']) ? $errors['category'] : 'Выберите категорию';?></span>
        </div>
    </div>

    <div class="form__item form__item--wide <?= isset($errors['message']) ? 'form__item--invalid' : '';?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= isset($lot['message']) ? $lot['message'] : '';?></textarea>
        <span class="form__error"><?= isset($errors['message']) ? $errors['message'] : '';?></span>
    </div>

    <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file <?= isset($errors['file']) ? 'form__item--invalid' : '';?>">
            <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
            <label for="lot-img">
                Добавить
            </label>
            <span class="form__error"><?= isset($errors['file']) ? $errors['file'] : '';?></span>
        </div>
    </div>

    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($errors['lot-rate']) ? 'form__item--invalid' : '';?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" value="<?= isset($lot['lot-rate']) ? $lot['lot-rate'] : '';?>" placeholder="0">
            <span class="form__error"><?= isset($errors['lot-rate']) ? $errors['lot-rate'] : '';?></span>
        </div>

        <div class="form__item form__item--small <?= isset($errors['lot-step']) ? 'form__item--invalid' : '';?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" value="<?= isset($lot['lot-step']) ? $lot['lot-step'] : '';?>" placeholder="0">
            <span class="form__error"><?= isset($errors['lot-step']) ? $errors['lot-step'] : '';?></span>
        </div>

        <div class="form__item <?= isset($errors['lot-date']) ? 'form__item--invalid' : '';?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" value="<?= isset($lot['lot-date']) ? $lot['lot-date'] : '';?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <span class="form__error"><?= isset($errors['lot-date']) ? $errors['lot-date'] : '';?></span>
        </div>
    </div>
    <?php if (isset($errors)): ?>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
