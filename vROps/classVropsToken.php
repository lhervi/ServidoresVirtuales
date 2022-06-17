<?php

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

/**
 * VropsToken
 * Esta clase regresa un objeto array con toda información del Token más un ['error´]=false si todo está bien, 
 * o ['error']=true si hubo algún problema en la creación del token mas ['mensaje'] describiendo el error
 * 
 * 
 */
class VropsToken{
    
    /**
     * getVropsToken
     *
     * Es una función estática que no recibe parámetros. Esta función regresa la información del token
     * en un array $tokenInfo
     * 
     * @return array $tokenInfo
     */    
    /**
     * getVropsToken
     *Regresa la información del Bearertoken que le entrega la aplicación vROps en un arreglo con las siguientes claves:
     * 'token' string
     * 'validity' int
     * 'expiresAt' string   
     * 'roles' array (sin valores en este caso)
     * @return void
     */
    static function getVropsToken(){

        include_once 'classVropsConf.php';
        include_once 'classCurl.php';
        include_once '../controller/utils/classDecodeJsonFile.php';
        include_once '../controller/utils/classBitacora.php';        
        
        //===== [CORREGIR] ======
        $confArch = DecodeJF::decodeJsonFile(HOME.VROPS."vROpsConf.json");
        if ($confArch['error']){
            $tokenInfo['error']=true;
            $tokenInfo['mensaje']=$confArch['mensaje'];
            return $tokenInfo;
        }else{
           return self::VropsParamToken();
        }
    }

    static function tokenOkfromVrops(string $token){
        $tokenArray = json_decode($token, true);
        if(is_array($tokenArray) && self::tokenOk($tokenArray)){
            return true;
        }else{
            return false;
        }
    }

