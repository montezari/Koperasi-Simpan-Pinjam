<?php


class TTable{
  var $name;
  var $filtertext;
  var $numrows;
  var $data;
  var $sql;
  var $custSQL;
  var $cNum=0;

  function TTable($name){
    
	$this->uselimit = false;
	$this->name = $name;
  }

  function PopulateRecord(){
	global $conn;

	if($this->custSQL == ""){
      $sql = "SELECT COUNT(*) as jml FROM ".$this->name ;
      if($this->filtertext != ""){
        $sql .= " WHERE " .$this->filtertext;
      }
      $rs = $conn->Execute($sql);      
      $this->numrows = $rs->fields["jml"]; 
	  $rs->Close();
	}else{
	  $sql = $this->custSQL; 
      $rs = $conn->Execute($sql);
	  $this->numrows = $rs->RecordCount();
	  $rs->Close();
	}
  }

  function Limit($startrow,$rowcount) {
    $this->uselimit = true;
    $this->startrow = $startrow;
    $this->rowcount = $rowcount;
  }

  function Filter($filter) {
    $this->filtertext = $filter;
  }

  function FetchRows(){
    
	if($this->custSQL == ""){
	  $sql = "SELECT (@rownum:=@rownum+1) as idrow, mst.* FROM ".$this->name." mst, (SELECT @rownum:=$this->cNum) r ";
      if($this->filtertext != ""){
        $sql .= " WHERE " . $this->filtertext;
      }
      if($this->indexorder){
        $sql .= " ORDER BY " . $this->indexorder;
      }
    }else{
	  $sql = $this->custSQL;  
	}
	if($this->uselimit){
      $sql .= " LIMIT " . $this->startrow . "," . $this->rowcount;
    }

	$this->sql = $sql;
	//echo $sql;
  }
  

  function FetchPage($pagesize,$currentpage=1){
    $this->PopulateRecord();
	//echo $pagesize;
    $pagenum = ceil($this->numrows/$pagesize);
    if($currentpage >= $pagenum) {
      $currentpage = $pagenum;
    } elseif ($currentpage < 1){
      $currentpage = 1;
    }
    $x = $currentpage * $pagesize;
    $y = $this->numrows > 0 ? $x-$pagesize : 0;
	$this->Limit($y,$pagesize);
	$this->cNum = $y;
    $this->FetchRows();
    if($this->numrows > 0){
      $str  = "<span id='page' class='page'>";
	  if ($currentpage>1) $str .= " <a href='?".$this->pagelinkparam."&page=1'>First</a> ";
	  if ($currentpage>1) $str .= " <a href='?".$this->pagelinkparam."&page=".($currentpage-1)."'>Prev</a> ";
      for($i=1;$i<=$pagenum;$i++){
        $link = $this->pagelinkparam."&page=$i";
         if ((($i >= $currentpage-3) && ($i<=$currentpage+3)) || ($i==1) || ($i == $pagenum)){   
            if (($showPage==1) && ($i != 2)) $str .= " ... "; 
            if (($showPage != ($pagenum-1)) && ($i == $pagenum)) $str .= " ... ";
			$str .= $i == $currentpage ? " <span class='currentPage'><b>$i</b></span> " : " <a href='?$link' class='page'>$i</a> ";
			$showPage = $i;
         }
      }
	  if ($currentpage < $pagenum) $str .= "<a href='?".$this->pagelinkparam."&page=".($currentpage+1)."'>Next</a> ";
	  if ($currentpage < $pagenum) $str .= "<a href='?".$this->pagelinkparam."&page=".$pagenum."'>Last</a> ";
      $str .= "</span>";
      return $str;
    } else {        
      return false;
    }
  }

  function Show(){

  }
}

?>