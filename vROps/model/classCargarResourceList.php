<?php

/**
 * CargarResourceList  Clase que contiene métodos estáticos para procesar la información de la lista de recursos resourceList
 */
class CargarResourceList{

    
    /**
     * crearResourceListTable Función estática que crea la tabla vmware_recursos si no existe
     *
     * @return $result es un arreglo que
     */
    //=========================================================
    // CREAR TABLA  
    //=========================================================
    static function crearResourceListTable(){    
        
        include_once 'classVropsConnection.php';

        $consultaCreaTabla = "CREATE TABLE IF NOT EXISTS vmware_recursos (servidor VARCHAR NOT NULL, nombre VARCHAR NOT NULL, ";
        $consultaCreaTabla .= "recursos_id VARCHAR NOT NULL, adapterKindKey VARCHAR NOT NULL, tipo VARCHAR NOT NULL, ";        
        $consultaCreaTabla .= "linkToSelf VARCHAR NOT NULL, relationsOfResource VARCHAR NOT NULL, propertiesOfResource VARCHAR NOT NULL, "; 
        $consultaCreaTabla .= "alertsOfResource VARCHAR NOT NULL, symptomsOfResource VARCHAR NOT NULL, statKeysOfResource VARCHAR NOT NULL, "; 
        $consultaCreaTabla .= "latestStatsOfResource VARCHAR NOT NULL, latestPropertiesOfResource VARCHAR NOT NULL, credentialsOfResource VARCHAR NOT NULL)"; 

        $result = VropsConexion::insertar($consultaCreaTabla);  
        
        file_put_contents("consulta.txt","");

        return $result;
        
    }

    static function insertar($consultaInsert, $strInsert){
      include_once 'classVropsConnection.php';

      $consultaInsert .= implode(", ", $strInsert);
      file_put_contents("consulta.txt", $strInsert, FILE_APPEND);
      $result = VropsConexion::insertar($consultaInsert);
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

    static function insertRegistrosResourceList(array $registros){ //recibe un arreglo con los registos a insertar

      //include_once './classVropsConnection.php';
      include_once 'classVropsConnection.php';

      self::crearResourceListTable();
      
      $strInsert = array();
      
      $tope=0; //contador de registros para insertar el número máximo definido en la constante NUMREGINSERT
            
      $consultaInsert = self::iniConsulta();
      $registrosAlmacenados=0;
      
      foreach($registros as $campos){

        //contruir el string con los valores a insertar        
        $strInsert[] = "('" . implode("','", $campos) . "') ";
        
        $tope++; //tope se incrementa fuera del if y dentro del foreach
        
        if ($tope>NUMREGINSERT){ //inserta un lote de registros de acuerdo al tope definido como NUMREGINSERT
          
          $result = self::insertar($consultaInsert, $strInsert);

          if (!$result){
            die("ocurrió un error al intentar grabar a información de los resourceList en la BD");
          }else{
            $registrosAlmacenados+=$tope;
          }
          $tope=0;
          $strInsert=array();
          $consultaInsert = self::iniConsulta();
        }

      }

      if ($tope>0){  //inserta los últimos registros que hayan quedado fuera de los lotes 
        $result = self::insertar($consultaInsert, $strInsert);
        $registrosAlmacenados+=$tope;
      }
      return $registrosAlmacenados;
    }

    //=========================================================
    // OBTENER LA LISTA DE LOS RECURSOS DE UN ARCHIVO
    //=========================================================

    static function readResourceListArray(string $file,   bool $linkActive=false){
       
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
          return $arrayProv;    
        }
      }
}

?>