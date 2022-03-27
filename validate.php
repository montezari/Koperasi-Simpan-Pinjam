<?php
require_once "includes/global.inc.php";

$valid = true;
if (($_REQUEST["m"] == "user.user") && isset($_POST['cUserName'])){
  $sql = "SELECT COUNT(*) as jml FROM tsm_pemakai WHERE cUserName = '".$_POST['cUserName']."' ";
  $rs = $conn->Execute($sql);
  if($rs->fields["jml"]>0){
    $valid=false;
  }
}

echo json_encode(array(
    'valid' => $valid
));

?>