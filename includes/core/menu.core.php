<?php

/*
class TMenuItem {
  var $Name;
  var $Parent;
  var $Caption;
  var $Icon = 0;
  var $Handler;
  var $Items;

  function TMenuItem($Name){
    
	$this->Name = $Name;
  }

  function Add($mnuItem){
    
	$this->Items[$mnuItem->Name] = new TMenuItem($mnuItem);
    $this->Items[$mnuItem->Name]->Parent = $this->Name;
  }

  function AddItem($mnuItem){
   
	$this->Items[$mnuItem->Name] = $mnuItem;
  }
}

class TMainMenu { 
  var $Name;
  var $Items;
  var $AllItems;

  function TMainMenu($Name){
    
	$this->Name = $Name;
  }

  function Add($mnuItem){
    
	$this->MergeItem();
    $this->Items[$mnuItem] = new TMenuItem($mnuItem);
  }

  function Item($idxName){    
    
	if(array_key_exists($idxName,$this->Items)){      
      return $this->Items[$idxName];
    }else{
      return false;
    }
  }
  
  function MergeItem($items=null){
    
	return false;
  }

  function BuildDOI($Self,$items){
    
	if(is_array($items)){
      foreach($items as $item){
        $this->doiStr .= "var ".$item->Name." = new TPopMenu(";
        $this->doiStr .= "'".$item->Caption."',";
        $this->doiStr .= "'".$item->Icon."',";
        $this->doiStr .= $item->Handler == "" ? "''," : "'a',";
        $this->doiStr .= "'".$item->Handler."',";
        $this->doiStr .= "'".$item->Caption."');\n";
        $this->doiBuilder .= "$Self.Add(".$item->Name.");\n";
        $this->doiStr .= $this->BuildDOI($item->Name,$item->Items);
      }
    }
  }

  function Build(){
    
	$this->doiStr = "var ".$this->Name." = new TMainMenu('".$this->Name."','horizontal');\n";
    if(is_array($this->Items)){
      $this->BuildDOI($this->Name,$this->Items);
      $str  = $this->doiStr;
      $str .= "\n\n".$this->doiBuilder;
      echo $str;
    }else{
      return false;
    }
  }
}

class TModules {
  var $Name;
  var $Handler;
  var $NoEdit;
  var $Read;
  var $Write;
  var $Delete;

  function TModules($Name){
    $this->Name = $Name;
  }
}

class TJSMenuManager {
  var $Name;
  var $Menu;
  var $Packages;
  var $Modules;

  function TJSMenuManager($name="mm0"){

    $this->Menu = new TMainMenu($name);
	$this->BuildMainItem();
	$this->BuildChildItem();
    $this->PackageList();
    $this->ModuleList();
  }

  function BuildMainItem(){
    global $conn, $tables;
	  
	$sql  = "select fmenu, fcaption, fhandler, ficon from sync_menu ";
	$sql .="where fparent is null and fnoedit = 0 order by forder";
	$rs = $conn->Execute($sql);
	while(!$rs->EOF){
	  $name = "mnu_".$rs->fields["fmenu"];
      $this->Menu->Add($name);
      $this->Menu->Item($name)->Caption = $rs->fields["fcaption"];
      $this->Menu->Item($name)->Handler = $rs->fields["fhandler"];
      $this->Menu->Item($name)->Icon = $rs->fields["ficon"];   
	  $rs->MoveNext();
    }
	$rs->Close();
  }

  function BuildChildItem(){
    global $conn, $tables;

    $mainMenu = array_keys($this->Menu->Items);
    $parents  = "";
    foreach($mainMenu as $mm){
      $parents .= "'".substr($mm,4)."',";
    }
    $parents  = substr($parents,0,-1);

    $sql  = "select fmenu, fparent, fcaption, fhandler, ficon from sync_menu ";
	$sql .= " where fparent in ($parents) and fnoedit = 0 order by forder";
    $rs = $conn->Execute($sql);
	while(!$rs->EOF){
	  $name   = "mnu_".$rs->fields["fmenu"];
      $parent = "mnu_".$rs->fields["fparent"];
      $mi = new TMenuItem($name);
      $mi->Caption = $rs->fields["fcaption"];
      $mi->Handler = $rs->fields["fhandler"] == "" ? "" : "?m=".$rs->fields["fhandler"];
      $mi->Icon    = $rs->fields["ficon"];
      $this->Menu->Item($parent)->AddItem($mi);
      unset($mi);
	  $rs->MoveNext();
    }
	$rs->Close();
  }

  function PackageList(){
    global $conn, $tables;

    $sql  = "select fmenu, fcaption from sync_menu where fpublic = '0' and fparent is null order by forder";
    $rs = $conn->Execute($sql);
    while(!$rs->EOF){
      $this->Packages[$rs->fields["fmenu"]] = new TModules($rs->fields["fcaption"]);
	  $rs->MoveNext();
    }
	$rs->Close();
  }

  function ModuleList(){
    global $conn, $tables;

    $sql  = "select fmenu, fparent, fcaption from sync_menu ";
	$sql .= "where fpublic = '0' and fparent is not null order by fparent,forder";
    $rs = $conn->Execute($sql);
    while(!$rs->EOF){
      $this->Modules[$rs->fields["fparent"]][$rs->fields["fmenu"]] = new TModules($rs->fields["fcaption"]);
	  $rs->MoveNext();
    }
	$rs->Close();
  }
  
}
*/

