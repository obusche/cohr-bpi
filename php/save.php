<?php

$values = $_POST['form'];
$id = $values['id'];

/*
 *  If a user wants anonymity:
 *  Check if the user has done a survey this month
 *  and if not exchange the id by a random one and set the survey date
 *  in the user settings to avoid that the user does another survey this month
 */ 
if ($values['anonymous'] == 'on') {
    $userFile = '../json/hide/user.json';
    $json = file_get_contents($userFile);
    $arr = json_decode($json, true);
    $arr[$id]['comment'] = '';
    if ($arr[$id]['last_response'] == date('Y-m', time())) {
        $return['code'] = 'failure';
        $return['message'][] = "We have already received one survey from you this month. Because you have sent this survey anonymous, we cannot track it and cannot offer to change your input.";
    } else {       
        $new_id = '*'.md5(time());
        $return['new_id'] = $new_id;
        $arr[$id]['last_response'] = date('Y-m', time());        
        $return[] = jsonWrite ($userFile, $arr, 'user');
        $values['id'] = $new_id;
    }
    
}
$file = '../responses/'.date("Y-m", time()).'-'.$values['id'].'.json';
$return[] = jsonWrite ($file, $values, 'survey');
echo json_encode($return, JSON_PRETTY_PRINT);


function jsonWrite ($file, $arr, $code) {
    $json = json_encode($arr, JSON_PRETTY_PRINT);
    $fp = fopen($file, 'w');
    if($fp) {
        if (fwrite($fp, $json)) {
            $result['code'] = 'success';
            $result['message'] = $code.' data saved successfully';
        } else {
            $result['code'] = 'failure';
            $result['message'] = $code.' data could not be saved';
        }
    } else {
        $result['code'] = 'failure';
        $result['message'] = $code.' file could not be opened';
    }
    return $result;
}

?>