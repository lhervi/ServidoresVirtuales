<?php

/**
 * CargarStatsVrops
 * Clase con métodos estáticos para pasar las estadísticas de los tipos de recursos desde los archivos json a la base de datos
 */
class CargarStatsVrops {    
    
    static public $proceso = array();
    static int $contadorInsertar=1000;
    //Lee el listado de archivos y los convierte uno a uno a arreglo
    //Para cada arreglo de estadísticas, crea un objeto que regresa un arreglo de string
    //Cada string del arreglo, son los 1000 registros (configurable) a ser insertados en la BD
    //El número de registros a insertar se lee desde vropsConfigDB.php

    /*
    static function comprobar(array $muestra){
        
        $consulta = "select * from vm_waremetricas where recursos_id in ('";
        $tope=0;
        
        foreach ($muestra as $reg){                
            $valores = implode("', '", $muesta);
        }
            
        $consulta .= $valores . "');";

    }
    */
    
    /**
     * insertStats
     *
     * @param  array $arrayStats arreglo de estadísticas a insertar en formato string
     * @return array 'error'= false si todo salió bien, false en caso contrario más, 
     * un mensaje 'mensaje' con la descripción del error
     */
    static function insertStats(array $arrayStats){
        include_once 'classVropsConnection.php';
        
        $insertStr = "INSERT INTO vmware_metricas (recursos_id, fecha, metrica, valor, resourcekinds, servidor)";
        $insertStr .= " VALUES ";      

        $insertStr .= implode(", ", $arrayStats);


        $result = VropsConexion::insertar($insertStr);
        
        if($result){
            $error['error'] = false;
            return $error;
        }else{
            $error['error'] = true;
            $error['mensaje'] = "hubo un error al insertar los registros en la BD";
            return $error;
        }
    }


    static function procesarValores(array $valores, string $resourceId, array $fecha, string $metrica, $valor, string $resourceKinds, string $vropsServer){
        //LLena un arreglo y para cuando hay mil registros
        
        $arrayStats = array(); //se inicializa el arreglo donde se vaciarán los valores a insertar
        $numReg=0; //Inicializa el contador de registros que controla el máximo número de valores a insertar (1000 registros)

        foreach($valores as $ind=>$valor){  //se varían los valores de cada metrica 
                    
            //$a = $indStat; //Comparar con $numreg [PROVISIONAL][ELIMINAR]
                        //En caso de que coincida con $numReg, sustituir

            //$arrayStats[$numReg]="('". $resourceId . "', '" . Fechas::getDatefromMiliSeconds($fecha[$ind]) . "', '" . $metrica . "', '". strval($valor) . "', '" . $resourceKinds . "', '" . $vropsServer . "')";                         
            $arrayStats[]="('". $resourceId . "', '" . Fechas::getDatefromMiliSeconds($fecha[$ind]) . "', '" . $metrica . "', '". strval($valor) . "', '" . $resourceKinds . "', '" . $vropsServer . "')";                         

            $numReg++;          //Si numReg > 1000 hay que ejecutar el insert en la BD                    

            if ($numReg>1000){  //Cambiar por una constante               
                //====================================================
                $resultInsert = self::insertStats($arrayStats);  //llama al proceso de inserción de registros pasando el arreglo con los 1000 datos
                //====================================================
                
                if ($resultInsert['error']){
                    return $resultInsert;
                }
                
                self::$proceso['registros']++;
                $numReg = 0; //Inicializa el contador
                $arrayStats = null;                   //inicializa el arreglo                        
            }
        }

        //cuando sale del foreach es que ya se terminaron los valores, pero... pueden haber remanentes, por eso lo siguiente:
        //Si el número de resgistros es mayor a 0 pero menor a mil, hay que insertar esos registros
        if ($numReg>0 && !is_null($arrayStats)){
            $resultInsert = self::insertStats($arrayStats);           
            $arrayStats = null;   //Esta inicialización no estaba antes del 17/3/2020 18:18       
            $numReg = 0;
        }

        return $resultInsert;  //Esta instrucción se ejecuta siempre
    }
    
