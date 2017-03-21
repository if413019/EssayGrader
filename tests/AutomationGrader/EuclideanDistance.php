<?php
    namespace Tests\AutomationGrader;
    /**
    * Class EuclideanDistance
    * @package AutomationGrader
    *
    */
    //Normalized Euclidean Distance
    class EuclideanDistance{
        public function __construct(){
        }

        public function normalizedDistance($vector1, $vector2)
        {
            $exp  = 2;
            $sumOfArr1 = $sumOfArr2 = $sum = 0;
            $n = count($vector1);
            for ($i = 0; $i < $n; $i++) {
                $sumOfArr1 += pow($vector1[$i], $exp);
                $sumOfArr2 += pow($vector2[$i], $exp);
            }

            $sumOfArr1 = sqrt($sumOfArr1);
            $sumOfArr2 = sqrt($sumOfArr2);
            if($sumOfArr1 == 0){
                $sumOfArr1 = 1;
            }
            if($sumOfArr2 == 0){
                $sumOfArr2 = 1;
            }

            for ($i = 0; $i < $n; $i++) {
                $vector1[$i] = $vector1[$i]/$sumOfArr1;
                $vector2[$i] = $vector2[$i]/$sumOfArr2;
            }

            for ($i = 0; $i < $n; $i++) {
                $sum += ($vector1[$i] - $vector2[$i]) * ($vector1[$i] - $vector2[$i]);
            }
            return sqrt($sum);
        }
    }