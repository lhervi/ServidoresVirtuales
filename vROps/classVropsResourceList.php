<?php

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

include_once '../controller/utils/classFechas.php';
include_once '../vROps/classVropsConf.php';


/**
 * VropsResourceList
 * 
 * Esta clase contiene métodos estáticos para gestionar la información de los recursos manejados en vROps
 * 
 * Métodos que contiene: 
 * **getResourceList()**, regresa la lista de los recursos Hostsytem y VirtualMachine
 * **getIds(), regresa sólo el listado de los Ids de Hostsystem y VirtualMachine
 * **getCamposForStats($ini=null, $fin=null, $interval="HOURS", $quaintifier=1, $rollUpType="AVG"), regresa
 * los campos a ser empleados en la consulta de las estadísticas de Hostsystem y VirtualMachine
 * 
 * 
 * 
 */
class VropsResourceList{

        
    static function getVirtualMachineGuest(){
        // Se obtiene el contenido del archivo, y si no hay error, 
            // Se hace la consulta usando la clase classCurl        
            // El resultado se deja en un archivo json para ser procesado porterior mente y convertirlo en una tabla
    }
    
    
    /**
     * creatVirtualmachineIdentifierFile
     *
     * @param  array $virtualmachineIdentifierList
     * @return void
     */
    static function createVirtualmachineIdentifierFile(array $virtualmachineIdentifierList){
        
        $fileName=HOME . SALIDAS . "virtualmachineOnlyIdentifiersList.json";;
        $virtulaMachiIdlist = '{"' . implode('", "', $virtualmachineIdentifierList) . '"}';
        file_put_contents($fileName, $virtulaMachiIdlist);
    }
    
    /**
     * getResourceList
     * Método estático de la clase VropsResourceList que regresa un arreglo que contiene la lista de recursos
     * 
     * en caso de error regresa:
     *      ['error']=true y ['mensjae']="descripción del error"
     *     
     * en caso de éxito regresa:
     *      ['error']=false
     *      ['resourceList'] conteniendo la lista de recursos ampliada:
     *  ['name'], ['identifier'], ['adapterKindKey'], ['resourceKindKey'] de tipo string 
     *  ['links'] que es un arreglo que contiene las claves 'name' => ['href']
     *      *
     * @return array
     */
    static function getResourceList($resourceKinds){ //ahora sabe qué ResourceKind va a usar        

        include_once 'classVropsConf.php';
        include_once 'classCurl.php';
        include_once '../controller/utils/classDecodeJsonFile.php';
        include_once '../controller/utils/classBitacora.php';      
        //virtualmachine --- hostsystem  
        
        $tokenInfo=VropsToken::getToken();

        $server = VropsConf::getCampo('vropsServer')['vropsServer'];

        if ($tokenInfo['error']){     

            $error['mensaje'] = $tokenInfo['mensaje'];
            $error['error'] = true;  
            return $error;

        }else{            

            $newToken = VropsToken::getTokenFromVrops(null, true);

            //$resultCurl = Curl::execCurl($tokenInfo['token'], "tipoResourceKinds", null, null, $resourceKinds);   //Para obtener la lista de recursos                                         
            $resultCurl = Curl::execCurl($newToken, "tipoResourceKinds", null, null, $resourceKinds);   //Para obtener la lista de recursos                                         
            //execCurl(string $token, string $tipo, string $campo=null, array $camposArray=null, $resourceKinds=null)

            if ($resultCurl['error']===true){

                $error['mensaje'] = $resultCurl['mensaje'];
                $error['error'] = true;
                return $error;

            }else{            

                $arch  = $resultCurl['arch'];

                $FileArr = DecodeJF::decodeJsonFile($arch);  //$resultCurl['arch']

                if ($FileArr['error']){

                    $error['mensaje'] = $FileArr['mensaje'];
                    $error['error'] = true; 
                    return $error; 

                }elseif(array_key_exists('resourceList', $FileArr)){
    
                    $resourceListInfo['error']=false;
                    $listaVirtualMachineIdentifiers = "";
                   
                    foreach ($FileArr['resourceList'] as $ind=>$resource){		
                        
                        if ($resource){
                            
                            $rlArray['vropsServer'] = $server;
                            $rlArray['name'] = $resource['resourceKey']['name'];
                            $rlArray['identifier'] = $resource['identifier'];
                            $rlArray['adapterKindKey'] = $resource['resourceKey']['adapterKindKey'];
                            $rlArray['resourceKindKey'] = $resource['resourceKey']['resourceKindKey'];


                            //============ Crear listado de ids de virtualmachine ==================

                            if ($resourceKinds=='virtualMachine'){                                
                                $arrayVirtualmachineIdentifier[] = $resource['identifier'];
                            }

                            //============ Fin de crear listado de ids de virtualmachine ============


                            
                            foreach($resource['links'] as $link){
                                $linksArray[$link['name']] = $link['href'];
                            }
                            
                            $rlArray['links'] = $linksArray;
                            
                            $resourceListArray[$ind]=$rlArray;
                        }
                    }                     
                    
                    //Al finalizar el bucle anterior, en caso de que resourceKinds=="virtualmachine" entonces se crea el archivo con la lista de resourId de virtualmachine
                    if ($resourceKinds=='virtualMachine'){
                        //Se crea el archivo que contiene la lista de identifiers de virtualmachine
                        self::createVirtualmachineIdentifierFile($arrayVirtualmachineIdentifier);

                        //Se obtiene la información de los huéspedes
                        //$guest =;

                        //========================[ OJO  OJO  OJO]==================================
                        // Aquí va el código para crear la lista de virtualmachine
                        // y con ella, contruir la de los huéspedes, 
                        // para convertirla en tabla después
                        //============================================================================


                    }
                    
                    $resourceListInfo['resourceList'] = $resourceListArray;
                    $resourceListInfo['error'] = false;
                    
                    $nomArchivo = HOME . SALIDAS . $resourceKinds . "ResourceListArray.json";
                    // [ELIMINAR] ¿¿eliminar??
                    
                    //Crea el archivo con el nombre acorde al tipo de recurso
                    file_put_contents($nomArchivo, json_encode($resourceListArray)); 
                    
                    $allResourceList = HOME . SALIDAS . ALLRESOURCELIST;
                   
                    $allResourceListProvisional = DecodeJF::decodeJsonFile($allResourceList);
                    if(!$allResourceListProvisional['error']){
                        unset($allResourceListProvisional['error']);                        
                        $resourceListArray = array_merge($resourceListArray, $allResourceListProvisional);
                    } 

                    file_put_contents(HOME . SALIDAS . ALLRESOURCELIST, json_encode($resourceListArray));    

                    //=========================== SALIDA ========================================
                    return $resourceListInfo;  //['resourceList'] y ['error']
                    //=========================== SALIDA ========================================

                }else{
                    $error['mensaje'] = "hay un error en la lista de recursos";
                    $error['error'] = true; 
                    return $error;
                }
            } 
        } 
    }

