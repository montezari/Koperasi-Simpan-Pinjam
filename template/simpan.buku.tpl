<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
              <div style="padding-bottom:5px;padding-left:10px;">
                <div style="font-size:14px;font-weight:bold;">Saldo : [var.saldo_tabungan;frm='0,000.00';noerr] </div>
              </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk Simpanan</th>
                            <th>Total Simpanan</th>
                            <th>Total Pencairan</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>[blksql.#; block=tr;noerr]</td>
                            <td>[blksql.vNmProduk;noerr;block=tr]</td>
                            <td align="right">[blksql.vNilaiSimpanan;noerr;frm='0,000.00';block=tr]</td>
                            <td align="right">[blksql.vNilaiPencairan;noerr;frm='0,000.00';block=tr]</td>
                            <td align="right">[blksql.vNilaiSisa;noerr;frm='0,000.00';block=tr]</td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        
    </div>
</div>



