
<?php

$email = $data["email"] ?? "";
$password = $data["email"] ?? "";






?>

<div class="modal">
    <a href="/"><button class="modal__close" type="button" name="button">Закрыть</button></a>

    <h2 class="modal__heading">Вход на сайт</h2>

       

    <form class="form" action="/index.php?login" method="post">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['email'])) echo 'form__input--error';?>" type="text" name="email" id="email" value="<?php echo $email ?>" placeholder="Введите e-mail">

        <?php if (isset($errors['email'])): ?> 
            <p class="form__message"><?php echo $errors["email"]?></p>
        <?php endif; ?>  
      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль<sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['password'])) echo 'form__input--error';?>" type="password" name="password" id="password" value="<?php echo $password ?>" placeholder="Введите пароль">
      
        <?php if (isset($errors['password'])): ?> 
            <p class="form__message"><?php echo $errors["password"]?></p>
        <?php endif; ?> 

      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="auth_submit" value="Войти">
      </div>
    </form>
  </div>