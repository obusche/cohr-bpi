<?php
$address = $_POST['address'];
$head = "Your key: ".$_POST['key'];
$from = "From: rma-mc.com <no_reply@rma-mc.com>";
$text = "Please use the key  to enable editing on www.rma-mc.com/edit.php";

if (mail($address, $head, $text, $from)) {
    echo "Email sent to ".$address;
} else {
    echo "Email not sent to ".$address;
}

?>