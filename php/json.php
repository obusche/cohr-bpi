<?php

if ($_POST['action'] == 'read' || $_GET['action'] == 'read') {
    
    $json = file_get_contents($_POST['file']);
    //$json = file_get_contents('../se-jobs/SE-1.json');
    echo $json;

} else if ($_POST['action'] === 'update-user') {
    
    $json = file_get_contents($_POST['file']);
    $arr = json_decode($json, true);
    $arr[$_POST['email']]['name'] = $_POST['name'];
    $arr[$_POST['email']]['access'] = $_POST['access'];
    $arr[$_POST['email']]['key'] = $_POST['key'];
    $arr[$_POST['email']]['level'] = $_POST['level'];
    $arr[$_POST['email']]['level'] = $_POST['level'];

    $fp = fopen($_POST['file'], 'w');
    if($fp) {
        fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
        fclose($fp);
    }
    echo json_encode($arr);

} else if ($_POST['action'] == 'confirm-se') {
    
    $json = file_get_contents($_POST['file']);
    if ($json) {
        $arr = json_decode($json, true);
        if ($arr['email'] == $_POST['email'] && $arr['se-number'] == $_POST['senumber']) {
            echo $json;
        } else {
            echo '0';
        }
    } else {
        //Do nothing
    }
        
}

?>