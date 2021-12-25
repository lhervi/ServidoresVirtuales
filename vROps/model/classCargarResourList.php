<?php

class CargarResourceList{

    
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