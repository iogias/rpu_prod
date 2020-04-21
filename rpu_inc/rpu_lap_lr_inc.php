<?php
if (!defined('WEB_ROOT')) {
    exit;
}
?>
<section class="content">
<div class="container-fluid">
    <div class="no-print">
    <form role="form">
        <div class="row">
            <div class="col-sm-3"><p>&nbsp;</p></div>
            <div class="col-sm-2">
                <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="tgl-awal-lr">Dari</label>
                <div class="col-sm-10">
                <input value="<?php echo $tglnow;?>" type="text" class="form-control" id="tgl-awal-lr" name="tgl-awal-lr" data-zdp_readonly_element="false" required>
                </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group row">
                <label class="col-sm-2 col-form-label text-center" for="tgl-akhir-lr">s.d</label>
                <div class="col-sm-10">
                <input value="<?php echo $tglnow;?>" type="text" class="form-control" id="tgl-akhir-lr" name="tgl-akhir-lr" data-zdp_readonly_element="false" required>
                </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row">
                <button type="button" id="btn-proses-lr" class="btn btn-info ml-3">PROSES<i class="fas fa-angle-double-right ml-3"></i></button>
                <button type="button" id="btn-print-lr" class="btn btn-secondary ml-3"><i class="fas fa-print mr-3"></i>PRINT</button>
                </div>
            </div>
            <div class="col-sm-1"><p>&nbsp;</p></div>
        </div>
    </form>
    </div>
<div class="card">
<div class="card-header text-center">
    <h5>Laporan Laba-Rugi</h5>
    <small>Periode <span id="tgl-periode-a"></span>&nbsp;&nbsp;s.d&nbsp;&nbsp;<span id="tgl-periode-b"></span></small>
</div>
<div class="card-body">
    <div class="row p-2">
        <div class="col-sm-7">
            <h5>Pendapatan</h5>
            <table class="table table-sm table-striped">
                <tr>
                    <td>Penjualan Kotor</td>
                    <td>&nbsp;</td>
                    <td id="total-pendapatan-kotor" class="text-right">
                    </td>
                </tr>
                <tr>
                    <td>Diskon/Retur Penjualan ( - )</td>
                    <td id="diskon-retur-penjualan" class="text-right">
                    <td>&nbsp;</td>
                    </td>
                </tr>
                <tr>
                    <td>Penjualan Bersih</td>
                    <td>&nbsp;</td>
                    <td id="total-pendapatan-bersih" class="text-right">
                    </td>
                </tr>
                <tr>
                <td class="font-weight-bold">Total Pendapatan</td>
                <td>&nbsp;</td>
                <td id="total-pendapatan" class="text-right font-weight-bold"></td>
                </tr>
            </table>
            <br />
            <h5>Pengeluaran</h5>
            <table class="table table-sm table-striped">
                <tr>
                <td>Biaya Operasional (BT+BB+BP)</td>
                <td>&nbsp;</td>
                <td id="total-biaya" class="text-right"></td>
                </tr>
                <tr>
                    <td><div class="pl-2 text-sm">Rincian Biaya : </div></td>
                    <td><div id="biaya-list"></div></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Biaya HPP</td>
                <td>&nbsp;</td>
                <td id="total-hpp" class="text-right"></td>
                </tr>
                <tr>
                <td class="font-weight-bold">Total Pengeluaran</td>
                <td>&nbsp;</td>
                <td id="total-pengeluaran" class="text-right font-weight-bold""></td>
                </tr>
            </table>
            <br />
            <h5>Laba Rugi</h5>
            <table class="table table-sm table-striped">
                <tr>
                    <th>Total Laba / Rugi</th>
                    <td class="text-sm">Total Pendapatan - Total Pengeluaran</td>
                    <th id="total-lr" class="text-right"></th>
                </tr>
            </table>
        </div>
        <div class="col-md-5">
            <h5>Biaya Variabel</h5>
            <table class="table table-sm table-striped">
                <tr>
                    <td>Biaya Bahan Baku Produksi (BV)</td>
                    <td>&nbsp;</td>
                    <td id="total-biaya-bv" class="text-right">
                    </td>
                </tr>
                <tr>
                    <td><div class="pl-2 text-sm">Rincian Biaya : </div></td>
                    <td><div id="biaya-bv-list"></div></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</section>
