<?php 
    session_start();
    include 'php/include/functions.php'; 
?> 

<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>Invite to Surveys</title>
        <meta name="description" content="Invite for surveys">
        <link rel="stylesheet" type="text/css" href="css/main.css" />   
    </head> 
    <body>
        <br><br>
        <header>
            <div class="noprint">
                <img src="img/coherent_logo_white.png" id="logo">
                <div class="title">Survey Invitations</div>
            </div>
        </header>
        <div class="container">
               
            <div class="simple-wrapper list"> 
                <h3>Survey Invitation List</h3>
                    <?= filter('survey'); ?>
                    <?= filter('productline'); ?>
                    <?= filter('region'); ?>
                <div class="inline-button" id="set">SET</div>
                <div id="resultslist"></div>
            </div>
          
        </div>
    
        <script type="text/javascript" src="js/jquery-3.1.1.js"></script>
        <script type="text/javascript" src="js/invitations.js"></script>

        <?php include 'php/include/menu.php'; ?>
        

    </body>   
</html>