<?php

class BitacoraGestor{

    private bool $error;
    private string $mensajeError;

    private static BitacoraHistorica $bitacoraHistorica;    

    function __construct(){
        include_once ("./classBitacoraHistorica.php");

        $bitacoraHistoricaProvisional = new BitacoraHistorica();

        if($bitacoraHistoricaProvisional->getError()){
            $this->error = true;
            $this->mensajeError = "no pudo recuperarse el histórico de las bitácoras";
        }else{
            $this->error = false;
            $this->bitacoraHistorica = $bitacoraHistorica;
        }

        
    }

    function getBitacoraHistorica(){
        if(isset($this->bitacoraHistorica)){
            $this->error = false;
            return $this->bitacoraHistorica;
        }else{
            $this->error = false;
            $this->mensajeError = "no pudo recuperarse el histórico de las bitácoras";
        }
        
    }

    function getBitacoraTemporal(int $timeStamp = null){

        if(isset($timeStamp)){

            if(isset($this->bitacoraHistorica[$timeStamp])){
                $this->error = false;
                return $this->bitacoraHistorica[$timeStamp];
            }else{
                $this->error = true;
                $this->mensajeError = "no hay bitácora temporal";
            }

        }else{
            $filePathTemporal = isset($filePathTemporal) ? $filePathTemporal : HOME . BITACORATEMPORALFILEPATH;
            $bitacoraTemporal = json_decode(file_get_contents($filePathTemporal), true);
            if(isset($bitacoraTemporal)){
                return $bitacoraTemporal;
            }else{
                $this->error = true;
                $this->mensajeError = "no hay bitácora temporal";
                return null;
            }
        }
        
        
        
    }
}

?>