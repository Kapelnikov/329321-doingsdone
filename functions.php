<?php




// функция подсчета числа задач из массива
function count_tasks($tasks, $project_name) {
 if ($project_name == 'Все') {
  return count($tasks);
 }

 $count = 0;
 foreach ($tasks as $task) {
  if ($task['Категория'] == $project_name) {
   $count++; // Прибавляем 1
  }
 }

 return $count;
}

// функция шаблонизации
function renderTemplate($path, $page_name) {
    if (!file_exists($path and $page_name)) {
        ob_start();
        extract($page_name);
        include($path);
        return $html = ob_get_clean();
    } else {
        return $html = "";
    }
}



