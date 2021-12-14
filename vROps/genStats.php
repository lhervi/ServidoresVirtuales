    <?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '-1');
    ini_set('display_errors', 1);

    include_once '../view/encabezado.php';
    include_once 'classVropsToken.php';
    include_once 'classVropsResourceList.php';
    include_once '../controller/utils/classFechas.php';
    include_once '../vROps/classCurl.php';
    include_once '../constantes.php';
    include_once 'model/classCargarStatsVrops.php';
    ?>
    <body class="m-0 vh-100 row justify-content-start align-items-center">
        <div class="container col-auto">

                <?php

                    // -----------------------------------------------  S A L T O  --------------------------------------

                    //Revisar entradas ======== I N C L U I R ========

                    

                    $begin = array_key_exists("inicio", $_POST) ? $_POST["inicio"] : Fechas::fechaQuery("hoy", true);
                    $end = array_key_exists("fin", $_POST) ? $_POST["fin"] : Fechas::fechaQuery("fin", true);

                    $intervalType = array_key_exists("intervalType", $_POST) ? $_POST["intervalType"] : "HOURS";
                    $intervalQuantifier = array_key_exists("intervalQuantifier", $_POST) ? intval($_POST["intervalQuantifier"]) : 1;
                    $rollUpType = array_key_exists("rollUpType", $_POST) ? $_POST["rollUpType"] : "AVG";

                    foreach($_POST as $ind=>$val){
                        if("resourceKinds"==$val){
                            $resourceKindsArray[] = $ind;
                        }
                    }

                    //------  VERIFICAR QUE LOS CAMPOS ESTÉN CORRECTOS 
                    //------  Fechas con valores no mayores a tres meses de amplitud

                    $resourceKinds = array_key_exists("resourceKinds", $_POST) ? $_POST["resourceKinds"] : "virtualmachine";

                    $tokenInfo=VropsToken::getToken();

                    if ($tokenInfo['error']){
                        die('{"error":true, "mensaje":' . $tokenInfo["mensaje"] . '}');  //Se detiene el programa en caso de que ocurra un error al obtener el token
                    }else{
                        $token = $tokenInfo['token'];
                        $_SESSION['tokenOk']=true;
                    }

                    file_put_contents(HOME.SALIDAS.'indiceDeConsultas.json', '{"indiceDeConsultas":0}');
                    foreach($resourceKindsArray as $resourceKinds){
                                
                        $camposForStats = VropsResourceList::getCamposForStats($begin, $end, $intervalType, $intervalQuantifier, $rollUpType, $resourceKinds);  //además crea el archivo campos.json

                        if ($camposForStats['error']){
                            die($camposForStats['mensaje']);
                        }
                        echo '<div class="w-100" style="background-color: #19D4BD; height: 100px; max-width: 100%;">';
                        echo "<h2>Estatus del procesamiento</h2>";                
                        echo "<div>";                        
                        echo '<div class="w-100" style="background-color: #eee;">';
                        echo "<h3>1- Procesando: " . $resourceKinds . "</h3>";                        
                        echo "<div>";
                        echo "<br/>";
                    
                        $campos = $camposForStats['campos']; //'camposArray contiene todos los campos para execCurl, menos porciones "REVISAR"
                        
                        $resultCurl = Curl::prepareExecCurl($tokenInfo['token'], "tipoMediciones", $campos, $resourceKinds);
                    }

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
                            echo "<br/><h2>La información está lista para pasarse a la base de datos</h2><br/>";                           
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


