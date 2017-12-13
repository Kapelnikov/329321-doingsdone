



<div class="modal">
    <a href="index.php"><button class="modal__close" type="button" name="button">Закрыть</button></a>

    <h2 class="modal__heading">Добавление проекта</h2>

    <form class="form"  action="/index.php?add=project" method="post">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите название">
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>