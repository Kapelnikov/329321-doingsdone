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

if (isset ($_GET["show_completed"])) {
    setcookie("show_completed", $_GET["show_completed"]);
    header("Location: /"); // относительный путь на главную
}

if (isset ($_COOKIE ["show_completed"]) and $_COOKIE ["show_completed"] == 0)
    foreach ($filtered_tasks as $i => $task) {
        if ($task['Выполнен'] == 'Да') {
            unset($filtered_tasks[$i]);
        }

    }

$modal = '';
$overlay = '';
if (isset($_GET['add'])) {
    $overlay = "overlay";
   
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_tasks = [
            "Задача" => $_POST["name"],
            "Дата выполнения" => $_POST["date"],
            "Категория" => $_POST["project"],
            ];
    array_unshift($tasks, $new_tasks);
    array_unshift($filtered_tasks, $new_tasks);
}
   
$errors = [];   
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_posts = $_POST;

    $required = ['name', 'project'];
    $dict = ['name' => 'Задача', 'project' => 'Проект'];
    $errors = [];
    $data = $_POST;

    foreach ($_POST as $key => $value) {
        if (in_array($key, $required)) {
            if (!$value or empty($value)) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

    }

    if (!empty($_FILES) and isset ($_FILES["preview"])) {


        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = $_FILES['preview']['name'];
        

        move_uploaded_file($tmp_name, './uploads/' . $path);
    }

if (!empty($errors)) {
        $page_content = renderTemplate($config['templates_path'] . 'modal.php', ['projects' => $projects, 'errors' => $errors, 'data' => $data]);
        echo $page_content;
    }

else {$overlay = "";}

}

else {

    $modal = require_once(__DIR__.'../templates/modal.php');
}



   

}

if (isset($_GET['close'])) {
    !$modal;
    }




$page_content = renderTemplate($config['templates_path'] . 'index.php', ['projects' => $projects, 'tasks' => $tasks, 'filtered_tasks' => $filtered_tasks]);

if ($config['enable']) {
    $layout_content = renderTemplate($config['templates_path'] . 'layout.php', ['projects' => $projects, 'tasks' => $tasks, 'content' => $page_content, 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks, 'overlay' => $overlay, 'modal' => $modal]);
} else {
    $layout_content = renderTemplate($config['templates_path'] . 'off.php', ['projects' => $projects, 'tasks' => $tasks, 'error_msg' => 'Сорямба гайз, сайт не работает!', 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks, 'overlay' => $overlay, 'modal' => $modal]);



}

print($layout_content);

?>



<!--Скрипт LiveReload-->
    <script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>