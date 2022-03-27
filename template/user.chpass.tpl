<script src="js/advanced-form/advanced-form.js"></script>
<script type="text/javascript">
  function dovalidasi(){
    var passlama = document.frmchpass.passwordlama;
    var passbaru = document.frmchpass.passwordbaru;
    var passbaru2 = document.frmchpass.passwordbaruulang;
    
	if(passlama.value == ''){
	  alert('Password lama tidak boleh kosong');
	  passlama.focus();
	  return false;
    } 
	if(passbaru.value == ''){
	  alert('Password baru tidak boleh kosong');
	  passbaru.focus();
	  return false;
    } 
	if(passbaru.value != passbaru2.value){
	  alert('Verifikasi password baru anda tidak cocok.');
	  passbaru.focus();
	  return false;
    } 
	
  }
</script>
<form action="index.php?m=[var.~moduleid;noerr]" method="post" name='frmchpass' id='frmchpass' class="form-horizontal bucket-form" onsubmit="return dovalidasi();" >
<div class="form-group" style="float: none !important">
	<label class="control-label col-md-3">Password Lama</label>
	<div class="col-md-4 col-xs-11">
		<input type="password" name="passwordlama" id="passwordlama" class="form-control" placeholder="Password Lama">
	</div>
</div>
<div class="form-group" style="float: none !important;border-bottom:none;margin-bottom:5px;">
	<label class="control-label col-md-3">Password Baru</label>
	<div class="col-md-4 col-xs-11">
		<input type="password" name="passwordbaru" id="passwordbaru" class="form-control" placeholder="Password Baru">
	</div>
</div>
<div class="form-group" style="float: none !important">
	<label class="control-label col-md-3">Password Baru (Ulang)</label>
	<div class="col-md-4 col-xs-11">
		<input type="password" name="passwordbaruulang" id="passwordbaruulang" class="form-control" placeholder="Ulang Password Baru">
	</div>
</div>
<button type="submit" name="FormAction" id="FormAction" value="UBAHPASS" class="btn btn-success">Ubah Password</button>
</form>
