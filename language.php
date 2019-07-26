<?php include 'php/include/functions.php'; ?> 

<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>BPI Survey Results</title>
        <meta name="description" content="Show survey results">
        <link rel="stylesheet" type="text/css" href="css/main.css" />   
    </head> 
    <body>
    <div id="editmode" class="mini"></div>
        <header>
            <div class="noprint">
                <img src="/img/coherent_logo_white.png" id="logo">
                <div class="title">Show survey results</div>
            </div>
        </header>
        <div class="container">
            <div class="wrapper">
                <ul class="steps noprint">
                    <li class="is-active">Login</li>
                    <li>Select</li>
                    <li>Language</li>
                    <li>Test</li>
                </ul>
                <form class="form-wrapper" method="POST" id="form">
                    <fieldset class="section is-active" name="login">
                        <h3>Please, login to change or create new language</h3>
                        <p id="failure" class="message invisible">Login failed</p>
                        <input type="text" id="myemail" placeholder="E-Mail" value="<?= $_GET['e'] ?>">
                        <input type="password" id="password" placeholder="Password" >
                        <div class="button next login">Login</div>
                    </fieldset>

                    <fieldset class="section select" name="select">
                        <h3>Select language</h3>
                        <?php echo language_selector (); ?>
                        <!-- <div class="set-button" id="set">SET</div> -->
                        <div id="language_form" class="results"></div>
                        <div class="button last">Last</div>
                        <div class="button next save">Save</div>
                    </fieldset>

                    <fieldset class="section graph" name="result">                        
                        <div class="separator"></div>
                        <div class="button last">Last</div>
                        <!-- <div class="button next">Next</div> -->
                    </fieldset>

                    
                </form>
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-3.1.1.js"></script>
        <script type="text/javascript" src="js/language.js"></script>
    </body>   
</html>
