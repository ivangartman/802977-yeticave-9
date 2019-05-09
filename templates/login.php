<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<form enctype="multipart/form-data" class="form container <?= isset($errors) ? 'form--invalid' : ''?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : ''?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"  value="<?= isset($login['email']) ? $login['email'] : ''?>">
        <span class="form__error"><?= $errors['email'] ?></span>
    </div>
    <div class="form__item form__item--last <?= isset($errors['password']) ? 'form__item--invalid' : ''?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"  value="<?= isset($login['password']) ? $login['password'] : ''?>">
        <span class="form__error"><?= $errors['password'] ?></span>
    </div>
    <button type="submit" class="button" name="submit">Войти</button>
</form>
