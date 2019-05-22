<?php

/**
 *
 * @var array  $categories    Категории лотов
 * @var int    $pagecat       ID категории
 * @var string $error_message Сообщение об ошибке
 *
 */
?>

<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item <?= ($pagecat === htmlspecialchars($category['id'])) ? ' nav__item--current' : '' ?>">
                <a href="all-lots.php?pagecat=<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2>Страница не найдена.</h2>
    <p><?= $error_message ?></p>
</section>
