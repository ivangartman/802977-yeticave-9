<?php

/**
 *
 * @var string $user_name Имя пользователя
 * @var string $lot_link  Ссылка на лот
 * @var string $lot_name  Название лота
 * @var string $my_bets   Ссылка на мои ставки
 */
?>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= htmlspecialchars($user_name) ?></p>
<p>Ваша ставка для лота <a href="<?= htmlspecialchars($lot_link) ?>"><?= htmlspecialchars($lot_name) ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= htmlspecialchars($my_bets) ?>">мои ставки</a>, чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
