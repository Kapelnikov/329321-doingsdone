<!--Скрипт LiveReload-->
<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')

</script>

<?php

require_once(__DIR__.'../functions.php');
require_once(__DIR__.'../templates/data.php');









?>

<p><?=$projects[0];?></p>

<p><?=addition(1, 2);?></p>