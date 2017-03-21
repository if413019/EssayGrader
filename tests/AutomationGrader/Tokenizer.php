<?php

namespace Tests\AutomationGrader;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class Tokenizer 
{
    public function __construct(){
    }
    
    public function tokenize($wordBags){
        $wordBags = preg_replace('/[?!:.,()*\"£€\$\\n]|[-]|\'/','', $wordBags);
        $wordBags = str_replace('/', '', $wordBags);
        $wordBags = str_replace('  ', ' ', $wordBags);
        $term = explode(" ",$wordBags);
        return $term;
    }
}
