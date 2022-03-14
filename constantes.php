<?php

define("FFECHA", "d-m-Y H:i:s");
define("TOKENHEADER", "Authorization: vRealizeOpsToken ");

define("HOSTVROPS", "vrops01.intra.banesco.com");

define("ALLRESOURCELIST", "allResourceList.json");

define("REPORTERRORACTIVE", false);

define("URLVROPSTAIL", "&page=0&pageSize=");
define("PAGESIZE", "1800"); //Este es el número de recursos vrops que trae una página en una cosulta
define("URLTAIL", URLVROPSTAIL . PAGESIZE); //Concatena el string en el que termina la cola de la consulta con el núm max de recursos

// ------------------ R U T A S -------------------------------------

define("HOME", __DIR__);

define("INICIO", "/STISCR/vROps/view/ingreso.php");

define("ARCHIVODECONFIGURACION", HOME."/vROps/vROpsConf.json");

define("VROPSLOGFILE", HOME . "/vROps/errors/vROpsErrors.json");

define("SALIDAS", "/vROps/salidas/");
define("STATS", "/vROps/salidas/stats/");
define ("VROPS", "/vROps/");
define ("VROPSVIEW", "/vROps/view/");
define ("CONTROLLER", "/controller/");
define ("ERRORES", "Errores.json");
define("ERRORLOGJSONFILE", HOME.VROPS."/errors/vROpsErrors.json");
define("CSS", "/view/css/");

//define("VROPSVIEW", HOME . VROPS . "/view/");

define("VROPSERROREMAILFROM","From: vROps programm");
define("VROPSERROREMAILTO","lhervi@gmail.com");

// ------------------ R U T A S -------------------------------------

define('SEGMENTOS', 2000);
define("ARCHIVOSDEIDS", "IdsFilesNames.json");

// ------------------ E S T I L O S -------------------------------------
define ("TABLE", "table table-bordered");
define ("TABLEDARK", "table table-dark");
define ("TABLEDARKESTRIPED", "table table-striped table-dark");
define ("TABLESTRIPED", "table table-striped");
define ("TABLEHOVER", "table table-hover");
define ("TABLEHOVERDARK", "table table-hover table-dark");
define ("TABLESMALLDARK", "table table-sm table-dark");
define ("TABLESMALL", "table table-sm");
// ------------------ E S T I L O S -------------------------------------

?>