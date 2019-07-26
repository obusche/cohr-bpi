<?php
include '../php/include/graph.php';
/*
 *  This function is started by clicking on the 3-colored bargraph and
 *  displays all responses for the clicked part in the selected timeframe
 */

$from_date = date_create_from_format('Y-m-d', $_POST['from_date']);
$to_date = date_create_from_format('Y-m-d', $_POST['to_date']);

$json = file_get_contents('../json/language.json');
$lang = json_decode($json, true);
$result = '';
$dir = "../responses/";
chdir($dir);
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_ASC, $files);
//var_dump($files);
foreach ($files as $k => $file) {	
    if (!is_dir($file)) {
        //$result .= '<p>File: '.$k.' => '.$file.'</p>';
        $json = file_get_contents($dir.'/'.$file);
        $arr = json_decode($json, true);
        $survey_date = date_create_from_format('Y-m-d', $arr['date']);

        if ($survey_date >= $from_date && $survey_date <= $to_date) {
            $mark = $arr['input'][$_POST['question']]['mark'];
            if ($_POST['color'] == 'green' && $mark >= 9){
                $result .= display ($arr, $lang);
            } elseif ($_POST['color'] == 'yellow' && $mark >= 7 && $mark <= 8){
                $result .= display ($arr, $lang);
            } elseif ($_POST['color'] == 'red' && $mark <= 6 && $mark > 0){
                $result .= display ($arr, $lang);
            }
        }
    }
    //

}
echo $result;

function display ($arr, $lang) {
    /*
    * Translate the response parameters into text
    */
    $survey_date = date_create_from_format('Y-m-d', $arr['date']);

    if ($arr['survey'] == 'sales') {
        $thejob = 'who sells'; 
    } elseif ($arr['survey'] == 'service') {
        $thejob = 'who services'; 
    }
    if ($arr['productline'] == 'rails') {
        $theproduct = 'Rails'; 
    } elseif ($arr['productline'] == 'tools') {
        $theproduct = 'Tools'; 
    } elseif ($arr['productline'] == 'standard') {
        $theproduct = 'Standard Tools'; 
    }
    
    /*
    * Create the detail views on eache survey
    */
    $response .= '<div class="survey-response marginbottom">';            
    $response .= '<hr>';
    $response .= '<p align="right">'.date_format($survey_date, 'd. M. Y').'</p>';
    $response .= '<h3>Survey feedback from '.$arr['firstname'].' '.$arr['lastname'].' '.$thejob.' '.$theproduct.' in '.ucfirst($arr['region']).'</h3>';
    $response .= '<p>'.$arr['userdescription'].'</p>';
    
    $response .= '<table>';
    foreach ($arr['input'] as $k => $a) {
        $response .= '<tr>';                  
        $response .= '<td class="no_wrap">'.$lang[$k]['short'].'</td>';
        $response .= '<td>'.bargraph ($a['mark']).'</td>';
        $response .= '<tr><td></td><td>'.$a['comment'].'</td></tr>';                
    }
    $response .= '<tr><td colspan="2" class="padded">'.$arr['comments1'].'</td></tr>';
    $response .= '</table>';
    $response .= '</div>';

    return $response;
    //return var_dump ($arr);
}



?>