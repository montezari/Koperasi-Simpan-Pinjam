<?php

class TTemplate{
  var $template;
  var $name;
  var $page;
  var $title;
  var $content;
  var $position;
  var $HTMLSource;

  function TTemplate($template){
	global $config;

	$nTpl = $template != "" ? $template : "index";
	$this->template = new clsTinyButStrong;
	$this->template->ObjectRef =& $this;
	$this->template->LoadTemplate($config["dir_tpl"].$nTpl.".tpl",false);
	$this->position = "left";
	$this->name = $template;
  }
  
  function Prepare(){
    
  }

  function addCustomBlocks($blkName,$blkValue){
    
	$this->template->MergeBlock($blkName,$blkValue); 
  }

  function addCustomSQLBlocks($blkName,$BlkSQL){
    global $conn, $db_connect;

    $this->template->MergeBlock($blkName, "adodb", $BlkSQL);
  }

  function Show($flush=true){

	$this->Prepare();
	//$this->template->PlugIn(TBS_PLUS);
	if($flush){
	  $this->template->Show($flush);
	}else{
	  $this->template->Show(TBS_NOTHING);
	  $this->HTMLSource = $this->template->Source;
	  return $this->HTMLSource;
	}
  }
}

class TPageTemplate extends TTemplate{
var $blocks=array();
var $numblock;

  function TPageTemplate($template){

    parent::TTemplate($template);
  }

  function AddTemplateBlock($cmsBlock){
    
    $this->{$cmsBlock->name} = $cmsBlock; 
	$this->blocks[$cmsBlock->name] = $this->{$cmsBlock->name};    
    $this->numblock = sizeof($this->blocks);
  }

  function Prepare(){
	if($this->numblock> 0){
     $bl = array();
     $bc = array();
     $br = array();
     $bt = array();
     $bb = array();
     foreach($this->blocks as $block){
		$tblk["title"]  = $block->title;
        $tblk["content"]= $block->content;
		$tblk["htmlsource"] = $block->HTMLSource;
        $tblk["left"]   = $block->position=="left"   ? 1 : 0;
        $tblk["right"]  = $block->position=="right"  ? 1 : 0;
        $tblk["center"] = $block->position=="center" ? 1 : 0;
        $tblk["top"]	= $block->position=="top"    ? 1 : 0;
        $tblk["bottom"] = $block->position=="bottom" ? 1 : 0;
		switch($block->position){
          default:
          case "left":
            $bl[] = $tblk;
            break;
          case "right":
            $br[] = $tblk;
            break;
          case "center":
            $bc[] = $tblk;
            break;
          case "top":
            $bt[] = $tblk;
            break;
          case "bottom":
            $bb[] = $tblk;
            break;
        }
        $blocks[] = $tblk;
      }
	  $this->template->MergeBlock("blk_pl",$bl); 
      $this->template->MergeBlock("blk_pc",$bc); 
      $this->template->MergeBlock("blk_pr",$br); 
      $this->template->MergeBlock("blk_pt",$bt); 
      $this->template->MergeBlock("blk_pb",$bb); 
	  $this->template->MergeBlock("blk_l,blk_r,blk_c,blk_t,blk_b",$blocks);  
	}
  }
}

class TBlock extends TTemplate{
  var $datasource;
  var $id;
  var $display;
  var $customdata;

  function TBlock($template){
    
	parent::TTemplate($template);
  }

  function Prepare(){
	
	if(is_array($this->datasource) ){
	  $rows = $this->datasource;
    }elseif(eregi("select",$this->datasource)){
      $rs = mysql_query($this->datasource);
      $rows = array();
      while($r=mysql_fetch_object($rs)){
        $rows[] = array("id"=>$r->{$this->id},
			            "title"=>$r->{$this->title},
                        "page"=>$this->page,
                        "display"=>$r->{$this->display});
      }
    }else{
	  $rows[] = array("id"=>$this->id,
					  "title"=>$this->title,
					  "customdata"=>$this->customdata,
					  "page"=>$this->page,
					  "display"=>$this->display);
	}
	
    if(sizeof($rows) > 0){
	  $this->template->MergeBlock("b_block", $rows);
    }
	parent::Prepare();
  }
}

class TMainPage extends TTemplate{
  var $logged=0;
  var $maincontent;
  var $mainmenu;
  
  function TMainPage(){

	$this->logged = isset($_SESSION["Logged"]) ? 1 : 0;
	$tpl = $this->logged ? "" : "index.no";
	parent::TTemplate($tpl);
  } 

  function Show($flush=true){

    parent::Show($flush);
  }
}

class TSQLTemplate extends TTemplate{
  var $moduleid;
  var $SQL;

  function TSQLTemplate($template){
  
	parent::TTemplate($template); 
  }

  function Prepare(){
	global $conn;
  
	$this->template->MergeBlock("blksql", "adodb", $this->SQL);
	parent::Prepare();
  }
}

class TGridTemplate extends TTemplate{
  var $moduleid;
  var $tablename;
  var $custSQL;
  var $sqlWhere;
  var $indexorder;
  var $datasource;
  var $page_sequence;
  var $recordcount;
  var $addform;
  var $delform;

  function TGridTemplate($template){
    
	parent::TTemplate($template); 
  }

  function GetDetail(){
	global $config;

	$cp = isset($_GET["page"]) ? $_GET["page"] : 1;
    $tbl = new TTable($this->tablename);
    $tbl->custSQL = $this->custSQL;
	$tbl->filtertext = $this->sqlWhere;
	$tbl->indexorder = $this->indexorder;
	$tbl->pagelinkparam = "m=".$this->moduleid;
	$this->page_sequence = $tbl->FetchPage($config["db_fetchpage"],$cp);
	$this->recordcount = $tbl->numrows;
	$this->datasource = $tbl->sql;
  }

  function Prepare(){
	global $conn;

	$this->GetDetail();
	$this->template->MergeBlock("grid_blk", "adodb", $this->datasource);

	parent::Prepare();
  }

}


?>