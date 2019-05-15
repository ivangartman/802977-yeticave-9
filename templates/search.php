<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.php?pagecat=<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search ?></span>»</h2>
        <ul class="lots__list">
            <?php if ($search && $lots): ?>
                <?php foreach ($lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= htmlspecialchars($lot['picture_url']) ?>" width="350" height="260" alt="<?= htmlspecialchars($lot['name_cat']) ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot['name_cat']) ?></span>
                            <h3 class="lot__title"><a class="text-link" href="lot.php?page=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['name_lot']) ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= htmlspecialchars(price_format($lot['price'])) ?><b class="rub">р</b></span>
                                </div>
                                <div class="lot__timer timer <?= timer_finishing($lot['date_end']) ? 'timer--finishing' : '' ?>">
                                    <?= timer($lot['date_end']) ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            <?php else: ?>
                <p>Ничего не найдено по вашему запросу</p>>
            <?php endif ?>
        </ul>
    </section>
    <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a href="search.php?page=<?= ($cur_page - 1 > 0) ? $cur_page - 1 : 1 ?>&search=<?= $search ?>">Назад</a></li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item <?= ($page == $cur_page) ? 'pagination-item-active' : '' ?>"><a href="search.php?page=<?= $page ?>&search=<?= $search ?>"><?= $page ?></a>
                </li>
            <?php endforeach ?>
            <li class="pagination-item pagination-item-next"><a href="search.php?page=<?= ($cur_page + 1 < $pages_count) ? $cur_page + 1 : $pages_count ?>&search=<?= $search ?>">Вперед</a>
            </li>
        </ul>
    <?php endif ?>
</div>
