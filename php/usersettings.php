<?php
$userfile = '../json/hide/user.json';
$json = file_get_contents($userfile);
$users = json_decode($json, true);

$thisuser = $users[$_POST['id']];

if ($thisuser['password'] === md5($_POST['password'])) {
    /*
     *  Set the new values
     */
    $users[$_POST['id']]['firstname'] = $_POST['firstname'];
    $users[$_POST['id']]['lastname'] = $_POST['lastname'];
    $users[$_POST['id']]['email'] = $_POST['email'];
    /*
     *  Write into user file
     */
    $fp = fopen($userfile, 'w');
    if($fp) {
        if ( fwrite($fp, json_encode($users, JSON_PRETTY_PRINT)) ) {
            $response = 'User settings changed';
        } else {
            $response = 'User settings could not be changed';
        }
        fclose($fp);       
    }
} else {
    $response = 'Password incorrect';
}

echo $response;

?>