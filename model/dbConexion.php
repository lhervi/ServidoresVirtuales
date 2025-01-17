<?php

class Conexion {

    private static $conexion;    

    public static function abrirConexion(){
	
		include_once "configdb.php";
		// D:\xampp\htdocs\CTISCR\model\dbConexion.php

        if (!isset(self::$conexion)){

            try{                
				
                self::$conexion = new PDO('pgsql:host='. SERVIDOR . ' ; port=' . PORT . '; dbname=' . BASE_DE_DATOS, USUARIO, PASSWORD);
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
    public static function insertar (string $consulta){  
        
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