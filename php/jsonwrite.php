<?php
echo 'start....';
$fp = fopen($_POST['file'], 'w');
if($fp) {
    echo 'open...';
    fwrite($fp, $_POST["json"]);
    fclose($fp);
}

?>