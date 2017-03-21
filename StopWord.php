<?php
    $string = file_get_contents("stopword_list.txt");
        $string = preg_replace('/[?!:.,()*\"£€\$\\n]|[-]|\r\n|\'/',' ', $string);
        $string = str_replace('/', '', $string);
        $string = str_replace('  ', ' ', $string);
        $string = str_replace(PHP_EOL, ' ', $string);
    $term = explode(" ",$string);
    $toWrite = "";
    $index = 1;
    foreach($term as $word){
        $toWrite = $toWrite . '(' . $index .',&#039'. $word .'&#039)';
        $toWrite = $toWrite . ',';
        $index++;
    }
    echo $toWrite;
?>