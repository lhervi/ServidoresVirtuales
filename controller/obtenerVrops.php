<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);
$url = "https://vrops01.INX.sec.com/suite-api/api/auth/token/acquire";
$nom_arch = "vmware_host.xml";
$xmlUrl = "vmware_host.xml";
$tipo = 1;


$hola = capturadatadesdeurl($url, $xmlUrl, $nom_arch);

$arch = fopen($nom_arch , "w") or exit ("No se pudo abrir el archivo");
$curl = curl_init();
$proxy = "prxsrv.INX.sec.com:9090";
$userproxy = "INX\\usuario:mbo";
$uservrops = "need:clave";
$certfirefox = "C:\\xampp\\htdocs\\vmware_parse\\vrops.pem";

$up = array("username" => "need", "password" => "clave");
$userpassword = json_encode($up);

echo $hola;

//exit;

function capturadatadesdeurl($url, $xmlUrl, $nom_arch){
    $arch = fopen($nom_arch , "w") or exit ("No se pudo abrir el archivo");
    $curl = curl_init();
    $proxy = "prxsrv.INX.sec.com:9090";
    $userproxy = "INX\\usuario:mbo";
    $uservrops = "need:clave";
    $certfirefox = "C:\\xampp\\htdocs\\vmware_parse\\vrops.pem";
    
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
    curl_setopt($curl, CURLOPT_PROXY, $proxy);
    curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_PROXYUSERPWD, $userproxy);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $uservrops);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_CAINFO, $certfirefox);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt($curl, CURLOPT_FILE, $arch);
     
    $result = curl_exec($curl);
    
    $xmlStr = file($xmlUrl);
    echo "<pre>";
    var_dump($xmlStr);
    echo "<pre>";
    if(isset($xmlStr[0])){
        $b = $xmlStr[0];
    } else {
        $b = "no_existe";
    }
    return $b;
    }

