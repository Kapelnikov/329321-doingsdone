<?php

require_once(__DIR__.'../config.php');
require_once(__DIR__.'../functions.php');
require_once(__DIR__.'../data.php');

$project_temp = null;
$filtered_tasks = $tasks;

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    if (!isset($projects[$project_id])) {
        //послать заголовок 404
        http_response_code(404);
        print("Сорямба, гайз! Такой страницы нет! Ошибка 404!");  
        die();
    } 
    else {
        
        foreach ($tasks as $i => $task) {
        if ($task['Категория'] != $projects[$project_id]) {
            unset($filtered_tasks[$i]);
        }
    }
    }

}


$page_content = renderTemplate($config['templates_path'] . 'index.php', ['projects' => $projects, 'tasks' => $tasks, 'filtered_tasks' => $filtered_tasks]);

if ($config['enable']) {
    $layout_content = renderTemplate($config['templates_path'] . 'layout.php', ['projects' => $projects, 'tasks' => $tasks, 'content' => $page_content, 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks]);
} else {
    $layout_content = renderTemplate($config['templates_path'] . 'off.php', ['projects' => $projects, 'tasks' => $tasks, 'error_msg' => 'Сорямба гайз, сайт не работает!', 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks]);
}

print($layout_content);

?>