    /**
     * procesarArchivo
     *
     * @param  array $statArrayInfValues recibe un arreglo con los valores a ser procesados
     * @return bool false si hubo un error en el proceso
     */
    static function procesarArchivo(array $statArrayInfValues, $resourceKinds){  //todos los archivos   
       
        include_once HOME . '/constantes.php';
        include_once HOME . '/controller/utils/classBitacora.php';
        include_once HOME . '/controller/utils/classFechas.php';
        include_once HOME . '/vROps/classVropsConf.php';
        
        //En el acrchivo de configuración está el nombre del servidor que se está procesando
        $vropsServer = VropsConf::getCampo('vropsServer')['vropsServer'];
        
        self::$proceso['registros']=0;
        
        $numReg=0;                

        if(count($statArrayInfValues)==0) {
            die("no hay archivos que procesar");
        }

        $cantDeStat=0;

        foreach($statArrayInfValues as $statInfo){ //iterando los registros dentro de cada archivo de stats

            Bitacora::avance("Procesando el recurso: " . $statInfo['resourceId']);
            
            $cantDeStat++; //cuenta el numero de registros en total [ELIMINAR???][ELIMINAR]
            
            $resourceId = strval($statInfo['resourceId']);
            self::$proceso['resourceId'][]=$resourceId;  //[ELIMINAR]

            $contadorProv = count($statInfo['stat-list']['stat']); //[PROVISIONAL][ELIMINAR]
            
            foreach($statInfo['stat-list']['stat'] as $indStat=>$regStat){    
                
                $fecha = $regStat['timestamps']; //Arreglo con todas las horas
                $metrica = strval($regStat['statKey']['key']);
                $valores = $regStat['data']; //Arreglo de valores
                
                //======================== 
                $result = self::procesarValores($valores, $resourceId, $fecha, $metrica, $valor, $resourceKinds, $vropsServer);
                //========================
                if ($result['error']){
                    return $result;  //Si ocurre un error, se detine la ejecución del foreach y se regresa el error y el mensaje
                }
            }
            // Aquí se debe preguntar si el arrglo tiene datos, y de tenerlos, insertarlos
        }
        return $result;  //Regresa ['error']= false si no hay error o verdadero si lo hubo
    }
        
    /**
     * procesarLotedeFileStat
     * Método estático para procesar todos los archivos json que contienen estadísticas, tomando como fuente el archivo que contiene 
     * todas las rutas.
     * @return array 'error' true si hubo error y 'mensaje' con la información de lo sucedido. 'error' false si todo salió bien.
     */
    static function procesarLoteDeFileStat(array  $listArchArray){
        //Recibe un arreglo con todas las rutas de estadísticas y procesa una a una
        include_once __DIR__ . '/../../controller/utils/classErrors.php';
        if ($listArchArray){
            self::$proceso['files']=0;
            foreach($listArchArray as $dirFileStats){
                //$listArchArray[n]['nombreArchSalida'] = "nonArchSalida.txt"
                //$listArchArray[n]['resourceKinds'] = "virtualmachine"
                if(isset($dirFileStats['nombreArchSalida'])){
                    $solDirFileStats ??= $dirFileStats['nombreArchSalida'];
                }
                
                try{
                    if(!is_file($solDirFileStats)){
                        $a=5;
                        continue;
                    }                
                    if (isset($dirFileStats['nombreArchSalida'])){
                        $cont = file_get_contents($dirFileStats['nombreArchSalida']);
                    }                  

                }catch(Exception $e){
                    RegistError::logError($e, __FILE__, __LINE__);
                    die($e);
                }
               
                if($cont){
                    $statArrayInfo = json_decode($cont, true); //metricas por archivo
                    if(isset($statArrayInfo['values'])){
                        //if(array_key_exists('values', $statArrayInfo) && count($statArrayInfo['values'])>0){
                            if(count($statArrayInfo['values'])>0){
//===================================================================================================
                                $val ??= $statArrayInfo['values'];
                                $reKind ??= $dirFileStats['resourceKinds'];

                                $result = self::procesarArchivo($val, $reKind);
    //===================================================================================================

                                self::$proceso['files']++;
                                if ($result['error']){                        
                                    $error['error'] = true;
                                    $error['mensaje'] = "no se pudo decodificar el archivo";
                                    return $error;
                                }
                            }                      
                    }
                }                
            }
            $error['error'] = false;
            return $error;
        }else{
            $error['error'] = true;
            $error['mensaje'] = "hubo un error con el listado de archivos a procesar";
            return $error;
        }
    }

