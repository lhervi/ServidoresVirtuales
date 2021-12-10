<?php

// send an email 
//error_log("Database not available!", 1, "admin@domain.com", "From: myscript");

// log to a file 
//error_log("Database not available!", 3, "/usr/home/foo/error.log");

class Error{   

    function errorHandling(string $mensaje=null, int $line=null, bool $log=false, bool $email=false){
        include_once '../../constantes.php';        

        $error['error'] = true;
        $error['mensaje'] = $mensaje;        
        if (is_int($line)) $error[$line]=$line;
        if ($log){
            error_log($mensaje, 3, HOME.VROPSLOGFILE);
        }
        if ($email){
            error_log($mensaje, 1, VROPSERROREMAILTO, VROPSERROREMAILFROM);
        }
        return $error;
    }
}

?>