<?php 
session_start(); 
include 'php/include/functions.php';
?>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>New user for survey</title>
        <meta name="description" content="Create a new user for survey">
        <link rel="stylesheet" type="text/css" href="css/main.css" />   
    </head> 
    <body>
        <header>
            <div class="noprint">
                <img src="/img/coherent_logo_white.png" id="logo">
                <div class="title">Create new participant</div>
            </div>
        </header>
        <div class="container">
            <div class="wrapper">
                <ul class="steps noprint">
                    <li class="is-active clickable" value="new_user">New User</li>
                    <li class="clickable" value="change_user">Change User</li>
                </ul>
                <form class="form-wrapper" method="POST" id="form">

                    <fieldset class="section is-active  new_user" name="new_user">
                        <h3>Create new user</h3>
                        <input type="email" id="email" placeholder="E-Mail" >
                        <input type="text" id="firstname" placeholder="First Name" >
                        <input type="text" id="lastname" placeholder="Last Name" >
                        <select id="gender" >
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                        </select>
                        <?= select_simple ('survey'); ?>
                        <!-- <select id="survey" >
                            <option value="sales" selected>Sales</option>
                            <option value="service">Service</option>
                            <option value="customer">Customer</option>
                            <option value="demo">Demo</option>
                            <option value="no">No</option> 
                        </select>-->
                        <?= select_simple ('region'); ?>
                        <!-- <select id="region" >
                            <option value="northamerica" selected>North America</option>
                            <option value="europe">Europe</option>
                            <option value="asia">Asia</option>
                            <option value="other">Other</option>
                        </select> -->
                        <select id="language" >
                            <option value="en" selected>English</option>
                            <option value="de">Deutsch</option>
                        </select>
                        <?= select_simple ('productline'); ?>
                        <!-- <select id="productline" >
                            <option value="standard" selected>Standard Tools (EasyMark, Manual Welder)</option>
                            <option value="rails">Rails (e. g. PowerLine, StarFiber)</option>
                            <option value="tools">Tools (e. g. Easymark, Exact, Combiline)</option>
                            <option value="other">Other</option>
                        </select> -->
                        <select id="access" >
                            <option value="true" selected>Active</option>
                            <option value="false">Inactive</option>
                        </select>
                        <select id="level" >
                            <option value="participant" selected>Participant</option>
                            <option value="view">Viewer</option>
                            <option value="admin">Admin</option>
                        </select>
                        <select id="read" >
                            <option value="false" selected>Can't see results</option>
                            <option value="true">Has access to results</option>
                        </select>
                        <select id="action" >
                            <option value="add" selected>Add new user</option>
                            <option value="update">Overwrite user settings</option>
                        </select>
                        <textarea id="comment" rows="3" placeholder="Comment about user"></textarea>
                        <input type="password" id="newuserpassword" class="hidden" placeholder="New User Password">
                        <div class="button last">Last</div>
                        <div class="button confirm">Save</div>
                    </fieldset>

                    <!-- <fieldset class="section change_user" name="change_user">                        
                        <div class="button last">Last</div>
                    </fieldset> -->

                </form>
                <div class="sheet hidden"></div>
                <div class="messagewindow autosize" id="useradmin">
                    <div class="close-icon">&#xD7;</div>
                    <h3>User Admin</h3>
                    <div class="response"></div>                  
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-3.1.1.js"></script>
        <script type="text/javascript" src="js/user.js"></script>
        <?php include 'php/include/menu.php'; ?>
    </body>   
</html>