<?php

class VropsConexion {    

    private static $conexion;

    public static function abrirConexion(){

        if (!isset(self::$conexion)){

            try{                
                include_once 'vropsConfigDB.php';

                self::$conexion = new PDO('pgsql:host='. HOSTBDVROPS . ' ;port=' . PORT . '; dbname=' . BASEDEDATOS, USUARIO, PASSWORD);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conexion->exec("SET NAMES 'utf8'");

            }
            catch(PDOException $ex){
                print "ERROR" . $ex->getMessage() . "<br>";
            }
        }
    } 

    public static function cerrarConexion(){
        if (isset(self::$conexion)){
            self::$conexion=null;
        }
    } 

    public static function obtenerConexion(){
        if (isset(self::$conexion)){
            return self::$conexion;
        }else{
            self::abrirConexion();
            if (isset(self::$conexion)){
                return self::$conexion;
            }else{
                die("La conexión no pudo ser establecida");
            }
        }        
    } 

    public static function consultar (string $consulta){          
        
        if ($consulta!==null && $consulta!==""){
            $con = self::obtenerConexion();     
            
            try{
                $result = $con->prepare($consulta);
                $result->execute();
                $datos = $result->fetchAll();                
                return $datos;                              
            }catch(PDOException $e){
                $datos[0] = TRUE;
                $datos[1]= "hubo un error al realizar la consulta. " . $e->getMessage();
                return $datos;
            }
                  
        }else{
            $datos[0] = TRUE;
            $datos[1]= "la consulta está vacía";
            return $datos;
        }
    }

    //Esta función además de servir para insertar registros de la linea base, sirve también para crear 
    //la tabla en caso de que la misma no haya sido creada.    
    /**
     * insertar es una función estática que inserta registros en la BD, sin necesidad de crear la conexión
     * 
     * @param  string $consulta la cadena que contiene la consulta a realizar
     * @return true or false dependiendo el resultado de la operación
     */
    public static function insertar (string $consulta){

        file_put_contents("consulta2.txt", $consulta);

        //                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           die;
        
        if ($consulta!==null && $consulta!==""){

            $con = self::obtenerConexion();
            
            try{
                    
                $result = $con->prepare($consulta);
               
                $registros = $result->execute();

                return $registros;

            }catch(PDOException $e){

                die("hubo un problema, error: " . $e->getMessage() . "<br>");
            }
            
        }else{
            die("algo no está bien, la consulta está vacía o no hay información que insertar en la BD");
        }     
    }      

}

?>