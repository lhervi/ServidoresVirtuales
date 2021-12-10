<?php

/**
 * ConvertToTable
 * Clase para convertir archivos a tablas html. Esta clase recibe en un arreglo, tres parámetros (encabezado, campo_tabla y clase)
 * Regresa un objeto ConvertToTable que posee un método toTable que regresa un string que es el HTML que construye la tabla
 */
class ConvertToTable {

    private $encabezado;
    private $campo_tabla;
    private $clase;

    function __construct($param)
    {
        $this->encabezado = $param['encabezado'];
        $this->campo_tabla = $param['campo_tabla'];
        $this->clase = $param['clase'];        
    }
    
    /**
     * toTable
     * Método de un objeto ConvertToTable que regresa un string que pinta una tabla
     * @return string
     */
    public function toTable(){
        $tableClass = $this->clase;
        $columnas = count($this->encabezado);
        $filas = count($this->campo_tabla);
        
        
        $salida="<Table " . $tableClass  . " >";

        $salida= $salida . "<thead><tr>";

        $enc = $this->encabezado;
        array_unshift($enc, "#");
        foreach($enc as $campo){
            $salida= $salida . "<th>" . $campo . "</th>";
        }

        $salida= $salida . "</tr></thead><tbody>";


        for($i=1; $i<$filas+1; $i++){
            $salida= $salida . "<tr><td>" . strval($i) . "</td>";            
            for ($j=0; $j<$columnas; $j++){
                $salida = $salida . "<td>" . $this->campo_tabla[$i][$j] . '</td>';
            }
            $salida= $salida . "</tr>";
            //if ($i < $filas) {
            //    $salida= $salida . "</>";
            //}
        }       
        $salida= $salida . "</tbody></table>";
        return $salida;
    }
}

?>