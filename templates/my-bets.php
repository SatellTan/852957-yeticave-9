<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $val): ?>
        <li class="nav__item">
            <a href="/lots-by-category.php?category=<?=$val['id'];?>"><?=$val['name'];?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bids as $key => $val): ?>
        <tr class="rates__item <?= ($val['winner_id'] === $user_id) ? 'rates__item--win': '';?><?= ((strtotime($val['finish_date']) < time()) && ($val['winner_id'] !== $user_id)) ? 'rates__item--end': '';?>">
            <td class="rates__info">
                <div class="rates__img">
                <img src="<?='/'.$val['img_URL'];?>" width="54" height="40" alt="<?=esc($val['name']);?>">
                </div>
                <h3 class="rates__title"><a href="/lot.php?lot_id=<?= $val['lot_id'];?>"><?=esc($val['name']);?></a></h3>
                <?php if ($val['winner_id'] === $user_id): ?>
                <p><?=esc($val['contacts']);?></p>
                <?php endif; ?>
            </td>
            <td class="rates__category">
                <?=esc($val['category']);?>
            </td>
            <?php if (strtotime($val['finish_date']) > time()): ?>
            <td class="rates__timer">
                <div class="timer  <?=get_timer_finishing($val['finish_date']) ? 'timer--finishing': ''?>"><?= get_time_count($val['finish_date']); ?></div>
            </td>
            <?php endif; ?>

            <?php if ($val['winner_id'] === $user_id): ?>
            <td class="rates__timer">
                <div class="timer timer--win">Ставка выиграла</div>
            </td>
            <?php endif; ?>
            <?php if ((strtotime($val['finish_date']) < time()) && ($val['winner_id'] !== $user_id)): ?>
            <td class="rates__timer">
                <div class="timer timer--end">Торги окончены</div>
            </td>
            <?php endif; ?>

            <td class="rates__price">
                <?=esc($val['price']).' р';?>
            </td>
            <td class="rates__time">
                <?=showDate(StrToTime($val['bid_date']));?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
