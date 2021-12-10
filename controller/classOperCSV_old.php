<?php

require_once 'classObjCSV.php';
require_once 'classOperCSV.php';
require_once 'classTrasObj.php';

class OperCSV {

    public $fila =1;
    public array $encabezado;
    public array $campo_tabla;
    public $conf;  //variable huérfana cuando se crea el objeto OJO OJO OJO OJO
    public $fecha;
    public ObjCSV $objCSV3;

    function loadData(objCSV $obj){

        $this->objCSV3 = new ObjCSV();
        $tras = new TrasObj();
        $tras->traspasar($obj, $this->objCSV3);    
        $this->objCSV3=$obj;
        $a=4;
    }

    public function getFecha(){
        return $this->fecha;
    }

    public function getFileModifiedDate(){      
        return $this->fecha;        
    }

    public function getEncabezado(){       
        return implode(", ", $this->encabezado);
    }

    public function getEncabezadoArray(){       
        return $this->encabezado;
    }

    public function getNumeroDeCampos(){
        return count($this->encabezado);
    }

    public function getNumeroDeRegistros(){
        return $this->fila-1;
    }

    public function getRegistros(){
        return $this->campo_tabla;
    }

    public function getRegistrosJSON(){
        return json_encode($this->campo_tabla);
    }
    
    public function getEncabezadoJSON(){
        return json_encode($this->encabezado);
    }

    public function getAllJSON(){
        $columnas = count($this->encabezado);
        $filas = count($this->campo_tabla);
        
        $salida="[";

        for($i=1; $i<$filas+1; $i++){
            $salida=$salida . '{"id":' .  strval($i);            
            for ($j=0; $j<$columnas; $j++){
                $salida = $salida . ', ' . '"' . $this->encabezado[$j] . '":"' . $this->campo_tabla[$i][$j] . '"';
            }
            $salida= $salida . "}";
            if ($i < $filas) {
                $salida= $salida . ",";
            }
        }       
        $salida= $salida . "]";
        return $salida;
    }

    public function getAllTable($tableClass='class="table table-striped"'){

        require_once '../controller/utils/classConvertToTable.php';
        // Se pasan los parámetros para crear la tabla en un arreglo
        
        $param['encabezado'] = $this->encabezado;
        $param['campo_tabla'] = $this->campo_tabla;
        $param['clase'] = $tableClass;

        $obj = new ConvertToTable($param);
        return $obj->toTable();
    }    

    public function getObjCSV(){                 
        return $this->objCSV3;
    }
}

?>
