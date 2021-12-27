<?php

class CargarResourceList{


    function crearResourceListTable(){    
        
        include_once 'classVropsConnection.php';

        $consultaCreaTabla = "CREATE TABLE IF NOT EXISTS vmware_recursos (nombre VARCHAR NOT NULL, ";
        $consultaCreaTabla .= "recursos_id VARCHAR NOT NULL, adapterKindKey VARCHAR NOT NULL, tipo VARCHAR NOT NULL, ";
        $consultaCreaTabla .= "linkToSelf VARCHAR NOT NULL, relationsOfResource VARCHAR NOT NULL, propertiesOfResource VARCHAR NOT NULL,)"; 
        $consultaCreaTabla .= "alertsOfResource VARCHAR NOT NULL, symptomsOfResource VARCHAR NOT NULL, statKeysOfResource VARCHAR NOT NULL,)"; 
        $consultaCreaTabla .= "latestStatsOfResource VARCHAR NOT NULL, latestPropertiesOfResource VARCHAR NOT NULL, credentialsOfResource VARCHAR NOT NULL,)"; 

        $result = VropsConexion::insertar($consultaCreaTabla);       

        return $result;
    }

    function readResourceListArray(string $file){
        $array = DecodeJF::decodeJsonFile($file);
        //$prov = getProv();
        $prov = array();
        $arrayProv = array();
        $prefix = "<a href=http://" . HOSTVROPS; 
        
        
        if($array['error']){
          return $array();
        }else{
            foreach($array as $reg){      
                $prov['name'] = $reg['name'] ?? "";
                $prov['identifier'] = $reg['identifier'] ?? "";
                $prov['adapterKindKey'] = $reg['adapterKindKey'] ?? "";
                $prov['resourceKindKey'] = $reg['resourceKindKey'] ?? "";           
                $prov['linkToSelf'] = $reg['links']['linkToSelf'] ?? "";          
                $prov['relationsOfResource'] = $reg['links']['relationsOfResource'] ?? "";
                $prov['propertiesOfResource'] = $reg['links']['propertiesOfResource'] ?? "";
                $prov['alertsOfResource'] = $reg['links']['alertsOfResource'] ?? "";
                $prov['symptomsOfResource'] = $reg['links']['symptomsOfResource'] ?? "";
                $prov['statKeysOfResource'] = $reg['links']['statKeysOfResource'] ?? "";
                $prov['latestStatsOfResource'] = $reg['links']['latestStatsOfResource'] ?? "";
                $prov['latestPropertiesOfResource'] = $reg['links']['latestPropertiesOfResource'] ?? "";
                $prov['credentialsOfResource'] = $reg['links']['credentialsOfResource'] ?? "";   
      
                $salto=0;
                foreach($prov as $ind=>$campo){
                  if ($salto<4){
                    $salto++;
                    continue;
                  }            
                  $prov[$ind]= $prefix . $campo . ">" . $ind . "</a>";          
                }         
        
                $arrayProv[]=$prov;
                
              }
      
              
          
          return $arrayProv;    
        }
      }
    
    function loadResourceList(string $resourceListFile){ //Recibir el nombre del archivo a cargar
        include_once HOME . "/STISCR/vROps/model/classVropsConnection.php";
        include_once HOME . "/STISCR/controller/utils/classDecodeJsonFile.php";

        $resourceListArray = DecodeJF::decodeJsonFile($resourceListFile);

        if ($resourceListArray['error']){
            die($resourceListArray['mensaje']);
        }else{
           
            $insertStr= "INSERT INTO vmware_recursos (nombre, indentificador, tipo_de_adaptador, recursos_id, ";
            $insertStr .= "linkToSelf, relationsOfResource, propertiesOfResource, alertsOfResource, symptomsOfResource, ";
            $insertStr .= "statKeysOfResource, latestStatsOfResource, latestPropertiesOfResource, credentialsOfResource)";
            $insertStr .= " VALUES ";
            foreach($resourceListArray as $registro){
                $arrayProv[] = $registro['nombre'];    

            }
            
        }
                

        
        //Convertir el archivo en un arreglo
        //Si la tabla no existe en la BD, crearla
        //Recorrer el arreglo
        //Escribir la información en lotes de 1000 registros

    }

}

?>