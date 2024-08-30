<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

require_once("consultas.php");	
require_once './../controller/utils/classFechas.php';

$añoMes = $_POST['mes'];

$añoMesArr = Fechas::splitMesAño($añoMes);

if($añoMesArr['error']){
	die('hay un problema con la fecha suministrada');
}else{
	$mes=$añoMesArr['mes'];
	$año=$añoMesArr['año'];
}

$DSNyBD = ["INX"=>"odbc:\\\\192.132.71.50\SIS_INX", "BOL"=>"odbc:\\192.132.71.50\SIS_BOL", "AGENCIA"=>"odbc:\\192.132.71.50\SIS_AGENCIAS"];

foreach ($DSNyBD as $bd=>$dsn){
	$sql = consulta(1, $bd, $año, $mes);
	$ejecutar = ejecutar($dsn, $sql, $bd, strval($mes));
	if ($ejecutar=1){
		echo "Listo ". $bd . date(DATE_RFC2822) . "<br/>";//enviar esta salida a un log donde además se añada la hora
	}
}

function ejecutar($dsn, $sql, $nombre, $mes){
	try {
		$dbh = new PDO($dsn);

		$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$stmt = $dbh->query($sql);
		$stmt->execute();
		
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
	}catch(PDOException $e){
		
		die("ocurrió un error al hacer la consulta: " . $e->getMessage());
	}
	//cerrar aquí la funcion ejecutar
	
	$encabezado = "";
	$cuerpo = "";
	$prefijo="prueba";

	foreach($result as $key=>$val)
		{
			if($encabezado == "")
			{
				$datos_encabezado = array_keys($val);
				$encabezado = "";
				foreach($datos_encabezado as $val_encabezado){
					$encabezado .= $val_encabezado . ';';
				}
				$encabezado .= "
				";
			}
		foreach($val as $valores)
		{
			$cuerpo .= trim($valores).";";
		}
		$cuerpo .= "			";
		$cuerpo .= "/r/n";
		}
	try{
		if($dsn == "odbc:SIS_BOL"){
			$archivo = $cuerpo;
			file_put_contents($prefijo . $nombre. "" . $mes . ".csv", $archivo, FILE_APPEND | LOCK_EX);
		} else {
		$archivo = $encabezado."".$cuerpo;
		file_put_contents($prefijo . $nombre . "" . $mes . ".csv", $archivo);
		}

    	$dbh = null;
	}catch(Exception $e){
    	echo $e->getMessage();
    }

	if($cuerpo != "" && $encabezado != ""){
		return 1;
	}else{
		return 0;
	}
}	

?>