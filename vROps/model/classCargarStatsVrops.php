<?php

/**
 * CargarStatsVrops
 * Clase con métodos estáticos para pasar las estadísticas de los tipos de recursos desde los archivos json a la base de datos
 */
class CargarStatsVrops {    
    
    static public $proceso = array();
    static int $contadorInsertar=1000;
    static int $contadorArchivos =0; //[ELIMINAR]
    static int $contadorResourceKinds =0; //[ELIMINAR]
    static int $contadorInsert =0; //[ELIMINAR]
    //Lee el listado de archivos y los convierte uno a uno a arreglo
    //Para cada arreglo de estadísticas, crea un objeto que regresa un arreglo de string
    //Cada string del arreglo, son los 1000 registros (configurable) a ser insertados en la BD
    //El número de registros a insertar se lee desde vropsConfigDB.php

    function dropTable(string $tableName){
       
        include_once 'classVropsConnection.php';
                
        $dropQuery = 'DROP TABLE IF EXISTS "' . $tableName . '"';
        $result = VropsConexion::insertar($dropQuery);

        return $result;

    }

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
    static function insertStats(array $arrayStats, string $nombreTabla){
        include_once 'classVropsConnection.php';
        
        //Añadir el nombre de la tabla 
        $insertStr = "INSERT INTO " . $nombreTabla ." (recursos_id, fecha, metrica, valor, resourcekinds, servidor)";
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

    //===================================================================================================


    static function procesarValores(array $valores, string $resourceId, array $fecha, string $metrica, string $resourceKinds, string $vropsServer, string $nombreTabla){
        //LLena un arreglo y para cuando hay mil registros
        
        $arrayStats = array(); //se inicializa el arreglo donde se vaciarán los valores a insertar
        $numReg=0; //Inicializa el contador de registros que controla el máximo número de valores a insertar (1000 registros)

        foreach($valores as $ind=>$valor){  //se varían los valores de cada metrica 
            
            $fecMili=$fecha[$ind];
            $fec = Fechas::getDatefromMiliSeconds($fecMili);

            $strinInsert = "('". $resourceId . "', '" . $fec . "', '" . $metrica . "', '". strval($valor) . "', '" . $resourceKinds . "', '" . $vropsServer . "')";            
            $arrayStats[] = $strinInsert;

            $numReg++;          //Si numReg > 1000 hay que ejecutar el insert en la BD                    

            if ($numReg>1000){  //Cambiar por una constante    
                //====================================================
                $resultInsert = self::insertStats($arrayStats, $nombreTabla);  //llama al proceso de inserción de registros pasando el arreglo con los 1000 datos
                //====================================================
                if ($resultInsert['error']){ //Si ocurre un error, se sale del proceso
                    return $resultInsert;
                }
                $numReg = 0; //Inicializa el contador
                $arrayStats = null;                   //inicializa el arreglo                        
            }
        }

        //cuando sale del foreach es que ya se terminaron los valores, pero... pueden haber remanentes, por eso lo siguiente:
        //Si el número de resgistros es mayor a 0 pero menor a mil, hay que insertar esos registros
        if ($numReg>0 && !is_null($arrayStats)){
            $resultInsert = self::insertStats($arrayStats, $nombreTabla);           
            $arrayStats = null;   //Esta inicialización no estaba antes del 17/3/2020 18:18       
            $numReg = 0;
        }

        return $resultInsert;  //Esta instrucción se ejecuta siempre
    }

    //===================================================================================================
    
    /**
     * procesarArchivo
     *
     * @param  array $statArrayInfValues recibe un arreglo con los valores a ser procesados
     * @return bool false si hubo un error en el proceso
     */
    static function procesarArchivo(array $statArrayInfValues, string $resourceKinds, string $arch, string $nombreTabla){  //todos los archivos   
       
        include_once HOME . '/constantes.php';
        include_once HOME . '/controller/utils/classBitacora.php';
        include_once HOME . '/controller/utils/classFechas.php';
        include_once HOME . '/vROps/classVropsConf.php';
        
        //En el acrchivo de configuración está el nombre del servidor que se está procesando
        $vropsServer = VropsConf::getCampo('vropsServer')['vropsServer'];
        
        //$numReg=0;                

        if(count($statArrayInfValues)==0) {
            die("no hay archivos que procesar");
        }

        self::$contadorResourceKinds=0; //[ELIMINAR][PROVISIONAL]

        foreach($statArrayInfValues as $ind=>$statInfo){ //iterando los registros dentro de cada archivo de stats  //[ELIMINAR las claves [valor] y [valores]]
    
            $resourceId = strval($statInfo['resourceId']);           
            
            foreach($statInfo['stat-list']['stat'] as $indStat=>$regStat){  
               
                $fecha = $regStat['timestamps']; //Arreglo con todas las horas
                $metrica = strval($regStat['statKey']['key']);
                $valores = $regStat['data']; //Arreglo de valores
                
                //========================
                $result = self::procesarValores($valores, $resourceId, $fecha, $metrica, $resourceKinds, $vropsServer, $nombreTabla);
                //========================
                if ($result['error']){
                    return $result;  //Si ocurre un error, se detine la ejecución del foreach y se regresa el error y el mensaje
                }
            }
            // Aquí se debe preguntar si el arrglo tiene datos, y de tenerlos, insertarlos
        }
        return $result;  //Regresa ['error']= false si no hay error o verdadero si lo hubo
    }

    //===================================================================================================
        
    /**
     * procesarLotedeFileStat
     * Método estático para procesar todos los archivos json que contienen estadísticas, tomando como fuente el archivo que contiene 
     * todas las rutas.
     * @return array 'error' true si hubo error y 'mensaje' con la información de lo sucedido. 'error' false si todo salió bien.
     */
    static function procesarLoteDeFileStat(array  $listArchArray, string $nombreTabla){
        //Recibe un arreglo con todas las rutas de estadísticas y procesa una a una
        include_once __DIR__ . '/../../controller/utils/classErrors.php';

        self::$contadorArchivos=0;

        foreach($listArchArray as $indArch =>$dirFileStats){ 
            
            try{
                if(isset($dirFileStats['nombreArchSalida'])){
                    $solDirFileStats = $dirFileStats['nombreArchSalida']; 
                    $cont = file_get_contents($solDirFileStats); //Cont debe tener la información de un archivo de estadística                           
                    //----------------------------
                    if($cont){
                        $statArrayInfo = json_decode($cont, true); //métricas por archivo [???][VERIFICAR]
                    }
                }else{
                    $error['error'] = true;
                    $error['mensaje']= "hay un problema con el archivo de estadísticas";
                }                   
                
            }catch(Exception $e){
                RegistError::logError($e, __FILE__, __LINE__);
                die($e);
            }            
            
            if(isset($statArrayInfo['values']) && count($statArrayInfo['values'])>0){                        
                $val = $statArrayInfo['values'];
                $reKind = $dirFileStats['resourceKinds'];
                $arch = $dirFileStats['nombreArchSalida'];
//===================================================================================================
                $result = self::procesarArchivo($val, $reKind, $arch, $nombreTabla);
//===================================================================================================
                if ($result['error']){      //Si hubiera un error se sale sin continuar                  
                    $error['error'] = true;
                    $error['mensaje'] = "no se pudo decodificar el archivo";
                    return $error;
                }                                                  
            }
                         
        }

        $error['error'] = false;
        return $error;
        
    }

    //===================================================================================================

    static function listFileToArray(string $archStats){        
        if(file_exists($archStats)){
            $fileOpen = fopen($archStats, "r");
            if($fileOpen){                
                while(!feof($fileOpen)){
                    $linea=fgets($fileOpen);
                    if ($linea!==null && $linea!=""){                            
                        $archFileDir[]=json_decode(trim($linea), true);//   
                    }           
                }                    
            }
            $archFileDir['error'] = false;
            return $archFileDir;
        }else{
            $error['error']=true;
            $error['mensaje']="no se pudo leer el archivo de direcciones";
            return $error;
        }
    }

    //===================================================================================================

    /**
     * jsonStatsListToFile
     * Método estático que convierte un archivo json a un arreglo y lo guarda en como archivo en una ruta específica
     * para que sea usad posteriormente
     * @return array 'error' false si todo salió bien, y verdadero más un mensaje descriptivo del error 'mensaje' si hubo algún problema
     */
    static function jsonStatsListToFile($archStats){   //crea un archivo con las rutas de los archivos a procesar
        
        //$archStats = HOME.SALIDAS."statsList.json";       
        $resultArch = self::listFileToArray($archStats);

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
    static function cargarStats(string $numMesTabla){

        include_once 'vropsConfigDB.php';
        include_once 'classVropsConnection.php';

        include_once (__DIR__ . "/../model/classVropsServerName.php");
        include_once (__DIR__ . "/../classVropsConf.php");
        
        $nombreTabla = VropsServerName::getServerName("vmware_metricas", $numMesTabla);

        $objcon = new VropsConexion();        

        //-------- Crear la tabla dependiendo del mes [PENDIENTE] ------------
        
        //$nombreTabla= "vmware_metricas_" . $numMesTabla; //$numMesTabla = '01' | '02' | ... '12'

        self::dropTable($nombreTabla); //Elimina la tabla si existe
        
        $consultaCreaTabla = "CREATE TABLE IF NOT EXISTS " . $nombreTabla . " (id serial, recursos_id VARCHAR NOT NULL, fecha TIMESTAMP NOT NULL, metrica VARCHAR NOT NULL, valor VARCHAR NOT NULL,  resourcekinds VARCHAR NOT NULL, servidor VARCHAR NOT NULL, PRIMARY KEY(recursos_id, fecha, metrica))"; 
        
        $registros = $objcon->insertar($consultaCreaTabla);  //Crea la tabla si no existe
                
        //$archStats = HOME.SALIDAS."statsAllFileList.txt";
        $archStatsJson = HOME.SALIDAS."statsAllJsonFileList.txt";

        //$result = self::jsonStatsListToFile($archStats, true); //Regresa un arreglo con los nombres de los archivos
        $result = self::jsonStatsListToFile($archStatsJson, true);  
        //[ELIMINAR] Verificar que $result contenga la lista de nombre de archivos

        if ($result['error']){
            die($result['mensaje']);
        }else{
            if (isset($result['error'])) unset($result['error']);     
    //----------------------------------------------------------------                    
            $error = self::procesarLoteDeFileStat($result, $nombreTabla);
    //----------------------------------------------------------------
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
        
            echo LookAndFeel::enlace("regresar", MENUINICIO);
        
        echo "<script>" . PHP_EOL;

        echo "<br/>";
        
        echo '<div id="regresar" style="cursor:pointer"><h3> -> Regresar </h3></div>';
        
        //----------------------------------------------------------------------------
       
    }
                
}        

?>