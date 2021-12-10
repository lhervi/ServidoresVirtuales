<?php

class Log{       
    
    static function getLog(int $linea, string $tipoEvento, string $evento){

        include_once 'classTiempo.php';

        $date=new DateTime();
        $id = $date->getTimestamp();
        $fecha = Tiempo::ahora();         

        $log[]=array();
        $detralle[]=array();
        $info=['linea'=>$linea, 'tipo'=>$tipoEvento, 'evento'=>$evento];
        $log=['id'=>$id, 'fecha'=>$fecha, 'info'=>$info];
        
        return $log;
    }
}
?>