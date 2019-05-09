<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<section class="lot-item container">
    <?php foreach ($lots as $lot): ?>
        <h2><?= htmlspecialchars($lot['name_lot']) ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot['picture_url']) ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['name_cat']) ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['content']) ?></p>
            </div>

            <?php if ($user_name): ?>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?= timer_finishing($lot['date_end']) ? 'timer--finishing' : '' ?>">
                        <?= timer($lot['date_end']) ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= $lot['price_rate'] ? price_format($lot['price_rate']) : price_format($lot['price_lot']) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span>12 000 р</span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>

                <div class="history">
                    <h3>История ставок (<span><?= $sum ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($rates as $rate): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($rate['name']) ?></td>
                                <td class="history__price"><?= htmlspecialchars($rate['price']) ?></td>
                                <td class="history__time"><?= htmlspecialchars($rate['date_add']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</section>