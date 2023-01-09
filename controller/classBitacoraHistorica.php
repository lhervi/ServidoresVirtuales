<?php

class BitacoraHistorica{
    
    private array $bitacorasHistoricas;
    private bool $error;
    private string $mensajeError;
    
    function __construct(string $bitacoraPathFile = null){
        include_once ("./../constantes.php");

        $bitacoraPathFile = isset($bitacoraPathFile) ? $bitacoraPathFile : BITACORAHISTORICAFILEPATH;
        
        if(is_file($bitacoraPathFile)){
            
            $file = file_get_contents($bitacoraPathFile);

            $this->$bitacorasHistoricas = json_decode($file, true);
            $this->error = false;
        }else{
            $this->error = true;
            $this->mensajeError = "no pudo obtenerse la lista de bitacóras";
        }
    }    

    function getBitacoras():?array{
        if(isset($this->bitacorasHistoricas)){
            $this->error = false;
            return $this->bitacorasHistoricas;
        }else{
            $this->error = true;
            $this->mensajeError = "no hay bitacora que retornar";
            return null;
        }        
    }

    function getBitacoraByTimeStamp(int $timeStamp):?array{
        if(isset($this->bitacorasHistoricas['timeStamp'])){
            $this->error = false;
            return $this->bitacorasHistoricas['bitacora'];
        }else{
            $this->error = true;
            $this->mensajeError = "no se encontró la bitácora seleccionada";
            return null;
        }        
    }   

    function getError(){
        return $this->error;
    }
    function getMensajeError(){
        return $this->mensajeError;
    }

}

?>