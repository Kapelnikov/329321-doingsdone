 

<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

$days = rand(-3, 3);
$task_deadline_ts = strtotime("+" . $days . " day midnight"); // метка времени даты выполнения задачи
$current_ts = strtotime('now midnight'); // текущая метка времени

// запишите сюда дату выполнения задачи в формате дд.мм.гггг
$date_deadline = date ('d.m.Y',$task_deadline_ts);

// в эту переменную запишите кол-во дней до даты задачи
$days_until_deadline = ($task_deadline_ts - $current_ts) / 86400;

?>



                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.html" method="post">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <a href="/">
                            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
                            
                            <input  id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox" <?=($show_complete_tasks == 1) ? 'checked' : '' ?>> 
                            <span class="checkbox__text">Показывать выполненные</span>
                        </a>
                    </label>
                </div>
                    
                    <table class="tasks">
                     <?php foreach ($tasks as $key => $value): ?> 
                            <tr class="tasks__item task <?=($value['Выполнен'] == 'Да') ? 'task--completed' : '' ?>">
                            <td class="task__select">
                            <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox" checked>
                            <span class="checkbox__text"><?=$value['Задача']; ?></span>
                            </label>
                            </td>
                            <td class="task__date"><?=$value['Дата выполнения']; ?></td>
                            <td class="task__controls">
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
    
  