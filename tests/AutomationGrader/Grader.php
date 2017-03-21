<?php

namespace Tests\AutomationGrader;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class Grader 
{
    private $kunciJawaban = array();
    private $jawaban = array();
    private $caseFolding;
    private $tokenizer;
    private $stopWord;
    private $stemming;
    private $tfidf;
    private $euclidean;
    private $svdKunciJawaban;
    private $svdJawaban = array();
    private $score = array();
    
    public function __construct(){
        $this->caseFolding = new CaseFolding;
        $this->tokenizer = new Tokenizer;
        $this->stopWord = new StopWord;
        $this->stemming = new Stemming;
        $this->tfidf = new TfIdf;
        $this->euclidean = new  EuclideanDistance;
    }

    public function grade($wordBags){
        echo '<pre>';
        $this->readFromJson($wordBags);
        $this->caseFolding();
        $this->tokenize();
        $this->stopWord();
        $this->ubahAngka();
        $this->stemming();
        $this->tfidf();
        $this->rotateArray();
        $this->getSVD();
        $this->rotateArray();
        //echo $this->euclidean->normalizedDistance($this->kunciJawaban[0], $this->jawaban[0][0]);
        $this->getScore();
        // print_r($this->kunciJawaban);
        // // print_r($this->svdKunciJawaban);
        // print_r($this->jawaban);
        // print_r($this->svdJawaban);
        // print_r($this->score);
        //$this->svdJawaban = (new Matrix($this->Jawaban))->svd();
        //print_r($this->jawaban);
        //die();
        //$wordBags = (new CaseFolding)->caseFolding($wordBags);
        //print_r($kunciJawaban);
        // die();
        //return (new stemming)->stemming($wordBags);
        return $this->score;
    }

    private function readFromJson($wordBags){
        foreach($wordBags as $wordBag){
            array_push($this->kunciJawaban, $wordBag['kunciJawaban']);
            $temp = array();
            foreach($wordBag['jawabanSiswa'] as $jawabanSiswa){
                array_push($temp, $jawabanSiswa['jawaban']);
            }
            array_push($this->jawaban, $temp);
        }
    }

    private function caseFolding(){
        for($i = 0; $i < (count($this->kunciJawaban)); $i++){
            $this->kunciJawaban[$i] = $this->caseFolding->caseFolding($this->kunciJawaban[$i]);
        }
        for($i = 0; $i < (count($this->jawaban)); $i++){
            for($j = 0; $j < (count($this->jawaban[$i])); $j++){
                $this->jawaban[$i][$j] = $this->caseFolding->caseFolding($this->jawaban[$i][$j]);
            }
        }
    }

    private function tokenize(){
        for($i = 0; $i < (count($this->kunciJawaban)); $i++){
            $this->kunciJawaban[$i] = $this->tokenizer->tokenize($this->kunciJawaban[$i]);
        }
        for($i = 0; $i < (count($this->jawaban)); $i++){
            for($j = 0; $j < (count($this->jawaban[$i])); $j++){
                $this->jawaban[$i][$j] = $this->tokenizer->tokenize($this->jawaban[$i][$j]);
            }
        }
    }

    private function stopWord(){
        for($i = 0; $i < (count($this->kunciJawaban)); $i++){
            $this->kunciJawaban[$i] = $this->stopWord->cekStopWord($this->kunciJawaban[$i]);
        }
        for($i = 0; $i < (count($this->jawaban)); $i++){
            for($j = 0; $j < (count($this->jawaban[$i])); $j++){
                $this->jawaban[$i][$j] = $this->stopWord->cekStopWord($this->jawaban[$i][$j]);
            }
        }
    }

    private function stemming(){
        for($i = 0; $i < (count($this->kunciJawaban)); $i++){
            $this->kunciJawaban[$i] = $this->stemming->cekStemming($this->kunciJawaban[$i]);
        }
        for($i = 0; $i < (count($this->jawaban)); $i++){
            for($j = 0; $j < (count($this->jawaban[$i])); $j++){
                $this->jawaban[$i][$j] = $this->stemming->cekStemming($this->jawaban[$i][$j]);
            }
        }
    }

    private function ubahAngka(){
        for($i = 0; $i < (count($this->kunciJawaban)); $i++){
            $this->kunciJawaban[$i] = $this->cekTerbilang($this->kunciJawaban[$i]);
        }
        for($i = 0; $i < (count($this->jawaban)); $i++){
            for($j = 0; $j < (count($this->jawaban[$i])); $j++){
                $this->jawaban[$i][$j] = $this->cekTerbilang($this->jawaban[$i][$j]);
            }
        }
    }

    private function cekTerbilang($wordBags){
        $res = array();
        foreach($wordBags as $wordBag){
            if (is_numeric($wordBag)){
                $temp = $this->Terbilang($wordBag);
                $temp[0] = "";
                $temp = $this->tokenizer->tokenize($temp);
                foreach($temp as $term){
                    array_push($res, $term);
                }
            }else{
                array_push($res, $wordBag);
            }
        }
        return $res;
    }

