<?php
include '../php/include/graph.php';
$json = file_get_contents('../json/language.json');
$lang = json_decode($json, true);

$responses = '';
$index = 1;
$dir = "../responses/";
chdir($dir);
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_ASC, $files);
foreach($files as $file) {	
    if (!is_dir($file)) {
        
        $json = file_get_contents($dir.'/'.$file);
        $arr = json_decode($json, true);

        /*
         *  Show result if it fulfills the filter settings
         *  In case the filter is stet to 'all' all sets will be displayed
         */
        if ( $_POST['filter'] == 'all' || ( in_array($arr['survey'], $_POST['filter']) && in_array($arr['productline'], $_POST['filter']) && in_array($arr['region'], $_POST['filter'])) ) {
            $survey_date = date_create_from_format('Y-m-d', $arr['date']);
            $history[$index]['date'] = date_format($survey_date, 'm/d');

            /*
             * Find the bpi question and write the response into array
             */
            foreach ($arr['input'] as $a) {
                if ($a['bpi'] == 'true') {
                    $history[$index]['bpi'] = $a ['mark'];
                }
            }

            /*
             * Translate the response parameters into text
             */
            if ($arr['survey'] == 'sales') {
                $thejob = 'who sells'; 
                $job = 'Sales';
            } elseif ($arr['survey'] == 'service') {
                $thejob = 'who services'; 
                $job = 'Service';
            }
            if ($arr['productline'] == 'rails') {
                $theproduct = 'Rails'; 
            } elseif ($arr['productline'] == 'tools') {
                $theproduct = 'Tools'; 
            } elseif ($arr['productline'] == 'standard') {
                $theproduct = 'Standard Tools'; 
            }


            // Write info for bar graph tooltips
            $history[$index]['info'] = $theproduct.'<br>'.$job.'<br>'.ucfirst ($arr['region']);
            
            /*
             * Create the detail views on eache survey
             */
            $responses .= '<div class="survey-response marginbottom" style="display:none;" id="'.$index.'-">';            
            $responses .= '<hr>';
            $responses .= '<p align="right">'.date_format($survey_date, 'd. M. Y').'</p>';
            $responses .= '<h3>Survey feedback from '.$arr['firstname'].' '.$arr['lastname'].' '.$thejob.' '.$theproduct.' in '.ucfirst($arr['region']).'</h3>';;
            if ($arr['email'] != 'anonymous') {
                $responses .= '<p>'.$arr['userdescription'].'</p>';
            }
            
            $responses .= '<table>';
            foreach ($arr['input'] as $k => $a) {
                $responses .= '<tr>';                  
                $responses .= '<td class="no_wrap tooltip">'.$lang[$k]['short'].'<span class="tooltiptext auto">'.$lang[$k]['en'].'</span></td>';
                $responses .= '<td>'.bargraph ($a['mark']).'</td>';
                $responses .= '<tr><td></td><td>'.$a['comment'].'</td></tr>';                
            }
            $responses .= '<tr><td colspan="2" class="padded">'.$arr['comments1'].'</td></tr>';
            $responses .= '<tr><td colspan="2" class="padded"><a href="'.email_response ($arr['firstname'], 'en', $arr['email']).'">Send response</a></td></tr>';
            $responses .= '</table>';
            $responses .= '</div>';

            $index++;
        }
    }   
}

echo historygraph ($history, $index-20, $index);

echo $responses;

function email_response ($name, $language, $email) {
    $json = file_get_contents('../json/language.json');
    $lang = json_decode($json, true);

    $subject = $lang['email_response_header'][$language];
    $body = $lang['salutation-informal']['male'][$language]." ".$name."\r\r";
    $body .= $lang['email_response'][$language]."\r\r";
    return 'mailto:'.$email.'?subject='.rawurlencode($subject).'&body='.rawurlencode($body);

}

function historygraph ($history, $start, $end) {
    $i = 0;
    $graph = '<h3 align="center">Business Partner Index - Last Responses</h3>';
    $graph .= '<div class="bpi-wrapper"><div class="bpi-graph marginbottom">';
    foreach ($history as $k => $h) {
        if ($i >= $start) {
            $graph .= '<div class="x-axis3">'.$h['date'].'</div>';
            $color = color($h['bpi']);
            if (!is_numeric($h['bpi'])) {
                $h['bpi'] = 10;
            }
            $graph .= '<div class="bpidot tooltip" style="height: '.($h['bpi']*10).'px; background-color: '.$color.'" id="'.$k.'">
                    <span class="tooltiptext slim">'.$h['info'].'</span>
                </div>';
        }
        if ($i < $end) {
            $i++;
        } else {
            break 1;
        }
    }
    $graph .= '</div></div>';
    return $graph;
}



/*
 *  Just returns the style tag for a CSS rotation
 */ 
function rotate ($angle) {
    return 'style="-webkit-transform:rotate('.$angle.'deg); -moz-transform:rotate('.$angle.'deg); -o-transform:rotate('.$angle.'deg); transform:rotate('.$angle.'deg);"';
}


?>