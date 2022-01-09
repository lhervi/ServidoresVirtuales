<?php

date_default_timezone_set("America/Caracas");

/**
 * Fechas
 * Clase con funciones estáticas para la manipulación de fechas. Contiene los siguientes métodos estáticos:
 * 
 * *static function toStringFechaToken($fec){}: recibe una fecha tipo string, tal como el Bearertoken y
 * la regresa como una cadena que puede ser transformada a formato timestamp.
 * 
 * *static function toStringFechaAhora(){}: regresa la fecha actual en un string que puede ser transformado a
 * timestamp 
 * 
 * *static function diferenciaFechas($fecMayor, $fecMenor){}: recibe una fecha posterior y una reciente, y regresa
 * un arreglo con las diferencias en horas, minutos o segundos entre las dos fechas. 
 * Importante: Este método no contempla fechas que excedan semanas
 * semanas.
 * 
 * *static function fechaQuery(string $tipo="hoy"){} Regresa una fecha en formato timestamp * 1000
 * 
 */

class Fechas{
    
    /**
     * toStringFechaToken
     *Función que regresa recibe un string trasnformable a formato fecha, específicamente la fecha expiredAt del
     *BearerToken Ejemplo de valor de entrada: "Thursday, August 26, 2021 9:09:33 PM VET", el resultado es una 
     * "2021-8-26 21:09:33", una fecha en formato string
     * 
     * @param  mixed $fec
     * @return string
     */
    static function toStringFechaToken($fec){
        $fechaExpArray= date_parse($fec);
        $fec=$fechaExpArray['year'] . "-" . $fechaExpArray['month']. "-" . $fechaExpArray['day']. " ";
        $fec.= $fechaExpArray['hour'] . ":" . $fechaExpArray['minute']. ":" . $fechaExpArray['second'];    
        return $fec;
    }
    
    /**
     * toStringFechaAhora
     *Regresa la fecha actual en formato string. Ejemplo de salida: "2021-8-26 21:09:33"
     
     * @return string
     */
    static function toStringFechaAhora(){
        $ahora=getdate();
        $now = $ahora['year'] . "-" . $ahora['mon']. "-" . $ahora['mday']. " ";
        $now .= $ahora['hours'] . ":" . $ahora['minutes']. ":" . $ahora['seconds'];
        return $now;
    }
    
    /**
     * diferenciaFechas
     * Recibe dos fechas en formato string y regresa un arreglo con la diferencia en horas, o minutos o segundos
     * entre ambas fechas. El arreglo de salida tiene las siguientes claves:
     * $res['horas'], $res['minutos'], $res['segundos']
     * 
     * @param  string $fecMayor
     * @param  string $fecMenor
     * @return array
     */
    static function diferenciaFechas(string $fecMayor, string$fecMenor){
        
        $d1 = new DateTime($fecMayor);
        $d2 = new DateTime($fecMenor);

        $vencido = ($d1<$d2) ? true : false;
        
        $interv = $d1->diff($d2);        
        
        $res['horas'] = ($interv->d * 24) + $interv->h;

        $res['minutos'] = ($res['horas'] * 60) + $interv->i;

        $res['segundos'] = ($res['minutos'] * 60) + $interv->s;

        $res['vencido'] = $vencido;

        return $res;
        
    }
    
    /**
     * evalFechaToken
     * Este método estático recibe el valor del "expiresAt" del BearerToken y regresa un arreglo
     * con el tiempo restante en horas, minutos o segundos con las siguientes claves:
     * $difFec['horas'], $difFec['minutos'], $difFec['segundos']
     * 
     * 
     * @param  mixed $fec
     * @return array
     */
    static function evalFechaToken(string $fec){
        $ft=self::toStringFechaToken($fec);
        $difFec=self::diferenciaFechas($ft, self::toStringFechaAhora());
        return $difFec;
    }
    
