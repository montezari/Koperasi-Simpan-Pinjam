<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
              <div class="box-body table-responsive">
                <table id="example1" class="table">
                    <tbody>
                        <tr>
                            <td width="15%">No Pinjaman</td>
                            <td width="35%">[blk_hdr.cIdPinjam;noerr;]</td>
                            <td width="15%">Produk</td>
                            <td width="35%">[blk_hdr.vNmProduk;noerr;]</td>
                        </tr>
                        <tr>
                            <td>Tgl Pinjaman</td>
                            <td>[blk_hdr.dTglPinjam;noerr;]</td>
                            <td>Jenis Bunga</td>
                            <td>[blk_hdr.vTipeBunga;noerr;]</td>
                        </tr>
                        <tr>
                            <td>Peminjam</td>
                            <td>[blk_hdr.vPinjaman;noerr;]</td>
                            <td>Bunga</td>
                            <td>[blk_hdr.cBunga;frm='0,000.00';noerr;] %</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>[blk_hdr.vNmAnggota;noerr;]</td>
                            <td>Lama Pinjaman</td>
                            <td>[blk_hdr.cTenor;noerr;] [blk_hdr.vTipeTenor;noerr;]</td>
                        </tr>
                        <tr>
                            <td>Nilai Pinjaman</td>
                            <td>[blk_hdr.vNilaiPinjaman;frm='0,000.00';noerr;]</td>
                            <td>JT</td>
                            <td>[blk_hdr.cJatuhTempo;noerr;] HARI</td>
                        </tr>
                    </tbody>
                </table>
              </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                     <thead>
                        <tr>
                            <th>Ang Ke</th>
                            <th>Tgl Ang</th>
                            <th>Tgl JT</th>
                            <th>Ang Pokok</th>
                            <th>Ang Bunga</th>
                            <th>Ang Total</th>
                            <th>Sisa Pinjaman</th>
                            <th>Tgl Byr</th>
                            <th>Bayar</th>
                            <th>Sisa Ang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>[blksql.cNoAngsuran;noerr;block=tr]</td>
                            <td>[blksql.dTglAngsuran;noerr;frm='dd/mm/yyyy';block=tr]</td>
                            <td>[blksql.dTglJatuhTempo;noerr;frm='dd/mm/yyyy';block=tr]</td>
                            <td align="right">[blksql.vAngPokok;noerr;frm='0,000.00';block=tr]</td>
                            <td align="right">[blksql.vAngBunga;noerr;frm='0,000.00';block=tr]</td>
                            <td align="right">[blksql.vAngTotal;noerr;frm='0,000.00';block=tr]</td>
                            <td align="right">[blksql.cSisaPinjaman;noerr;frm='0,000.00';block=tr]</td>
                            <td>[blksql.dTglPayment;frm='dd/mm/yyyy';noerr;block=tr]</td>
                            <td align="right">[blksql.vBayar;noerr;frm='0,000.00';block=tr]</td>
                            <td align="right">[blksql.nSisaAng;noerr;frm='0,000.00';block=tr]</td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        
    </div>
</div>