    static function getTokenFromVrops(array $param=null, $tokenFresh=false){
        include_once 'classCurl.php';

        if (is_null($param)){
            $objConf = new VropsConf("tipoVmwareToken"); 
            $param = $objConf->getParam();
        }

        $curl = curl_init();

        $curlParam = array(
            CURLOPT_URL => $param['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_PROXY => $param['proxy'],
            CURLOPT_PROXYUSERPWD => $param['userproxy'],
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO => $param['certfirefox'],
            CURLOPT_CUSTOMREQUEST => 'POST',                       
            CURLOPT_POSTFIELDS => $param['campos'],             
            CURLOPT_HTTPHEADER => $param['header']            
        );

        curl_setopt_array($curl, $curlParam);
        
        $response = curl_exec($curl);
        
        /*
        $textError = '{"message":"The provided token for auth scheme \"vRealizeOpsToken\" is either invalid or has expired.","httpStatusCode":401,"apiErrorCode":1512}';
        $contador = 0;
        while($response == $textError && $contador<20){           
            
            $response = curl_exec($curl);   
            $contador++;         
        }
        */
        
        curl_close($curl);        
        file_put_contents(VMWARETOKENFILE, $response);

        if($tokenFresh){
            $tokenInfo = json_decode($response, true);
            $token = $tokenInfo['token'];
            file_put_contents(VMWARETOKENFILE, $response);
            return $token;
        }

        return self::tokenOkfromVrops($response);
    }

    static function VropsParamToken(){
        
        include_once 'classCurl.php';      
        
        $objConf = new VropsConf("tipoVmwareToken"); //arreglo con los datos de la configuración que provienen de vROpsConf.json
        $param = $objConf->getParam();
        //$curl = curl_init();        
        
        //Cambiar por una nueva función que obtenga el token
        //Curl::curlSetOpt($curl, $param); //configura el Curl
        //$result = curl_exec($curl);  //regresa true si no hubo error
        //curl_close($curl);
        $result = self::getTokenFromVrops($param);
        if ($result===true){

            $tokenInfo = self::getTokenFromFile();  

        }else{

            $tokenInfo['error']=true;
            $tokenInfo['mensaje']="hubo un problema al ejecutar el cURL";
            
        }
        return $tokenInfo; 
    }        
           
    
    
    /**
     * getTokenFromFile
     * 
     * Lee del archivo de configuración vROpsConf.json la ubicación del archivo que contiene el BearerToken y lo regresa en un 
     * arreglo junto con el estatus del error ['error']=false si está bien, o ['error']=true con un mensaje describiendo el error
     * si hubo algún problema
     *
     * @return array
     */
    static function getTokenFromFile(){
        include_once 'classVropsConf.php';
        include_once 'classCurl.php';
        include_once '../controller/utils/classDecodeJsonFile.php';
        include_once '../controller/utils/classBitacora.php';
        include_once '../controller/utils/classFechas.php';
        include_once '../constantes.php';

        $confArch = DecodeJF::decodeJsonFile(HOME.VROPS."vROpsConf.json"); //contiene la información del archivo de configuración
        
        if ($confArch['error']){

            $tokenInfo['error']=true;  //significa que no se pudo leer el archivo de configuración que contiene la ubicación y nombre del archivo del token.

            $tokenInfo['mensaje']=$confArch['mensaje'];            

            return $tokenInfo;

        }else{                          
        
            $fileName = HOME . SALIDAS . $confArch['VmwareToken'];
            $tokenInfo = DecodeJF::decodeJsonFile($fileName); //arreglo con la posible información del token o un mensaje de error
            
            if ($tokenInfo['error']){

                $tokenInfo['mensaje']=$tokenInfo['mensaje'];                

                return $tokenInfo;

            }else{

                $tokenInfo['tokenHeader'] = TOKENHEADER .  $tokenInfo['token'];

                $tokenInfo = self::validaToken($tokenInfo);

            }    

        }
                
        return $tokenInfo; //Regresa el token con ['error']=false o regresa ['error']=true con un mensaje describiendo el error
    }
        
    /**
     * tokenOk
     *Valida que el contenido del Token cumpla con lo que se espera, Recibe como parámetro un array con la información del token
     * regresa un booleano, true si todo está bien, false si hay algún error.
     * @param  mixed $tokenInfo
     * @return bool
     */
    static function tokenOk(array $tokenInfo){
        $TokenOk=strlen($tokenInfo['token'])>0 ? true : false; //valida que token tenga un valor                
        $ValidityOk= (is_integer($tokenInfo['validity']) &&  $tokenInfo['validity'] > 0) ? true : false; //verifica que el tiempo sea válido
        $expiresAtOk=strlen($tokenInfo['expiresAt'])>0 ? true : false; //valida que haya un tiempo de expirado 
        
        if ($TokenOk && $ValidityOk && $expiresAtOk){
            return true;            
        }else{            
            return false;
        }

    }
        
    /**
     * validaToken
     * Este método recibe un array con los datos del token y valida que todos los elementos que lo componen estén bien. En caso de error,
     * regresa la información en el arreglo recibido con la clave ['error]=true y el mensaje asociado ['mensaje']. Si el token está bien
     * se envía dentro del token la clave ['error]=false. En todo caso, la información original del token se mantiene.
     * @param  array $tokenInfo
     * @return array
     */
    static function validaToken(array $tokenInfo){

        $tokenInfo['error']=true; //Este valor cambiará si todo está bien
 
        if (array_key_exists('token', $tokenInfo) && array_key_exists('validity', $tokenInfo) && array_key_exists('expiresAt', $tokenInfo)){
           
            if (self::tokenOk($tokenInfo)){

                $plazoDelToken=Fechas::evalFechaToken($tokenInfo['expiresAt']);

                if (!$plazoDelToken['vencido']){ 
                    $tokenInfo['error']=false;  //El token está pérfecto y es válido          
                }else{                   
                    $tokenInfo['mensaje']="el token expiró";
                }
            }else{                
                $tokenInfo['mensaje']="la información del Bearetoken es inconsistente";
                                    
            }                
        }else{          
            $tokenInfo['mensaje']="hay información faltante en el Bearertoken";             
        }

        return $tokenInfo;
    }    
    
    /**
     * getToken
     * 
     * Método estático que entrega un arreglo con la información del token, chequeando si ya existe uno y es válido
     * o solicitando uno nuevo a la aplicación vROps. En caso de que todo esté bien, regresa dentro del arreglo una
     * clave de ['error']=false. Si hubo algún problema, regresa una clave de ['error']=true y un mensaje ['mensaje'] con 
     * la descripción del mismo.
     *
     * @return array
     */
    static function getToken(){
        
        $tokenInfo = self::getTokenFromFile(); //busca si ya existe un token
        
        if ($tokenInfo['error']){

            $tokenInfo = self::getVropsToken(); //pide un nuevo token a vROps

        }else{

            return $tokenInfo;

        }

        if($tokenInfo['error']){     

            $tokenInfo['mensaje']="no se pudo encontrar un BearerToken válido ni obtener uno nuevo";

            return $tokenInfo;
            
        }else{

            return $tokenInfo;

        }
        
    }
    

}
    
?>