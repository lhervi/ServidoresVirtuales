<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

   $numMesTabla = $_SESSION['numMesTabla'];
   

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '-1');
    ini_set('display_errors', 1);

    include_once __DIR__ . '/../constantes.php';
    include_once '../view/encabezado.php';
    include_once 'model/classCargarStatsVrops.php';   
    include HOME . '/controller/utils/classLookAndFeel.php';
    
?>
    <body class="m-0 vh-100 row justify-content-start align-items-center">
        <div class="container col-auto">
            <br/>
            <div><h2>Proceso de Carga de Estadísticas en la BD</h2></div><br/>
<?php

$seguir = isset($_POST['continuar']);
$seguir=true;
   
   if ($seguir){
    
        echo LookAndFeel::estatus("Iniciando el proceso de carga", 2);     

        $result ??= CargarStatsVrops::cargarStats($numMesTabla);

        $error ??= isset($result['error']) ? $result['error'] : true;

        if($error){
            echo LookAndFeel::estatus($result['mensaje'], 2);
            echo "<script>" . PHP_EOL;
        
            echo LookAndFeel::enlace("regresar", MENUINICIO);        
            echo "</script>" . PHP_EOL;  
            
        }else{
            echo LookAndFeel::estatus("Culminó con éxito la carga de los registros", 2);
        }                 
        
        
        
        //echo '<div id="regresar" style="cursor:pointer"><h3> -> Regresar </h3></div>';  
        echo LookAndFeel::estatus('<div id="regresar" style="cursor:pointer"><h3> -> Regresar </h3></div>', 2);
        echo "<br/><br/>";       

   }

?>
        </div>


<?php 

    echo "<script>" . PHP_EOL;
        
        echo LookAndFeel::enlace("regresar", MENUINICIO);
        
    echo "</script>" . PHP_EOL;        

include '../view/bodyScripts.php'; 

?>           
                
        
    </body>
</html>