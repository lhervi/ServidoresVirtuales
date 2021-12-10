<?php


/**
 * Utils
 * Clase que contiene métodos estáticos para diversos usos tales como: chequear si la conexión con un 
 * servidor está activa "chequearConexion($host, $numPaquetes=4)", verificar el sistema operativo en el 
 * que se hizo la instalación "esWindows()"
 * 
 */
class Utils{
    
    /**
     * esWindows
     * Este método estático regresa un bool true si el S.O es windows, y false si no lo es
     * @return bool
     */
    static function esWindows(){
        $result = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? true : false;
        return $result;
    }
        
    /**
     * chequearConexion
     *
     * @param  string $host
     * @param  int $numPaquetes
     * @return array retorna un arreglo con tres inidices: 
     * ['result'] con true si hay conexión, false si no la hay. 
     * ['relacion'] relación de paquetes rechazados 
     * ['mensaje'] mensaje de texto con el resultado
     */
    static function chequearConexion(string $host, int $numPaquetes=4){    

        if ($numPaquetes>10 || $numPaquetes<0){
            $numPaquetes=4;
        }
        
        $parametro = self::esWindows() ? "-n " : "-c ";       
        
        exec("ping ". $parametro . $numPaquetes  . " ". $host, $recibidos, $perdidos);    
       
        $result['result'] = $perdidos==0 ? true : false;        

        $resp = $perdidos==0 ? "Sí " : "No ";

        $result['mensaje']= $resp . "hay conexión con " . $host . PHP_EOL; 
       
        $result['relacion'] = "Paquetes perdidos " . $perdidos . " de " . $numPaquetes . " paquetes enviados" . PHP_EOL;
        
        return $result;
    }

}


?>