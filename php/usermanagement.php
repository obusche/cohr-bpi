<?php

/*
 *  This page creates or modifies users
 */

$fileName = '../json/hide/user.json';
$json = file_get_contents($fileName);
$arr = json_decode($json, true);

/*
    * In case a dataset should be updated, the existing
    * ID is searched for in the user.json
    */ 
if ($_POST['action'] === 'update') {
    foreach ($arr as $k => $user) {
        //echo '<p>'.$id.' => '.$user['email'].'</p>';
        if ($_POST['email'] === $user['email']) {
            $id = $k;
            break 1;
        }
    }
}
/*
* Generate a new ID
*/
if (!$id) {
    $id = md5(rand());
}

$arr[$id]['email'] = $_POST['email'];
$arr[$id]['firstname'] = $_POST['firstname'];
$arr[$id]['lastname'] = $_POST['lastname'];
$arr[$id]['access'] = $_POST['access'];
$arr[$id]['level'] = $_POST['level'];
$arr[$id]['survey'] = $_POST['survey'];
$arr[$id]['region'] = $_POST['region'];
$arr[$id]['productline'] = $_POST['productline'];
$arr[$id]['language'] = $_POST['language'];
$arr[$id]['read'] = $_POST['read'];
$arr[$id]['comment'] = $_POST['comment'];
$arr[$id]['password'] = md5($_POST['newuserpassword']);
$arr[$id]['createdby'] = $_POST['user'];
$arr[$id]['lastchanged'] = date('Y-m-d H:s', time());

$fp = fopen($fileName, 'w');
if($fp) {
    fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
    fclose($fp);
}

if ($_POST['level'] === 'admin') {
    $fileName = '../json/hide/admin.json';
    $json = file_get_contents($fileName);
    $arr = json_decode($json, true);
    $arr[$_POST['email']] = md5($_POST['newuserpassword']);
    $fp = fopen($fileName, 'w');
    if($fp) {
        fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
        fclose($fp);
    }
}

echo $id;

?>