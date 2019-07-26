<?php
echo '<h2>Invitations sent</h2>';
$url = 'http://www.busche.eu.com/bpi/index.php';
$json = file_get_contents('../json/hide/user.json');
$allusers = json_decode($json, true);

$language = file_get_contents('../json/language.json');
$lng = json_decode($language, true);


foreach ($_POST['users'] as $id) {
    $user = $allusers[$id];
    if ($user['level'] === 'participant') {
        $l = $user['language'];
        //echo '<p>'.$id.' => '.$user['email'].'</p>';
        $link = '<a href="'.$url.'?id='.$id.'">Link to survey</a>';
        //echo '<p>'.$link.'</p>';

        $address = $user['email'];
        $head =     "ILS Global Tools Customer Satisfaction Survey\r\n";
        $headers =  "From: Global Tools Business Partner Survey <oliver.busche@coherent.com>\r\n";
        $headers .= "Reply-To: Oliver Busche <oliver.busche@coherent.com>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        /*
         * Handle the formal or informal addressing of the participant
         */
        if ($user['survey'] === 'customer') {
            $salutation = 'formal';
            $name = $user['lastname'];
        } else {
            $salutation = 'informal';
            $name = $user['firstname'];
        }


        $text =     '<p>'.$lng['salutation-'.$salutation][$user['gender']][$l].' '.$name.'</p>';
        $text .=    '<p>'.$lng['participate1'][$user['survey']][$l].'</p>';
        $text .=    '<p>'.$link.'</p>';
        $text .=    '<p>'.$lng['participate2'][$user['survey']][$l].'</p>';
        $text .=    '<p>'.$lng['participate3'][$user['survey']][$l].'</p>';
        echo $address;
        echo $text;
        echo '<hr>';
        if (mail($address, $head, $text, $headers)) {
            echo "<p>Email sent to ".$address.'</p>';
        } else {
            echo "Email not sent to ".$address;
        }
    }
}

?>