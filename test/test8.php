<?php



//$servidor="sapl8442";
$usuario="usuario";
$password="Caracas2021..";


if(isset($_GET["servidor"])){
	$servidor = $_GET["servidor"];
} else {
	echo "Por favor enviar via GET las variables tipo y servidor</br>tipo 1 es para llenar la base de datos</br> Tipo 2 para ver en una tabla";
	exit;	
}

$token=tokens($servidor,$usuario,$password);

if($_GET["tipo"] == 1){
	$info = consultar_swagger(0, $token, $servidor);
	$llena = llenardb_posgres($info, $servidor);	
} elseif($_GET["tipo"] == 2){
	$info = consultar_swagger(0, $token, $servidor);
	pintar_tabla($info);
} else {
	echo "Por favor enviar via GET la variable tipo</br>tipo 1 es para llenar la base de datos</br> Tipo 2 para ver en una tabla";
	exit;
}


function tokens($servidor,$usuario,$password){ 
$url = "https://".$servidor.".INX.sec.com/suite-api/api/auth/token/acquire";
//vrops.INX.sec.com
$nom_arch = "vmware_host.xml";
$xmlUrl = "vmware_host.xml";
$tipo = 1;

$arch = fopen($nom_arch , "w") or exit ("No se pudo abrir el archivo");
$curl = curl_init();
$proxy = "prxsrv.INX.sec.com:9090";
$userproxy = "INX\\".$usuario.":".$password;
//echo $userproxy;
$uservrops = "need:clave";
$certfirefox = "C:\\xampp\\htdocs\\vrops_licencia\\multi_part_sapl8442.pem";
//$certfirefox = "C:\\xampp\\htdocs\\vrops_licencia\\vrops.pem";
$up = array("username" => "need", "password" => "clave");
$userpassword = json_encode($up);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json"));
curl_setopt($curl, CURLOPT_PROXY, $proxy);
//curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_PROXYUSERPWD, $userproxy);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($curl, CURLOPT_USERPWD, $uservrops);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
//curl_setopt($curl, CURLOPT_POSTFIELDS, $userpassword); //deprecated
//curl_setopt($curl, CURLOPT_HTTPGET, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_CAINFO, $certfirefox);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 600);
curl_setopt($curl, CURLOPT_FILE, $arch);
 
$result = curl_exec($curl);
$xmlStr = file($xmlUrl);
if(isset($xmlStr[0])){
	$b = $xmlStr[0];
} else {
	$b = "no_existe";
}
$lala = json_decode($b,true);
	foreach($lala as $key => $v){
		if(!is_array($v)){
		{
			if($key == "token"){
				$token = $v;
			}
		}
	}
	return $token;
	}
}

function consultar_swagger($page, $token, $servidor){
$proxy = "prxsrv.INX.sec.com:9090";
$userproxy = "INX\\usuario:Caracas2021..";
$uservrops = "need:clave";
//$certfirefox = "C:\\xampp\\htdocs\\vrops\\vrops.pem";
$certfirefox = "C:\\xampp\\htdocs\\vrops_licencia\\multi_part_sapl8442.pem";
$up = array("username" => "need", "password" => "clave");
$userpassword = json_encode($up);
$curl2 = curl_init();
$xmlUrl2 = "hola.txt";

$arch2 =  fopen("hola.txt" , "w") or exit ("No se pudo abrir el archivo");


$url2 = "https://".$servidor.".INX.sec.com/suite-api/api/resources?page=".$page."&pageSize=-1";

$header[] = "Content-Type: application/json";
$header[] = "Accept: application/json";
$header[] = "Authorization: vRealizeOpsToken ".$token;




curl_setopt($curl2, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl2, CURLOPT_PROXY, $proxy);
//curl_setopt($curl2, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
curl_setopt($curl2, CURLOPT_PROXYUSERPWD, $userproxy);
curl_setopt($curl2, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($curl2, CURLOPT_USERPWD, $uservrops);
curl_setopt($curl2, CURLOPT_URL, $url2);
curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl2, CURLOPT_POST, 1);
//curl_setopt($curl2, CURLOPT_BINARYTRANSFER, true); DEPRECATED
curl_setopt($curl2, CURLOPT_POSTFIELDS, $userpassword);
//curl_setopt($curl2, CURLOPT_POSTFIELDS, $datos);
curl_setopt($curl2, CURLOPT_HTTPGET, 1);
curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl2, CURLOPT_CAINFO, $certfirefox);
curl_setopt($curl2, CURLOPT_CONNECTTIMEOUT, 300);
curl_setopt($curl2, CURLOPT_FILE, $arch2);
 
