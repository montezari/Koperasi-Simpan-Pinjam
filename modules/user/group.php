<?php

$mode = $_REQUEST["mode"] == "form" ? 1 : 0;

if(strtoupper($_POST["FormAction"])=="SIMPAN"){
  $val_aktif = $_POST["cAktif"]	== "" ? "0" : $_POST["cAktif"]; 
  if($_POST["fkey"] != ""){
    $sql  = "update tsm_groupuser set vNmGroupUser='$_POST[vNmGroupUser]', cAktif='".$val_aktif."', cuseredit = '$_SESSION[Logged]', cdateedit = CURRENT_TIMESTAMP ";
    $sql .= "where cKdGroupUser = '$_POST[fkey]' ";
  }else{
    $sql  = "insert into tsm_groupuser (vNmGroupUser,cAktif, cUserEntry, cDateEntry, cUserEdit, cDateEdit) ";
    $sql .= "values('$_POST[vNmGroupUser]','".$val_aktif."','$_SESSION[Logged]',CURRENT_TIMESTAMP,'$_SESSION[Logged]',CURRENT_TIMESTAMP) ";
  }
  $conn->Execute($sql); 
  header("Location: $config[http]$_SERVER[REQUEST_URI]");
  exit;
}elseif(strtoupper($_POST["FormAction"])=="HAPUS"){
  $sql = "delete from tsm_groupuser where cKdGroupUser = '$_POST[fkey]' ";
  $conn->Execute($sql);
  header("Location: $config[http]$_SERVER[REQUEST_URI]");
  exit;
}

$admGrdTpl = new TGridTemplate("user.group");
$sql  = "select * from tsm_groupuser "; 
if($_GET["key"] != "" || $_GET["mode"] == "form"){
  $sql .= " WHERE cKdGroupUser = '$_GET[key]' ";
}
$sql .= "ORDER BY cKdGroupUser ";
$admGrdTpl->moduleid  = "user.group";
$admGrdTpl->delform   = "m=user.group&page=$_GET[page]";
$admGrdTpl->custSQL = $sql;
$gTpl = new TBlock("form.general_box");
$gTpl->name		= "gTpl";
$gTpl->title    = "User Group";
$gTpl->display  = $admGrdTpl->Show(false);
$content = $gTpl->Show(false);

?>