<?php

class TrasObj{
    //EL contenido del primer objeto pasa al segundo
    static function traspasar($obj1, $obj2){

        require_once 'classObjCSV.php';

        $obj2->encabezado = $obj1->encabezado;
        $obj2->campo_tabla = $obj1->campo_tabla;
        
        $obj2->campo_tabla = $obj1->campo_tabla;
       
        $obj2->fila = $obj1->fila;  //campo en blanco
       
        $obj2->fecha = $obj1->fecha;
        $obj2->conf = $obj1->conf;
    }

}

?>