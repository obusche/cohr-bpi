<?php
include 'include/functions.php';
$json = file_get_contents('../json/language.json');
$lang = json_decode($json, true);

if ($language_selected = $_POST['language']) {
    /*
     *  Creation of a new language set
     */
    if ($_POST['language'] == 'new') {
        $available = google_get_available_languages();
        
        echo language_select($available);
        /*
         *  Form with new language prefilled
         */
        if ($target_language = $_POST['target_language']) {
            echo '<h4>Language selected: '.$target_language.'</h4>';
            
            $form = '<form class="form-wrapper" id="form">';
            foreach ($lang as $k => $l) {
                if ($l['en']) {
                    if (!$text = google_translate ($l['en'], $target_language, 'en')) {
                        $text = 'failure';
                    }
                    //$text = '123';
                    $form .= '<h4>'.$l['en'].'</h4>';
                    if ($len = strlen($l['en']) > 50) {
                        $form .= '<textarea name="form['.$k.']['.$target_language.']" rows="'.round(strlen($l['en'])/30).'" >'.$text.'</textarea>';
                    } else {
                        $form .= '<input type="text" name="form['.$k.']['.$target_language.']" value="'.$text.'">';
                    }
                } else {
                    foreach ($l as $q => $a) {
                        $text = google_translate ($a['en'], $target_language);
                        //$text = '123';
                        $form .= '<h4>'.$a['en'].'</h4>';
                        if (strlen($a['en']) > 50) {
                            $form .= '<textarea name="form['.$k.']['.$q.']['.$target_language.']" rows="'.round(strlen($a['en'])/30).'">'.$text.'</textarea>';
                        } else {
                            $form .= '<input type="text" name="form['.$k.']['.$q.']['.$target_language.']" value="'.$text.'">';
                        }
                    }
                }
            }  
            $form .= '</form>';


            echo $form;
            
        }
        echo '<div class="separator"></div>';

    } else { 
        $form = create_form ($lang, false, $language_selected);   
        echo $form;
    }
} else {
    echo '<h4>No language selected</h4>';
}

function create_form ($lang, $translate, $language_selected) {
    $form = '<form class="form-wrapper" id="form">';
    foreach ($lang as $k => $l) {
        if ($l[$language_selected]) {
            $text = $l[$language_selected];
            $form .= '<h4>'.$l[$language_selected].'</h4>';
            if ($len = strlen($l[$language_selected]) > 50) {
                $form .= '<textarea name="form['.$k.']['.$language_selected.']" rows="'.round(strlen($l[$language_selected])/30).'" >'.$text.'</textarea>';
            } else {
                $form .= '<input type="text" name="form['.$k.']['.$language_selected.']" value="'.$text.'">';
            }
        } else {
            $text = $a[$language_selected];
            foreach ($l as $q => $a) {
                $form .= '<h4>'.$a[$language_selected].'</h4>';
                if (strlen($a[$language_selected]) > 50) {
                    $form .= '<textarea name="form['.$k.']['.$q.']['.$language_selected.']" rows="'.round(strlen($a[$language_selected])/30).'">'.$text.'</textarea>';
                } else {
                    $form .= '<input type="text" name="form['.$k.']['.$q.']['.$language_selected.']" value="'.$text.'">';
                }
            }
        }
    }  
    $form .= '</form>';
    return $form;
    //return '234';
}

function language_select ($available = false) {
    /*
     *  Language Selector
     */
    $available = google_get_available_languages();

    $result = '<div class="center">';
    $result .= '<h4>Select new language</h4>';
    $result .= '<select id="available_languages">';
    $result .= '<option value="">Please select</option>';
    
    foreach ($available as $lang) {
        $result .= '<option value="'.$lang['language'].'">'.$lang['language'].'</option>';
    }
    $result .= '</select>';
    $result .= '</div>';

    return $result;
}

?>