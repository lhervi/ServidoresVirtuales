<?php

/**
 * JsonToTable
 * Clase para crear convertir un archivo json a una tabla
 */
class JsonToTable{

    private $camposArray;
    private $param;
    
    /**
     * __construct
     * **param[] puede contener:
     * **param['class'] el nombre de la clase css (bootstrap )a emplear en la tabla 
     * **param['caption'] el nombre de la tabla
     * **param["maxCeldaPorFila"] el número máximo de celdas por fila (caso de campos json que contienen array)
     * *Tipos de tablas definidas como constantes:
     * "TABLE", "table table-bordered", "TABLEDARK", "table table-dark" 
     * "TABLEDARKESTRIPED", "table table-striped table-dark"
     * "TABLESTRIPED", "table table-striped", "TABLEHOVER", "table table-hover",
     * "TABLEHOVERDARK", "table table-hover table-dark", "TABLESMALLDARK", "table table-sm table-dark"
     * "TABLESMALL", "table table-sm");
     * 
     *
     * @param  string $nomFile //un string que contiene el nombre y la ruta de un archivo json
     * @param  mixed $param //un array que contiene parámetros que modifican la tabla que se obtiene del objeto
     * @return void
     */
    function __construct(string $nomFile, array $param=null)
    {
        $this->camposArray = json_decode(file_get_contents($nomFile), true); //Campos del archivo json
        $this->param = $param;  //Parámetros recibidos en el arreglo        
    }

    //-----------------------------------------------------------------------
    
    /**
     * getClass
     * Regresa un string que contiene la información de la clase css de un objeto de la clase JsonToTable
     * @return string
     */
    function getClass() {

        if(!is_null($this->param) && array_key_exists('class', $this->param) && $this->param['class']!=""){
            
            return $this->param['class'];

        }elseif(array_key_exists('class', $this->camposArray) && $this->param['class']!=""){      

            return $this->camposArray['class'];            

        }else{
            return null;
        }
        
    }

    //-----------------------------------------------------------------------    
    
    /**
     * getCaption
     * Regresa el string que contiene el título de la tabla a construir. 
     * Es un método propio de un objeto de la clase JsonToTable.
     * En caso de que no haya un título para la tabla, regresa null
     * 
     * @return string
     */
    function getCaption() {

        if(!is_null($this->param) && array_key_exists('caption', $this->param) && $this->param['caption']!=""){
            
            return $this->param['caption'];

        }elseif(array_key_exists('caption', $this->camposArray) && $this->param['caption']!=""){      

            return $this->camposArray['caption'];            

        }else{
            return null;
        }
        
    }

    //-----------------------------------------------------------------------
    
    /**
     * JsonToTable
     * Función que regresa como string la información de un archivo json
     *
     * @return string  //Contiene el string que pinta la tabla
     */
    function getTable(){
        
        //$param ['caption'] contiene el título de la tabla
        /**Funcionamiento
        * Lee el contenido de un archivo Json, y crea una tabla colocando como título el caption que se pase
        * por parámetro en $param. Este arreglo también recibe el nombre de la clase a emplear (disponible en bootsrap)
        */

        $idCount =0;

        $id = function(){            
            return $this->idCount++;
        };
    
        $camposArray = $this->camposArray;
        $class = $this->getClass();
        $caption = $this->getCaption();
        $max = array_key_exists("maxCeldaPorFila", $this->param) ? $this->param["maxCeldaPorFila"] : 4 ;
                
        $table = "<table class='" . $class . "'>";
        $table.= "<caption>". $caption . "</caption>";
        
        foreach($camposArray as $clave=>$campo){
           
            $table .= "<thead><tr><th>";            
            $table .= $clave; //encabezado
            $table .= "</th></tr></thead>";
            
            $table .= "<tbody><tr>";          

            if(is_array($campo)){  
                $m=0;             
                foreach($campo as $ind=>$valor){                   
                    
                   if ($m < $max){
                        $table .= "<td>" . strval($valor) . "</td>";
                        $m++;
                    }else{
                        $m=0;
                        $table .= "</tr><tr>";
                        $table .= "<td>" . strval($valor) . "</td>";
                    }
                }
                $table .= "</tr></tbody>";
            }else{
                $table .= "<td>" . strval($campo) . "</td>"; 
                $table .= "</tr></tbody>";               
            }            
        }

        $table.= "</table>"; //Cierra la tabla

        return $table; //regresa el string con la tabla
    }
    
    
}

?>