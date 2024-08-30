<?php

function consulta($tipo, $bd, $anio, $mes){
if($tipo == 1){
	$sql = "select replace(replace(replace(upper(TAR.TARGET_HOST_NAME),'.INX',''),'.sec.COM',''),'.DMZ.COM','') as srv, 
MONITOR_LOGICAL_NAME,MSNAME,DAT.TIME_STAMP, 
iif(DAT.RMH_MEAS_VALUE_SUM is NULL,NULL,iif(DAT.RMH_THRESH_QUALITY_GOOD_SUM is NULL,NULL,
iif(DAT.RMH_THRESH_QUALITY_GOOD_SUM=0,0,DAT.RMH_MEAS_VALUE_SUM/DAT.RMH_THRESH_QUALITY_GOOD_SUM/100))) valor
from [HP_BSM_SiS_".$bd."].dbo.SM_DEF_MONITOR as MON,
[HP_BSM_SiS_".$bd."].dbo.SM_DEF_MEASUREMENT as MEA,
[HP_BSM_SiS_".$bd."].dbo.SM_DEF_TARGET as TAR,
[HP_BSM_SiS_".$bd."].dbo.SM_RAWDATA_MEAS_HOUR as DAT
where MON.SESSION_ID=MEA.SESSION_ID and MON.MONITOR_ID=MEA.MONITOR_ID and
MON.SESSION_ID=TAR.SESSION_ID and MEA.TARGET_ID=TAR.TARGET_ID 
and MEA.SESSION_ID=TAR.SESSION_ID 
and MON.SESSION_ID=DAT.SESSION_ID 
and MON.MONITOR_ID=DAT.RMH_MONITOR_ID
and MEA.SESSION_ID=DAT.SESSION_ID 
and MEA.TARGET_ID=DAT.RMH_TARGET_ID
and TAR.SESSION_ID=DAT.SESSION_ID 
and TAR.TARGET_ID=DAT.RMH_TARGET_ID 
and MEA.MEASUREMENT_ID=DAT.MEASUREMENT_ID
and MONITOR_LOGICAL_NAME in ('CPU','Memory') 
and MSNAME in ('utilization','percent used')
--and replace(replace(replace(upper(TAR.TARGET_HOST_NAME),'.INX',''),'.sec.COM',''),'.DMZ.COM','')
and year(DAT.TIME_STAMP)= ".$anio." 
and month(DAT.TIME_STAMP) in ( ".$mes." )
order by replace(replace(replace(upper(TAR.TARGET_HOST_NAME),'.INX',''),'.sec.COM',''),'.DMZ.COM',''),MONITOR_LOGICAL_NAME,MSNAME,DAT.TIME_STAMP";
} elseif($tipo == 2)
{

$sql="select distinct replace(replace(replace(upper(TAR.TARGET_HOST_NAME),'.INX',''),'.sec.COM',''),'.DMZ.COM','') as srv,
DAT.TIME_STAMP,MON.MONITOR_LOGICAL_NAME + ' - ' + MEA.MSNAME as met,
iif(DAT.RMH_MEAS_VALUE_SUM is NULL,NULL,iif(DAT.RMH_THRESH_QUALITY_GOOD_SUM is NULL,NULL,
iif(DAT.RMH_THRESH_QUALITY_GOOD_SUM=0,0,DAT.RMH_MEAS_VALUE_SUM/DAT.RMH_THRESH_QUALITY_GOOD_SUM/100))) valor
from [HP_BSM_SiS_BOL].dbo.SM_DEF_MONITOR as MON,
[HP_BSM_SiS_BOL].dbo.SM_DEF_MEASUREMENT as MEA,
[HP_BSM_SiS_BOL].dbo.SM_DEF_TARGET as TAR,
[HP_BSM_SiS_BOL].dbo.SM_RAWDATA_MEAS_HOUR as DAT
where 
MON.SESSION_ID=MEA.SESSION_ID 
and MON.MONITOR_ID=MEA.MONITOR_ID 
and MON.SESSION_ID=TAR.SESSION_ID 
and MEA.TARGET_ID=TAR.TARGET_ID
and MEA.SESSION_ID=TAR.SESSION_ID 
and MON.SESSION_ID=DAT.SESSION_ID 
and MON.MONITOR_ID=DAT.RMH_MONITOR_ID
and MEA.SESSION_ID=DAT.SESSION_ID 
and MEA.TARGET_ID=DAT.RMH_TARGET_ID
and TAR.SESSION_ID=DAT.SESSION_ID 
and TAR.TARGET_ID=DAT.RMH_TARGET_ID 
and MEA.MEASUREMENT_ID=DAT.MEASUREMENT_ID
/*and MONITOR_LOGICAL_NAME in ('Ping','URL Monitor') and MSNAME in ('% packets good','roundtrip time (milliseconds)','round trip time')*/
and year(DAT.TIME_STAMP)=".$anio."
and month(DAT.TIME_STAMP) in (".$mes.")
order by replace(replace(replace(upper(TAR.TARGET_HOST_NAME),'.INX',''),'.sec.COM',''),'.DMZ.COM',''),
DAT.TIME_STAMP";

}
return $sql;
}

?>