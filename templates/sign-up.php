<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.php?pagecat=<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<form enctype="multipart/form-data" class="form container <?= isset($errors) ? 'form--invalid' : '' ?>" action="sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= isset($user['email']) ? $user['email'] : '' ?>">
        <span class="form__error"><?= $errors['email'] ?></span>
    </div>
    <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= isset($user['password']) ? $user['password'] : '' ?>">
        <span class="form__error"><?= $errors['password'] ?></span>
    </div>
    <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= isset($user['name']) ? $user['name'] : '' ?>">
        <span class="form__error"><?= $errors['name'] ?></span>
    </div>
    <div class="form__item <?= isset($errors['contact']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contact" placeholder="Напишите как с вами связаться"><?= isset($user['contact']) ? $user['contact'] : '' ?></textarea>
        <span class="form__error"><?= $errors['contact'] ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
