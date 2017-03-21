<?php
namespace Tests\AutomationGrader;
/**
 * Class StopWord
 * @package AutomationGrader
 *
 */

//require_once('connect.php'); //Koneksi ke database
//fungsi untuk mengecek kata dalam tabel dictionary


class StopWord{
    
    public function __construct(){
    }

    public function cekStopWord($bagWords) {
        $link = mysqli_connect("localhost", "root", "", "dictionary");
        $res = array();
        $sql = $link->prepare("SELECT word from stopword where word =? LIMIT 1");
        foreach($bagWords as $bagWord){
            $sql->bind_param("s", $bagWord);
            $sql->execute();
            $sql->bind_result($word);
            $sql->fetch();
            if($word == ""){
                array_push($res, $bagWord);
            }
        }
        mysqli_close($link);
        return $res;
    }

}
