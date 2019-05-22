<?php

/**
 *
 * @var array  $categories  Категории лотов
 * @var array  $errors      Ошибки валидации
 * @var array  $lots        Содержание лота
 * @var string $user_name   Имя пользователя
 * @var int    $date_end    Дата окончания лота
 * @var int    $user_id     ID пользователя
 * @var int    $lot_userid  ID пользователя создавшего лот
 * @var int    $rate_userid ID пользователя сделавшего ставку
 * @var int    $min_rate    Минимальная ставка лота
 * @var int    $rate        Текущая ставка лота
 * @var int    $sum         Количество ставок
 * @var array  $rates       Данные по ставке
 *
 */
?>

<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.php?pagecat=<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></a>
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
                <p class="lot-item__category">Категория:<span><?= htmlspecialchars($lot['name_cat']) ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['content']) ?></p>
            </div>

            <div class="lot-item__right">
                <?php if ($user_name && $date_end == null && $user_id != $lot_userid && $user_id != $rate_userid ): ?>
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
                                Мин. ставка
                                <span><?= price_format($min_rate) ?></span>
                            </div>
                        </div>
                        <form enctype="multipart/form-data" class="lot-item__form <?= isset($errors) ? 'form--invalid' : '' ?>" action="lot.php" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?= isset($errors['price']) ? 'form__item--invalid' : '' ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="price" placeholder="<?= price_format($min_rate) ?>" value="<?= isset($rate['price']) ? $rate['price'] : '' ?>">
                                <span class="form__error"><?= $errors['price'] ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif ?>
                <div class="history">
                    <h3>История ставок (<span><?= $sum ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($rates as $rate): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($rate['name']) ?></td>
                                <td class="history__price"><?= htmlspecialchars(price_format($rate['price'])) ?></td>
                                <td class="history__time"><?= htmlspecialchars($rate['date_add']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</section>