<script>
$(function(){
    let today = new Date()
    let dd = String(today.getDate()).padStart(2, '0')
    let mm = String(today.getMonth() + 1).padStart(2, '0') //January is 0!
    let yyyy = today.getFullYear()
    today = dd + '-' + mm + '-' + yyyy

    $('#tgl-awal-lr').Zebra_DatePicker({
         format:'d-m-Y',
         pair:$('#tgl-akhir-lr')
    })

    $('#tgl-akhir-lr').Zebra_DatePicker({
         format:'d-m-Y',
         direction:true
    })

    $('#btn-proses-lr').click(function(e){
        e.preventDefault()
        let awal = $('#tgl-awal-lr').val()
        let akhir = $('#tgl-akhir-lr').val()
        laporan_lr(awal,akhir)
    })

    $('#btn-print-lr').click(function(e){
        e.preventDefault()
        window.print()

    })

    function laporan_lr(aw,ah){
        let total = $.post(service_url+'s_laporan.php',{
            token:'items_laku',
            awal:aw,
            akhir:ah
            },function(data){
                let diskon_retur=0
                let total_pengeluaran=0
                let total_lr
                let biaya_list = ''
                let biaya_bv_list=''
                for(var z=0;z<data.biaya.length;z++){
                    biaya_list +='<tr style="border:none;background-color:rgba(0,0,0,0);">'
                    biaya_list +='<td class="text-sm">'+data.biaya[z].kode_kategori+'</td>'
                    biaya_list +='<td class="text-sm">'+data.biaya[z].nama+'</td>'
                    biaya_list +='<td class="text-sm text-right">'+formatCurrency(data.biaya[z].nominal)+'</td>'
                    biaya_list +='</tr>'
                }
                $('#biaya-list').html(biaya_list)
                for(var z=0;z<data.biayav.length;z++){
                    biaya_bv_list +='<tr style="border:none;background-color:rgba(0,0,0,0);">'
                    biaya_bv_list +='<td class="text-sm">'+data.biayav[z].nama+'</td>'
                    biaya_bv_list +='<td class="text-sm text-right">'+formatCurrency(data.biayav[z].nominal)+'</td>'
                    biaya_bv_list +='</tr>'
                }
                $('#biaya-bv-list').html(biaya_bv_list)
                diskon_retur = parseInt(data.jual_kotor.total_rp)-parseInt(data.jual_bersih.total_rp)
                total_pengeluaran = parseInt(data.total_biaya.total)+parseInt(data.jual_kotor.total_hpp)
                total_lr = parseInt(data.jual_bersih.total_rp) - total_pengeluaran
                $('#tgl-periode-a').text(aw)
                $('#tgl-periode-b').text(ah)
                $('#total-biaya').text(formatCurrency(data.total_biaya.total))
                $('#total-biaya-bv').text(formatCurrency(data.total_bv.total))
                $('#total-hpp').text(formatCurrency(data.jual_kotor.total_hpp))
                $('#total-pendapatan-kotor').text(formatCurrency(data.jual_kotor.total_rp))
                $('#diskon-retur-penjualan').text(formatCurrency(diskon_retur))
                $('#total-pendapatan-bersih').text(formatCurrency(data.jual_bersih.total_rp))
                $('#total-pendapatan').text(formatCurrency(data.jual_bersih.total_rp))
                $('#total-pengeluaran').text(formatCurrency(total_pengeluaran))
                $('#total-lr').text(formatCurrency(total_lr))
            }
        )
    }
})
</script>
