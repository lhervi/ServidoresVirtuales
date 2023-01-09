<?php

class Bitacora{

    //private string $nombreDeLaBitacora;
    //private int $timestampCreacion;
    private string $filePathTemporal;
    private string $filePathHistorico;    
    //private array $eventos;
    private array $bitacoraTemporal;
    private array $bitacoraHistorico;
    private bool $error;
    private string $mensajeError;
    
    
    function __construct(string $nombreDeLaBitacora=null, 
                        string $filePathTemporal=null,
                        string $filePathHistorico=null)
    {
        include_once(__DIR__ . "/../controller/utils/classFechas.php");
        include_once(__DIR__ . "/../constantes.php");
        
        $date = new DateTimeImmutable();

        $this->filePathTemporal = isset($filePathTemporal) ? $filePathTemporal : HOME . BITACORATEMPORALFILEPATH;
        $this->filePathHistorico = isset($filePathHistorico) ? $filePathHistorico : HOME . BITACORAHISTORICAFILEPATH;

        $this->bitacoraTemporal['nombreDeLaBitacora'] = isset($nombreDeLaBitacora) ? $nombreDeLaBitacora : NOMBREBITACORA;        
        $this->bitacoraTemporal['timestampCreacion'] = $date->getTimestamp(); 
        $this->bitacoraTemporal['FechaHora'] = Fechas::toStringFechaAhora();   
        $this->bitacoraTemporal['eventos'] = array();

        $arrayHistorico = json_decode(file_get_contents($this->filePathHistorico), true);
        if(isset($arrayHistorico)){
            $this->bitacoraHistorico = $arrayHistorico;
        }else{
            file_put_contents($this->filePathHistorico, "[]");
        }
                
        $this->bitacoraHistorico=[];              
        
        $this->iniDataFileBitacoraTemporal();
    }

    function __destruct()  
    {
        $file = file_get_contents($this->filePathHistorico);        
        $this->bitacoraHistorico = json_decode($file, true);
       
        $date = new DateTimeImmutable();
        $bitacora['timeStamp'] = $date->getTimestamp();
        $bitacora['fechaHora'] = Fechas::toStringFechaAhora();
        $bitacora['bitacora'] = $this->bitacoraTemporal;
        array_unshift($this->bitacoraHistorico, $bitacora);            
        file_put_contents($this->filePathHistorico, json_encode($this->bitacoraHistorico, JSON_PRETTY_PRINT));                            
        //$this->borrarBitacoraTemporal(false);
    }

    private function iniDataFileBitacoraTemporal(){
        //nombreDeLaBitacora, timestampCreacion, eventos
     
        if (!is_file($this->filePathHistorico)){
            file_put_contents($this->filePathHistorico, "");
        }
        
        if (!is_file($this->filePathTemporal)){
            file_put_contents($this->filePathTemporal, json_encode($this->bitacoraTemporal, JSON_PRETTY_PRINT));
        }else{
            $this->setDataFileBitacoraTemporal();            
        }
    }

    private function setError(string $mensajeError){
        $this->error = true;
        $this->mensajeError = $mensajeError;
    }

    function borrarBitacoraTemporal(bool $borrarArchivo=true){
        file_put_contents($this->filePathTemporal, "");
        if($borrarArchivo){
            unlink($this->filePathTemporal);
        }        
    }
    
    private function setDataFileBitacoraTemporal(){
        $bitacoraContent = file_get_contents(HOME . BITACORATEMPORALFILEPATH);            
        $bitacoraArray = json_decode($bitacoraContent, true);
        $keysOk = is_array($bitacoraArray) && 
                key_exists('nombreDeLaBitacora', $bitacoraArray) && 
                key_exists('timestampCreacion', $bitacoraArray) && 
                key_exists('eventos', $bitacoraArray);       
        if ($keysOk) {           
            $this->bitacoraTemporal['nombreDeLaBitacora'] = $bitacoraArray['nombreDeLaBitacora'];        
            $this->bitacoraTemporal['timestampCreacion'] = $bitacoraArray['timestampCreacion'];    
            $this->bitacoraTemporal['eventos'] = $bitacoraArray['eventos'];
        }else{            
            $this->setError('no se pudo obtener la información para inicializar la bitácora temporal');
        }
    }

    function registrarEvento(string $contenido):bool{
        include_once ("./../controller/utils/classFechas.php");
        include_once("./../constantes.php");
        
        //nombreDeLaBitacora, timestampCreacion, eventos
        $fec = function(){
            return Fechas::toStringFechaAhora();
        };

        $evento = ['fecha' => $fec(), 'clase'=>CLASEEVENTO, 'contenido'=>$contenido, 'indiceDelEvento'=>(count($this->bitacoraTemporal['eventos'])+1)];
        
        array_unshift($this->bitacoraTemporal['eventos'], $evento);        

        $okTemporal = file_put_contents($this->filePathTemporal, json_encode($this->bitacoraTemporal, JSON_PRETTY_PRINT));        
 
        if (!$okTemporal){
            $this->setError("no se realizó el evento");
        }
        
        return $okTemporal;        
    }

    function cerrarBitacora(){
        $evento = ['fin' => true];
        array_push($this->bitacoraTemporal['eventos'], $evento);
        file_put_contents($this->filePathTemporal, json_encode($this->bitacoraTemporal, JSON_PRETTY_PRINT));
    }

}

/*
include_once("./../constantes.php");

$evObj = new Bitacora();

foreach(range(1, 3) as $ind){
    $evObj->registrarEvento("Evento de ejemplo");
}

//$evObj = null;

$a = 5;
*/

?>