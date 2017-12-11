<?php

session_start();

require_once(__DIR__.'../config.php');
require_once(__DIR__.'../functions.php');
require_once(__DIR__.'../mysql_helper.php');
require_once(__DIR__.'../init.php');
require_once(__DIR__.'../data.php');
require_once(__DIR__.'../userdata.php');

$projects = [];
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : false;


if ($user_id and $result = mysqli_query($con, "SELECT * FROM projects WHERE user_id = $user_id")) {
    while ($row = mysqli_fetch_assoc($result)) {
        $projects [] = $row;
    }
}

$tasks = [];

if ($user_id and $result = mysqli_query($con, "SELECT * FROM tasks WHERE user_id = $user_id")) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks [] = $row;
    }
}

$project_temp = null;
$filtered_tasks = $tasks;
$modal = '';
$overlay = '';

if (isset($_GET["login"])) {
    
    $overlay = "overlay";

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = $_POST;
         
            if (isset($_POST["auth_submit"])) {
            $password = $_POST["password"];
            $email = $_POST["email"];

            if (empty($password)) {
            $errors["password"] = "Введите пароль!";


            }

           if (empty($email)) {
                $errors["email"] = "Введите email!";
                

           }
                    
        if (!empty ($password) and !empty ($email)) {
            $search = null;
            
            $query = "SELECT * FROM users WHERE email = '$email'";
            if ($result = mysqli_query($con, $query)) {
                    $row = mysqli_fetch_assoc($result);
                        if (password_verify($password, $row["password"])) {
                           $search = $row["id"];    
                        }

                        else {
                            $errors["password"] = "Вы ввели неверный пароль";
                        }
                }    
          

           if (is_null($search)) {

                if (!isset($errors["password"])) {
                    $errors["email"] = "Такого email нет!";
                }
                
           } 

           else {
                $_SESSION["user_id"] = $search;
                header("Location: /"); // относительный путь на главную
           }
        }
   }


}

echo renderTemplate($config['templates_path'] . 'auth_form.php', ['errors' => $errors, 'data' => $data]);

}

if (isset($_GET["register"])) {
    $errors = [];
    $success = "";

        if (isset($_SESSION["user_id"])) {
           $errors["auth"] = "Вы уже авторизованы";     
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = $_POST;
         
            if (isset($_POST["register_submit"])) {
            $password = $_POST["password"];
            $email = $_POST["email"];
            $name = $_POST["name"];

            if (empty($password)) {
            $errors["password"] = "Введите пароль!";


            }

           if (empty($email)) {
                $errors["email"] = "Введите email!";
                

           }

           else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                   $errors["email"] = "Неправильно набран email!";
                }
                $query = "SELECT * FROM users WHERE email = '$email'";
                    if (empty($errors) and $result = mysqli_query($con, $query)) {
                        $errors["email"] = "Данный email уже существует!";
                

                    }

                }

           if (empty($name)) {
                $errors["name"] = "Введите имя!";
                

           }
                    
        if (empty($errors)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password_hash')";
            $insert = mysqli_query($con, $query);
                if ($insert == TRUE) {
                    $success = "Вы успешно зарегистрированы!";
                }
        }
   }


}    

        $page_content = renderTemplate($config['templates_path'] . 'register.php', ["errors" => $errors, "success" => $success]);
}



if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    if (!isset($projects[$project_id])) {
        

        //послать заголовок 404
        http_response_code(404);
        print("Извините! Такой страницы нет! Ошибка 404!");  
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

if (isset ($_GET["task_completed"])) {
   $task_id = $_GET["id"];
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

if (isset ($_COOKIE ["show_completed"]) and $_COOKIE ["show_completed"] == 0)
    foreach ($filtered_tasks as $i => $task) {
        if ($task['completed_at'] != NULL) {
            unset($filtered_tasks[$i]);
        }

    }

    


if (isset($_GET['add'])) {
    $overlay = "overlay";
   
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $deadline = $_POST["date"];
    $project_id = $_POST["project"];
    $now = "NOW()";
    $query = "INSERT INTO tasks (name, deadline, project_id, user_id, created_at) VALUES ('$name', '$deadline', '$project_id', '$user_id', $now)";
            $insert = mysqli_query($con, $query);
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

 $user = FALSE;

    $query = "SELECT * FROM users WHERE id = '$user_id'";
        if ($result = mysqli_query($con, $query)) {
            $user = mysqli_fetch_assoc($result);
            }


if (!isset($page_content)) 
    $page_content = renderTemplate($config['templates_path'] . 'index.php', ['projects' => $projects, 'tasks' => $tasks, 'filtered_tasks' => $filtered_tasks]);

if ($config['enable']) {
    $layout_content = renderTemplate($config['templates_path'] . 'layout.php', ['projects' => $projects, 'tasks' => $tasks, 'content' => $page_content, 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks, 'overlay' => $overlay, 'modal' => $modal, 'user' => $user]);
} else {
    $layout_content = renderTemplate($config['templates_path'] . 'off.php', ['projects' => $projects, 'tasks' => $tasks, 'error_msg' => 'Извините, сайт не работает!', 'page_title' => 'Дела в порядке', 'filtered_tasks' => $filtered_tasks, 'overlay' => $overlay, 'modal' => $modal]);



}

print($layout_content);

?>



<!--Скрипт LiveReload-->
    <script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>