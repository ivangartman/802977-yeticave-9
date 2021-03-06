<?php

/**
 *
 * @var array $categories Категории лотов
 * @var array $lot        Содержание лота
 *
 */
?>

<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['code'] ?>">
                <a class="promo__link" href="all-lots.php?pagecat=<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
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
                            <span class="lot__cost"><?= htmlspecialchars(substr(price_format($lot['price']),0, -2)) ?><b class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer <?= timer_finishing($lot['date_end']) ? 'timer--finishing' : '' ?>">
                            <?= timer($lot['date_end']) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</section>
