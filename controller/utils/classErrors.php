<?php

// send an email 
//error_log("Database not available!", 1, "admin@domain.com", "From: myscript");

// log to a file 
//error_log("Database not available!", 3, "/usr/home/foo/error.log");

class RegistError{   
    
    /**
     * logError método estático para gestionar el archivo de Log en formato json
     *
     * @param  string $mensaje Descripción del error ocurrido
     * @param  string $archivo resultado del parámtro __FILE__
     * @param  int $linea resultado del parámetro __LINE__
     * @param  int $nivel  nivel de gravedad del error 1->warning 2->crítico (detiene la ejecución)
     * @return int|false un entero con el número de bytes registrados o falso si hubo error
     */
    static function logError(string $mensaje, string $archivo, int $linea, int $nivel=2){
        //include_once '../../constantes.php';   
        include_once __DIR__ . '/../../constantes.php';   
        include_once '/vROps/classVropsConf.php';
        include_once 'classFechas.php';        
        include_once 'classDecodeJsonFile.php';
        //Level 1->warning(no detienen la ejecución) 2->crítico (detiene la ejecución)
        
        if(is_file(VROPSLOGFILE)){
            //inicializa el archivo de error con lo que había antes
            $errorArray = DecodeJF::decodeJsonFile(VROPSLOGFILE); 
        }
                
        $error['fecha'] = Fechas::fechaHoy("completa") . PHP_EOL;
        $error['archivo'] = $archivo . PHP_EOL;
        $error['linea'] = $linea . PHP_EOL;
        $error['mensaje'] = $mensaje . PHP_EOL;  
        $error['nivel'] = $nivel . PHP_EOL;        
        $error['vropsServer'] = VropsConf::getCampo('vropsServer')['vropsServer'] . PHP_EOL;
        $error['server']= $_SERVER['SERVER_ADDR'] . PHP_EOL;
        $error['remoteUser'] = $_SERVER['REMOTE_USER'] . PHP_EOL;
        $error['userIP'] = $_SERVER['REMOTE_ADDR'] . PHP_EOL;
        //$error['userBrowser'] = get_browser(null, true);

        $errorArray[]= $error;

        $contenido = json_encode($errorArray);
        
        $result = file_put_contents(VROPSLOGFILE, $contenido);

        return $result;
    }
}

?>