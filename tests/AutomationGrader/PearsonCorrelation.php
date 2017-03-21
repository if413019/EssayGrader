<?php
    namespace Tests\AutomationGrader;
    /**
    * Class Pearson
    * @package AutomationGrader
    *
    */
    //Pearson Coorelation
    class Pearson{
        public function __construct(){
        }

        public function corelation($variable1, $variable2)
        {
            $exp  = 2;
            $sumOfProductVar = $sumOfSquareVar1 = $sumOfSquareVar2 = $sumOfVar1 = $sumOfVar2 = $sum = 0;
            $n    = count($variable1);
            for ($i = 0; $i < $n; $i++) {
                $sumOfVar1       += $variable1[$i];
                $sumOfVar2       += $variable2[$i];
                $sumOfSquareVar1 += pow($variable1[$i], $exp);
                $sumOfSquareVar2 += pow($variable2[$i], $exp);
                $sumOfProductVar += $variable1[$i] * $variable2[$i];
            }
            echo $sumOfVar1.'\n';
            echo $sumOfVar2.'\n';
            echo $sumOfSquareVar1.'\n';
            echo $sumOfSquareVar2.'\n';
            echo $sumOfProductVar.'\n';

            $coorelation = ($n * $sumOfProductVar - $sumOfVar1 * $sumOfVar2)/sqrt(($n * $sumOfSquareVar1 - pow($sumOfVar1, $exp))*($n * $sumOfSquareVar2 - pow($sumOfVar2, $exp)));
            return $coorelation;
        }
    }

    