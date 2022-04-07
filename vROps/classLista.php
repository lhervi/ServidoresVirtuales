<?php

class Lista{

    //static function agregarConsulta(array $listaDeConsultas, string $consulta){
    static function agregarConsulta(array $listaDeConsultas, string $clave){
        include_once __DIR__ . '/../constantes.php';
        include_once HOME . '/controller/utils/classFechas.php';
        include_once './classVropsConf.php';
        //Crear un arreglo con 12 posiciones, en la que en cada una se almacena las últimas 12
        //consultas que se han realizado a un arreglo de consultas
        //El arreglo tiene como clave de cada elemento, el hash de la consulta
        
        //$clave = hash("md5", $consulta);

        if(array_key_exists($clave, $listaDeConsultas)){
            $error['error'] = true;
            $error['mensaje'] = "la consulta ya había sido realizada y el resultado se encuentra en la BD";
            $error['fecha'] = $listaDeConsultas[$clave]['fecha'];
            $error['user'] = $listaDeConsultas[$clave]['user'];
            $error['user'] = $listaDeConsultas[$clave]['pos'];
            return $error;
        }else{
            $listaDeConsultas['error'] = false;
            $listaDeConsultas[$clave]['fecha'] = Fechas::getMilisecondsFromDate();
            $listaDeConsultas[$clave]['verified'] = false; //cambia a true cuando se guarda en la BD
            $listaDeConsultas[$clave]['user'] = VropsConf::getCampo('userBA')['userBA'];
            $tamañoLista = count($listaDeConsultas)-1;
            if ($tamañoLista<TAMAÑOLISTACONSULTA){ 
                $listaDeConsultas[$clave]['pos'] = $tamañoLista;
            }else{
                array_shift($listaDeConsultas); //Elimina el primer
                $listaDeConsultas[$clave]['pos'] = $tamañoLista;            
            }
            return $listaDeConsultas[$clave];
        }        
    }
   
    static function getLista($tipoJson=false){
        include_once __DIR__ . '/../constantes.php';
        include_once HOME . '/controller/utils/classDecodeJsonFile.php';
        if(is_file(LISTADECONSULTAS)){
            $listaArray = DecodeJF::decodeJsonFile(LISTADECONSULTAS);
            if ($listaArray['error']){
                return array();
            }else{
                if($tipoJson){
                    return json_encode($listaArray);
                }else{
                    return $listaArray;
                }
            }    
        }else{
            //Si no encuentra el archivo de consultas, crea uno nuevo            
            file_put_contents(LISTADECONSULTAS,"");            
            return array();
        }
    }

    static function existeEnLista(string $consulta){
        
        $listaArray = self::getLista();   
        
        if(count($listaArray)==0){
            return $result['existe'] = false;
        }
        
        if(array_key_exists('error', $listaArray) && !$listaArray['error']){
            if(array_key_exists($consulta, $listaArray)){
                $result['existe'] = true;
                $result['fecha'] = $listaArray[$consulta]['fecha'];
                $result['user'] = $listaArray[$consulta]['user'];
                return $result;
            }else{
                $result['existe'] = false;
            }
        }      
        $result['existe'] = false;
    }

}// fin de la clase

?>