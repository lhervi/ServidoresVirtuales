    <?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '-1');
    ini_set('display_errors', 1);

    include_once '../constantes.php';
    include_once '../view/encabezado.php';
    include_once 'classVropsToken.php';
    include_once './classVropsConf.php';
    include_once 'classVropsResourceList.php';
    include_once '../controller/utils/classFechas.php';
    include_once '../vROps/classCurl.php';    
    include_once 'model/classCargarStatsVrops.php';
    include_once './model/classCargarResourceList.php';
    ?>
    <body class="m-0 vh-100 row justify-content-start align-items-center">
        <div class="container col-auto">

                <?php

                $server = VropsConf::getCampo('vropsServer')['vropsServer'];

                    // -----------------------------------------------  S A L T O  --------------------------------------
                    //============ Procesamiento de entradas ================//
                    //Revisar entradas ======== I N C L U I R ========
                    
                    if(isset($_POST["mesConsulta"])){
                       
                        $begin = $_POST["mesConsulta"] . "-01";
                        $end = $_POST["mesConsulta"] . "-" . Fechas::lastDay($_POST["mesConsulta"]);

                    }
                 
                    $intervalType = array_key_exists("intervalType", $_POST) ? $_POST["intervalType"] : "HOURS";
                    $intervalQuantifier = array_key_exists("intervalQuantifier", $_POST) ? intval($_POST["intervalQuantifier"]) : 1;
                    $rollUpType = array_key_exists("rollUpType", $_POST) ? $_POST["rollUpType"] : "AVG";

                    foreach($_POST as $ind=>$val){
                        if("resourceKinds"==$val){
                            $resourceKindsArray[] = $ind;
                        }
                    }
                    
                    $resourceKinds = array_key_exists("resourceKinds", $_POST) ? $_POST["resourceKinds"] : "virtualmachine";

                    //============ Fin de Procesamiento de entradas ================//

                    //------------------------------------------------------------------------------------------------------------

                    //============ Obtención de token de acceso ================//
                    
                    $tokenInfo=VropsToken::getToken();

                    if ($tokenInfo['error']){
                        die('{"error":true, "mensaje":' . $tokenInfo["mensaje"] . '}');  //Se detiene el programa en caso de que ocurra un error al obtener el token
                    }else{
                        $token = $tokenInfo['token'];
                        $_SESSION['tokenOk']=true;
                    }

                    //============ Fin de obtención de token de acceso ================//
                    //-------------------------------------------------------------------
                   

                    file_put_contents(HOME.SALIDAS.'indiceDeConsultas.json', '{"indiceDeConsultas":0}');


                    foreach($resourceKindsArray as $resourceKinds){
                                
                        $camposForStats = VropsResourceList::getCamposForStats($begin, $end, $intervalType, $intervalQuantifier, $rollUpType, $resourceKinds);  //además crea el archivo campos.json

                        if ($camposForStats['error']){
                            die($camposForStats['mensaje']);
                        }
                        //====== [PENDIENTE] Crear una funcion a la que se le pasen parámetros y pinte el estatus
                        echo '<div class="w-100" style="background-color: #19D4BD; height: 100px; max-width: 100%;">';
                        echo "<h2>Estatus del procesamiento</h2>";                
                        echo "<div>";                        
                        echo '<div class="w-100" style="background-color: #eee;">';
                        echo "<h3>1- Procesando: " . $resourceKinds . "</h3>";                        
                        echo "<div>";
                        echo "<br/>";

                        //===========================================================================

                        //===== Crear la lista de recursos "resourceList" ---
                        $file = HOME . SALIDAS . ALLRESOURCELIST;
                        $arrayProv = CargarResourceList::readResourceListArray($file);
                        //===== Fin de crear la lista de recursos "resourceList" ---------------------
                        //----------------------------------------------------------------------------
                        //===== Creación y cargar en la BD de la lista de recursos "resourceList" ----

                        //--------[PENDIENTE] 
                        //1 Preguntar acá si se desea pasar la información a la BD
                        //2 Comprobar que la información no haya sido cargada previamente
                        //3 Advertir si los registros ya existen en la BD o la conclusión de la carga
                        
                        //----------- [PROVISIONAL]-----------------[HABILITAR DESPUÉS DE CARAGAR LAS ESTADÍSTICAS]                        
                        //$result = CargarResourceList::insertRegistrosResourceList($arrayProv);

                        //===== Fin de la creación y carga en la BD de la lista de recursos "resourceList" ---
                        
                        echo ("<div><h2> fin del proceso de carga de a lista de " . $resourceKinds . " </h2></div><br/>");
                        //[ELIMINAR]  cambiar esta linea por un echo

                        // ==== Fin de la reación y carga en la BD de la lista de recursos "resourceList"                    
                        //-------------------------------------------------------------------------------

                        //===========================================================================
                        $campos = $camposForStats['campos']; //'camposArray contiene todos los campos para execCurl, menos porciones "REVISAR"
                        
                        $vropsServerArray = VropsConf::getCampo('vropsServer');

                        if ($vropsServerArray['error']){
                            die ('no se pudo obtener el listado de servidores vrops');
                        }else{                            
                            $resultCurl = Curl::prepareExecCurl($tokenInfo['token'], "tipoMediciones", $campos, $resourceKinds, $vropsServerArray['vropsServer']);
                        }
                        

                    }
                                                      
                    echo("<div><h2>Se ha completado el procesamiento de resourceList</h2></div><br/>");

                    if ($resultCurl['error']){
                        die($resultCurl["mensaje"]);
                    }else{

                        //#28EB76 color central https://color.adobe.com/es/create/color-wheel
                        //echo "================================================================";
                        
                        echo '<div class="w-100" style="background-color: #BCF53D; height: 250px; max-width: 100%;">';
                        echo "<h3> La información de los recursos ha sido obtenida exitosamente</h3>";                        
                        echo "<br/><h3>2- Inicia el proceso de generación de estadísticas</h3>";   
                        echo "</div>";                         
                        
                        $arrayProv=array();
                        $consultaFile = fopen(HOME.SALIDAS."consultas.json", "r");
                        
                        while(!feof($consultaFile)){
                            $reg=fgets($consultaFile);
                            if (feof($consultaFile)) break;
                            if ($reg!=false) $arrayProv[]=$reg;      
                        }   

                        $resultConsulta = file_put_contents(HOME.SALIDAS."arrayConsultas.json", json_encode($arrayProv));
                            
                        if (!$resultConsulta){
                            die("hubo un problema al escribir el archivo de consultas");
                        }else{
                            
                            $fileArrayConsultas = file_get_contents(HOME.SALIDAS."arrayConsultas.json");
                            //echo "================================================================";
                            echo '<div class="w-100" style="background-color: #19D4BD; height: 250px; max-width: 100%;">';
                            echo "<br/><h3>3.1- Se crearon las consultas exitosamente </h3><br/>";    
                            echo "<h3>3.2- Procesando las consultas </h3><br/>";    
                        
                            $arrayConsulta = json_decode($fileArrayConsultas, true);
                            echo "<h4>Se generaron " . count($arrayConsulta). " registros de consulta</h4><br/>";
                            
                            foreach($arrayConsulta as $ind=>$jsonConsulta){
                                $consulta=json_decode($jsonConsulta, true);
                                Curl::execCurlTipoMediciones($consulta['paramArray'], $consulta['resourceKinds']);
                            }        
                            
                            echo '<div class="w-100" style="background-color: #BCF53D; height: 250px; max-width: 100%;">';
                            echo "<br/><h2>La información del servidor" . $server . "está lista para pasarse a la base de datos</h2><br/>";                           
                            echo "</div>"; 

                            echo '<div class="w-100" style="padding :15px background-color: #EEE; height: 250px; max-width: 100%;">';
                                echo "<form action='loadVrops.php' method='POST'>";
                                echo "<input type='submit'value='Continuar' id='continuar'><br/><br/>";
                                echo "<label for='continuar'>Presione el botón para continuar</label>";                                                       
                                echo "</form>";
                            echo "</div>";
                                                                    
                        } 
                    }

                ?>

                <?php include '../view/bodyScripts.php'; ?>
                
        </div>
    </body>
</html>


