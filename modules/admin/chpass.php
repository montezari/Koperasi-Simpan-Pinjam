<?php

$mode = $_REQUEST["mode"] == "form" ? 1 : 0;
if(strtoupper($_POST["FormAction"])=="UBAHPASS"){
  $iscanupdate = false;
  $pass = encrypt_decrypt('encrypt',$_POST["passwordlama"]);
  $sql = "SELECT COUNT(*) as jml FROM tsm_user WHERE cUserName = '".$_SESSION["Logged"]."' AND cPassword = '".$pass."' ";	
  $rs = $conn->Execute($sql);
  if($rs->fields["jml"]>0){
    $iscanupdate = true;  
  }else{
    $iscanupdate = false;
	echo "<script>alert('Password lama anda salah. Gagal proses ganti password.');</script>";
  }	
  if($iscanupdate==true){
    $pass = encrypt_decrypt('encrypt',$_POST["passwordbaru"]);
	$sql  = "UPDATE tsm_user SET cPassword = '".$pass."' WHERE cUserName = '".$_SESSION["Logged"]."' ";
	$conn->Execute($sql);
	echo "<script>alert('Proses ganti password selesai. Aplikasi akan otomatis logout. Mohon Login kembali');</script>";
	echo "<script>window.location = 'index.php?m=admin.auth&logout=1&confirm=1'</script>";
    //header("Location: $config[http]$_SERVER[REQUEST_URI]");
    //exit;
  }
}

$admGrdTpl = new TTemplate("user.chpass");
$admGrdTpl->name = "cpTpl";
$admGrdTpl->moduleid  = "admin.chpass";
$gTpl = new TBlock("form.general_box");
$gTpl->name		= "gTpl";
$gTpl->title    = "Ganti Password";
$gTpl->display  = $admGrdTpl->Show(false);
$content = $gTpl->Show(false);

?>