<?php

require_once('mysql_helper.php');
require_once('init.php');



/**
 * функция подсчета числа задач из массива
 *
 * @param $tasks array массив задач
 * @param $project_id int айди проекта
 *
 * @return $count int число задач в проекте
 */

function count_tasks($tasks, $project_id) {
    if ($project_id == 0) {
        return count($tasks);
    }

    $count = 0;
    foreach ($tasks as $task) {
        if ($task['project_id'] == $project_id) {
            $count++; // Прибавляем 1
        }
    }

    return $count;
}




/**
 * функция шаблонизации
 *
 * @param $path string путь до шаблона
 * @param $page_name string имя страницы
 *
 * @return $html string результат обработки шаблона
 */

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

/**
 * функция авторизации
 *
 * @param $con mysqli ресурс соединения
 *
 * @return mixed
 */

function auth($con) {
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);

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
        
        } else {
            $_SESSION["user_id"] = $search;
            header("Location: /"); // относительный путь на главную
        }
    }
}

/**
 * функция регистрации
 *
 * @param $con mysqli ресурс соединения
 *
 * @return mixed
 */

function register($con) {
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);

    if (empty($password)) {
        $errors["password"] = "Введите пароль!";
    }

    if (empty($email)) {
        $errors["email"] = "Введите email!";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $errors["email"] = "Неправильно набран email!";
        }
        $query = "SELECT * FROM users WHERE email = '$email'";
        if (empty($errors) and $result = mysqli_query($con, $query)) {
            if ($exist = mysqli_fetch_assoc($result)) {
                $errors["email"] = "Данный email уже существует!";
            }
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

/**
 * функция сортировки задач по датам
 *
 * @param $con mysqli ресурс соединения
 * @param $filtered_tasks array отфильтрованные задачи
 *
 * @return filtered_tasks array отсортированный массив задач по определенной дате
 */

function taskSwitcher($con, $filtered_tasks) {
    if ($_GET['task_switch'] == 'today') {
        $sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user_id'] . " AND deadline = CURDATE()";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $filtered_tasks = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        }
    }
    if ($_GET['task_switch'] == 'tomorrow') {
        $sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user_id'] . " AND deadline = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $filtered_tasks = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        }
    }
    if ($_GET['task_switch'] == 'overdue') {
        $sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user_id'] . " AND completed_at IS NULL AND deadline < CURDATE()";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $filtered_tasks = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        }
    }
    return $filtered_tasks;
}