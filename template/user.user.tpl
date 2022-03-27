<div class="row">
    <div class="col-xs-12">
        <div class="box">
        [onload_1; when [var.mode;noerr]==0; block=div]
            <div class="box-header">
              <!--
              <div style="padding-top:5px;padding-left:10px;">
                <div>Jumlah Data : [var.~recordcount;noerr]</div>
                <div>Halaman : [var.~page_sequence;noerr]</div>
              </div>
              -->
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                <form action="index.php?m=[var.~moduleid;noerr][var.~page_url;noerr]" method="post" name='grid'>
                <input name='fkey' id='fkey' type='hidden' size=/>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Anggota</th>
                            <th>User Name</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>[blksql.#; block=tr;noerr]</td>
                            <td>[blksql.vNmAnggota;noerr;block=tr]</td>
                            <td>[blksql.cUserName;noerr;block=tr]</td>
                            <td>[blksql.cStatus;noerr;if [blksql.cStatus;noerr]=='aktif';then 'Aktif';else 'Tidak Aktif';block=tr]</td>
                            <td width="25" align="center">[var.button.E;if [var.button.E;noerr]=='valid';then <a href="#" class="back" onClick="window.location = '?m=[var.~moduleid;noerr][var.~page_url;noerr]&mode=form&key=[blksql.cKdAnggota;noerr]'"><span class="fa fa-edit"></span></a>;else <span class="fa fa-edit"></span>]</td>
                        </tr>
                    </tbody>
  					</form>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        
		<div class="col-md">
        [onload_1; when [var.mode;noerr]==1; block=div]
            <!-- general form elements disabled -->
            <div class="box box-warning">
                <div class="box-body">
                    <form name="form" id="form" method="post" action="?m=[var.~moduleid;noerr][var.~page_url;noerr]">
                    <input name='fkey' id='fkey' type='hidden' value='[blksql.cUserId;noerr;]' size=/>
                    <input name='fang' id='fang' type='hidden' value='[blksql.cKdAnggota;noerr;]' size=/>
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nama Anggota</label>
                            <input type="text" name="nama" id="nama" class="form-control" style="width:250px;" value="[blksql.vNmAnggota;noerr;]" readonly/>
                        </div>
                       <div class="form-group">
                            <label>User Name</label>
                            <input type="text" name="user" id="user" class="form-control" style="width:250px;" value="[blksql.cUserName;noerr;]"/>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="pass" id="pass" class="form-control" style="width:250px;" value="[var.cPassword;noerr;]"/>
                        </div>
                        <div class="box-footer">
                            <button type="submit" id="FormAction" name="FormAction" value="Simpan" class="btn btn-primary">Simpan</button>
                            <button type="button" id="FormBatal" name="FormBatal" onclick="window.location = 'index.php?m=[var.~moduleid;noerr][var.~page_url;noerr]';" value="Batal" class="btn btn-primary">Batal</button>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
<script type="text/javascript">
$(document).ready(function() {
    $('#form').bootstrapValidator({
        message: 'Nilai tidak valid',
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        fields: {
            user: {
                message: 'Inputan tidak valid',
                validators: {
                    notEmpty: {
                        message: 'Inputan tidak boleh kosong dan harus di isi'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: 'Inputan maksimal 3 karakter'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: 'Inputan tidak valid'
                    },
                    remote: {
                        url: 'validate.php?m=[var.~moduleid;noerr]',
                        message: 'Inputan sudah ada'
                    }
                }
            }
		}
    });
});
</script>
        </div>
        
    </div>
</div>



