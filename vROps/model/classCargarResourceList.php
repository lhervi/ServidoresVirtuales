<?php

/**
 * CargarResourceList  Clase que contiene métodos estáticos para procesar la información de la lista de recursos resourceList
 */
class CargarResourceList{

    static int $contadorDeRegistros=0;
    /*
    function __construct()
    {
      self::$contadorDeRegistros=0;
    }
    */

    //================ BORRAR TABLA ===============================
    
    static function eliminarResourceListTable($mesConsulta){    
        
      include_once 'classVropsConnection.php';

      $consultaEliminaTabla = "DROP TABLE IF EXISTS vmware_recursos_". $mesConsulta;

      $result = VropsConexion::insertar($consultaEliminaTabla);

      return $result;
      
  }


    /**
     * crearResourceListTable Función estática que crea la tabla vmware_recursos si no existe
     *
     * @return array $result es un arreglo con el resultado de la consulta
     */
    //=========================================================
    // CREAR TABLA  
    //=========================================================
    static function crearResourceListTable($mesConsulta){    
        
        include_once 'classVropsConnection.php';

        $consultaCreaTabla = "CREATE TABLE IF NOT EXISTS vmware_recursos_" . $mesConsulta . " (servidor VARCHAR NOT NULL, nombre VARCHAR NOT NULL, ";
        $consultaCreaTabla .= "recursos_id VARCHAR NOT NULL, adapterKindKey VARCHAR NOT NULL, tipo VARCHAR NOT NULL, ";        
        $consultaCreaTabla .= "linkToSelf VARCHAR NOT NULL, relationsOfResource VARCHAR NOT NULL, propertiesOfResource VARCHAR NOT NULL, "; 
        $consultaCreaTabla .= "alertsOfResource VARCHAR NOT NULL, symptomsOfResource VARCHAR NOT NULL, statKeysOfResource VARCHAR NOT NULL, "; 
        $consultaCreaTabla .= "latestStatsOfResource VARCHAR NOT NULL, latestPropertiesOfResource VARCHAR NOT NULL, credentialsOfResource VARCHAR NOT NULL)"; 

        $result = VropsConexion::insertar($consultaCreaTabla);  
        
        //file_put_contents("consulta.txt","");[ELIMINAR]

        return $result;
        
    }

    //======================== TEST ========================
    static function yaEstanLosDatos(array $registros){

      include_once __DIR__ . '/../../constantes.php'; 
      $iniConsultaPrueba = "SELECT recursos_id From vmware_recursos WHERE recursos_id IN ";   
      $finConsultaPrueba = ";";
      $arrayReg= array();
      foreach($registros as $ind=>$reg){
        if($ind == NUMREGPARACOMPROBAR) break;  //Tope de la cantidad de registros para comprobar
        $arrayReg[] = $reg['identifier'];        
      }
      
      $valConsulta =  "('" . implode("','", $arrayReg ) . "') ";
      
      $consulta = $iniConsultaPrueba . $valConsulta . $finConsultaPrueba;

      $resultConsulta = VropsConexion::consultar($consulta);

      $esArray= is_array($resultConsulta);
      $hayCero = array_key_exists(0, $resultConsulta);
      
      if($hayCero){
        $esVerdadero = $resultConsulta[0] === true;
      }else{
        $esVerdadero=false;
      }      

      if($esArray && $hayCero && $esVerdadero){
        return false;
      }else{
        return true;
      }
      
    }
    //======================== FIN TEST ========================

    //static function insertar($consultaInsert, $strInsert){
    static function insertar($strInsert){
      include_once 'classVropsConnection.php';
      $valoresConsulta = implode(", ", $strInsert);
      $consultaInsert = self::iniConsulta() . $valoresConsulta;     

      $result = VropsConexion::insertar($consultaInsert);
      //file_put_contents("consulta.txt", $strInsert, FILE_APPEND);[ELIMINAR]
      return $result;
    }
    

    //=========================================================
    // INICIALIZAR LA CONSULTA
    //=========================================================

    static function iniConsulta(){
      $consultaInsert = "INSERT INTO vmware_recursos (servidor, nombre, recursos_id, adapterKindKey, tipo, ";
      $consultaInsert .= "linkToSelf, relationsOfResource, propertiesOfResource, "; 
      $consultaInsert .= "alertsOfResource, symptomsOfResource, statKeysOfResource, "; 
      $consultaInsert .= "latestStatsOfResource, latestPropertiesOfResource, credentialsOfResource) "; 
      $consultaInsert .= "VALUES ";
      return $consultaInsert;
    }

