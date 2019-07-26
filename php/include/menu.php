<?php 
    if ($_SESSION['id']) {
        echo '<script> let logged_in = true; </script>';
    } else {
        echo '<script> let logged_in = false; </script>';
    } 

    /* 
     * Get user data and prepare page
     */    

    $json = file_get_contents('json/hide/user.json');
    $arrUser = json_decode($json, true);
    $thisUser =  $arrUser[$_SESSION['id']];


    /*
     *  Create a hamburger menu on the top left corner for all users
     *  who have access rights.
     */
    echo '
        <div class="menu">
            <span class="hamburger">&#9776;</span>          
            <span class="hamburger hidden">&#xD7;</span>
            <div class="dropdown hidden">
        ';
    if ($_SESSION['id']) { 
        echo '<div class="item name">'. $_SESSION['username'].'</div>';
        echo '<a href="index.php?id='.$_SESSION['id'].'"><div class="item login">Back to Survey</div></a>';
        echo '<a href="#"><div class="item usersettings">User Settings</div></a>';
        echo '<a href="#"><div class="item changepassword">Change Password</div></a>';
    } else {
        echo '<a href="#"><div class="item login">Login</div></a>';   
    }
    if ($_SESSION['admin']) {  
        echo '<a href="user.php"><div class="item login">User Admin</div></a>';
        echo '<a href="results.php"><div class="item login">Results</div></a>';
        echo '<a href="invitations.php"><div class="item login">Send Surveys</div></a>';
        echo '<a href="language.php"><div class="item login">Change Language File</div></a>';
    }
    if ($_SESSION['id']) { 
        echo '<a href="#"><div class="item logout">Logout</div></a>';
    }
    echo '
        </div>
    </div>
    ';
?>

<div class="messagewindow" id="changepassword">
    <div class="close-icon">&#xD7;</div>
    <h3>Change Password</h3>
    <div class="response"></div>
    <input type="password" id="old_pw" placeholder="Old Password" >
    <input type="password" id="new_pw1" placeholder="New Password" >
    <input type="password" id="new_pw2" placeholder="Repeat Password" >
    <div class="set-button">Save</div>
</div>

<div class="messagewindow" id="usersettings">
    <div class="close-icon">&#xD7;</div>
    <h3>User Settings</h3>
    <div class="response"></div>
    <input type="text" id="us_firstname" placeholder="First Name" value="<?= $thisUser['firstname'] ?>">
    <input type="text" id="us_lastname" placeholder="Last Name" value="<?= $thisUser['lastname'] ?>">
    <input type="text" id="us_email" placeholder="E-Mail" value="<?= $thisUser['email'] ?>">
    <input type="password" id="us_pw" placeholder="Type in Password" >
    <div class="set-button">Save</div>
</div>

<div class="messagewindow auto-open" id="login">
    <div class="close-icon">&#xD7;</div>
    <h3>Login</h3>
    <div class="response"></div>
    <input type="text" id="em" placeholder="User E-Mail" >
    <input type="password" id="pw" placeholder="Password" >
    <div class="set-button">Login</div>
</div>

<div class="messagewindow autosize" id="login">
    <div class="close-icon">&#xD7;</div>
    <h3>Login failed</h3>
    <div class="response"></div>
</div>

<script type="text/javascript" src="js/menu.js"></script>

