<?php

function setcss_js($mode="common"){
  $path  = "default";

if(!isset($_SESSION["Logged"])){
  $str   = "<link type=\"text/css\" href=\"bs3/css/bootstrap.min.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"css/bootstrap-reset.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"css/style.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"css/style-responsive.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"js/slideshow/css/supersized.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"js/slideshow/theme/supersized.shutter.css\" rel=\"stylesheet\">\n";
}else{
  $str   = "<link type=\"text/css\" href=\"bs3/css/bootstrap.min.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"assets/jquery-ui/jquery-ui-1.10.1.custom.min.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"css/bootstrap-reset.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"assets/font-awesome/css/font-awesome.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"assets/bootstrap-datepicker/css/datepicker.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"assets/bootstrap-datetimepicker/css/datetimepicker.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"js/validation/css/bootstrapValidator.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"css/style.css\" rel=\"stylesheet\">\n";
  $str  .= "<link type=\"text/css\" href=\"css/style-responsive.css\" rel=\"stylesheet\">\n";
}

  return $str;

}

function imageProportions($image){
  $imageLoad = @getimagesize($image);
  if($imageLoad[1] > 100){
    $proportion =  $imageLoad[1] / $imageLoad[0];
    $imageLoad[1] = 100;
    $imageLoad[0] = $imageLoad[1] / $proportion;
    return array(ceil($imageLoad[0]),$imageLoad[1]);
  }else {
    return array($imageLoad[0], $imageLoad[1]);
  }
}

function parseHTMLTags($val){

  $html = str_get_html($val);
  foreach($html->find('img') as $e){
    $imageStats = imageProportions($e->src);
	$e->width = $imageStats[0];
    $e->height = $imageStats[1];
  }

  return $html->save();
}

function setComboTanggal($pVal){
  echo $pVal;
  $str = "<option></option>";
  for($i=1;$i<=31;$i++){ 
    $s = $i == $pVal ? "selected" : "";
    $str .= "<option value='$i' $s>$i</option>";
  }
  return $str;
}

function setComboBulan($pVal){
global $arr_bulan;

  $str = "<option></option>";
  foreach($arr_bulan as $i=>$b){  
    if(isset($pVal)){
	  $s = ($i+1) == $pVal ? "selected" : "";
    }else{
	  //$s = ($i+1) == date("n") ? "selected" : "";
    }
    $str .= "<option value='".($i+1)."' $s>$b</option>";
  }

  return $str;
}

function setComboTahun($pVal,$pAwal=5,$pAkhir=0){
  $str = "<option></option>";
  for($i=date("Y")-$pAwal;$i<=date("Y")-$pAkhir;$i++){  
    $s = $i == $pVal ? "selected" : "";
    $str .= "<option value='$i' $s>$i</option>";
  }

  return $str;
}

function encrypt_decrypt($action, $string) {
   $output = false;

   $key = 'My strong random secret key';
   $iv = md5(md5($key));
   if( $action == 'encrypt' ) {
       $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
       $output = base64_encode($output);
   }
   else if( $action == 'decrypt' ){
       $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
       $output = rtrim($output, "");
   }
   return $output;
}

?>