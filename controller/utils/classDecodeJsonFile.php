<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

/**
 * DecodeJF  Clase que contiene un método estático "decodeJsonFile($file)" que recibe el nombre de un archivo
 * JSON y regresa su contenido en un arreglo
 */
class DecodeJF {
        
    /**
     * **decodeJsonFile**  
     * Lee un archivo JSON y lo regresa en un arreglo
     * Si el archivo no puede ser regresado como arreglo, devuelve un mensaje un error ['error'] y un mensaje ['mensaje]
     * Si el archivo es decodificado, regresa el contenido en el arreglo
     *```php
     *    static function decodeJsonFile(string $file){
        
     *   $fileInfo=file_get_contents($file);
     *   
     *   $fileVacio=strlen($fileInfo) >0 ? false : true;
     *   
     *   if ($fileVacio){
     *       $arreglo['error']=true;
     *       $arreglo['mensaje']="el archivo " .  $file ."está vacío";
     *   }else{
     *       $arreglo= json_decode($fileInfo, true);
     *       if (is_array($arreglo)){
     *           $arreglo['error']=false; 
     *           return $arreglo;
     *        }else{
     *            $arreglo['error']=true;
     *            $arreglo['mensaje']="el archivo " .  $file ."no tiene formato JSON";                 
     *        }
     *   }
     *   return $arreglo;
     *   
     *}
     *```    
     * 
     * @param  string $file
     * @return array     
     *     
     */
    static function decodeJsonFile(string $file){

        //include_once '../../constantes.php'; //constantes.php  controller\utils\classDecodeJsonFile.php
        include_once __DIR__ . '/../../constantes.php';             
                
        if (file_exists($file)){

            $fileInfo=file_get_contents($file, 1);

            if ($fileInfo){ //El archivo pudo ser leído

                $fileVacio = strlen($fileInfo) >0 ? false : true;

                if ($fileVacio){

                    $error['error']=true;
                    $error['mensaje']="el archivo: " .  $file ." está vacío";
                    return $error;
                    
                }else{

                    $arreglo= json_decode($fileInfo, true);

                    if (is_array($arreglo)){

                        $arreglo['error']=false;
                        return $arreglo;

                    }else{                    
                        $arreglo['error']=true;
                        $arreglo['mensaje']="el archivo " .  $file ." no tiene formato JSON";     
                        return $arreglo;         
                    }
                }         
            }else{
                $error['error']=true;
                $error['mensaje']="El archivo ". $file . " no pudo ser leído correctamente desde la fuente" ;
                return $error;
            }
           
        }else{ //El archivo no existe
            $error['error']=true;
            $error['mensaje']="no se encontró el archivo: " .  $file;
            return $error;
        }        
    }

}

?>