$result = curl_exec($curl2);

$xmlStr2 = file($xmlUrl2);
if(isset($xmlStr2[0])){
	$b2 = $xmlStr2[0];
} else {
	$b2 = "no_existe";
}
//echo $b;
$lala = json_decode($b2,true);
/*echo "<pre>";
var_dump($lala);
echo "</pre>";*/
//var_dump($lala);
foreach($lala as $valor1 => $valor2){
	if($valor1 == "resourceList"){
		foreach($valor2 as $valor3 => $valor4){
			if(is_array($valor4)){
				foreach($valor4 as $valor5 => $valor6){
					if($valor5 != "badges" && $valor5 != "resourceStatusStates" && $valor5 != "resourceHealth" && $valor5 != "resourceHealthValue"){
					if(is_array($valor6)){
						foreach($valor6 as $valor7 => $valor8){
							if(is_array($valor8)){
								foreach($valor8 as $valor9 => $valor10){
									if(is_array($valor10)){
										foreach($valor10 as $valor11 => $valor12){
											if(is_array($valor12)){
												foreach($valor12 as $valor13 => $valor14)
													//echo "valor1: ".$valor1." valor3: ".$valor3." valor5: ".$valor5." valor7: ".$valor7." valor9: ".$valor9." valor11: ".$valor11." valor13: ".$valor13." valor14: ".$valor14."</br>";
													$info[$valor3][$valor13][] = $valor14;
											}
											else
											{
												//echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 7:".$valor7." valor 9".$valor9." valor 11".$valor11." valor 12".$valor12."</br>";
												$info[$valor3][$valor11][] = $valor12;
											}
										}
									}
									else
									{
										//echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 7:".$valor7." valor 9".$valor9." valor 10".$valor192."</br>";
										$info[$valor3][$valor9][] = $valor10;
									}
								}
							}
							else
							{
								//echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 7:".$valor7." valor 8".$valor8."</br>";
								$info[$valor3][$valor7][] = $valor8;
								
							}
						}
					}
					else
					{
						//echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 6:".$valor6."</br>";
						$info[$valor3][$valor5][] = $valor6;
					}
					}
				}	
			} 
		}
	}
}
return $info;
}	

