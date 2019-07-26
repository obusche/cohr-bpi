<?php 
include '../php/include/functions.php'; 

$json = file_get_contents('../json/hide/user.json');
$arr = json_decode($json, true);

$json = file_get_contents('../json/param.json');
$param = json_decode($json, true);
$url = $param['url'];


foreach ($arr as $k => $user) {
    if ( in_array($user['survey'], $_POST['filter']) && in_array($user['productline'], $_POST['filter']) && in_array($user['region'], $_POST['filter']) && $user['level'] === 'participant') {
        $hits[$k] = $user;
        $hits[$k]['mailto'] = mailto ($user['email'], 'en', $k, $user['firstname'], $user['survey'], $url);
    }
}

echo json_encode($hits, JSON_PRETTY_PRINT);

?>