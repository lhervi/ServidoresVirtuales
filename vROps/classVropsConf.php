<?php

/**
 * VropsConf
 * Clase que con métodos para optener y modificar los parámtros del cURL desde el archivio de configuración vROps.json
 * 
 */
class VropsConf{    

    private $conf;

    function __construct(string $tipo="tipoVmwareToken", $resourceKinds=null, string $vropsServer=null){ 

        //include_once '../constantes.php';
        include_once HOME . '/controller/utils/classDecodeJsonFile.php';        
        
        $conf = DecodeJF::decodeJsonFile(ARCHIVODECONFIGURACION); //Se inicializa el objeto con el arreglo de configuración vROpsConf.json        
              
        if ($conf['error']){
            $this->conf['error']=true;
            $this->conf['mensaje']="el objeto no pudo crearse debido a que no se pudo leer el archivo de configuración";
        }else{

            foreach($conf as $ind=>$val){           
                $this->conf[$ind] = $val;
            }
            
            $this->conf['tipo']=$tipo; //asigna el tipo de configuración que determina el url, el archivo de salida y el tipo de petición según lo definiod en vROpsConf.json
            
            if (!is_null($resourceKinds)){
                $this->setResourceKinds($resourceKinds);                
                $this->setStatKey($this->conf[$resourceKinds]);                   
            }

            if (!is_null($vropsServer)){
                $this->conf['vropsServer'] = $vropsServer;         
            }            

        }

    }

    //--------------------------- métodos vropsServer ---------------------------//

    function getVropsServers(){  //método que retorna un array de strings con la lista de los servidores vrops                
        return $this->conf["vropsServers"];        
    }

    function getVropsServer(){  //método que retorna un string con el nombre del servidor vrops actual
        return $this->conf["vropsServer"];        
    }

    function setVropsServer($vropsServer){  //método que permite inyectarle el nombre del servidor vrops a un objeto conf
        $this->conf['vropsServer'] = $vropsServer;      
    }

    //--------------------------- fin métodos vropsServer ---------------------------//

    //--------------------------- métodos resourceKinds ---------------------------//
    
    function setResourceKinds($resourceKinds){  //Le asigna un resourceKind al objeto ["virtualmachine"|"hostsystem"]
        $this->conf['resourceKinds']=$resourceKinds;
    }
    
    function getResourceKinds(){  //Le asigna un resourceKind al objeto ["virtualmachine"|"hostsystem"]
        
        if (array_key_exists('resourceKinds', $this->conf)){
            return $this->conf['resourceKinds'];
        }else{
            return null;
        }        
    }

    //--------------------------- fin métodos resourceKinds ---------------------------//

    function getStatKey(){
        $rk=$this->getResourceKinds();
        if (!is_null($rk)){
            return $this->conf[$rk];
        }else{
            return null;
        }
    }

    function getError(){
        if (array_key_exists('error', $this->conf)){
            return $this->conf['error'];
        }else{
            return null;
        }        
    }

    function getMensaje(){
        if (array_key_exists('mensaje', $this->conf)){
            return $this->conf['mensaje'];
        }else{
            return null;
        }       
    }

    function getHeader(){
        return $this->conf['header'];
    }

    function setTipo($tipo){
        $this->conf['tipo'] = $tipo;
    }
    
    /**
     * setToken
     * Permite añadir el valor del token al objeto VrofConf
     * esta información será tomada cuando se emplee el método "getParam($tipo)"
     * 
     * @param  mixed $token
     * @return void
     */

    function setGET(bool $val){
        $this->conf[$this->tipo]['GET']=$val;
    }
    
    //--------------------------- métodos token ---------------------------//
    /**
     * setToken
     * Este método pertenece a la clase VropsConf, recibe un string con la información del Token para asignar el
     * valor al objeto instanciado de esta clase. 
     * 
     * @param  string $token
     * @return void
     */
    function setToken(string $token){
        include_once '../constantes.php';
        
        $this->conf['token']=$token;
        $this->conf['header'][] = TOKENHEADER . $token;        
    }    
        
    /**
     * getToken
     *
     * @return void
     */
    function getToken(){
        if (array_key_exists('token', $this->conf)){            
            return $this->conf['token'];
        }else{
            return null;
        }
    }

    //--------------------------- fin métodos token ---------------------------//

    //--------------------------- métodos nombre de archivo ---------------------------//

    function setNomArch(string $arch){
        $this->conf[$this->tipo]['arch']=$arch;
    }

    function getNomArch(){
        //include_once  '/var/www/html/CTISCR/constantes.php'; ///var/www/html/CTISCR/vROps/classVropsConf.php      
        if(($this->conf['tipo']=="tipoMediciones") || ($this->conf['tipo']=="tipoResourceKinds")){
            $arch = HOME . SALIDAS . $this->getResourceKinds() . $this->conf[$this->conf['tipo']]['arch'];
        }else{
            $arch = HOME . SALIDAS . $this->conf[$this->conf['tipo']]['arch'];
        }
        return $arch;
    }  

    //--------------------------- fin métodos nombre de archivo ---------------------------//

    //-------------------------------- método URL -----------------------------------------//
    
    //Este método es candidato a ser eliminado!! [ ¿ *** ELIMINAR *** ? ]
    function setUrl(string $url){ 
       $this->conf[$this->tipo]['url'] =  $this->conf->getVropsServer() . $url;
    }

    //---------------------------- fin del método URL -------------------------------------//

    function getUserBA(){
        return $this->conf['userBA'];
    }

    function getPasswordBA(){
        return $this->conf['passwordBA'];
    }  

    function getUserProxy(){        
        return $this->getUserPasswordBA();
    }   

