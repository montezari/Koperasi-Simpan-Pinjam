<?php

if(isset($_REQUEST["logout"]) && $_REQUEST["logout"] == 1){
  if($_REQUEST["confirm"] == 1){
    session_unset(md5("WWW_DUTAKSP_WEB_SESID"));
	unset($_SESSION["Session_ID"]);
	unset($_SESSION["KdAnggota"]);
	unset($_SESSION["cLoggedName"]);
	unset($_SESSION["Logged"]);
    echo "<script>window.location = 'index.php';</script>";
  }else{
    echo "<script>if(confirm('Anda yakin ingin Logout dari Sistem ?')){window.location = 'index.php?m=admin.auth&logout=1&confirm=1';}else{history.back(-1);}</script>";
  }  
}else{
  $pass = encrypt_decrypt('encrypt',$_POST["edtPassword"]);
  $sql = "select * from dbo.tsm_pemakai where UPPER(cUserName) = UPPER('$_POST[edtUserName]') and cPassword = '".$pass."'";
  $rs = $conn->Execute($sql);
  if($rs->RecordCount() > 0){  
	$sql  = "update tsm_pemakai set dLastLogin = CURRENT_TIMESTAMP where cUserName = '$_POST[edtUserName]' ";
    $conn->Execute($sql);
    $_SESSION["Logged"]	= strtoupper($rs->fields["cUserName"]);
    $_SESSION["cLoggedName"] = $rs->fields["nNamaPemakai"];
    $_SESSION["KdAnggota"] = $rs->fields["cKdAnggota"];
    

    $sqldt = "SELECT DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') as LOGINDATETIME ";
    $rsdt  = $conn->Execute($sqldt);
    $session_id = md5($r->fields["cUserName"].$rsdt->fields["LOGINDATETIME"]);
    $_SESSION["Session_ID"] = $session_id;

    //$host = $_SERVER["REMOTE_ADDR"] != "" ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
    //$sql  = "insert into sync_logged (dTgl, fip, cUserName, csession_id) ";
    //$sql .= "values (CURRENT_TIMESTAMP, '$host', '$_POST[edtUserName]', '$session_id')";
    //$conn->Execute($sql);
    echo "<script>window.location='index.php';</script>";
  }else{
	echo "<script>alert('User ID dan Password yang anda masukan tidak benar...');</script>";
    echo "<script>window.location='index.php';</script>";
  }
}

?>