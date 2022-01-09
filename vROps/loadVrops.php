<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '-1');
    ini_set('display_errors', 1);

    include_once '../view/encabezado.php';
    include_once 'model/classCargarStatsVrops.php';
?>
    <body class="m-0 vh-100 row justify-content-start align-items-center">
        <div class="container col-auto">
<?php

   if (isset($_POST['continuar'])){
               
        CargarStatsVrops::cargarStats();

        echo '<div class="w-100"  max-width: 100%; style="background-color: #eee; height: 250px; max-width: 100%;">';
        echo "<br/><h3>Culminó la carga</h3><br/>";
        echo "</div>";
   }

?>

<?php include '../view/bodyScripts.php'; ?>           
                
        </div>
    </body>
</html>