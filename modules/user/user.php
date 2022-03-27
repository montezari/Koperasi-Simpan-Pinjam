<?php

$mode = $_REQUEST["mode"] == "form" ? 1 : 0;

if(strtoupper($_POST["FormAction"])=="SIMPAN"){
  $val_aktif = $_POST["cAktif"]	== "" ? "0" : $_POST["cAktif"]; 
  $val_pass = trim(encrypt_decrypt('encrypt',$_POST["pass"]));
  if($_POST["fkey"] != ""){
    $sql  = "update tsm_pemakai set cUserName='$_POST[user]', cPassword = '".$val_pass."', nNamaPemakai='$_POST[nama]' ";
    $sql .= "where cKdAnggota = '$_POST[fang]' ";
  }else{
    $sql  = "insert into tsm_pemakai (cKdAnggota,cUserName,cPassword,nNamaPemakai) ";
    $sql .= "values('$_POST[fang]','$_POST[user]','".$val_pass."','$_POST[nama]') ";
  }
  $conn->Execute($sql); 
  header("Location: $config[http]$_SERVER[REQUEST_URI]");
  exit;
}

$admGrdTpl = new TSQLTemplate("user.user");
$sql  = "select ang.cKdAnggota, ang.vNmAnggota, usr.cUserId, usr.cUserName, CASE WHEN usr.cUserId IS NOT NULL THEN 'aktif' ELSE '' END AS cStatus "; 
$sql .= "from DUTA_MST.dbo.Ms_Anggota ang ";
$sql .= "left join dbo.tsm_pemakai usr ON usr.cKdAnggota = ang.cKdAnggota ";
$sql .= "WHERE ang.cKdAnggota IS NOT NULL ";
if($_GET["key"] != "" || $_GET["mode"] == "form"){
  $sql .= " AND ang.cKdAnggota = '$_GET[key]' ";
}
$sql .= "ORDER BY ang.cKdAnggota  ";

$admGrdTpl->moduleid  = "user.user";
$admGrdTpl->delform   = "m=user.user&page=$_GET[page]";
$admGrdTpl->SQL = $sql;
$sql = "SELECT cPassword FROM tsm_pemakai WHERE cKdAnggota = '$_GET[key]' ";
$rs = $conn->Execute($sql);
if($rs->fields["cPassword"]!=""){
  $cPassword = trim(encrypt_decrypt('decrypt',$rs->fields["cPassword"]));
}else{
  $cPassword = "";
}
$gTpl = new TBlock("form.general_box");
$gTpl->name		= "gTpl";
$gTpl->title    = "Users";
$gTpl->display  = $admGrdTpl->Show(false);
$content = $gTpl->Show(false);

?>