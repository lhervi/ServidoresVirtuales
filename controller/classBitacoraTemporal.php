<?php

class BitacoraTemporal{

    private array $bitacora;
    private int $timestampCreacion;
    private string $nombreDeLaBitacora;
    private string $fechaHora;
    private array $eventos;
    
    function __construct(array $bitacora=null){
        include_once ("./../constantes.php");

        if(isset($bitacora)){
            $this->bitacora = $bitacora;            
        }else{
            $file = file_get_contents(HOME . BITACORATEMPORALFILEPATH);
            $this->bitacora = json_decode($file, true);
        }        
        $this->nombreDeLaBitacora = isset($bitacora['nombreDeLaBitacora']) ? $bitacora['nombreDeLaBitacora'] : null;
        $this->timestampCreacion = isset($bitacora['timestampCreacion']) ? $bitacora['timestampCreacion'] : null;
        $this->fechaHora = isset($bitacora['fechaHora']) ? $bitacora['fechaHora'] : null;
        $this->eventos = isset($bitacora['eventos']) ? $bitacora['eventos'] : null;        
    }
    
    function getNombreDeLaBitacora(){
        return $this->nombreDeLaBitacora;
    }
    function gettimestampCreacion(){
        return $this->timestampCreacion;
    }
    function getfechaHora(){
        return $this->fechaHora;
    }
    function getEventos(){
        return $this->eventos;
    }
    function getBitacora(){
        return $this->bitacora;
    }

}



?>