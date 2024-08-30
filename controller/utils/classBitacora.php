<?php

class Bitacora {    
    
    static function log(int $linea, string $tipo, string $error, string $file="../CTISCRlogs.json"){
        include_once 'classLog.php';
        $obj = Log::getLog($linea, $tipo, $error);       
        $evento = json_encode($obj) . PHP_EOL;
        echo ($evento . "<br/>");
        file_put_contents($file, $evento, FILE_APPEND);
    }

    static function avance(string $evento, string $dif=null){
        //include_once '../utils/classFechas.php';
        include_once '../controller/utils/classFechas.php';
    
        $avance = HOME.SALIDAS."statusDeAvance" . $dif ."json";
    
        if (file_exists($avance)){
            $contenido = json_decode(file_get_contents($avance), true); //Si el archivo existe, se inicializa con el contenido del archivo           
        }
    
        //Archivo donde se registra el avance del proceso en la aplicación
        $fec=Fechas::fechaHoy("completa", "-");  //fecha de ocurrencia del evento
        
        $entrada['fecha']=$fec;
        $entrda['evento']=$evento;
        $contenido[]= $entrada;
        file_put_contents($avance, json_encode($contenido, JSON_PRETTY_PRINT), FILE_APPEND);
    }
}


?>