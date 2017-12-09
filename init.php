<?php
$con = mysqli_connect('localhost', 'root', '', 'doingsdone');
if (!$con) {
    print(renderTemplate('templates/error.php', ['error_text' => mysqli_connect_error()]));
    exit;
};
?>