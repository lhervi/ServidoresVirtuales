
<?php

class getSCV {
    protected $fila =1;
    protected string $user;
    protected string $password;
    protected string $netRoute;
    protected string $archivo;

    function __construct($user, $password, $netRoute, $archivo){
        $this->user = $user;
        $this->password = $password;
        $this->netRoute = $netRoute;
        $this->archivo = $archivo;
    }    

    public function connectToNetworkDrive() {
        exec('net use '. $this->netRoute .' /user:"'.$this->user.'" "'.$this->password.'" /persistent:no');
    }
    
    public function loadFile () {
        if (($gestor = fopen($this->netRoute . "\\" . $this->archivo, "r")) !== FALSE) {
            // Con este bucle obtengo los nombre de los encabezados del archivo y quedan almacenados en el arreglo $encabezado
            if (($registro = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                foreach ($registro as $indice => $titulo_de_la_celda) {
                    $encabezado[$indice] = $titulo_de_la_celda;
                }
            }
           
            while (($registro = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                //Con este bucle se llenan todos los campos de datos que provienen del archivo en la variable $campo_tabla
                //que es un arreglo de dos dimensiones
                foreach ($registro as $indice => $campo) {      
                    $campo_tabla[$this->fila][$indice] = utf8_encode($campo);            
                }
                $this->fila++; 
            }
        } 
    }   
    
    public function closeNetworkDrive() {
        //exec('net use "\\\smgm1034\\Linea_base_windows" /delete /yes');
        exec('net use ' . $this->netRoute . '/delete /yes');
    }
}

?>  