    static function listFileToArray(string $file, $archJson){
        $archFileDir=array();
        if(file_exists($file)){
            $fileOpen = fopen($file, "r");
            if($fileOpen){                
                while(!feof($fileOpen)){
                    $arch=fgets($fileOpen);
                    if ($arch!==null){
                        if ($archJson){    
                            $archFileDir[]=json_decode(trim($arch), true);//                            
                            $archFileDir['archJson']=true;
                        }else{
                            $archFileDir['archJson']=false;
                            $archFileDir[]=trim($arch);
                        }                        
                    }                    
                }
                $archFileDir['error'] = false;
                return $archFileDir;
            }
        }else{
            $error['error']=true;
            $error['mensaje']="no se pudo leer el archivo de direcciones";
            return $error;
        }
    }
        
    /**
     * jsonStatsListToFile
     * Método estático que convierte un archivo json a un arreglo y lo guarda en como archivo en una ruta específica
     * para que sea usad posteriormente
     * @return array 'error' false si todo salió bien, y verdadero más un mensaje descriptivo del error 'mensaje' si hubo algún problema
     */
    static function jsonStatsListToFile($archStats, bool $archJson=false){   //crea un archivo con las rutas de los archivos a procesar
        
        //$archStats = HOME.SALIDAS."statsList.json";       
        $resultArch = self::listFileToArray($archStats, $archJson);

        if(array_key_exists('error', $resultArch) &&  !$resultArch['error']) {            
                      
            return $resultArch;
            
        }else{

            $resultArch['error']=true;
            $resultArch['mensaje'] = "no pudo crearse el archivo " . HOME.SALIDAS."listFileStats.json";
            return $resultArch;

        }
    }
        
    /**
     * cargarStats
     * Método estático que procesa todas las estadísticas generadas por todos los tipos de recursos
     * @return array 'error' false si todo salió bien, y verdadero más un mensaje descriptivo del error 'mensaje' si hubo algún problema
     */
    static function cargarStats(){

        include_once 'vropsConfigDB.php';
        include_once 'classVropsConnection.php';

        $objcon = new VropsConexion();

        /*
        PENDIENTE:
            -Generar la fecha del momento e insertarlo en la tabla
            -Result tiene el listado de las estadísticas, con este listado se pueden borrar los archivos json
            -Generar un informe de estadísticas de registros procesados
        */

        //irecursos_id, fecha, metrica, valor, resourcekinds, servidor
        $consultaCreaTabla = "CREATE TABLE IF NOT EXISTS vmware_metricas (id serial, recursos_id VARCHAR NOT NULL, fecha TIMESTAMP NOT NULL, metrica VARCHAR NOT NULL, valor VARCHAR NOT NULL,  resourcekinds VARCHAR NOT NULL, servidor VARCHAR NOT NULL)"; 
        
        $registros = $objcon->insertar($consultaCreaTabla);  //Crea la tabla si no existe

        //--------------------------- PASOS ---------------------------
        //Leer cada archivo json del listado de archivos (16 archivos)
        //Procesar lote de archivos (Envía uno a uno a procesar archivo)
        //Una vez procesado. elimina el json que lo contenía
        
        //Contiene todas las direcciones de los archivos json de estadísticas
        $archStats = HOME.SALIDAS."statsAllFileList.txt";
        $archStatsJson = HOME.SALIDAS."statsAllJsonFileList.txt";

        //$result = self::jsonStatsListToFile($archStats, true); //Regresa un arreglo con los nombres de los archivos
        $result = self::jsonStatsListToFile($archStatsJson, true);

        if ($result['error']){
            die($result['mensaje']);
        }else{
            //unset($result['error']);            
            $error = self::procesarLoteDeFileStat($result); //
            if ($error['error']){
                $error['mensaje'] = "hubo un error en el procesamiento del archivo de estadísticas";
                return $error;                
                //=========== [PENDIENTE] [IMPORTANTE] En este punto debe hacerse un roll back
            }else{                
                
                $result['error']=false;
                $result['mensaje'] = "Culminó con éxito la carga de los registros";
                return $result;
            }
        }

        //----------------------------------------------------------------------------

        echo "<br/>";

        echo "<script>" . PHP_EOL;
        
            echo LookAndFeel::enlace("regresar", INICIO);
        
        echo "<script>" . PHP_EOL;

        echo "<br/>";
        
        echo '<div id="regresar" style="cursor:pointer"><h3> -> Regresar </h3></div>';
        
        //----------------------------------------------------------------------------
        
        file_put_contents(HOME . SALIDAS . "resultProcesJsonToPostgres.json", json_encode(self::$proceso));
       
    }
                
}        

?>