    function getUserPasswordBA(){        //usuario y password de la red (usuario BA) 
        //return json_encode(array('username' => $this->getUserBA(), 'password' => $this->getPasswordBA()));
        return "{'username':'" . $this->getUserBA() . "', 'password':'" . $this->getPasswordBA() . "'}";
    } 

    function getUserVrops(){                   
       $user = (array('username' => $this->conf['userVrops'], 'password' => $this->conf['passwordVrops'], 'authSource' => $this->conf['authSource']));
       $jsonUser = json_encode($user);
       return $jsonUser;
    } 

    function getProxy(){        
        return $this->conf['proxy'];
    }

    function UserPass($user, $pass){	
        return $user . ":" . $pass;
    }     
    //certfirefox
    function getCertfirefox(){	
        include_once __DIR__ . '/../constantes.php';
        return HOME.VROPS.$this->conf['certfirefox'];
    }

    function addItemsToCampos(array $campos){
        foreach($campos as $ind=>$campo){
            $this->conf[$ind]=$campo;
        }        
    }    

    function getUrl(){               
        $tipo = $this->conf['tipo'];
        $url = $this->getVropsServer();
        $url.= $this->conf[$tipo]['url'];
        if($tipo=="tipoResourceKinds"){
            $url .= $this->getResourceKinds();
            $url .= URLTAIL;         
        }
        return $url;
    }

    function getGet(){
        $tipo = $this->conf['tipo'];
        return $this->conf[$tipo]['GET'];
    }

    function setCampos(string $campos){        
        $this->conf['campos']=$campos;                
    }

    function setStatKey(array $statKey){
        $this->conf['statKey']=$statKey;
    }
    
    /**
     * getParam
     * Este método pertenece a la clase VropsConf y recibe como parámetro en $tipo de tipo string la información
     * de la conexión a realizar, que puede ser: 
     * 
     * **"inicio"**, **"consulta"**, **"mediciones"**, **"metricas"**, **"resourceKinds"**
     * 
     * Toma el resto de los valores del archivo de configuración vROpsConf.JSON
     * 
     * Regresa un arreglo con los valores para configurar el Curl según sea el caso definido por $tipo
     * 
     * 
     * @return array
     */    
    /**
     * getParam
     * Este método pertenece a la clase VropsConf 
     * Asigna todos los valores para realizar el cURL tomándolos del archivo del objeto que se crea a partir del archivo de configuración.
     * 
     * @param  string $tipo
     * @return array
     */
    function getParam(){        
        
        $param=[];          

        $param['header']= $this->conf['header'];
        $param['proxy']= $this->conf['proxy'];
        $param["vropsServers"] = $this->conf["vropsServers"];
        $param['userproxy']= $this->getUserProxy();        
        $param['certfirefox']= $this->getCertfirefox();
        $param['userpassword']= $this->getUserPasswordBA();
        $param['tipo']=$this->conf['tipo'];

        if (array_key_exists('statKey', $this->conf)) {
            $param['statKey']= $this->conf['statKey'];            
        }
       
        if (array_key_exists('token', $this->conf)){
            $param['token']= $this->conf['token'];            
        }else{
            $param['campos']=$this->getUserVrops();  //contiene el usuario, el password y tipo de autenticación vrops
        }
        if (array_key_exists('campos', $this->conf)) {
            $param['campos']= $this->conf['campos'];            
        }  
        $param['GET']=$this->getGet(); //Después de actualizar los parámetros de acuerdo al tipo es que se asigna GET
        $param['url']=$this->getUrl();
        $param['arch']=$this->getNomArch();        
        
        return $param;
    }     
    
    /**
     * getCampo
     * Método estático que retorna uno o varios campos del archivo de configuración.
     * Si se envía el nombre de un campo, regresa el valor en un arreglo con las siguiente caracteríticas:
     * ['error'] = false indicando que no hubo error y [$campo] con el valor del campo 
     * $campo es el índice del arreglo asociativo que corresponde con el nombre del campo)
     * 
     * Si hay error, regresa ['error'] = true y ['mensaje'] = con la descripción del error
     * 
     * @param  string $campo  --->cuando se desea recuperar un solo campo
     * @param  array $campos  --->cuando se desea recuperar una lista de campos
     * @param string $tipo ---> Cuando el campo a recuperar sea el *url 
     * @param string $resourceKinds indispensable si el url a recuperar es de tipo resourceKinds
     * @return array ['error'] = false y ['campo'] si todo está bien ó [mensaje]
     * 
     */
    static function getCampo(string $campo=null, array $campos=null, $tipo=null, $resourceKinds=null){
        
        include_once HOME . "/constantes.php";         
        include_once HOME . "/controller/utils/classDecodeJsonFile.php";        
                
        $conf = DecodeJF::decodeJsonFile(ARCHIVODECONFIGURACION);
        
        if ($conf['error']){
            $error['error'] = true;
            $error['mensaje'] = "no se pudo acceder al archivo de configuración";            
            return $error;        
        }
        
        if(!is_null($campo)){
           
            if ($campo==="url"){    
                if(!is_null($tipo)){
                    $url = $conf[$tipo]['url'] . $resourceKinds;
                    $resp['url'] = $url;
                    $resp['error'] = false;
                    return $url;
                }else{
                    $error['error'] = true;
                    $error['mensaje'] = "debe conocerse el tipo para determinar el url";
                    return $error;
                }         
            }else{
            $resp[$campo] = $conf[$campo];
            $resp['error'] = false;
            return $resp;
            }

        }elseif(!is_null($campos)){
           
            foreach($conf as $valor){
                $resp[$valor] = $conf[$valor];
            }        

            $resp['error'] = false;
            return $resp;

        }else{

            $error['error'] = true;
            $error[$campo] = "los campos de entrada están vacíos o dañados";
            return $error;

        }
    }        
}

?>