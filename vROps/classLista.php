<?php

class Lista{

    static function agregarConsulta(array $listaDeConsultas, string $consulta){
        include_once __DIR__ . '/../constantes.php';
        include_once HOME . '/controller/utils/classFechas.php';
        include_once HOME . './vROps/classVropsConf.php';
        //Crear un arreglo con 12 posiciones, en la que en cada una se almacena las últimas 12
        //consultas que se han realizado a un arreglo de consultas
        //El arreglo tiene como clave de cada elemento, el hash de la consulta
        
        $clave = hash("md5", $consulta);
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
            $tamañoLista = count($listaDeConsultas);
            if ($tamañoLista<TAMAÑOLISTACONSULTA){ 
                $listaDeConsultas[$clave]['pos'] = $tamañoLista;
            }else{
                array_shift($listaDeConsultas); //Elimina el primer
                $listaDeConsultas[$clave]['pos'] = $tamañoLista;            
            }
        }
        return json_encode($listaDeConsultas);
    }
   
    static function getLista(){
        include_once __DIR__ . '/../constantes.php';
        include_once HOME . '/controller/utils/classDecodeJsonFile.php';
        if(is_file(LISTADECONSULTAS)){
            $listaArray = DecodeJF::decodeJsonFile($listaJson);
            if ($listaArray['error']){
                return null;
            }else{
                unset($listaArray['error']);
                $listaJson = json_encode($listaArray);
                return $listaJson;
            }        
        }else{
            //Si no encuentra el archivo de consultas, crea uno nuevo            
            file_put_contents(LISTADECONSULTAS,"");
            return null;
        }
    }
}

?>