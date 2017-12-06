<?php

session_start();

unset($_SESSION["user_id"]);
header("Location: /"); // относительный путь на главную



?>