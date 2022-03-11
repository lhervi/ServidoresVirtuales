<?php

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

include_once __DIR__."/../constantes.php";

include_once HOME.'/mostrarErr.php';

class Curl {
    
    static function curlSetOpt($curl, $param=[]){      
        
        //EVALUAR si cURL está instalado
        
        $arch = fopen($param['arch'] , "w") or exit ("no se pudo abrir el archivo que almacenará el token");
        
        //echo "el parámetro recibido en el Curl es: ";              

        curl_setopt($curl, CURLOPT_HTTPHEADER, $param['header']);                   //1
        curl_setopt($curl, CURLOPT_PROXY, $param['proxy']);                         //2
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                         //3
        curl_setopt($curl, CURLOPT_URL, $param['url']);                             //4
        curl_setopt($curl, CURLOPT_PROXYUSERPWD, $param['userproxy']);              //5
                 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                           //6
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);                           //7
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);                           //8
        curl_setopt($curl, CURLOPT_CAINFO, $param['certfirefox']);                  //9
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);                            //10
        if ($param['GET']){ 
            curl_setopt($curl, CURLOPT_HTTPGET, 1);                                 //11
        }else{
            curl_setopt($curl, CURLOPT_POST, 1);                                    //12
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param['campos']);               //13
        }
        curl_setopt($curl, CURLOPT_FILE, $arch);                                    //14   
    }      
        
    /**
     * execCurl
     * Método estático que ejecuta un cURL. Recibe 2 parámetros: un token y un tipo de consulta y regresa un arreglo con 
     * el resultado. 
     * Si todo está bien, regresa en el arreglo: *['arch'] con el nombre del archivo, *['error'] = false y *['result'] = true;
     * Si algo salión mal, regresa: *['error'] = true y *['mensaje'] con la información del error
     * 
     *
     * @param  string $token - El valor del string que regresenta el token válido
     * @param  string $tipo  - El tipo de consulta a realizar, determina el url y su tipo de conexión, y el archivo de salida
     * @return array
     */
    
    static function execParamCurl($param){
        include_once '../controller/utils/classErrors.php';       

        $curl = curl_init();
        self::curlSetOpt($curl, $param);   //configura el Curl
        //Curl::curlSetOpt($curl, $param); //configura el Curl    
        $res = curl_exec($curl);
        //================================================
        $resultCurl['error'] = $res === true ? false : true;      
        //=================================================

        curl_close($curl);
        if($resultCurl['error']){            
            $mensaje = "hubo un problema al ejecutar el curl la consulta al servidor no fue exitosa ";
            $resultCurl['mensaje'] = $mensaje;            
            RegistError::logError($mensaje, __FILE__, __LINE__, 2);            
            return $resultCurl;
        }else{
            $resultCurl['mensaje'] = "todo bien";
        }
        
    }

    static function getCamposJson(array $arrayCampos){

        include_once '../controller/utils/classValidaciones.php';
        // -------------------   Aquí se preparan los campos para la consulta  -----------------------
        $result = Validaciones::camposNulos($arrayCampos);
        
        if($result['error']){
            echo "Error en la linea " . __LINE__ . " en: " . __FILE__ . "<br/><br/>";
            return $result;
        }else{
            $campos['error']=false;
            $begin = $arrayCampos["begin"];
            $end = $arrayCampos["end"];
            $intervalType = $arrayCampos["intervalType"];
            $intervalQuantifier = $arrayCampos["intervalQuantifier"];
            $rollUpType = $arrayCampos["rollUpType"];
            $resourceId = $arrayCampos["resourceId"];
            $statKey = $arrayCampos["statKey"];
                            
            $campos['campos'] = '{"begin":' . $begin . ',';
            $campos['campos'] .= '"end":' . $end . ',';
            $campos['campos'] .= '"intervalType":"' . $intervalType . '",';
            $campos['campos'] .= '"intervalQuantifier":' . $intervalQuantifier . ',';
            $campos['campos'] .= '"rollUpType":"' . $rollUpType . '",';
            $campos['campos'] .= '"resourceId":' . json_encode($resourceId) . ',';
            $campos['campos'] .= '"statKey":' . json_encode($statKey)  . '}';
                // -------------------------------  campos preparados ---------------------------------------
            return $campos;
        }
    }
        
    /**
     * execCurl
     *
     * @param  string $token
     * @param  string $tipo
     * @param  string $campo=null
     * @param  array $camposArray=null
     * @param  string $resourceKinds=null
     * @return array
     */

   
    //------------------------------------------------------ INICIO execCurlTipoMediciones  --------------------------------

    
    
    static function retomarProcesamiento(){

        include_once '../controller/utils/classDecodeJsonFile.php';

        $bitacora = DecodeJF::decodeJsonFile(HOME.SALIDAS."bitacoraStat.json");
        
        if ($bitacora['error']){
            echo "Error en la linea " . __LINE__ . " en: " . __FILE__ . "<br/><br/>";
            die("no se pudo acceder a la bitácora ".HOME.SALIDAS."bitacoraStat.json");
        }else{            
            if ($bitacora['numDeParametros']<$bitacora['paramProcesados']){
                $consultasArray=DecodeJF::decodeJsonFile(HOME.SALIDAS."consultas.json");
                if ($consultasArray['error']){
                    echo "Error en la linea " . __LINE__ . " en: " . __FILE__ . "<br/><br/>";
                    die("no puedo realizarse la operación de recuperación del procesamiento de datos");
                }else{
                    $resourceKinds = $consultasArray['resourceKinds'];
                    $desde = $bitacora['IndiceDelError'];                    
                    $inicio = 0; 
                    $paramArray=$consultasArray['paramArray'];

                    foreach($consultasArray['paramArray'] as $ind =>$param){
                        
                        if ($inicio < $desde) {  //avanza hasta el lugar del último param que se ejecutó sin error
                            $inicio++;
                            continue;
                        }
                        $nuevoParamArray['$ind']=$param;
                    }
                    self::execCurlTipoMediciones($nuevoParamArray, $resourceKinds);
                }
            }
        }
    }

    //----------------------------------------------------------------------------------------------------
    // === PENDIENTE AQUI ===
    static function execCurlTipoMediciones($paramArray, $resourceKinds){      
        
        include_once "../controller/utils/classUtils.php";        
        
        $error['error'] = false;
        $error['mensaje'] = "";
        $resultCurl['mensaje'] = "";       
        
        $bitacora['resourceKinds']=$resourceKinds;
        $bitacora['numDeParametros']=count($paramArray);
        
        foreach($paramArray as $ind=>$param){
            $resultCurl['error']=self::execParamCurl($param);
            if($resultCurl['error']){
                echo "Error en la linea " . __LINE__ . " en: " . __FILE__ . "<br/><br/>";
                $error['error'] = true;
                $error['mensaje'] .= "hubo un error al procesar " . $param['arch'] . PHP_EOL;
                $bitacora['IndiceDelerror']=$ind;
                $result=Utils::chequearConexion(HOSTVROPS);
                
                if($result['result']==false){
                    die("se perdió la conexión con el servidor vmware");                                        
                }
                return $error;
                
            }else{                                                       
                
                $bitacora['paramProcesados']=$ind;
                $resultCurl['mensaje'] .= "archivo " . $param['arch'] . "procesado" . PHP_EOL;
                $listaArchStats[] = $param['arch'];
            }
        }   

        $archEstado = HOME.SALIDAS."bitacoraStat.json";   
        //--------------------------------------------------------------------------------
        //Tiene la información del número de parámetros a procesar por resourceKinds

        file_put_contents($archEstado, json_encode($bitacora), FILE_APPEND); 
        //--------------------------------------------------------------------------------
        
        $archivoDeSalida = HOME.SALIDAS."listaArchStats".$resourceKinds.".json";
        file_put_contents($archivoDeSalida, json_encode($listaArchStats));
        
        $resultCurl['listaArchStats'] = $listaArchStats;
        $resultCurl['error'] = false;
        $resultCurl['mensaje'] .= "se completó el proceso correctamente " . PHP_EOL;
        return $resultCurl;        
        
    }

    //------------------------------------------------------ FIN execCurlTipoMediciones  --------------------------------

    //------------------------------------------------------ INICIO execCurl  -------------------------------------------

    static function prepareExecCurl(string $token, string $tipo, array $camposArray=null, $resourceKinds=null, $vropsServer=null){
        
        include_once '../vROps/classVropsConf.php';
        $error['error'] = "";
        $error['mensaje'] = "";

        $objConf = new VropsConf($tipo, $resourceKinds, $vropsServer);
        if($objConf->getError()){
            $error['error'] = true;
            $error['mensaje'] = "no se obtuvo la lista de recursos por problemas para acceder al archivo de configiración";
            return $error;
        }else{
            
            $objConf->setToken($token);
            $param = $objConf->getParam();
            $resultCurl['mensaje']="";
           
            $ind=0;
            $porciones=$camposArray['porciones'];
            unset($porciones['error']);                 //esto es para dejar sólo los arreglos
            unset($porciones['arrayIdsFileName']);      //esto es para dejar sólo los arreglos                
                            
            foreach($porciones as $name=>$resourceId){ //name es el nombre de cada porcion                    
                
                $arrayCampos = array('begin'=>$camposArray['begin'], 'end'=>$camposArray['end'],'intervalType'=>$camposArray['intervalType'],'intervalQuantifier'=>$camposArray['intervalQuantifier'],'rollUpType'=>$camposArray['rollUpType'], 'resourceId'=>$resourceId,'statKey'=>$camposArray['statKey']);

                $camposResult[$ind] =  self::getCamposJson($arrayCampos);  //arreglo de valores "$campo" en formato json
                if ($camposResult[$ind]['error']){  
                    $error['error'] = true;
                    $error['mensaje'] = "los parámetros suministrados para la consulta son incorrectos ";
                    return $error;
                }else{                                                //Si no hay error se crea el arreglo de los nombres de los archivos de salida de las estadísticas
                    
                    $nonArchSalida = HOME.STATS."stats" . $name;      //crea el nombre del archivo de salida de las estadísticas para el curl                       
                    
            
                    $param['arch'] = $nonArchSalida;                  //asigna el nombre de cada archivo de salida con las estadísticas para el curl                                           
                    $param['campos'] = $camposResult[$ind]['campos']; //asigna los campos en formato json a param
                    $nonArchSalidaArray[] = $nonArchSalida;             //Arreglo para registrar todos los nombres de los archivos de salida
                    $nombreArchSalidaJson = '{"nombreArchSalida":"' . $nonArchSalida . '", "resourceKinds":"' . $resourceKinds . '"}';
                    //file_put_contents(HOME.SALIDAS."statsAllFileList.txt", $nonArchSalida . PHP_EOL, FILE_APPEND); ==[ELIMINAR]
                    file_put_contents(HOME.SALIDAS."statsAllJsonFileList.txt",$nombreArchSalidaJson . PHP_EOL, FILE_APPEND);
                    $paramArray[$ind] = $param;                   //asigna este param al arreglo de parámetros
                    $ind++;
                }             
            }
            

            if(!is_null($paramArray)){
                //---------------------------- información de consulta ---------------------
                
               
                $fecha = Fechas::fechaHoy("completa", "-");

                $infConsulta['resourceKinds'] = $resourceKinds;
                $infConsulta['created'] = $fecha;
                $infConsulta['numeroDePorciones'] = count($porciones); 
                $infConsulta['begin'] = $camposArray['begin'];
                $infConsulta['end'] = $camposArray['end'];                    
                $infConsulta['paramArray'] = $paramArray;
                $infConsulta['vropsServer'] = $vropsServer;

                //---------------------------- información de los archivos de salida ----------
                
                $statsArchInfo['vropsServer']=$vropsServer;
                $statsArchInfo['resourceKinds']=$resourceKinds;
                $statsArchInfo['created']=$fecha;
                $statsArchInfo['begin']=$camposArray['begin'];
                $statsArchInfo['end']=$camposArray['end'];
                $statsArchInfo['listaDeArchivosDeStats']=$nonArchSalidaArray;               

                $resultFile=file_put_contents(HOME.SALIDAS."consultas.json", json_encode($infConsulta).PHP_EOL, FILE_APPEND);
                
                if ($resultFile===false){
                    $error['error'] = true;
                    $error['mensaje'] = "ocurrió un error al crear el archivo de consultas.json";
                    return $error;
                }

                file_put_contents(HOME.SALIDAS."statsList.json", json_encode($statsArchInfo).PHP_EOL, FILE_APPEND);
               
                
                if ($resultFile===false){ 
                    $error['error'] = true;
                    $error['mensaje'] = "ocurrió un error al crear el archivo de statsList.json";
                    return $error;
                }

                // $resultCurl= self::execCurlTipoMediciones($paramArray, $resourceKinds);
                // La salida es un mensaje de error =  false 
                // el procesamiento de las consultas se hace en otro módulo
            }
            //------------------------------ SALIDA -----------------------------// 
            $resultCurl['error'] = false;
            return $resultCurl;                        // --- tipoMediciones --- //
            //------------------------------ SALIDA -----------------------------//    
        }
    }


    static function execCurl(string $token, string $tipo, string $campo=null, array $camposArray=null, $resourceKinds=null){
        
        include_once '../vROps/classVropsConf.php';
        $error['error'] = "";
        $error['mensaje'] = "";
        
        $objConf = new VropsConf($tipo, $resourceKinds);
        if($objConf->getError()){
            $error['error'] = true;
            $error['mensaje'] = "no se obtuvo la lista de recursos por problemas para acceder al archivo de configiración";
            return $error;
        }else{
            
            $objConf->setToken($token);
            $param = $objConf->getParam();
            $resultCurl['mensaje']="";        
            
            //------------------------------ ENTRADA tipoMediciones----------------//

            if ($tipo=="tipoMediciones" && !is_null($camposArray)){
            
                $resultCurl = self::prepareExecCurl($token, $tipo, null, $camposArray, $resourceKinds);
                return $resultCurl;
        
            }elseif(!is_null($campo) && $tipo=="tipoVmwareToken"){    // --- Caso cuando no es tipo tipoVmwareToken
                    $param['campos'] = $campo;
                    $resultCurl['error']=self::execParamCurl($param);
                    if($resultCurl['error']){
                        $error['error'] = true;
                        $error['mensaje'] .= "no hubo contacto con Vrops para obtener el Token ";
                        return $error;             
                    }else{                                                                           
                        $resultCurl['mensaje'] .= "se obtuvo un token" . PHP_EOL;
                        return $resultCurl;
                    }                
            }else{
                    $resultCurl['error']=self::execParamCurl($param);
                    if($resultCurl['error']){
                        $error['error'] = true;
                        $error['mensaje'] = "hubo problemas para conectar con la API de Vrops";
                        return $error;             
                    }else{                                                                           
                        $resultCurl['mensaje'] .= "todo en orden" . PHP_EOL;
                        $resultCurl['arch']=$objConf->getNomArch();
                        return $resultCurl;
                    }                                
            }         
        }
    }

}

?>