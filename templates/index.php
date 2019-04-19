<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $key => $val): ?>
        <li class="promo__item promo__item--<?=$val['class'];?>">
            <a class="promo__link" href="pages/all-lots.html"><?=$val['name'];?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($lots as $key => $val): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=esc($val['img_URL']);?>" width="350" height="260" alt="">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=$val['category']['name'];?></span>
                <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=esc($val['name']);?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?=esc(get_str_price($val['price']));?><b class="rub"><?=$currency?></b></span>
                    </div>
                    <div class="lot__timer timer <?=get_time_count()[0]<1 ? 'timer--finishing': ''?>">
                        <?=implode(':', get_time_count());?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
