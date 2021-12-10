<?php

class CargarLineaBase {

    public $error;
    public $objLineaBase;
    public $cargada;

    function __construct($objLineaBase)
    {
        $this->objLineaBase = $objLineaBase;
    }

    function cargarLineaBase(){

        include_once 'dbConexion.php';        
        
        $objcon = new Conexion();

        //verifica que la Lnea Base no haya sido cargada previamente
        $consulta ="SELECT fecha FROM linea_base where fecha=";
        $consulta= $consulta . "'" . $this->objLineaBase->getFecha() . "' LIMIT 1;";        
        $registros = $objcon->consultar($consulta);
        $this->cargada = count($registros)>0 ? TRUE : FALSE;

        //Crear la tabla si no existe
        //Fecha es la fecha en la que se creó el archivo de Linea Base
        //Fecha_carga es la fecha en la que se carga a la BD
        $consultaCreaTabla = "CREATE TABLE IF NOT EXISTS linea_base (id SERIAL, fecha timestamp NOT NULL, fecha_carga timestamp NOT NULL, servidor VARCHAR, "; 
        $consultaCreaTabla = $consultaCreaTabla . "rol VARCHAR, ambiente VARCHAR, nucleo VARCHAR, ";
        $consultaCreaTabla = $consultaCreaTabla . "tipo VARCHAR, nombre_red VARCHAR, so VARCHAR, servicio_de_negocio VARCHAR, ";
        $consultaCreaTabla = $consultaCreaTabla . "estatus VARCHAR, modelo VARCHAR, serial VARCHAR, nombre_localidad VARCHAR);";

        $registros = $objcon->insertar($consultaCreaTabla);  //Crea la tabla si no existe
                
        //Leer el arreglo fila por fila para ir insertando cada registro         

        $numReg = $this->objLineaBase->getNumeroDeRegistros();              

        $encabezados= strtolower($this->objLineaBase->getEncabezado());

        $consulta="(INSERT INTO linea_base ( fecha, fecha_carga, "  . $encabezados . ")";

        $consulta = "INSERT INTO linea_base (fecha, fecha_carga, servidor, rol, ambiente, nucleo, tipo, nombre_red,";            
        $consulta = $consulta . " so, servicio_de_negocio, estatus, modelo, serial, nombre_localidad) VALUES ";         
        
        $fecha_carga = date("Y-m-d H:i:s");
        $fecha = $this->objLineaBase->getFecha();

        $arrEncabezados = $this->objLineaBase->getEncabezadoArray(); //encabezados

        $valores="";
        $matrix=$this->objLineaBase->getRegistros(); //arreglo de dos dimensiones con los registros
        
        $arrayValores= array();
       
        foreach ($matrix as $reg){
        
            $valores ="('" . $fecha . "', '" . $fecha_carga . "', ";
            $regStr=array();
            
            foreach ($reg as $i=>$campo){
                $regStr[$i] = "'" . $campo . "'";
            }

            $valores = $valores . implode(', ',$regStr);
            $valores = $valores . ")";
            $arrayValores[] = $valores;

        }

        $consulta = $consulta . implode(', ', $arrayValores) . ";";

        $registros = $objcon->insertar($consulta);

        $error = $registros ? FALSE : TRUE;
    }        

    function cargada(){ 
        if (isset($this->cargada)){
            return $this->cargada;
        }else{
            $resp ="la linea base no ha sido cargada";
            return $resp;
        }        
    }
                 
}        

?>