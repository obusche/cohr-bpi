<?php 
session_start();

    /* 
     * Get user data and prepare page
     */    
    if (!$userID = $_GET['id']) {
        $userID = $_SESSION['id'];
    }

    $json = file_get_contents('json/hide/user.json');
    $arrUser = json_decode($json, true);
    $thisUser =  $arrUser[$userID];

    
?>
<!DOCTYPE html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>Tools Business Partner Survey</title>
        <meta name="description" content="Business Partner Index">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head> 
    
    <body>
    <?php if($thisUser['access'] == 'true') { ?>
        <div class="noprint">
            <div class="language-selector">
                <div class="flag german">de</div>
                <div class="flag english">en</div>
            </div>
        </div>
        <header>
            <div class="noprint">
                <img src="img/coherent_logo_white.png" id="logo">
                <div class="title language" code="title"></div>
            </div>
        </header>

        <div class="container">
            <div class="wrapper">
                <ul class="steps noprint">
                    <li class="is-active language" code="survey"></li>
                    <?php
                        foreach (categories($thisUser['survey'], $thisUser['language']) as $c) {
                            echo '<li fieldset="'.$c.'" class="language" code="'.$c.'"></li>';
                        }
                    ?>
                    <li class="language" code="comments"></li>
                    <li class="language" code="final"></li>
                </ul>

                <form class="form-wrapper" method="POST" id="cs-form">          

                    <fieldset class="section is-active" name="survey">
                            
                        <input type="hidden" name="form[id]" id="input_id" value="<?=$userID ?>">
                        <input type="hidden" name="form[firstname]" id="input_fname" value="<?= $thisUser['firstname'] ?>">
                        <input type="hidden" name="form[lastname]" id="input_lname" value="<?= $thisUser['lastname'] ?>">
                        <input type="hidden" name="form[email]" id="input_email" value="<?= $thisUser['email'] ?>">
                        <input type="hidden" name="form[survey]" value="<?= $thisUser['survey']; ?>">
                        <input type="hidden" name="form[date]" value="<?= date("Y-m-d", time()); ?>">
                        <input type="hidden" name="form[region]" value="<?= $thisUser['region'] ?>">
                        <input type="hidden" name="form[productline]" value="<?= $thisUser['productline'] ?>">
                        <input type="hidden" name="form[userdescription]" value="<?= $thisUser['comment'] ?>">

                        <h3 class="language" code="welcome"></h3>
                        <p class="language" code="anonymous" ></p>
                        <div class="tooltip">
                            <input type="checkbox" id="make_anonymous" name="form[anonymous]">
                            <span class="language" code="make_anonymous"></span><span class="tooltiptext language" code="tooltip_anonymous"></span>
                        </div>

                        <div class="button-container">
                            <div class="button last noprint language contact" code="contactus"></div>
                            <div class="button next language" code="next"></div>
                        </div>
                    </fieldset>
                    
                    <?php 
                    /*
                     * Searches the survey file first for all categories and then creates a fieldset for each
                     * containing all questions
                     */
                    
                    foreach (categories($thisUser['survey'], $thisUser['language']) as $c) {
                        echo fieldset ($thisUser['survey'], $thisUser['language'], $c); 
                    }
                    ?>

                    <fieldset class="section" name="comments">
                        <h3 class="language" code="comments0"></h3>
                        <label class="label language" code="comments1"></label>
                        <textarea name="form[comments1]" id="comments1" class="language" code="comments1" placeholder="" rows="10"></textarea>
                        <div class="button-container">
                            <div class="button last language" code="back"></div>
                            <div class="button next save language" code="save"></div>
                        </div>
                    </fieldset>

                    <fieldset class="section" name="thanks">
                        <h3 class="language thanks" code="thanks"></h3>
                        <h3 class="language thanks_failure hidden" code="thanks_failure"></h3>
                        <div class="button-container">
                            <div class="button last language" code="back"></div>
                        </div>
                    </fieldset>

                </form>
            </div>
        </div>

        <div class="sheet hidden"></div>

        
        <script type="text/javascript" >
            <?php
                //Set language from user file or English as default
                if ($thisUser['language']) {
                    echo 'let lng = "'.$thisUser['language'].'";';
                } else {
                    echo 'let lng = "en";';
                }
                
            ?>
        </script>

        <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <?php include 'php/include/menu.php'; ?>

    <?php } else {
        echo '<div class="center-of-page">Access denied</div>';
    }?>    

    </body>   
</html>



<?php

/*
 *  Function library specific to this page
 */

function fieldset ($survey, $language, $category) {
    $result = '<fieldset class="section" name="'.$category.'">';
    $json = file_get_contents('json/'.$survey.'.json');
    $arrQuery = json_decode($json, true);
    // $result .= '<div class="language left" code="verybad"></div>';
    // $result .= '<div class="language right" code="verygood"></div>';
    $result .= '<div class="language mini" code="legende"></div>';
    foreach ($arrQuery as $k => $q) {
        $scale = '';
        if ($q['category'] == $category) {
            $result .= '<div class="slidecontainer">';
            $result .= '<p class="language" code="'.$q['question'].'"></p> ';
            $result .= '<div class="range_box_wrapper">';
            for ($i = 1; $i <= 10; $i++) {
                $result .= '<div class="range_box" value="'.$i.'">'.$i.'</div>';
            }
            $result .= '<input type="hidden" class="hidden-slider-value" name="form[input]['.$q['question'].'][mark]">';
            $result .= '<input type="checkbox" name="form[input]['.$q['question'].'][skip]" class="skip" ><span class="mini">skip</span>';
            $result .= '</div>';
            // <input type="range" min="1" max="10"  class="range-slider" id="'.$q['question'].'">
            $result .= '<div class="negative-comment">
                            <p class="language" code="comment-negative"></p>
                            <textarea name="form[input]['.$q['question'].'][comment]" id="'.$q['question'].'-comment" class="language comment" code="comments1" rows="3"></textarea>
                        </div>';
            $result .= '</div>';
        }
        if ($q['bpi'] == 'true') {
            $result .= '<input type="hidden" name="form[input]['.$q['question'].'][bpi]" value="true">';
        }
    }
    

    $result .= '
    <div class="button-container">
        <div class="button last language" code="back"></div>
        <div class="button next language" code="next"></div>
    </div>';
    $result .= '</fieldset>';

    return $result;

}

function categories ($query, $language) {
    $json = file_get_contents('json/'.$query.'.json');
    $arrQuery = json_decode($json, true);
    foreach ($arrQuery as $q) {
        $a[] = $q['category'];
    }
    return array_unique($a);
}
    
?>
