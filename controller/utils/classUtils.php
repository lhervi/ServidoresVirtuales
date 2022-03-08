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
    
    /**
     * eraseFiles
     *
     * @param  string $ruta La ruta del directorio de los archivos a eliminar
     * @param  array $lista La lista de rutas de los archivos a evitar
     * @return array $result['eliminados'] el número de archivos eliminados, $result['excluidos'] el número de excluidos
     * 
     * Función estática para eliminar todos los archivos de un directorio, dada una ruta
     * 
     */
    static function eraseFiles(string $ruta, array $lista=null){
        $files = glob($ruta); // get all file names        
        $result['eliminados'] = 0; //cuenta los archivos eliminados
        $result['excluidos'] = 0; //cuenta los archivos excluidos
        $result['rarezas'] = 0; //cuenta lo que no se consideró archivo
        
        foreach($files as $file){ // iterate files  
            if(is_file($file)){                
                if(in_array($file, $lista)){
                    $result['excluidos']++;
                    continue;
                }else{
                    unlink($file);
                    $result['eliminados']++;
                }   
            }else{
                $result['rarezas']++;
                continue;
            }
        }

       return $result; //Regresa el arreglo con el número de archivos eliminados y excluidos        
    }     
       
    /**
     * limpiarDirectorio método estático para eliminar los archivos de un directorio
     *
     * @param  string $dir  la dirección del directorio que será limpiado
     * @param  array $exceptions un arreglo co las direcciones de los archivos a excluir
     * @return void
     */
    static function limpiarDirectorio(string $dir, array $exceptions = array()){
    
        $listado = scandir($dir);    
        unset($listado[array_search('..', $listado, true)]);
        unset($listado[array_search('.', $listado, true)]);    
        
        foreach($listado as $file){
            
            $noExcluido = array_search($file, $exceptions) === false ? true : false;
            
            if($noExcluido){           
                $f = $dir . "/" . $file;
                if (is_dir($f)){
                    continue;
                }else{
                    unlink($f);
                }
                
            }        
        }    
        return true;
    }   

    static function listarDirectorio(string $dir){
   
        $listado = scandir($dir);
        unset($listado[array_search(".", $listado)]);
        unset($listado[array_search("..", $listado)]);
        return $listado;
    }  
}

?>