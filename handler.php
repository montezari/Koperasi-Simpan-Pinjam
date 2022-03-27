<?php
  require_once "includes/global.inc.php";

  $mainTpl = new TMainPage();
  $content = "Modul tidak diketemukan...!!!";
  $css_js  = setcss_js();
  if(isset($_REQUEST["m"]) && $_REQUEST["m"] != ""){
	list($m,$f) = explode(".",$_REQUEST["m"]);
    $modpath = "modules/$m/$f.php";
	if($m!="admin"){
	  if(isset($_SESSION["Logged"])){
	    if($_GET["mode"] != "form"){
		  $button["A"] = "valid";
		  $button["E"] = "valid";
		  $button["D"] = "valid";
		}	
	  }else{  
		echo "<script>alert('Anda tidak mendapatkan akses modul ini, mohon login terlebih dahulu');</script>";
        echo "<script>window.location = 'index.php'</script>";
	  }
	}
    $modpath = "modules/$m/$f.php"; 
    if(file_exists($modpath)){
	  include($modpath);
    }
  }elseif(isset($_SESSION["Logged"])){
	$sql  = "SELECT ang.cKdAnggota, ang.vNmAnggota, grp.cNmGroupAgt, subgrp.cNmSubGroupAgt FROM DUTA_MST.dbo.Ms_Anggota ang ";
	$sql .= "LEFT JOIN (SELECT cKdGroupAgt,cNmGroupAgt FROM DUTA_MST.dbo.Ms_AnggotaGrp) grp ON grp.cKdGroupAgt = ang.cKdGroupAgt ";
	$sql .= "LEFT JOIN (SELECT cKdSubGroupAgt,cNmSubGroupAgt FROM DUTA_MST.dbo.Ms_AnggotaSubGrp) subgrp ON subgrp.cKdSubGroupAgt = ang.cKdSubGroupAgt ";
	$sql .= "WHERE ang.cKdAnggota = '".$_SESSION["KdAnggota"]."' ";

	$fpageTpl = new TSQLTemplate("index.content");
	$fpageTpl->SQL = $sql;
	$content = $fpageTpl->Show(false);
  }

if(!isset($_SESSION["Logged"])){
  $fpageTpl = new TTemplate("sm.frmlogin");
  $content = $fpageTpl->Show(false);
}

$mainTpl->content = $content;

?>