<?php

require_once 'classObjCSV.php';
require_once 'classOperCSV.php';
require_once 'classTrasObj.php';

class OperCSV {

    public ObjCSV $objCSV3;

    function __construct(objCSV $obj)
    {
        $this->objCSV3 = new ObjCSV();
        $tras = new TrasObj();
        $tras->traspasar($obj, $this->objCSV3);    
        $this->objCSV3=$obj;

        //return $this; 

    }   

    public function getFecha(){
        return $this->objCSV3->fecha;
    }

    public function getFileModifiedDate(){      
        return $this->objCSV3->fecha;        
    }

    public function getEncabezado(){       
        return implode(", ", $this->objCSV3->encabezado);
    }

    public function getEncabezadoArray(){       
        return $this->objCSV3->encabezado;
    }

    public function getNumeroDeCampos(){
        return count($this->objCSV3->encabezado);
    }

    public function getNumeroDeRegistros(){
        return $this->objCSV3->fila-1;
    }

    public function getRegistros(){
        return $this->objCSV3->campo_tabla;
    }

    public function getRegistrosJSON(){
        return json_encode($this->objCSV3->campo_tabla);
    }
    
    public function getEncabezadoJSON(){
        return json_encode($this->objCSV3->encabezado);
    }

    public function getAllJSON(){
        $columnas = count($this->objCSV3->encabezado);
        $filas = count($this->objCSV3->campo_tabla);
        
        $salida="[";

        for($i=1; $i<$filas+1; $i++){
            $salida=$salida . '{"id":' .  strval($i);            
            for ($j=0; $j<$columnas; $j++){
                $salida = $salida . ', ' . '"' . $this->objCSV3->encabezado[$j] . '":"' . $this->objCSV3->campo_tabla[$i][$j] . '"';
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
        
        $param['encabezado'] = $this->objCSV3->encabezado;
        $param['campo_tabla'] = $this->objCSV3->campo_tabla;
        $param['clase'] = $tableClass;

        $obj = new ConvertToTable($param);
        return $obj->toTable();
    }    

    public function getObjCSV(){                 
        return $this->objCSV3;
    }
}

?>
