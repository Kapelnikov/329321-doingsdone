
<?php

if (!empty($success)) {
	echo $success;
}

if (isset($errors["auth"])) {
	echo $errors["auth"];

}

else {

?>


 <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="/?register" method="post">
            <div class="form__row">
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input <?php if (isset($errors['email'])) echo 'form__input--error';?>" type="text" name="email" id="email" value="" placeholder="Введите e-mail">

              <?php if (isset($errors['email'])): ?> 
            		<p class="form__message"><?php echo $errors["email"]?></p>
       		 <?php endif; ?> 

             
            </div>

            <div class="form__row">
              <label class="form__label" for="password">Пароль <sup>*</sup></label>

              <input class="form__input <?php if (isset($errors['password'])) echo 'form__input--error';?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">

              	<?php if (isset($errors['password'])): ?> 
            		<p class="form__message"><?php echo $errors["password"]?></p>
        		<?php endif; ?> 

            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>

              <input class="form__input <?php if (isset($errors['name'])) echo 'form__input--error';?>" type="text" name="name" id="name" value="" placeholder="Введите имя">

              	<?php if (isset($errors['name'])): ?> 
           			<p class="form__message"><?php echo $errors["name"]?></p>
        		<?php endif; ?> 
            </div>

            <div class="form__row form__row--controls">
              



              <input class="button" type="submit" name="register_submit" value="Зарегистрироваться">
            </div>
          </form>

          <?php 

          	}
          ?>