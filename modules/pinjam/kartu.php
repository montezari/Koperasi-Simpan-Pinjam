<?php


$frmTpl = new TSQLTemplate("pinjam.kartu");
$frmTpl->SQL = "select * from vw_rpt_kartuangsuran where cKdAnggota = '".$_SESSION["KdAnggota"]."' ";
$frmTpl->template->MergeBlock("blk_hdr","adodb","select * from vw_rpt_kartuangsuran where cKdAnggota = '".$_SESSION["KdAnggota"]."' ");
$frmTpl->name = "pgTpl";
$frmTpl->moduleid  = "pinjam.kartu";
$gTpl = new TBlock("form.general_box");
$gTpl->name	= "gTpl";
$gTpl->title = "Data Pinjaman Anggota";
$gTpl->display = $frmTpl->Show(false);
$content = $gTpl->Show(false);

?>