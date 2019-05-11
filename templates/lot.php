<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?=esc($val['name']);?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=esc($lot['name']);?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$lot['img_URL'];?>" width="730" height="548" alt="<?=esc($lot['name']);?>">
            </div>
            <p class="lot-item__category">Категория: <span><?=$lot['category'];?></span></p>
            <p class="lot-item__description"><?=esc($lot['description']);?></p>
        </div>
        <?php if (strtotime($lot['finish_date']) > time()): ?>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?=get_timer_finishing($lot['finish_date']) ? 'timer--finishing': ''?>">
                    <?=get_time_count($lot['finish_date']);?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=get_str_price($lot['current_price']);?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=get_str_price($lot['current_price'] + $lot['bid_step']);?> р</span>
                    </div>
                </div>

                <?php if ($display): ?>
                <form class="lot-item__form" action="/lot.php?lot_id=<?=$lot['id']?>" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?= isset($errors['cost']) ? 'form__item--invalid' : '';?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" value="<?= isset($form['cost']) ? $form['cost'] : '';?>" placeholder="12 000">
                        <span class="form__error"><?= isset($errors['cost']) ? $errors['cost'] : 'Введите сумму ставки';?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
                <?php endif; ?>
            </div>

            <div class="history">
                <h3>История ставок (<span><?= count($bids);?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bids as $key => $val): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= esc($val['name']);?></td>
                        <td class="history__price"><?= esc($val['price']).' р';?></td>
                        <td class="history__time"><?= showDate(StrToTime($val['bid_date']));?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