    /**
     * fechaQuery
     * Método estático que regresa la fecha de hoy * 1000 si no recibe parámeto o recibe "hoy".
     * Regresa la fecha colocando como día "01" si recibe un parámetro string distinto a "hoy".
     * Regresa la fecha en formato string si el parámetro str es verdadero. Ejemplo "14-10-2021"
     * 
     * @param  string $tipo
     * @return timestamp*1000
     */
    static function fechaQuery(string $tipo="hoy", bool $str=false){

               
        $ahora=getdate();

        $dia = $tipo=="hoy" ? $ahora['mday'] : "01";

        $fec = $ahora['year'] . "-" . $ahora['mon']. "-" . $dia;

        if ($str){
            return $fec;  //Regresa la fecha en formato string
        }else{
            return strtotime($fec) * 1000;   //Regresa la fecha en formato timestamp * 1000
        }
    }
    
    /**
     * fechaHoy
     * Función que regresa un string con la fecha de hoy en formato corto o largo}
     * 
     * @param  string $tipo Si el parámetro opcional tipo es igual a "completa" regresa un string con la fecha y hora del momento
     * @param  string $separador ["-"|"/"...] el caracter que desee usarse como separador
     * @return string regresa un string con el formato año-mes-dia ó año-mes-dia hora:minutos
     * Si no se indica el parámetro separador, pasa todo junto.
     */
    static function fechaHoy($tipo=null, $separador=null){
        $ahora=getdate();
        if(is_null($separador)){
            $separadorHora="";
            $separador="";
            $espacio="";
        }else{           
            $espacio=" ";
            $separadorHora=":";
        }        
        
        if(is_null($tipo)){
            return $ahora['year'] . $separador . $ahora['mon'] . $separador . $ahora['mday'];
        }elseif($tipo=="completa"){          
            return $ahora['year'].$separador.$ahora['mon'] . $separador . $ahora['mday'] . $espacio. $ahora['hours'] . $separadorHora . $ahora['minutes'];
        }
    }

    static function splitMesAño(string $mesAño){
               
        $patronMesAño ="/([(0-9)]{2}-[(0-9)]{4}){1}/";
        
        $patronAñoMes = "/([(0-9)]{4}-[(0-9)]{2}){1}/";
       
        $matches = array();
        $resp['error'] =false;
        if(preg_match($patronMesAño, $mesAño, $matches)){                        
            $resp['mes'] = substr($mesAño, 0, 2);
            $resp['año'] = substr($mesAño, 3, 4);
            return $resp;
        }elseif(preg_match($patronAñoMes, $mesAño, $matches)){
            $resp['año'] = substr($mesAño, 0, 4);
            $resp['mes'] = substr($mesAño, 5, 2);
            return $resp;
        }else{
            $error['error']=true;
            $error['mensaje']="no hay un patrón mes año ó año mes en el parámetro de entrada";
            return $error;
        }
    }
    
    /**
     * getDatefromMiliSeconds Método estático que regresa una fecha válida en formato string
     *
     * @param  int $fecMiliSeconds el entero que representa una fecha en milisegundos
     * @param  string $tipo un string con el formato de fecha de salida. Si no se pasa el formato de salida es año/mes/dia hora:min:seg "Y/m/d H:i:s"
     * @return string $fecha un string con la representación de la fecha
     */
    static function getDatefromMiliSeconds(int $fecMiliSeconds, $tipo="Y/m/d H:i:s"){ 
        /*
            $tipo("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
            $tipo("m.d.y");                         // 03.10.01
            $tipo("j, n, Y");                       // 10, 3, 2001
            $tipo("Ymd");                           // 20010310
            $tipo('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
            $tipo('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
            $tipo("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
            $tipo('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
            $tipo("H:i:s");                         // 17:16:18
        
        */     
        $fecha = date($tipo, ($fecMiliSeconds/1000));
        return $fecha;
    }
        
    /**
     * getMilisecondsFromDate Método estático que regresa un entero que representa una fecha en milisegundos
     *
     * @param  string $fecha Fecha válida en formato string en formato año/mes/día hora:min:seg
     * Si no se pasa una fecha, regresa la fecha actual en miliseconds
     * @return int un entero que representa la fecha en milisegundos
     */
    static function getMilisecondsFromDate(string $fecha){
    
        if(is_null($fecha)){
            $fecMili = new DateTime();
        }else{
            $fecMili = new DateTime($fecha);
        }
        return  $fecMili->getTimestamp()*1000;    
    }
}

?>