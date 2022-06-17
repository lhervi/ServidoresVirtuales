

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
    include_once './../controller/utils/classLookAndFeel.php';
    include_once './../controller/utils/classErrors.php';
    include_once './classLista.php';
    

    
    $_SESSION['genStats']=isset($_SESSION['genStats']) ?  $_SESSION['genStats']++ : 0;
    
    ?>



    <body class="m-0 vh-100 row justify-content-start align-items-center" id="cuerpo">
        <div class="container col-auto">

        <?php      

            function regresar(){
                echo LookAndFeel::estatus('<div id="regresar" style="cursor:pointer"><h3> -> Regresar </h3></div>', 2);
                echo "<script> " . PHP_EOL;
                echo LookAndFeel::enlace('regresar', MENUINICIO);
                echo "</script> " . PHP_EOL;
            }

            $vropsServerArray  = VropsConf::getCampo('vropsServer');

            if ($vropsServerArray['error']){
                $mensaje = "no se pudo obtener el listado de servidores vrops";
                RegistError::logError($mensaje, __FILE__, __LINE__, 2);
                die ($mensaje);
            }else{
                $server = VropsConf::getCampo('vropsServer')['vropsServer'];
            }

            // -----------------------------------------------  S A L T O  --------------------------------------
            //============ Procesamiento de entradas ================//
            //Revisar entradas ======== I N C L U I R ========
            
            if(isset($_POST["mesConsulta"])){
                
                $mesConsulta = $_POST["mesConsulta"];

                $begin = $mesConsulta . "-01";
                $end = $mesConsulta . "-" . Fechas::lastDay($mesConsulta);    
                $_SESSION['numMesTabla'] = substr($mesConsulta, 5);

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
            
           /*
            $consulta = $begin . " " . $end . " " . $intervalType. " " . $end . " " . $intervalQuantifier . " ";
            $consulta .= $rollUpType . " " . $end . implode(" ", $resourceKindsArray);

            $consultaHash = hash("md5", $consulta);
            */

            //---------------------  VERIFICAR QUE LA CONSULTA NO HAYA SIDO REALIZADA ANTES ------------------            
           /* ---------------------------- [REVISAR] ---------------------------------------------------------
            $existe = Lista::existeEnLista($consultaHash);

            if ($existe['existe']){ //[REVISAR] supuestamente llega un valor null

                $mensaje = "Los datos ya habían sido cargados BD anteriormente el: " . $existe['fecha'];
                
                echo LookAndFeel::estatus($mensaje, 1);
                
                echo "<script>" . PHP_EOL;
        
                echo LookAndFeel::enlace("regresar", MENUINICIO);
        
                echo "</script>" . PHP_EOL;     

                die();
            }else{
                $arrayLIsta = Lista::getLista();
                Lista::agregarConsulta($arrayLIsta, $consultaHash);
            }
            */
            //---------------------------- [REVISAR] -------------------------------------------------

            //-------------------------------------------------------------------------------------------------


            
            //============ Fin de Procesamiento de entradas ================//

            //------------------------------------------------------------------------------------------------------------

            //============ Obtención de token de acceso ================//
                
            $tokenInfo=VropsToken::getToken();

            if ($tokenInfo['error']){
                RegistError::logError($tokenInfo["mensaje"], __FILE__, __LINE__, 2);
                die('{"error":true, "mensaje":' . $tokenInfo["mensaje"] . '}');  //Se detiene el programa en caso de que ocurra un error al obtener el token
            }else{
                $token = $tokenInfo['token'];
                $_SESSION['tokenOk']=true;
            }

            //============ Fin de obtención de token de acceso ================//
            //-------------------------------------------------------------------                   

            //file_put_contents(HOME.SALIDAS.'indiceDeConsultas.json', '{"indiceDeConsultas":0}');

            echo LookAndFeel::estatus("Estatus del procesamiento de los ResourceKinds", 1);

            
            //----------------------------------------------------------------------------------
            //======================== CREACIÓN DE LA LISTA DE RECURSOS ========================
            
            //El proceso obtiene toda la lista de recursos, dado que toma los recursos definidos en el arreglo de vROpsConf.json

            // ============================== P R O V I S I O N A L ==============================
            
            
            
            foreach($resourceKindsArray as $resourceKinds){ //[REVISAR][ELIMINAE] Eliminar esta linea----------
                
                //Crea una lista de recursos que se almacena completa en allResorceList.json
               
                $result = VropsResourceList::getResourceList($resourceKinds);
                if ($result['error']){
                    echo LookAndFeel::estatus($result['mensaje']);
                    regresar();
                    die();
                }else{
                    echo LookAndFeel::estatusX("se obtuvo  la información de los recursos correctamente");
                }
            }  // [REVISAR][ELIMINAR] Eliminar esta llave que cierra el bucle, no es necesaria.

            

            // ============================== P R O V I S I O N A L ==============================

            //===== Crear la lista de recursos "resourceList" ---
            $file = HOME . SALIDAS . ALLRESOURCELIST;
            
            $arrayProv = CargarResourceList::readResourceListArray($file);
            //===== Fin de crear la lista de recursos "resourceList" ---------------------
            //----------------------------------------------------------------------------
                
            //===== Creación y cargar en la BD de la lista de recursos "resourceList" ----

            //================ [PENDIENTE] ==============================================
            //1 Preguntar acá si se desea pasar la información a la BD
            //2 Comprobar que la información no haya sido cargada previamente
            //3 Advertir si los registros ya existen en la BD o la conclusión de la carga   

            
            $result = CargarResourceList::insertRegistrosResourceList($arrayProv, $mesConsulta);

            
            //Si ya los registros se encuentran en la BD se detiene la ejecución

            if (isset($result['error']) && $result['error'] ){
                echo LookAndFeel::estatusX($result['mensaje']);
                
                regresar();
                die();               
                echo LookAndFeel::estatus('<div id="regresar" style="cursor:pointer"><h3> -> Regresar </h3></div>', 2);
                echo "<script> . " . PHP_EOL;
                echo LookAndFeel::enlace('->regresar', MENUINICIO);
                echo "</script> . " . PHP_EOL;
                
            }

            echo LookAndFeel::estatusX("Se registraron " . $result . " registros en la BD");


            //[OJO OJO] Hay que vaciar a allResourceList.json después de pasar su contenido a la BD

            //===== Fin de la creación y carga en la BD de la lista de recursos "resourceList" ---

            //echo LookAndFeel::estatusX("fin del proceso de carga de la lista de " . $resourceKinds);

            echo LookAndFeel::estatusX("Se ha completado el procesamiento de la lista de recursos", 2);


            foreach($resourceKindsArray as $resourceKinds){            
                    
            $camposForStats = VropsResourceList::getCamposForStats($begin, $end, $intervalType, $intervalQuantifier, $rollUpType, $resourceKinds);  //además crea el archivo campos.json

            if ($camposForStats['error']){
                RegistError::logError($camposForStats['mensaje'], __FILE__, __LINE__, 2);
                die($camposForStats['mensaje']);
            }
            
            echo LookAndFeel::estatus("Procesando ". $resourceKinds);  
            
            //===========================================================================
            $campos = $camposForStats['campos']; //'camposArray contiene todos los campos para execCurl, menos porciones "REVISAR"
            
            //========================== [ELIMINAR] ==========================
            /*
            $vropsServerArray = VropsConf::getCampo('vropsServer');
            
            if ($vropsServerArray['error']){
                $mensaje = "no se pudo obtener el listado de servidores vrops";
                RegistError::logError($mensaje, __FILE__, __LINE__, 2);
                die ($mensaje);
            }else{                            
                $resultCurl = Curl::prepareExecCurl($tokenInfo['token'], "tipoMediciones", $campos, $resourceKinds, $vropsServerArray['vropsServer']);
            }
            */ 
            //========================== [ELIMINAR] ==========================
            
            $resultCurl = Curl::prepareExecCurl($tokenInfo['token'], "tipoMediciones", $campos, $resourceKinds, $server);

            }

                
                if ($resultCurl['error']){
                    die($resultCurl["mensaje"]);
                }else{

                    echo LookAndFeel::estatusX("La información de los recursos ha sido obtenida exitosamente");

                    echo LookAndFeel::estatusX("Inicia el proceso de generación de estadísticas");                                               
                        
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
                        
                        echo LookAndFeel::estatusX("Se crearon las consultas exitosamente");
                    
                        $arrayConsulta = json_decode($fileArrayConsultas, true);
                        
                        echo LookAndFeel::estatusX("Se generaron " . strval(count($arrayConsulta)). " registros de consulta");
                        
                        foreach($arrayConsulta as $ind=>$jsonConsulta){
                            $consulta=json_decode($jsonConsulta, true);
                            Curl::execCurlTipoMediciones($consulta['paramArray'], $consulta['resourceKinds']);
                        }        

                        echo LookAndFeel::estatusX("La información del servidor <strong>" . $server . " </strong>está lista para pasarse a la base de datos", 2);

                        echo LookAndFeel::loader();

                       
                        echo '<div class="w-100" style="padding :15px background-color: #EEE; height: 250px; max-width: 100%;">';
                            echo "<form action='loadVrops.php' method='POST' id='enviarEstadisticas'>";
                            echo "<br/>";
                            echo "<input type='submit'value='continuar' id='continuar'><br/><br/>";
                            echo "<label for='continuar'>Presione el botón para continuar</label>";                                                       
                            echo "</form>";
                        echo "</div>";
                        
                                                                
                    } 
                }

                
                
               
                

        ?>

                      
                
            <?php 
            include '../view/bodyScripts.php'; 

            /*
            echo "<script> " . PHP_EOL;
            echo LookAndFeel::showLoader();
            echo "<script> " . PHP_EOL;
            */

            ?>            
            <script>               
                
                document.getElementById('cuerpo').addEventListener('load', function(){
                   document.getElementById("loader").style.display= "none";
                })

                document.getElementById('continuar').addEventListener('click', function(e){
                    e.preventDefault();
                    document.getElementById("loader").style.visibility= "visible"; 
                    document.getElementById("loader").style.display= "block";
                    document.getElementById("enviarEstadisticas").submit();
                })

            </script>
                
        </div>
    </body>
</html>


