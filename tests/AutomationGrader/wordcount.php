<?php

function wordcount($str) { 

  return count(explode(" ",$str));

} 



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



$str = "YourOpensource.Com for you .";

echo "There are " . wordcount($str) . " in that sentence";

echo "
";

$str = "YourOpensource.Com for You. You can upload the free articles in the site.";

echo "There are " . word_count($str , "You") . " in that sentence";

 ?> 