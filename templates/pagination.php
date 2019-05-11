<?php if ($pages_count > 1): ?>
<ul class="pagination-list">
    <?php if (intval($current_page) !== 1): ?>
        <li class="pagination-item pagination-item-prev"><a href="<?=$page_link;?><?=$current_page-1;?>">Назад</a></li>
    <?php endif; ?>

    <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?= ($page == $current_page) ? 'pagination-item-active' : '' ?>">
            <a href="<?=$page_link;?><?=$page;?>"><?=$page;?></a>
        </li>
    <?php endforeach; ?>

    <?php if (intval($current_page) < intval($pages_count)): ?>
        <li class="pagination-item pagination-item-next"><a href="<?=$page_link;?><?=$current_page+1;?>">Вперед</a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>
