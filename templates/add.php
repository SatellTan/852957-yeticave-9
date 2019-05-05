<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?=esc($val['name']);?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<form class="form form--add-lot container <?php isset($errors)? print 'form--invalid' : ''?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $classname = isset($errors['lot-name']) ? 'form__item--invalid' : '';
            $error_text = isset($errors['lot-name']) ? $errors['lot-name'] : '';
            $lot_name = isset($lot['lot-name']) ? $lot['lot-name'] : '';?>
            <div class="form__item <?=$classname;?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" value="<?=$lot_name;?>" placeholder="Введите наименование лота">
                <span class="form__error"><?=$error_text;?></span>
            </div>

            <?php $classname = isset($errors['category']) ? 'form__item--invalid' : '';
            $error_text = isset($errors['category']) ? $errors['category'] : 'Выберите категорию';
            $lot_category = isset($lot['category']) ? $lot['category'] : '';?>
            <div class="form__item <?=$classname;?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $key => $val): ?>
                    <option value="<?=$val['id']?>" <?$val['id']==$lot['category'] ? print 'selected' : '';?>><?=esc($val['name']);?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=$error_text;?></span>
            </div>
        </div>

        <?php $classname = isset($errors['message']) ? 'form__item--invalid' : '';
        $error_text = isset($errors['message']) ? $errors['message'] : '';
        $lot_message = isset($lot['message']) ? $lot['message'] : '';?>
        <div class="form__item form__item--wide <?=$classname;?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"><?=$lot_message;?></textarea>
            <span class="form__error"><?=$error_text;?></span>
        </div>

        <?php $classname = isset($errors['file']) ? 'form__item--invalid' : '';
        $error_text = isset($errors['file']) ? $errors['file'] : '';?>
        <div class="form__item form__item--file">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file <?=$classname;?>">
                <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
                <label for="lot-img">
                    Добавить
                </label>
                <span class="form__error"><?=$error_text;?></span>
            </div>
        </div>

        <div class="form__container-three">
            <?php $classname = isset($errors['lot-rate']) ? 'form__item--invalid' : '';
            $error_text = isset($errors['lot-rate']) ? $errors['lot-rate'] : '';
            $lot_rate = isset($lot['lot-rate']) ? $lot['lot-rate'] : '';?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" value="<?=$lot_rate;?>" placeholder="0">
                <span class="form__error"><?=$error_text;?></span>
            </div>

            <?php $classname = isset($errors['lot-step']) ? 'form__item--invalid' : '';
            $error_text = isset($errors['lot-step']) ? $errors['lot-step'] : '';
            $lot_step = isset($lot['lot-step']) ? $lot['lot-step'] : '';?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" value="<?=$lot_step;?>" placeholder="0">
                <span class="form__error"><?=$error_text;?></span>
            </div>

            <?php $classname = isset($errors['lot-date']) ? 'form__item--invalid' : '';
            $error_text = isset($errors['lot-date']) ? $errors['lot-date'] : '';
            $lot_date = isset($lot['lot-date']) ? $lot['lot-date'] : '';?>
            <div class="form__item <?=$classname;?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" value="<?=$lot_date;?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <span class="form__error"><?=$error_text;?></span>
            </div>
        </div>
        <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <?php endif; ?>
        <button type="submit" class="button">Добавить лот</button>
    </form>
