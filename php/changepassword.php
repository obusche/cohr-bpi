<?php
$userfile = '../json/hide/user.json';
$json = file_get_contents($userfile);
$users = json_decode($json, true);

$thisuser = $users[$_POST['id']];
if ($thisuser['password'] === md5($_POST['old_pw'])) {
    $users[$_POST['id']]['password'] = md5($_POST['new_pw']);
    $fp = fopen($userfile, 'w');
    if($fp) {
        if ( fwrite($fp, json_encode($users, JSON_PRETTY_PRINT)) ) {
            $response = 'Password changed';
        } else {
            $response = 'File could not be written';
        }
        fclose($fp);       
    }
} else {
    $response = 'Old password incorrect';
}

echo $response;

?>