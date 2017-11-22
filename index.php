<!--Скрипт LiveReload-->
<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')

</script>

<?php

require_once(__DIR__.'../config.php');
require_once(__DIR__.'../functions.php');
require_once(__DIR__.'../data.php');


$project_temp = null;

if (isset($_GET['project_index'])) {
    $project_id = $_GET['project_index'];

        foreach ($tasks as $item) {
        if ($item['Категория'] == $projects[$project_id]) {
            $project_temp = $item;
            break;
        }
    }

};




$page_content = renderTemplate($config['templates_path'] . 'index.php', ['projects' => $projects, 'tasks' => $tasks]);

if ($config['enable']) {
    $layout_content = renderTemplate($config['templates_path'] . 'layout.php', ['projects' => $projects, 'tasks' => $tasks, 'content' => $page_content, 'page_title' => 'Дела в порядке']);
} else {
    $layout_content = renderTemplate($config['templates_path'] . 'off.php', ['projects' => $projects, 'tasks' => $tasks, 'error_msg' => 'Сорямба гайз, сайт не работает!', 'page_title' => 'Дела в порядке']);
}

print($layout_content);

?>

