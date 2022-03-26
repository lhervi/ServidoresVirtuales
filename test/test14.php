<?php

/*
"user":"INTRA\\ljhervilla",
    "password":"Banesco2021..",
    "networkDrive":"\\\\smgm1034\\Linea_base_windows",
*/

$user="INTRA\\ljhervilla";
$password = "Banesco2021..";
//$netRoute = "\\\\smgm1034\\Linea_base_windows";
$netRoute = "\\\\10.132.71.50\\Linea_base_windows";
$archivo = "LB_Marzo_2021.csv";



$strConn = 'net use "'. $netRoute .'" /user:"' . $user . '" "' . $password .'" /persistent:no';
//"net use \\smgm1034\Linea_base_windows /user:"INTRA\ljhervilla" "Banesco2021.." /persistent:no"
//exec('net use "\\\INVEST-OP-001\test\music" /user:"'.$user.'" "'.$password.'" /persistent:no');

$res = exec($strConn);
$shellResult = shell_exec($strConn);
$sysResult =  system($strConn);
$gestor = fopen($netRoute . "\\" . $archivo, "r");

//$exceResult = exec('net use '. $netRoute .' /user:"'.$user.'" "'.$password.' " /persistent:no');

$arch = "\\\\smgm1034\\Linea_base_windows\\LB_Marzo_2021.csv";

$result = fopen($arch, 'r');

$a=5; 

/*
 exec('net use '. $this->netRoute .' /user:"'.$this->user.'" "'.$this->password.'" /persistent:no');
            if (($gestor = fopen($this->netRoute . "\\" . $this->archivo, "r")) !== FALSE) {
                $this->conf=stat($this->netRoute . "\\" . $this->archivo);
                $this->fecha = (date("Y-m-d H:i:s",$this->conf['mtime']));
                
                if (($registro = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                    foreach ($registro as $indice => $titulo_de_la_celda) {
                        $this->encabezado[$indice] = $titulo_de_la_celda;  
                        //esta variable $encabezado contiene los encabezados del archivo
                    }
                }
           
                while (($registro = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                    //Con este bucle se llenan todos los campos de datos que provienen del archivo en la variable $campo_tabla
                    //que es un arreglo de dos dimensiones
                    foreach ($registro as $indice => $campo) {      
                        $this->campo_tabla[$this->fila][$indice] = utf8_encode($campo);            
                    }
                    $this->fila++; 
                }
            } 
        
        exec('net use ' . $this->netRoute . '/delete /yes');
        
        }catch(Exception $e){
            echo ("ha habido un error al intentar leer el archivo: " . $e->getMessage());
        }               
    }     
*/

// exec('net use '. $this->netRoute .' /user:"'.$this->user.'" "'.$this->password.'" /persistent:no');
//if (($gestor = fopen($this->netRoute . "\\" . $this->archivo, "r")) !== FALSE) {

 
?>
