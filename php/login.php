<?php
session_start();

if ($_POST['type'] === 'results') {
    $json = file_get_contents("../json/hide/user.json");
    $arr = json_decode($json, true);
    foreach ($arr as $k => $a) {
        if (md5($_POST['password']) === $a['password'] &&  $_POST['user'] === $a['email']) {
            $_SESSION['username'] = $a['firstname'].' '.$a['lastname'];
            if ($a['access'] == 'true') {
                echo 'OK';
                $_SESSION['id'] = $k;
                if ($a['read'] == 'true') {
                    $_SESSION['read'] = true;
                }
                if ($a['level'] == 'admin') {
                    $_SESSION['admin'] = true;
                } else {
                    $_SESSION['admin'] = false;
                }
                break 1;
            } else {
                $_SESSION['id'] = false;
                echo 'denied';
            }
        }
    }
} else {
    $json = file_get_contents("../json/hide/admin.json");
    $arr = json_decode($json, true);

    if (md5($_POST['password']) === $arr[$_POST['user']]) {
        $_SESSION['admin'] = true;
        echo 'OK';
    } else {
        $_SESSION['admin'] = false;
        echo md5($_POST['password']);
    }
}

?>