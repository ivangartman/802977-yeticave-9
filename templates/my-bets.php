<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($lots_user as $lot): ?>
            <?php foreach (db_price_max($link, $lot['id']) as $price_max): ?>
                <?php if ($price_max['user_id'] == $user_id and endDate($lot['date_end'])): ?>
                    <tr class="rates__item rates__item--win">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($lot['picture_url']) ?>" width="54" height="40" alt="<?= htmlspecialchars($lot['name_cat']) ?>">
                            </div>
                            <div>
                                <h3 class="rates__title"><a href="lot.php?page=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['name_lot']) ?></a>
                                </h3>
                                <p><?= htmlspecialchars($lot['contact']) ?></p>
                            </div>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($lot['name_cat']) ?>
                        </td>
                        <td class="rates__timer">
                            <div class="timer timer--win">Ставка выиграла</div>
                        </td>
                        <td class="rates__price">
                            <?= htmlspecialchars(price_format($lot['price'])) ?>
                        </td>
                        <td class="rates__time">
                            <?= htmlspecialchars($lot['date_add']) ?>
                        </td>
                    </tr>
                <?php elseif (endDate($lot['date_end'])): ?>
                    <tr class="rates__item rates__item--end">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($lot['picture_url']) ?>" width="54" height="40" alt="<?= htmlspecialchars($lot['name_cat']) ?>">
                            </div>
                            <h3 class="rates__title"><a href="lot.php?page=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['name_lot']) ?></a></h3>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($lot['name_cat']) ?>
                        </td>
                        <td class="rates__timer">
                            <div class="timer timer--end">Торги окончены</div>
                        </td>
                        <td class="rates__price">
                            <?= htmlspecialchars(price_format($lot['price'])) ?>
                        </td>
                        <td class="rates__time">
                            <?= htmlspecialchars($lot['date_add']) ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr class="rates__item">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($lot['picture_url']) ?>" width="54" height="40" alt="Сноуборд">
                            </div>
                            <h3 class="rates__title"><a href="lot.php?page=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['name_lot']) ?></a>
                            </h3>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($lot['name_cat']) ?>
                        </td>
                        <td class="rates__timer">
                            <div class="timer <?= timer_finishing($lot['date_end']) ? 'timer--finishing' : '' ?>"><?= timer($lot['date_end']) ?></div>
                        </td>
                        <td class="rates__price">
                            <?= htmlspecialchars(price_format($lot['price'])) ?>
                        </td>
                        <td class="rates__time">
                            <?= htmlspecialchars($lot['date_add']) ?>
                        </td>
                    </tr>
                <?php endif ?>
            <?php endforeach ?>
        <?php endforeach ?>
    </table>
</section>
