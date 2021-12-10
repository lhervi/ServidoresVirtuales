<?php

class Tiempo{
    static function horaCorta(){
        $n = getdate();
        return $n['hours'] . ":" . $n['minutes'];
    }

    static function horaLarga(){
        $n = getdate();
        return  $n['hours'] . ":" . $n['minutes'] . ":" . $n['seconds'];
    }

    static function ahora(){
        $n = getdate();
        return $n['year'] . "-" . $n['mon'] . "-" . $n['mday'] . " " . $n['hours'] . " " . $n['minutes'] . ":" . $n['seconds'];
    }    
}

?>