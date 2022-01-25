<!DOCTYPE html>
<html>
<body>

<?php
include_once './controller/utils/classFechas.php';

function getPrimerUltimoDia(string $año, string $mes){
    
    $almostLastDay = $año . "-" . "$mes" . "-" . "28";    
    $nextDay = date_create($almostLastDay);   
    
    $b = date_format($nextDay, "Y-m-d");
    $a = $nextDay->format("m");
       
    while ($nextDay->format("m")==$mes){
        $almostLastDay = $nextDay->format("Y-m-d");
        date_add($nextDay, date_interval_create_from_date_string("1 days"));        
    }

    $result['primerDia'] = $año . "-" . "$mes" . "-" . "01";
    $result['ultimoDia'] = $almostLastDay;
    
    return $result;
}

$salida1="";
$salida2="";

if(isset($_POST['mes'])){

    $año = date("Y", $_POST['mes']);
    $mes = date("m", $_POST['mes']);

    $resutado = getPrimerUltimoDia($año, $mes);

    $salida1 = "para el mes  " . $mes . " del año " . $año . " el primer día es " . $resutado['primerDia'] . "<br/><br/>";
    $salida2 = "para el mes  " . $mes . " del año " . $año . " el ultimo día es " . $resutado['ultimoDia'];

}  


?>

    
    <div>
        <form Id="formMes" action="testDia.php" method="post">
            <label for="mes">Elija un mes</label>
            <input type="month" id="mes"><br/><br/>
            <input type="submit" value="Enviar" action="testDia.php">
        </form>

        <p id="salida1">
            <?php  echo $salida1 ?>
        </p>
        <br/>
        <p id="salida2">
        <?php  echo $salida2?>
        </p>
        </div>
    </body>
</html>
