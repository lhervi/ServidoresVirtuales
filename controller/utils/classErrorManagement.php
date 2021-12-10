<?php

/**
 * ErrorManagement
 * Clase para el manejo de errores
 * 
 */
class ErrorManagement {
         
    
    /**
     * error
     * Método estático que regrea un arreglo con los datos del error
     * Si se le suministra la línea del error __LINEA__
     * 
     * @param  bool $error  .............true si hay error y false si no lo hay
     * @param  string $mensaje La .......la descripción del error
     * @param  int $linea  __LINEA__ ....la línea donde se origino el error
     * @param  string $Ruta es la ruta del archivo donde se originó el error
     * @param  bool $bitacora Si es verdadero, se guarda la salida del error en un archivo que se llama "Error.json"
     * @return array ('error'=>$error, 'mensaje'=>$mensaje, 'linea'=>$linea)
     */
    static function error(bool $error=true, string $mensaje="", int $linea=-1, string $ruta="", bool $bitacora=false){
        
        $resp = array('error'=>$error, 'mensaje'=>$mensaje, 'linea'=>$linea, 'ruta'=>$ruta);
        
        if ($bitacora){
            file_put_contents(ERRORES, json_encode($resp).PHP_EOL); //Regsitra el error en formato json con salto de linea
        }
        
        return $resp;

    }

}


?>