    //---------------------------------------------------------------------------
    //Método estático que crea la tabla de padres e hijos
    //---------------------------------------------------------------------------

    static function padresEhijos(array $listaDeHijos){

        //URL = https://{{vrops}}/suite-api/api/resources/properties/latest/query
        //Obtener el URL con la función que toma el servidor activo
        //Obtener el resto del URL desde el archivo de configuración
        //Pasarle a la clase curl los datos que se requieren para la consulta
        //Construir $param
        //$param['url'] = $URL;
        //$param['proxy'] = $proxy (leerlo desde el arch de conf)
        //$param['userproxy']
        //$param['certfirefox']
        //$$param['GET']='false' (obtenido del arch de conf)
        //$param['campos'] = (la lista de recursos y )
        //Culr::execParamCurl($param, $ind=0){

        
        return $error;
    }


    //===========================================================================
          
    /**
     * getIds
     * 
     * @param  string   $resourceKinds  
     * @return array    Si tode está bien, regresa  $porcion['error']   $porcion[$fileName]   $porcion['arrayIds']
     */
    static function getIds(string $resourceKinds){ //Recibe los Ids y ahora sabe cuáles regresar
        
        $resourceList = self::getResourceList($resourceKinds);   //Pasa $resourceKinds para que sepa cuáles buscar

        if ($resourceList['error']){

            $error['error'] = true;
            $error['mensaje']="hubo un error para obtener la lista de recursos (Resources Ids)";
            return $error;

        }else{

            foreach($resourceList['resourceList'] as $rlist){            
               
                $resp[] = $rlist['identifier']; //arreglo de identifiers
    
            }

            //============= Nuevo código para extraer los padres de los virtuales =============

            //General: Crear una clase a la que se le pase la lista de identificadores y cree la tabla
            

            //=========== Fin del nuevo código para extraer los padres de los virtuales =======



            file_put_contents(HOME . SALIDAS . $resourceKinds . "ResourceList_Solo_Ids.json", json_encode($resp)); //Eliminar esta instrucción, solo por debugging

            if (empty($resp)){
                $error['error'] = true;
                $error['mensaje']="la lista de recursos está vacía";
                return $error;
            }else{

                $resourceId['error'] = false;
                $resourceId['resourceId'] = $resp; //arreglo de identifiers  //Revisar OJO OJO OJO ------
                
                $cantidadDeResourceId = count($resp);  //Número de Ids
                $segmentosArray= VropsConf::getCampo("segmentos"); //Cantidad de registros a agrupar
                $avance = $segmentosArray['error'] ? SEGMENTOS : $segmentosArray['segmentos'];               

                $nombre = $resourceKinds;
                //$nombre .= Fechas::fechaHoy("completa");
                $correlativo=100;
                $arregloDeNombresdeIds['resourceKinds']=$resourceKinds; //Crea un arreglo con el nombre del contenido de la variable $resourceKinds
               
                for ($i=0; $i<$cantidadDeResourceId; $i+= $avance){
                    //crear un nombre  único para cada archivo
                    $fileName = $nombre.$correlativo.".json"; //asigna un nombre único al grupo de archivos
                    $porcion[$fileName] = array_slice($resp, $i, $avance); //Selecciona la porción de Ids a almacenar                    
                    $arrayIdsFileName[$correlativo]=$fileName; //Crea un arreglo con el nombre del contenido de la variable $resourceKinds
                    $correlativo+=10;
                }
                
                foreach($porcion as $nomArch=>$slice){
                    file_put_contents(HOME.SALIDAS.$nomArch, json_encode($slice)); //Almacena en distintos archivos la porción de Ids en formato json 
                }
                
                file_put_contents(HOME.SALIDAS.$resourceKinds.ARCHIVOSDEIDS, json_encode($arrayIdsFileName)); //Archivo que contiene los nombres de los archivos creados

                $porcion['error'] = false;
                $porcion['arrayIdsFileName'] = $arrayIdsFileName;   
                
                return $porcion;
                
            }         
        }   
    }
    
