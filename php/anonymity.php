<?php
/*
 *  This function checks if the user has already sent one anonymous survey this month
 */

$id = $_POST['id'];
$userFile = '../json/hide/user.json';
$json = file_get_contents($userFile);
$arr = json_decode($json, true);

if ($arr[$id]['last_response'] == date('Y-m', time())) {
    echo 'false';
} else {       
    echo 'true';
}

?>