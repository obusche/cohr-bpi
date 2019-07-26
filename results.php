<?php 
session_start();
include 'php/include/functions.php'; 

$json = file_get_contents('json/hide/user.json');
$arrUser = json_decode($json, true);
$thisUser =  $arrUser[$_SESSION['id']];

?> 


<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>BPI Survey Results</title>
        <meta name="description" content="Show survey results">
        <link rel="stylesheet" type="text/css" href="css/main.css" />   
    </head> 
    <body>
    <br><br>
        <header>
            <div class="noprint">
                <img src="/img/coherent_logo_white.png" id="logo">
                <div class="title">Show survey results</div>
            </div>
        </header>
        <div class="container">
            <div class="wrapper">
                <ul class="steps noprint">
                    <li class="is-active clickable login" value="login">Menu</li>
                    <li class="clickable responses" value="responses">Responses</li>
                    <li class="clickable graph" value="graph">Graph</li>
                    <li class="clickable trend" value="trend">Trend</li>
                </ul>

                <form class="form-wrapper" method="POST" id="form">

                    <?php if ($_SESSION['id']) { ?>

                        <fieldset class="section is-active login" name="login">
                            <div class="picture-button" value="responses"><img src="/img/responses.png"></div>
                            <div class="picture-button" value="graph"><img src="/img/graph.png"></div>
                            <div class="picture-button" value="trend"><img src="/img/trend.png"></div>
                            
                            <div class="button next">Next</div>
                        </fieldset>
                    
                        <fieldset class="section responses" name="responses">
                            <div class="filter-box">                       
                                <?= select_fancy('survey'); ?>
                                <?= select_fancy('productline'); ?>
                                <?= select_fancy('region'); ?>
                                <div class="set-button">SET</div>
                            </div>
                            <div id="resultslist" class="results"></div>
                            
                            <div class="button last">Last</div>
                            <div class="button next">Next</div>
                        </fieldset>

                        <fieldset class="section graph" name="graph">
                            <div class="filter-box">                   
                                <?= select_fancy('survey'); ?>
                                <?= select_fancy('productline'); ?>
                                <?= select_fancy('region'); ?>
                                <p>
                                    <label for="from_date">From:</label>
                                    <input type="date" id="from_date" value="<?= date('Y-m-d', time() - 60 * 60 * 24 * 365 / 4); ?>">                   
                                    <label for="to_date">&nbsp;&nbsp;&nbsp;To:</label>
                                    <input type="date" id="to_date" value="<?= date('Y-m-d', time()); ?>">
                                </p>
                                <div class="set-button">SET</div>
                            </div>
                            <div id="resultslist21" class="results centered"></div>
                            <div class="separator"></div>
                            <div id="resultslist22" class="results centered"></div>
                            <div class="separator"></div>
                            <div class="button last">Last</div>
                            <div class="button next">Next</div>
                        </fieldset>

                        <fieldset class="section trend" name="trend">
                            <div class="filter-box">                   
                                <?= question_filter(array('sales', 'service')); ?>
                                <?= select_fancy('productline'); ?>
                                <?= select_fancy('region'); ?>
                                <p>
                                    <label for="from_date3">From:</label>
                                    <input type="date" id="from_date3" value="<?= date('Y-m-d', time() - 60 * 60 * 24 * 365); ?>">                   
                                    <label for="to_date3">&nbsp;&nbsp;&nbsp;To:</label>
                                    <input type="date" id="to_date3" value="<?= date('Y-m-d', time()); ?>">
                                </p>
                                <div class="set-button">SET</div>
                            </div>

                            <div id="resultslist3" class="results"></div>
                            <div class="separator"></div>
                            <div class="button last">Last</div>
                        </fieldset>               
                    
                    <?php  } ?>
                </form>
            </div>
        </div>

    <script type="text/javascript" src="js/jquery-3.1.1.js"></script>
    <script type="text/javascript" src="js/results.js"></script>

    <?php include 'php/include/menu.php'; ?>

    </body>   
</html>
