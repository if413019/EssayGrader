<?php
function word_count($str,$word){

    $str = str_replace(". "," ",$str);

    $words = explode(" ",$str);

    $count=0;

    foreach($words as $key=>$value){

        if($value == $word){

            $count++;

        }

    }

    return $count;

}
?>