    function Terbilang($satuan){
        $huruf = array ("", "satu", "dua", "tiga", "empat", "lima", "enam", 
        "tujuh", "delapan", "sembilan", "sepuluh","sebelas");
        if ($satuan < 12)
            return " ".$huruf[$satuan];
        elseif ($satuan < 20)
            return $this->Terbilang($satuan - 10)." belas";
        elseif ($satuan < 100)
            return $this->Terbilang($satuan / 10)." puluh".
            $this->Terbilang($satuan % 10);
        elseif ($satuan < 200)
            return "seratus".$this->Terbilang($satuan - 100);
        elseif ($satuan < 1000)
            return $this->Terbilang($satuan / 100)." ratus".
            $this->Terbilang($satuan % 100);
        elseif ($satuan < 2000)
            return "seribu".$this->Terbilang($satuan - 1000); 
        elseif ($satuan < 1000000)
            return $this->Terbilang($satuan / 1000)." ribu".
            $this->Terbilang($satuan % 1000); 
        elseif ($satuan < 1000000000)
            return $this->Terbilang($satuan / 1000000)." juta".
            $this->Terbilang($satuan % 1000000);
        }

    private function tfidf(){
        // $this->kunciJawaban = $this->tfidf->calculate($this->kunciJawaban);
        // for($i = 0; $i < count($this->jawaban); $i++){
        //     $this->jawaban[$i] = $this->tfidf->calculate($this->jawaban[$i]);
        // }
        $temp = $this->tfidf->calculate($this->kunciJawaban, $this->jawaban);
        $this->kunciJawaban = $temp[0];
        $this->jawaban = $temp[1];
    }

    private function rotateArray(){
        $temp = array();
        for($i = 0; $i < count($this->kunciJawaban[0]); $i++){
            $tempRow = array();
            for($j = 0; $j < count($this->kunciJawaban); $j++){
                array_push($tempRow, $this->kunciJawaban[$j][$i]);
            }
            array_push($temp, $tempRow);
        }
        $this->kunciJawaban = $temp;
        $tempJawaban = array();
        for($i = 0; $i < count($this->jawaban); $i++){
            $tempSingleJawaban = array();
            for($j = 0; $j < count($this->jawaban[$i][0]); $j++){
                $tempRow = array();
                for($k = 0; $k < count($this->jawaban[$i]); $k++){
                    array_push($tempRow, $this->jawaban[$i][$k][$j]);
                }
                array_push($tempSingleJawaban, $tempRow);
            }
            array_push($tempJawaban, $tempSingleJawaban);
        }
        $this->jawaban = $tempJawaban;
    }

    private function getSVD(){
        $tempMatriksKunciJawaban = new Matrix($this->kunciJawaban);
        $rank = $tempMatriksKunciJawaban->rank() - $tempMatriksKunciJawaban->rank()/3;
        $this->svdKunciJawaban = $tempMatriksKunciJawaban->svd();
        $UrKunciJawaban = $this->svdKunciJawaban->getUr($rank);
        $SrKunciJawaban = $this->svdKunciJawaban->getSr($rank);
        $VrTKunciJawaban = $this->svdKunciJawaban->getVr($rank)->transpose();
        $this->kunciJawaban = $UrKunciJawaban->times($SrKunciJawaban)->times($VrTKunciJawaban)->A;
        for($i = 0; $i < count($this->jawaban); $i++){
            $tempMatriksJawaban = new Matrix($this->jawaban[$i]);
            $rank = intval(ceil($tempMatriksJawaban->rank() - $tempMatriksJawaban->rank()/3));
            array_push($this->svdJawaban, $tempMatriksJawaban->svd());
            $UrJawaban = $this->svdJawaban[$i]->getUr($rank);
            // echo ("asd " . $i);
            // print_r($tempMatriksJawaban->rank());
            // print_r($this->svdJawaban[$i]);
            // print_r($this->svdJawaban[$i]->getSr($tempMatriksJawaban->rank()));
            // print_r($this->jawaban[$i]);
            $SrJawaban = $this->svdJawaban[$i]->getSr($rank);
            $VrTJawaban = $this->svdJawaban[$i]->getVr($rank)->transpose();
            $this->jawaban[$i] = $UrJawaban->times($SrJawaban)->times($VrTJawaban)->A;
        }
    }

    private function getScore(){
        for($i = 0; $i < count($this->kunciJawaban); $i++){
            $tempJawaban = array();
            for($j = 0; $j < count($this->jawaban[$i]); $j++){
                $score = $this->euclidean->normalizedDistance($this->kunciJawaban[$i], $this->jawaban[$i][$j]);
                if($score <= 0.40){
                    $score = 4;
                } else if($score <= 0.80){
                    $score = 3;
                } else if($score <= 1.20){
                    $score = 2;
                } else if($score <= 1.60){
                    $score = 1;
                } else {
                    $score = 0;
                }
                $tempScore = array(
                    "idSiswa"   => ($j + 1),
                    "score"     => ($score)
                );
                array_push($tempJawaban, $tempScore);
            }
            $tempKunciJawaban = array(
                "idSoal" => ($i + 1),
                "scoreSiswa" => $tempJawaban
            );
            array_push($this->score, $tempKunciJawaban);
        }
    }
}
