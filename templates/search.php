<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?=esc($val['name']);?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<div class="container">
    <section class="lots">
        <?php if ($lots): ?>
        <h2>Результаты поиска по запросу «<span><?=$search;?></span>»</h2>
        <?php else: ?>
        <h2>По вашему запросу ничего не найдено </h2>
        <?php endif; ?>

        <ul class="lots__list">
            <?php foreach ($lots as $key => $val): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$val['img_URL'];?>" width="350" height="260" alt="<?=esc($val['name']);?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$val['category'];?></span>
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?lot_id=<?=$val['id'];?>"><?=esc($val['name']);?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=get_str_price($val['start_price']);?><b class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer <?=get_timer_finishing($val['finish_date']) ? 'timer--finishing': ''?>">
                            <?=get_time_count($val['finish_date']);?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <!--<ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>-->
</div>