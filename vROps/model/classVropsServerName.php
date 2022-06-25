<?php

class VropsServerName{

    static function getServerNames(){
        
        include_once (__DIR__ . "/../classVropsConf.php");

        $arrayVropsServername = VropsConf::getCampo("vropsServers");
        if($arrayVropsServername["error"]){ //si hay un error regresa tru y la descripción del error
            return $arrayVropsServername;
        }else{
            return $arrayVropsServername["vropsServers"];//si todo va bien regresa el arreglo de los nombres de los servidores
        }   
    }

//Dado un nombre de servidor, regresa el nombre corto

    static function getVropsServerName(string $vropsServerName){
              
        $iniStr = strlen(PREFIJO);
        $finStr = strpos($vropsServerName, SUFIJO);
        $server = substr($vropsServerName, $iniStr ,$finStr-$iniStr);       
        
        return $server;       

    }

    static function getServerName(string $tableName, $mes){

        include_once (__DIR__ . "/../classVropsConf.php");
        $url = VropsConf::getCampo("vropsServer")["vropsServer"];        
        $shortServerName = self::getVropsServerName($url);
        $result = $tableName . "_" . $shortServerName . "_" . $mes; 
        
        return $result;

    }

}

?>