 

                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.html" method="post">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/?task_switch=all" class="tasks-switch__item <?php if ($_GET["task_switch"]=='all' || !isset($_GET["task_switch"])):?> tasks-switch__item--active <?php endif; ?>">Все задачи</a>
                        <a href="/?task_switch=today" class="tasks-switch__item <?php if ($_GET["task_switch"]=='today'):?> tasks-switch__item--active <?php endif; ?>">Повестка дня</a>
                        <a href="/?task_switch=tomorrow" class="tasks-switch__item <?php if ($_GET["task_switch"]=='tomorrow'):?> tasks-switch__item--active <?php endif; ?>">Завтра</a>
                        <a href="/?task_switch=overdue" class="tasks-switch__item <?php if ($_GET["task_switch"]=='overdue'):?> tasks-switch__item--active <?php endif; ?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">

                        <?php 
                            
                            if (isset($_COOKIE ["show_completed"]) and $_COOKIE ["show_completed"] == 1)

                            { $show_completed = 0; 

                            }
                            
                            else {
                                $show_completed = 1;
                            }
                        ?>

                        <a href="index.php?show_completed=<?php echo $show_completed ?>">
                           

                            <input  id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox" <?=($show_completed == 0) ? 'checked' : '' ?>> 
                            <span class="checkbox__text">Показывать выполненные</span>
                        </a>
                    </label>
                </div>
                    
                    <table class="tasks">
                     <?php foreach ($filtered_tasks as $key => $value): ?> 
                            <tr class="tasks__item task <?=($value['completed_at'] != NULL) ? 'task--completed' : '' ?>">
                            <td class="task__select">
                            <label class="checkbox task__checkbox">
                            <input onclick="return location.href='/?task_completed=true&id=<?php echo $value["id"]?>'" class="checkbox__input visually-hidden" type="checkbox" <?=($value['completed_at'] != NULL) ? 'checked' : '' ?>>
                            <span class="checkbox__text"><?=htmlspecialchars($value['name']); ?></span>
                            </label>
                            </td>
                            <td class="task__date"><?=htmlspecialchars($value['deadline']); ?></td>
                            <td class="task__controls">
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
    
  