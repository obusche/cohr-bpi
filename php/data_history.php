<?php
/*
 *  Creates the horizontal 3 colored bargraph display
 */

$from_date = date_create_from_format('Y-m-d', $_POST['from_date']);
$to_date = date_create_from_format('Y-m-d', $_POST['to_date']);

$json = file_get_contents('../json/language.json');
$lang = json_decode($json, true);

$dir = "../responses/";
chdir($dir);
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_ASC, $files);
foreach($files as $file) {	
    if (!is_dir($file)) {
       
        $json = file_get_contents($dir.'/'.$file);
        $arr = json_decode($json, true);

        $survey_date = date_create_from_format('Y-m-d', $arr['date']);

        if ($survey_date >= $from_date && $survey_date <= $to_date && ( $_POST['filter'] == 'all' || (in_array($arr['survey'], $_POST['filter']) && in_array($arr['productline'], $_POST['filter']) && in_array($arr['region'], $_POST['filter']))) ) {
            foreach ($arr['input'] as $k => $a) {
                if (is_numeric($a['mark'])) {
                    $history_sum[$k]['sum'] += $a['mark'];
                    $history_sum[$k]['count']++;
                    if ($a['mark'] >= 9) {
                        $history_sum[$k]['green']++;
                    } elseif ($a['mark'] >= 7) { 
                        $history_sum[$k]['yellow']++;
                    } elseif ($a['mark'] < 7) {
                        $history_sum[$k]['red']++;
                    }
                }
            }
        }
    }

}

ksort($history_sum);
$history_graph = '<h4>Summary of responses in this category.</h4>';
$history_graph .= '<table>';
foreach ($history_sum as $k => $h) {   
    $history_graph .= '<tr>';                  
    $history_graph .= '<td class="tooltip">'.$lang[$k]['short'].' ('.$h['count'].')<span class="tooltiptext auto">'.$lang[$k]['en'].'</span></td>';
    $history_graph .= '<td>'.coloredbargraph ($h['green'], $h['yellow'], $h['red'],$h['sum'], $h['count'], $k).'</td>'; 
    $history_graph .= '<tr>';              
}
$history_graph .= '</table>';

echo $history_graph;

function coloredbargraph ($green, $yellow, $red, $sum, $count, $question) {
    $scale = 300;
    $bar  = '<div class="bargraph green" question="'.$question.'" style="width:'.($green/$count * $scale).';"></div>';
    $bar .= '<div class="bargraph yellow" question="'.$question.'" style="width:'.($yellow/$count * $scale).';"></div>';
    $bar .= '<div class="bargraph red" question="'.$question.'" style="width:'.($red/$count * $scale).';"></div>';
    $bar .= '<span style="padding-left: 10px">'.round($sum/$count,1).'</span>';
    return $bar;
}


?>