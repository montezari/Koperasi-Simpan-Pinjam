<?php
session_name(md5("WWW_DUTAKSP_WEB_SESID"));
session_cache_limiter('private');
session_start();
header("Cache-control: private"); 

error_reporting(0);

$server_addr = $_SERVER["SERVER_NAME"]; 
$server_port = $_SERVER["SERVER_PORT"] == "80" ? "" : ":".$_SERVER["SERVER_PORT"] ; 
$server_uri = $server_addr.$server_port;

$config["page_header"] = "DutaKSP WEB";
$config["http_url"]    = "http://".$server_uri."/dutaksp";
$config["http"]    = "http://".$server_uri;
$config["db_host"] = "RIDWAN-PC\SQLEXPRESS";
$config["db_user"] = "sa";
$config["db_pass"] = "Agriadminagri1";
$config["db_name"] = "DUTA_KSP";

$config["db_fetchpage"] = 20;
$config["dir_class"] = "classes/";
$config["dir_tpl"] = "template/";

$arr_bulan = array("Januari","Februari","Maret","April","Mei","Juni",
				   "Juli","Agustus","September","Oktober","November","Desember");

?>