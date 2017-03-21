<?php

namespace Tests\AutomationGrader;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class TfIdf 
{

    private $term;
    private $value;
    private $idf;
    private $jawaban;
    private $jawabanIdf;

    public function __construct(){
    }

    // public function calculate($wordBags){
    //     $this->term = array();
    //     $this->value = array();
    //     $this->idf = array();
    //     $this->getTerm($wordBags);
    //     sort($this->term);
    //     $this->calculateTf($wordBags);
    //     $this->calculateIdf($wordBags);
    //     $this->calculateTfIdf();
    //     return $this->value;
    // }

    public function calculate($kunciJawaban, $jawaban){
        $this->term = array();
        $this->value = array();
        $this->idf = array();
        $this->getTerm($kunciJawaban);
        sort($this->term);
        $this->calculateTf($kunciJawaban);
        $this->calculateIdf($kunciJawaban);
        $this->calculateTfIdf();
        $this->jawaban = array();
        $this->jawabanIdf = array();
        $this->calculateTfJawaban($jawaban);
        $this->calculateIdfJawaban($jawaban);
        $this->calculateTfIdfJawaban($jawaban);
        return array(
            $this->value,
            $this->jawaban
        );
    }

    public function calculateTfJawaban($jawaban){
        for($i = 0; $i < count($jawaban); $i++){
            $tempSingleJawaban = array();
            for($j = 0; $j < count($jawaban[$i]); $j++){
                $temp = array();
                for($a = 0; $a < count($this->term); $a++){
                    $value = 0;
                    if($this->value[$i][$a] > 0){
                        for($k = 0; $k < count($jawaban[$i][$j]); $k++){
                            if($this->term[$a] == $jawaban[$i][$j][$k]){
                                $value++;
                            }
                        }
                    }
                    $value = $value/count($jawaban[$i][$j]);
                    array_push($temp, $value);
                }
                array_push($tempSingleJawaban, $temp);
            }
            array_push($this->jawaban, $tempSingleJawaban);
        }
    }

    public function calculateIdfJawaban($jawaban){
        for($i = 0; $i < count($jawaban); $i++){
            $tempSingleIdf = array();
            for($a = 0; $a < count($this->term); $a++){
                $value = 0;
                if($this->value[$i][$a] > 0){
                    for($j = 0; $j < count($jawaban[$i]); $j++){   
                        if(in_array($this->term[$a], $jawaban[$i][$j])){
                            $value++;
                        }
                    }
                }
                if($value == 0){
                    array_push($tempSingleIdf, 0);
                } else {
                    array_push($tempSingleIdf, log10(count($jawaban[$i])/$value));
                }
            }
            array_push($this->jawabanIdf, $tempSingleIdf);
        }
    }

    public function getTerm($wordBags){
        foreach($wordBags as $wordBag){
            foreach($wordBag as $word){
                if(!in_array($word,$this->term,true)){
                    array_push($this->term, $word);
                }
            }
        }
    }

    public function calculateTf($wordBags){
        foreach($wordBags as $wordBag){
            $temp = array();
            foreach($this->term as $term){
                $value = 0;
                foreach($wordBag as $word){
                    if($term == $word){
                        $value++;
                    }
                }
                $value = $value/count($wordBag);
                array_push($temp, $value);
            }
            array_push($this->value, $temp);
        }
    }

    public function calculateIdf($wordBags){
        foreach($this->term as $term){
            $value = 0;
            foreach($wordBags as $wordBag){
                if(in_array($term, $wordBag)){
                    $value++;
                }
            }
            array_push($this->idf, log10(count($wordBags)/$value));
        }
    }

    public function calculateTfIdf(){
        for($i = 0; $i < count($this->term); $i++){
            for($j = 0; $j < count($this->value); $j++){
                $this->value[$j][$i] = $this->value[$j][$i] * $this->idf[$i];
            }
        }
    }

    public function calculateTfIdfJawaban(){
        for($i = 0; $i < count($this->jawaban); $i++){
            for($j = 0; $j < count($this->jawabanIdf[$i]); $j++){
                for($k = 0; $k < count($this->jawaban[$i]); $k++){
                    $this->jawaban[$i][$k][$j] = $this->jawaban[$i][$k][$j] * $this->jawabanIdf[$i][$j];
                }
            }
        }
    }
}
