  

<?php

$task_name = $_POST["name"] ?? "";
$task_date = $_POST["date"] ?? "";






?>



  <div class="modal">
    
    <a href="index.php?close"><button class="modal__close" type="button" name="button">Закрыть</button></a>
    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form"  action="index.php?add" method="post" enctype="multipart/form-data">
     
      <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['name'])) echo 'form__input--error';?>" type="text" name="name" id="name" value="<?=$task_name;?>" placeholder="Введите название">

        <?php if (isset($errors['name'])): ?> 
            <p class="form__message"><?php echo $errors["name"]?></p>
        <?php endif; ?>  
      </div>



      <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <select class="form__input form__input--select <?php if (isset($errors['project'])) echo 'form__input--error';?>" name="project" id="project">
          
        
        
          
          <?php

            array_shift($projects);
            echo '<option value=""> Выберите проект</option>';  
            
            foreach ($projects as $project) {
              print('<option value="'. $project . '"> '. $project . '</option>'); 

                    }

                     
          ?>

        </select>

        <?php if (isset($errors['project'])): ?> 
            <p class="form__message"><?php echo $errors["project"]?></p>
            <?php endif; ?>  

      </div>

      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date" type="date" name="date" id="date" value="<?=$task_date;?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
      </div>

      <div class="form__row">
        <label class="form__label" for="preview">Файл</label>

        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="preview" id="preview" value="" enctype="multipart/form-data">

          <label class="button button--transparent" for="preview">
              <span>Выберите файл</span>
          </label>
        </div>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  </div>