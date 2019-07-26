<?php

function bargraph ($value) {
    $result = '<div class="bargraph" style="width:'.($value * 25).';background-color: '.color($value).';"></div><span style="padding-left: 10px">'.$value.'</span>';
    return $result;
}

function color ($value) {
    if (is_numeric($value)) {
        if ($value <= 6) {
            $color = 'red';
        } elseif ($value <= 8) {
            $color = 'yellow';
        } elseif ($value >= 9) {
            $color = 'green';
        }
    } else {
        $color = 'grey';
    }
    return $color;
}

?>