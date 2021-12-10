<?php

require_once 'classOperCSV.php';
require_once 'classObjCSV.php';
require_once 'classTrasObj.php';

class getCSV {
    public $fila =1;
    private string $user;
    private string $password;
    private string $netRoute;
    private string $archivo;
    
    public array $encabezado;    
    public array $campo_tabla;
    public $tableClass;
    public $conf;
    public $fecha;
    public ObjCSV $objCSV2;       

    function __construct($user, $password, $netRoute, $archivo, $tableClass="= class='table table-striped'"){
        $this->user = $user;
        $this->password = $password;
        $this->netRoute = $netRoute;
        $this->archivo = $archivo;
        $this->$tableClass=$tableClass;
    }

    public function loadFile () {       
        
        try{

            exec('net use '. $this->netRoute .' /user:"'.$this->user.'" "'.$this->password.'" /persistent:no');
            //shell_execute('net use '. $this->netRoute .' /user:"'.$this->user.'" "'.$this->password.'" /persistent:no');
            if (($gestor = fopen($this->netRoute . "\\" . $this->archivo, "r")) !== FALSE) {

                $this->conf=stat($this->netRoute . "\\" . $this->archivo);

                $this->fecha = (date("Y-m-d H:i:s",$this->conf['mtime']));
            // Con este bucle obtengo los nombre de los encabezados del archivo y quedan almacenados en el arreglo $encabezado
                
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

    function getObjCSV(){        

        $this->objCSV2 = new ObjCSV();        

        $tras = new TrasObj();

        $tras->traspasar($this, $this->objCSV2);       
        
        return $this->objCSV2;

    }
}
?>
