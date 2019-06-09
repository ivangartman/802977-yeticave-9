<?php

/**
 *
 * @var array $categories Категории лотов
 * @var array $errors     Ошибки валидации
 * @var array $lot        Содержание лота
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
<form enctype="multipart/form-data"
      class="form form--add-lot container <?= isset($errors) ? 'form--invalid' : '' ?>" action="add.php" method="post">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?= isset($lot['name']) ? $lot['name'] : '' ?>">
            <span class="form__error"><?= $errors['name'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['category_id']) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach ?>
            </select>
            <span class="form__error"><?= $errors['category_id'] ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($errors['content']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="content" placeholder="Напишите описание лота"><?= isset($lot['content']) ? $lot['content'] : '' ?></textarea>
        <span class="form__error"><?= $errors['content'] ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($errors['lot-img']) ? 'form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="lot-img" id="lot-imgg" value="">
            <label for="lot-imgg">
                Добавить
            </label>
            <span class="form__error"><?= $errors['lot-img'] ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($errors['price']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="price" placeholder="0" value="<?= isset($lot['price']) ? $lot['price'] : '' ?>">
            <span class="form__error"><?= $errors['price'] ?></span>
        </div>
        <div class="form__item form__item--small <?= isset($errors['step_rate']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="step_rate" placeholder="0" value="<?= isset($lot['step_rate']) ? $lot['step_rate'] : '' ?>">
            <span class="form__error"><?= $errors['step_rate'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['date_end']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="date_end" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= isset($lot['date_end']) ? $lot['date_end'] : '' ?>">
            <span class="form__error"><?= $errors['date_end'] ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