function consultar_swagger_vm_vs_host($info, $token){
$proxy = "prxsrv.INX.sec.com:9090";
$userproxy = "INX\\usuario:mbo";
$uservrops = "need:clave";
//$certfirefox = "C:\\xampp\\htdocs\\vrops\\vrops.pem";
$certfirefox = "C:\\xampp\\htdocs\\vrops_licencia\\multi_part_sapl8442.pem";
$up = array("username" => "need", "password" => "clave");
$userpassword = json_encode($up);
$curl2 = curl_init();
$xmlUrl2 = "hola.txt";

$arch2 =  fopen("hola.txt" , "w") or exit ("No se pudo abrir el archivo");


for($i=0;$i<=count($info);$i++)
{
	//echo "Servidor: ".$info[$i]["name"][0]."</br>";
	$url2 = "https://vrops01.INX.sec.com".$info[$i]["href"][1];
	//echo $url2."</br>";

//$url2 = "https://vrops01.INX.sec.com/suite-api/api/resources?page=".$page;

$header[] = "Content-Type: application/json";
$header[] = "Accept: application/json";
$header[] = "Authorization: vRealizeOpsToken ".$token;




curl_setopt($curl2, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl2, CURLOPT_PROXY, $proxy);
//curl_setopt($curl2, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
curl_setopt($curl2, CURLOPT_PROXYUSERPWD, $userproxy);
curl_setopt($curl2, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($curl2, CURLOPT_USERPWD, $uservrops);
curl_setopt($curl2, CURLOPT_URL, $url2);
curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl2, CURLOPT_POST, 1);
curl_setopt($curl2, CURLOPT_BINARYTRANSFER, true);
curl_setopt($curl2, CURLOPT_POSTFIELDS, $userpassword);
//curl_setopt($curl2, CURLOPT_POSTFIELDS, $datos);
curl_setopt($curl2, CURLOPT_HTTPGET, 1);
curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl2, CURLOPT_CAINFO, $certfirefox);
curl_setopt($curl2, CURLOPT_CONNECTTIMEOUT, 300);
curl_setopt($curl2, CURLOPT_FILE, $arch2);
 
$result = curl_exec($curl2);

$xmlStr2 = file($xmlUrl2);
if(isset($xmlStr2[0])){
	$b2 = $xmlStr2[0];
} else {
	$b2 = "no_existe";
}
//echo $b;
$lala = json_decode($b2,true);
/**echo "<pre>";
var_dump($lala);

echo "</pre>";
*/
if(is_array($lala)){
foreach($lala as $valor1 => $valor2){
	//if($valor1 == "resourceList"){
		foreach($valor2 as $valor3 => $valor4){
			if(is_array($valor4)){
				foreach($valor4 as $valor5 => $valor6){
					//if($valor5 != "badges" && $valor5 != "resourceStatusStates" && $valor5 != "resourceHealth" && $valor5 != "resourceHealthValue"){
					if(is_array($valor6)){
						foreach($valor6 as $valor7 => $valor8){
							if(is_array($valor8)){
								foreach($valor8 as $valor9 => $valor10){
									if(is_array($valor10)){
										foreach($valor10 as $valor11 => $valor12){
											if(is_array($valor12)){
												//foreach($valor12 as $valor13 => $valor14)
													//echo "valor1: ".$valor1." valor3: ".$valor3." valor5: ".$valor5." valor7: ".$valor7." valor9: ".$valor9." valor11: ".$valor11." valor13: ".$valor13." valor14: ".$valor14."</br>";
													//$info[$valor3][$valor13][] = $valor14;
											}
											else
											{
											//	echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 7:".$valor7." valor 9".$valor9." valor 11".$valor11." valor 12".$valor12."</br>";
												//$info[$valor3][$valor11][] = $valor12;
											}
										}
									}
									else
									{
										//echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 7:".$valor7." valor 9".$valor9." valor 10".$valor192."</br>";
										//$info[$valor3][$valor9][] = $valor10;
									}
								}
							}
							else
							{
								if($valor5 == "resourceKey" && $valor7 == "name" && $valor3 == 2){
									//echo "Servidor.".$info[$i]["name"][0]." valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 7:".$valor7." valor 8".$valor8."</br>";
								}
								$info[$valor3][$valor7][] = $valor8;
								
							}
						}
					}
					else
					{
						//echo "valor 1:".$valor1." valor 3:".$valor3." valor 5".$valor5." valor 6:".$valor6."</br>";
						$info[$valor3][$valor5][] = $valor6;
					}
					//}
				}	
			} 
		}
	//}
}
}
}
return $info;
}




