<?php

$from_date = date_create_from_format('Y-m-d', $_POST['from_date']);
$to_date = date_create_from_format('Y-m-d', $_POST['to_date']);

// $json = file_get_contents('../json/language.json');
// $lang = json_decode($json, true);

$dir = "../responses/";
$questions = $_POST['questions'];
chdir($dir);
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_ASC, $files);
foreach($files as $file) {	
    if (!is_dir($file)) {
       
        $json = file_get_contents($dir.'/'.$file);
        $arr = json_decode($json, true);

        $survey_date = date_create_from_format('Y-m-d', $arr['date']);
        $survey_month = date_format($survey_date, 'Y-m');

        if ($survey_date >= $from_date && $survey_date <= $to_date && ( $_POST['filter'] == 'all' || ( in_array($arr['productline'], $_POST['filter']) && in_array($arr['region'], $_POST['filter']))) ) {
            foreach ($questions as $q) {
                if ($arr['input'][$q]) {
                    $trend[$survey_month][] = $arr['input'][$q]['mark'];
                }
            }
        }
        
    }
    
}

// foreach ($trend as $month => $marks) {
//     echo '<p>'.$month.': '.array_average($marks).'</p>';
// }


$graph = '<h3 align="center">Business Partner Index - Selected Parameters</h3>';
$graph .= '<div class="bpi-wrapper"><div class="bpi-graph marginbottom">';
foreach ($trend as $month => $marks) {
    $graph .= '<div class="x-axis2">'.$month.'</div>';
    $graph .= '<div class="bpidot" style="height: '.round(array_average($marks)*20).'px; background-color:#888;"><span class="graph_label">'.array_average($marks).'</span></div>';
}
$graph .= '</div></div>';
echo $graph;

function array_average ($arr) {
    $count = 0; $sum = 0;
    foreach ($arr as $a) {
        if (is_numeric($a)) {
            $sum += $a;
            $count++;
        }
    }
    return round($sum/$count,1);
}


?>