<?php


$frmTpl = new TSQLTemplate("simpan.buku");
$frmTpl->SQL = "select * from vw_kartu_simpanan_anggota where cKdAnggota = '".$_SESSION["KdAnggota"]."' ";
$frmTpl->name = "pgTpl";
$frmTpl->moduleid  = "simpan.buku";
$sql = "select SUM(vNilaiSisa) as nSaldo from vw_kartu_simpanan_anggota where cKdAnggota = '".$_SESSION["KdAnggota"]."' ";
$rs = $conn->Execute($sql);
$saldo_tabungan = $rs->fields["nSaldo"];
$gTpl = new TBlock("form.general_box");
$gTpl->name	= "gTpl";
$gTpl->title = "Data Simpanan Anggota";
$gTpl->display = $frmTpl->Show(false);
$content = $gTpl->Show(false);

?>