class TMainMenu { 
  
  function TMainMenu(){
  }
  
  function Build($username){
	global $conn;
    
	$sql  = "SELECT COUNT(*) as jml FROM tsm_user WHERE cUserName = '".$username."' AND cSuper = '1' ";
	$rs = $conn->Execute($sql);
	$issuper = $rs->fields["jml"]>0;

	if ($issuper) {
	  $sql = "SELECT fmenu, fparent, ficon, fcaption, fhandler FROM tsm_menu WHERE fnoedit = '0' ";
	}else{ 
	  $sql  = "SELECT mm.* FROM tsm_priv priv ";
	  $sql .= "LEFT JOIN (SELECT fmenu, fparent, ficon, fcaption, fhandler FROM tsm_menu) mm ON mm.fmenu = priv.cKdModuleId ";
	  $sql .= "WHERE priv.cView = '1' ";
	  $sql .= "AND priv.cKdGroupUser IN (SELECT cKdGroupUser FROM tsm_user WHERE cUserName = '".$username."') ";
	}
	$rs = $conn->Execute($sql);
    while(!$rs->EOF){
	  $str .= "<li class=\"sub-menu\"> ";
	  $str .= "	<a href=\"#\"> ";
	  $str .= "		<i class=\"".$rs->fields["ficon"]."\"></i> ";
	  $str .= "		<span>".$rs->fields["fcaption"]."</span> ";
	  $str .= "		<i class=\"fa fa-angle-left pull-right\"></i> ";
	  $str .= "	</a> ";
	  $rs->MoveNext();
	  if($rs->fields["fparent"]!==null){
	    $str .= "	<ul class=\"sub\"> ";
		while((!$rs->EOF) && ($rs->fields["fparent"]!==null)){
		  $str .= "	  <li><a href=?m=".$rs->fields["fhandler"]."><i class=\"".$rs->fields["ficon"]."\"></i>".$rs->fields["fcaption"]."</a></li> ";
	      $rs->MoveNext();
		}
	    $str .= "	</ul> ";
	  }
	  $str .= "</li> ";
	}
	$rs->Close();

	return $str;
  } 

  function isakses($username,$modul){
	global $conn;
    
	$sql  = "SELECT COUNT(*) as jml FROM tsm_user WHERE cUserName = '".$username."' AND cSuper = '1' ";
	$rs = $conn->Execute($sql);
	$issuper = $rs->fields["jml"]>0;

	if(!$issuper){
	  $sql  = "SELECT COUNT(*) AS jml ";
	  $sql .= "FROM tsm_menu mnu "; 
	  $sql .= "LEFT JOIN tsm_priv priv ON priv.cKdModuleId = mnu.fmenu "; 
	  $sql .= "LEFT JOIN tsm_user usr ON usr.cKdGroupUser = priv.cKdGroupUser ";
	  $sql .= "WHERE COALESCE(priv.cView,0) = '1' AND mnu.fparent IS NOT NULL "; 
  	  $sql .= "AND usr.cUserName = '".$username."' AND mnu.fhandler = '".$modul."' ";
	  $rs = $conn->Execute($sql);
	  return $rs->fields["jml"]>0;
    }else{
	  return true;
	}
	
  }
}

?>