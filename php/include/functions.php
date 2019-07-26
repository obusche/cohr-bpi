<?php

function select_fancy ($category) {
    $json = file_get_contents('json/filter.json');
    $filters = json_decode($json, true);
    $filter  .= '<div class="select_wrapper filter">';
    $filter .= '<div class="'.$filters[$category]['class'].'">'.$filters[$category]['name'].'<div class="select_arrow"></div></div>';
    
    foreach ($filters[$category]['options'] as $option => $parameters) {
        $filter .= '<div class="'.$parameters['class'].'" value="'.$option.'">'.$parameters['display'].'</div>';
    }
    $filter .= '</div>';

    return $filter;
}

function select_simple ($category) {
    $json = file_get_contents('json/filter.json');
    $filters = json_decode($json, true);
    $filter  = '<select id="'.$category.'" >';   
    foreach ($filters[$category]['options'] as $option => $parameters) {
        $filter .= '<option value="'.$option.'">'.$parameters['display'].' '.$parameters['example']."</option>\n";
    }
    $filter .= '</select>';
    return $filter;
}

function question_filter ($surveys) {
    $json = file_get_contents('json/language.json');
    $lang = json_decode($json, true);
    $filter  = '<div class="select_wrapper questions">';
    $filter .= '<div class="select_head">Questions<div class="select_arrow"></div></div>';
    foreach ($surveys as $survey) {
        $json = file_get_contents('json/'.$survey.'.json');
        $arr = json_decode($json, true);

        foreach ($arr as $a) {
            if ( $a['category'] == 'conclusion') {
                $preselect = 'selected';
            } else {
                $preselect = '';
            }
            $filter .= '<div class="select_option questions hidden mini '.$preselect.'" value="'.$a['question'].'">'.$lang[$a['question']]['short'].'</div>';
        }
    }
    $filter .= '</div>';

    return $filter;
}

function mailto ($email, $language, $id, $name, $survey, $url) {
    $json = file_get_contents('../json/language.json');
    $lang = json_decode($json, true);
    $subject = rawurlencode('Global Tools Business Partner Satisfaction Survey');
    $body = "Dear ".$name."\r\r";
    $body .= $lang['participate1'][$survey][$language]."\r\r";
    $body .= $url.'index.php?id='.$id."\r\r";
    $body .= $lang['participate2'][$survey][$language]."\r\r";
    $body .= $lang['participate3'][$survey][$language];
    $mailToLink = 'mailto:'.$email.'?subject='.$subject.'&body='.rawurlencode($body);
    return $mailToLink;

}

function language_selector () {
    $json = file_get_contents('json/language.json');
    $lang = json_decode($json, true);

    $return = '<select id="languages" >';
    $return .= '<option value="">Please select</option>';
    foreach ($lang['language'] as $k => $l) {       
        $return .= '<option value="'.$k.'">'.$l.'</option>';       
    }
    $return .= '<option value="new">New Language</option>'; 
    $return .= '</select>';

    return $return;
}

function google_translate ($text, $target_language, $source_language = 'en') {
    $apiKey = 'AIzaSyBw6dGo8G6nV849hIdC63XdzI9OrsyoGwI';
    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source='.$source_language.'&target='.$target_language;

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);              
    $response_decoded = json_decode($response, true);
    $response_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    if($response_code != 200) {
        return '* this text could not be translated *';
    } else {
        return $response_decoded['data']['translations'][0]['translatedText'];
    }
}

/*
 *  Returns an array of languages available in the google translate API
 *  such as ['de', 'en']
 */
function google_get_available_languages () {
    $apiKey = 'AIzaSyBw6dGo8G6nV849hIdC63XdzI9OrsyoGwI';
    $url = 'https://www.googleapis.com/language/translate/v2/languages?key=' . $apiKey;
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                         
    curl_close($handle);

    $arr =  json_decode($response, true);
    return $arr['data']['languages'];
}

?>