function llenardb($info){
	$baseDeDatos = new PDO("sqlite:" . __DIR__ . "/vmware_recursos.db");
	$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$definicionTabla = "CREATE TABLE IF NOT EXISTS vmware_recursos(
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		nombre_ci TEXT NOT NULL,
		tipo_ci TEXT NOT NULL,
		recursoid TEXT NOT NULL,
		href1 TEXT NOT NULL,
		href2 TEXT NOT NULL,
		href3 TEXT NOT NULL,
		href4 TEXT NOT NULL,
		href5 TEXT NOT NULL,
		href6 TEXT NOT NULL,
		href7 TEXT NOT NULL,
		href8 TEXT NOT NULL,
		href9 TEXT NOT NULL
	);";
	#Podemos usar $baseDeDatos porque incluimos el archivo que la crea
	$resultado = $baseDeDatos->exec($definicionTabla);
	echo "Tablas creadas correctamente";

	# creamos una variable que tendrá la sentencia
	$sentencia = $baseDeDatos->prepare("INSERT INTO vmware_recursos(nombre_ci, tipo_ci, recursoid, href1, href2, href3, href4, href5, href6, href7, href8, href9)
		VALUES(:nombre_ci, :tipo_ci, :recursoid, :href1, :href2, :href3, :href4, :href5, :href6, :href7, :href8, :href9);");

	# Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
	# debido a que la llamada es por referencia

echo "<table border = 1>";
	for($i=0; $i <= 999; $i++){
		$sentencia->bindParam(":nombre_ci",$info[$i]["name"][0]);
		$sentencia->bindParam(":tipo_ci",$info[$i]["resourceKindKey"][0]);
		$hacha = explode("/", $info[$i]["href"][0]);
		$recursoID = $hacha[4];
		$sentencia->bindParam(":recursoid", $recursoID);
		$sentencia->bindParam(":href1", $info[$i]["href"][0]);
		$sentencia->bindParam(":href2", $info[$i]["href"][1]);
		$sentencia->bindParam(":href3", $info[$i]["href"][2]);
		$sentencia->bindParam(":href4", $info[$i]["href"][3]);
		$sentencia->bindParam(":href5", $info[$i]["href"][4]);
		$sentencia->bindParam(":href6", $info[$i]["href"][5]);
		$sentencia->bindParam(":href7", $info[$i]["href"][6]);
		$sentencia->bindParam(":href8", $info[$i]["href"][7]);
		$sentencia->bindParam(":href9", $info[$i]["href"][8]);

		$resultado = $sentencia->execute();
		if($resultado === true){
			echo "<tr><td>CI Registrado correctamente ".$i."</td></tr>";
		}else{
			echo "<tr><td>CI Alerta No fue Registrado correctamente ".$i."</td></tr>";
		}

	}
echo "</table>";
}

function llenardb_posgres($info, $server){

try{
       $con = pg_connect("host=192.135.197.120 port=8499 dbname=VROPS user=postgres password=Mercantil2021");
}
catch (Exception $e)
{
       die('Erreur connexion BDD: '.$e->getMessage());
}

$conn = $con;



$sql2 = "select nombre_ci from vmware_recursos where server = '".$server."'";

$result = pg_query($conn, $sql2);
while ($data = pg_fetch_row($result)) {
	foreach($data as $valor){
		$servidores[] = $valor;
		//echo $valor."</br>";
	}
}

if(!isset($servidores)){
	$servidores = array();
	
}

//echo "------------------------- Desde Aqui comienza la validacion -------------------";



	# creamos una variable que tendrá la sentencia
	$sql="INSERT INTO vmware_recursos(nombre_ci, tipo_ci, recursos_id, href1, href2, href3, href4, href5, href6, href7, href8, href9, server) VALUES($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13);";

	# Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
	# debido a que la llamada es por referencia
$i = 1;
echo "<table border = 1>";
	foreach($info as $infos){
		if(array_search($infos["name"][0],$servidores,true) === false){
			//echo $info[$i]["name"][0]."lo voy a registrar</br>";
		
			$hacha = explode("/", $infos["href"][0]);
			$recursoID = $hacha[4];
			$valores_query = array($infos["name"][0], $infos["resourceKindKey"][0], $recursoID, $infos["href"][0],$infos["href"][1],$infos["href"][2],$infos["href"][3],$infos["href"][4],$infos["href"][5], $infos["href"][6], $infos["href"][7],$infos["href"][8], $server);
			$resultado = pg_query_params($conn, $sql, $valores_query);
			if($resultado !== false){
				echo "<tr><td>CI Registrado correctamente ".$i." ".$infos["name"][0]."</td></tr>";
			}else{
				echo "<tr><td>CI Alerta No fue Registrado correctamente ".$i." ".$infos["name"][0]."</td></tr>";
			}
		} else {
			//echo $info[$i]["name"][0]."Ya existe</br>";
		}
		$i++;
	}
echo "</table>";
}

