<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="/lots-by-category.php?category=<?=$val['id'];?>"><?=$val['name'];?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<div class="container">
    <section class="lots">
        <h2><?=$message;?>
            <?php if ($search) : ?>
            «<span><?=$search;?></span>»
            <?php endif; ?>
        </h2>
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
                            <span class="lot__amount"><?=($val['count_bids']>0) ? $val['count_bids'].' '.get_noun_plural_form($val['count_bids'], 'ставка', 'ставки', 'ставок'): 'Стартовая цена'?></span>
                            <span class="lot__cost"><?=get_str_price($val['current_price']);?><b class="rub">р</b></span>
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
    <?= $pagination; ?>
</div>