    /**
     * getCamposForStats
     *
     * @param  string $ini          //un string convertible a fecha. Ejemplo: "28-03-2021"
     * @param  string $fin          //un string convertible a fecha. Ejemplo: "28-03-2021"
     * @param  string $interval por defecto "HOURS"
     * @param  int    $quaintifier int por defecto 1
     * @param  string $rollUpType string por defecto "AVG"
     * @param  string $rollUpType string por defecto "AVG"
     * @return array  bool ['error'] = false (si todo está bien) y string ['camposJson']= con los campos en formato json
     *                array ['camposArray'] todos los campos para la consulta en un array asociativo
     *                bool ['error']= true si ocurrió algún error y string ['mensaje'] con la descripción del mensaje de error
     */
    static function getCamposForStats(string $ini=null, string $fin=null, string $intervalType="HOURS", int $intervalQuantifier=1, string $rollUpType="AVG", string $resourceKinds="virtualmachine"){

        include_once '../controller/utils/classArrayToJson.php';
        include_once '../vROps/classVropsConf.php';
        
        //Contiene el arreglo de parámetros de consulta
        $statKeyArray = VropsConf::getCampo($resourceKinds);  // $resourceKinds=['virtualmachine'|'hostsystem']
 
        if ($statKeyArray['error']){
           
            $error['mensaje'] = $statKeyArray['mensaje'];
            $error['error'] = true;
            return $error;

        }else{
            
            $error['error'] = false;              // ----- statkey OK
            $statKey = $statKeyArray[$resourceKinds];      
                                      
        }

        $begin = is_null($ini) ? Fechas::fechaQuery("ini") : strtotime($ini) * 1000;   // ----- begin  OK

        $end = is_null($fin) ? Fechas::fechaQuery() : strtotime($fin) * 1000;          // ----- end  OK
       
        //-----------------------------------------------------------------------------------------------
        $porciones = self::getIds($resourceKinds);  //Ahora getIds sabe que Ids va a regresar        
        //-----------------------------------------------------------------------------------------------

        if ($porciones['error']){

            //$error['mensaje'] = $resourceIdsArray['mensaje'];
            $error['mensaje'] = $porciones['mensaje']; //porciones
            $error['error'] = true;
            return $error;

        }else{            
            // [$fileName](arreglo de arreglos de slices), ['arrayIdsFileName'] (contiene un arreglo[] de arreglos con los indices de los slices)                                    
            $arrayCampos['campos'] = array('begin'=>$begin, 'end'=>$end,'intervalType'=>$intervalType,'intervalQuantifier'=>$intervalQuantifier,'rollUpType'=>$rollUpType, 'porciones'=>$porciones,'statKey'=>$statKey);
            $arrayCampos['error']=false;
            //-----------------------------------------  SALIDA  -----------------------------------------
            
            return $arrayCampos;   // ['porciones'] ['arrayIds'] ['camposArray'] ['error'] 
            
            //-----------------------------------------  SALIDAS  -----------------------------------------//

        }
    }

}

?>