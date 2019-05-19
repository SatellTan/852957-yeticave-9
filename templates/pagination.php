<?php if ($pages_count > 1): ?>
<ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
        <a <?php if (intval($current_page) !== 1): ?>
            href="<?=$page_link;?><?=$current_page-1;?>"
            <?php endif; ?>>Назад
        </a>
    </li>

    <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?= (intval($page) === $current_page) ? 'pagination-item-active' : '' ?>">
            <a href="<?=$page_link;?><?=$page;?>"><?=$page;?></a>
        </li>
    <?php endforeach; ?>

    <li class="pagination-item pagination-item-next">
        <a <?php if (intval($current_page) < intval($pages_count)): ?>
            href="<?=$page_link;?><?=$current_page+1;?>"
            <?php endif; ?>>Вперед
        </a>
    </li>

</ul>
<?php endif; ?>
