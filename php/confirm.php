<?php
//setcookie('edit', 'oliver.busche@thermofisher.com**311261' , time()-(3600*24*365), "/");
//setcookie('edit', 'oliver.busche@thermofisher.com**311261' , time()-(3600*24*365));
//setcookie('edit', 'oliver.busche@thermofisher.com**311261' , time()+(3600*24*365), "/");
//print_r($_COOKIE); 

$json = file_get_contents("../json/hide/user.json");
$arr = json_decode($json, true);
$separator = '**';

/*
 * Return the Username indicating that the user has edit rights
 */
if ($_POST['action'] === 'edit-email'){
    /*
     * Confirms that the user is set up and active and 
     * send the email with the key
     */
    $userArr = $arr[$_POST['data']];
    if ($userArr['access'] === 'true') {
        $address = $userArr['email'];
        $head = "Your key: ".$userArr['key'];
        $from = "From: Oliver Busche <oliver.busche@coherent.com>";
        $text = "Please use the key  to enable editing on www.rma-mc.com/edit.php";

        if (mail($address, $head, $text, $from)) {
            echo 1;
        } else {
            //Return this when user could not be confirmed
        }
    }
} else if ($_POST['action'] === 'edit-key' || $_GET['action'] === 'edit-key'){ 
    /*
     * Confirms that the email and key sent are correct aand that the user is active a
     * Set the cookie and return true if successful
     */
    $userArr = $arr[$_POST['id']];

    if ($_POST['key'] == $userArr['key']) {
        $content = $_POST['data'].$separator.$userArr['key'];
        $result = setcookie('edit', $content, time()+(3600*24*365), "/");
        echo $result;
    } else {
        //Return nothing when key could not be confirmed
    }
} else if ($_POST['action'] === 'admin'){
    $post = explode($separator, $_COOKIE['edit']);
    $userArr = $arr[$post[0]];
    //print_r($userArr);
    if ($userArr['level'] == 'admin') {  
        echo $userArr['name'].' '.$userArr['level'];
    } 
}    

?>