function mostrardb(){
$baseDeDatos = new PDO("sqlite:" . __DIR__ . "/vmware_recursos.db");
$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$resultado = $baseDeDatos->query("SELECT * FROM vmware_recursos where tipo_ci = 'VirtualMachine';");
$ci_vms = $resultado->fetchAll(PDO::FETCH_OBJ);

echo "<table>";
foreach($ci_vms as $ci_vm){ /*Notar la llave que dejamos abierta*/ 
				echo"<tr>";
				echo "<td>".$ci_vm->nombre_ci."</td>";
				echo "<td>".$ci_vm->tipo_ci."</td>";
				echo "<td>".$ci_vm->recursos_id."</td>";
				echo "<td>".$ci_vm->href1."</td>";
				echo "<td>".$ci_vm->href2."</td>";
				echo "<td>".$ci_vm->href3."</td>";
				echo "<td>".$ci_vm->href4."</td>";
				echo "<td>".$ci_vm->href5."</td>";
				echo "<td>".$ci_vm->href6."</td>";
				echo "<td>".$ci_vm->href7."</td>";
				echo "<td>".$ci_vm->href8."</td>";
				echo"</tr>";
} /*Cerrar llave, fin de foreach*/ 
echo "</table>";
}


function pintar_tabla($info){
//$baseDeDatos = new PDO("sqlite:" . __DIR__ . "/vmware_recursos.db");
//$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$resultado = $baseDeDatos->query("SELECT * FROM vmware_recursos where tipo_ci = 'VirtualMachine';");
//$ci_vms = $resultado->fetchAll(PDO::FETCH_OBJ);
//$codigo = json_encode($info);
//$ci_vms = json_decode($codigo);


//echo $ci_vms->stdClass->name[0];
//$info[$i]["name"][0];
/*echo "<pre>";
var_dump($info);
echo "</pre>";
exit;*/

$i=1;
echo "<table border = 1>";
foreach($info as $infos){ /*Notar la llave que dejamos abierta*/ 
				echo"<tr>";
				echo "<td>".$i."</td>";
				echo "<td>".$infos["name"][0]."</td>";
				echo "<td>".$infos["identifier"][0]."</td>";
				echo "<td>".$infos["resourceKindKey"][0]."</td>";
				//echo "<td>".$ci_vm->resourceKindKey."</td>";
				//echo "<td>".$ci_vm->href."</td>";
				//echo "<td>".$ci_vm->href1."</td>";
				//echo "<td>".$ci_vm->href2."</td>";
				//echo "<td>".$ci_vm->href3."</td>";
				//echo "<td>".$ci_vm->href4."</td>";
				//echo "<td>".$ci_vm->href5."</td>";
				//echo "<td>".$ci_vm->href6."</td>";
				//echo "<td>".$ci_vm->href7."</td>";
				//echo "<td>".$ci_vm->href8."</td>";
				//echo"</tr>";
$i++;
} /*Cerrar llave, fin de foreach*/ 
echo "</table>";
}

?>