    //=========================================================
    // INSERTAR DATOS 
    //=========================================================

    static function insertRegistrosResourceList(array $registros, $mesConsulta){ //recibe un arreglo con los registos a insertar
      
      include_once 'classVropsConnection.php';

       $result = self::eliminarResourceListTable($mesConsulta);


        self::$contadorDeRegistros=0;

        self::crearResourceListTable($mesConsulta); 
        
        $strInsert = array();
        
        $tope=0; //contador de registros para insertar el número máximo definido en la constante NUMREGINSERT
              
        //$consultaInsert = self::iniConsulta();
        $registrosAlmacenados=0;
        
        foreach($registros as $campos){

          //contruir el string con los valores a insertar        
          $strInsert[] = "('" . implode("','", $campos) . "') "; 
          
          $tope++; //tope se incrementa fuera del if y dentro del foreach
          
          if ($tope>NUMREGINSERT){ //inserta un lote de registros de acuerdo al tope definido como NUMREGINSERT

            self::$contadorDeRegistros+=NUMREGINSERT;
            
            //=====================================================
            $result = self::insertar($strInsert);
            //=====================================================

            if (!$result){
              die("ocurrió un error al intentar grabar a información de los resourceList en la BD");          
            }
            $tope=0;
            $strInsert=array();          
          }
        }

          if ($tope>0){  //inserta los últimos registros que hayan quedado fuera de los lotes 
            //======================================================
            $result = self::insertar($strInsert);
            //===================================================
            
            self::$contadorDeRegistros+=$tope;

          }      
          return self::$contadorDeRegistros; //Número de registros(recursos) procesados        
             
    }

    //=========================================================
    // OBTENER LA LISTA DE LOS RECURSOS DE UN ARCHIVO
    //=========================================================

    static function readResourceListArray(string $file,   bool $linkActive=false, $vaciar=true){
       
        include_once __DIR__ . '/../../constantes.php';        
        include_once HOME . '/controller/utils/classDecodeJsonFile.php';        
      
        $array = DecodeJF::decodeJsonFile($file);
        //$file = "/var/www/html/STISCR/vROps/salidas/allResourceList.json"
        
        $prov = array();
        $arrayProv = array();
        $prefix = "<a href=http://" . HOSTVROPS; 
        
        
        if($array['error']){
          return $array();
        }else{
            foreach($array as $reg){      
                if($reg==false || is_null($reg) || $reg['name']==""){
                  continue;
                } 
                $prov['vropsServer'] = $reg['vropsServer'] ?? "";
                $prov['name'] = $reg['name'] ?? "";
                $prov['identifier'] = $reg['identifier'] ?? "";
                $prov['adapterKindKey'] = $reg['adapterKindKey'] ?? "";
                $prov['resourceKindKey'] = $reg['resourceKindKey'] ?? "";           
                $prov['linkToSelf'] = $reg['links']['linkToSelf'] ?? "";          
                $prov['relationsOfResource'] = $reg['links']['relationsOfResource'] ?? "";
                $prov['propertiesOfResource'] = $reg['links']['propertiesOfResource'] ?? "";
                $prov['alertsOfResource'] = $reg['links']['alertsOfResource'] ?? "";
                $prov['symptomsOfResource'] = $reg['links']['symptomsOfResource'] ?? "";
                $prov['statKeysOfResource'] = $reg['links']['statKeysOfResource'] ?? "";
                $prov['latestStatsOfResource'] = $reg['links']['latestStatsOfResource'] ?? "";
                $prov['latestPropertiesOfResource'] = $reg['links']['latestPropertiesOfResource'] ?? "";
                $prov['credentialsOfResource'] = $reg['links']['credentialsOfResource'] ?? "";   
      
                if($linkActive){  //si linkActive es igual a true, crea enlaces HTML en el campo de enlaces
                  $salto=0;
                  foreach($prov as $ind=>$campo){
                    if ($salto<4){
                      $salto++;
                      continue;
                    }            
                    $prov[$ind]= $prefix . $campo . ">" . $ind . "</a>";          
                  }         
                }
                
                $arrayProv[]=$prov;
                
              }
          if ($vaciar){
            file_put_contents($file, ""); //Se vacía el archivo de la lista de recursos al finalizar la carga 
          } 
          //============== SALIDA ===================================         
          return $arrayProv; //regresa un arreglo de arreglos con la información
          //============== SALIDA ===================================         
        }
      }
}

?>