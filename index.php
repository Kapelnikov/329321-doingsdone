<?php

session_start();

require_once(__DIR__.'/config.php');
require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/mysql_helper.php');
require_once(__DIR__.'/init.php');


$projects = [];
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : false;

$user = FALSE;

$query = "SELECT * FROM users WHERE id = '$user_id'";
if ($result = mysqli_query($con, $query)) {
    $user = mysqli_fetch_assoc($result);
}

if ($user_id and $result = mysqli_query($con, "SELECT * FROM projects WHERE user_id = $user_id")) {
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[$row['id']] = $row;
    }
}

$tasks = [];

if ($user_id and $result = mysqli_query($con, "SELECT * FROM tasks WHERE user_id = $user_id")) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[$row['id']] = $row;
    }
}

$project_temp = null;
$filtered_tasks = $tasks;
$modal = '';
$overlay = '';

// Вход
if (isset($_GET["login"])) {
    
    $overlay = "overlay";

    $errors = [];

    $data = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = $_POST;
         
        if (isset($_POST["auth_submit"])) {
            auth($con);
        }
    }

    echo renderTemplate($config['templates_path'] . 'auth_form.php', ['errors' => $errors, 'data' => $data]);

}

// Регистрация
if (isset($_GET["register"])) {
    $errors = [];
    $success = "";

    if (isset($_SESSION["user_id"])) {
       $errors["auth"] = "Вы уже авторизованы";     
    }
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = $_POST;
         
        if (isset($_POST["register_submit"])) {
            register($con);
        }
    }    

    $page_content = renderTemplate($config['templates_path'] . 'register.php', ["errors" => $errors, "success" => $success]);
}

// Переключатель задач по времени
if (isset($_GET['task_switch'])) { 
    $filtered_tasks = taskSwitcher($con, $filtered_tasks);
} 

// Задачи только выбранного проекта
if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    if (!isset($projects[$project_id])) {
        //послать заголовок 404
        http_response_code(404);
        print("Извините! Такой страницы нет! Ошибка 404!");  
        die();
    } else {
        foreach ($tasks as $i => $task) {
            if ($task['project_id'] != $project_id) {
                unset($filtered_tasks[$i]);
            }
        }
    }
}

// Показывать выполненные
if (isset($_GET["show_completed"])) {
    setcookie("show_completed", $_GET["show_completed"]);
    header("Location: /"); // относительный путь на главную
}

// Чекбокс отметить задачу выполненой
if (isset($_GET["task_completed"])) {
   $task_id = (int)$_GET["id"];
   $query = "SELECT * FROM tasks WHERE id = '$task_id'";
    if ($result = mysqli_query($con, $query)) {
        $task = mysqli_fetch_assoc($result);

        $completed_at = "NULL";
        if ($task["completed_at"] == NULL) {
            $completed_at = "NOW()";
        }

        $query = "UPDATE tasks SET completed_at = $completed_at WHERE id = '$task_id'";
        $insert = mysqli_query($con, $query);

    }
    header("Location: /"); // относительный путь на главную
}

// Чекбокс показывать выполненные
if (isset ($_COOKIE ["show_completed"]) and $_COOKIE ["show_completed"] == 0) {
    foreach ($filtered_tasks as $i => $task) {
        if ($task['completed_at'] != NULL) {
            unset($filtered_tasks[$i]);
        }
    }
}

// Добавление задачи и проекта
if (isset($_GET['add'])) {
    
    $overlay = "overlay";
    $errors = [];   

    if ($_GET["add"] == "project") {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = mysqli_real_escape_string($_POST["name"]);
            
            if (empty($name)) {
                $errors["name"] = "Это поле нужно заполнить";
            }
        
            if (!empty($errors)) {
                $page_content = renderTemplate($config['templates_path'] . 'modal.php', ['projects' => $projects, 'errors' => $errors, 'data' => $data]);
                echo $page_content;
            } else {
                $overlay = "";
            }

            $query = "INSERT INTO projects (name, user_id) VALUES ('$name', '$user_id')";
            $insert = mysqli_query($con, $query);

            if (!empty($errors)) {
                $page_content = renderTemplate($config['templates_path'] . 'project_add.php', ['errors' => $errors]);
                echo $page_content;
            } else {
                $overlay = "";
            }

        } else {
            $modal = require_once(__DIR__.'../templates/project_add.php');
        }

    } else {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = mysqli_real_escape_string($_POST["name"]);
            $deadline = mysqli_real_escape_string($_POST["date"]);
            $project_id = mysqli_real_escape_string($_POST["project"]);
            $now = "NOW()";

            $required = ['name', 'date', 'project'];

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

            $query = "INSERT INTO tasks (name, deadline, project_id, user_id, created_at) VALUES ('$name', '$deadline', '$project_id', '$user_id', $now)";
            $insert = mysqli_query($con, $query);
            if ($insert) {
                header('Location: /');
            }

        } else {
            $modal = require_once(__DIR__.'../templates/modal.php');
        }

    }

}

if (!isset($page_content)) 
    $page_content = renderTemplate($config['templates_path'] . 'index.php', ['projects' => $projects, 'tasks' => $tasks, 'filtered_tasks' => $filtered_tasks]);

if (!isset($_SESSION['user_id']) and $_SERVER['REQUEST_URI'] != '/index.php?register' and $_SERVER['REQUEST_URI'] != '/index.php?login') {
    $page_content = renderTemplate($config['templates_path'] . 'guest.php', ['projects' => $projects, 'tasks' => $tasks, 'filtered_tasks' => $filtered_tasks]);
}

if ($config['enable']) {
    $layout_content = renderTemplate($config['templates_path'] . 'layout.php', ['projects' => $projects, 'tasks' => $tasks, 'content' => $page_content, 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks, 'overlay' => $overlay, 'modal' => $modal, 'user' => $user]);
} else {
    $layout_content = renderTemplate($config['templates_path'] . 'off.php', ['projects' => $projects, 'tasks' => $tasks, 'error_msg' => 'Извините, сайт не работает!', 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks, 'overlay' => $overlay, 'modal' => $modal]);



}

print($layout_content);

?>



