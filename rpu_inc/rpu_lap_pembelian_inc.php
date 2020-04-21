<?php
if (!defined('WEB_ROOT')) {
    exit;
}?>
<section class="content">
<div class="container-fluid">
<div class="no-print">
<form role="form">
    <div class="row">
        <div class="col-sm-2"><p>&nbsp;</p></div>
        <div class="col-sm-2">
        <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="tgl-awal">Dari</label>
        <div class="col-sm-10">
        <input value="<?php echo $tglnow;?>" type="text" class="form-control" id="tgl-awal" name="tgl-awal" data-zdp_readonly_element="false" required>
        </div>
        </div>
        </div>
        <div class="col-sm-2">
        <div class="form-group row">
        <label class="col-sm-2 col-form-label text-center" for="tgl-akhir">s.d</label>
        <div class="col-sm-10">
        <input value="<?php echo $tglnow;?>" type="text" class="form-control" id="tgl-akhir" name="tgl-akhir" data-zdp_readonly_element="false" required>
        </div>
        </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group row">
            <label class="col-sm-5 col-form-label text-center" for="filter-lunas">Status Lunas</label>
            <div class="col-sm-7">
            <select class="form-control" id="filter-lunas">
                <option value="99">SEMUA</option>
                <option value="lunas">LUNAS</option>
                <option value="belum">BELUM</option>
            </select>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <button type="button" id="btn-proses-lap" class="btn btn-info">PROSES<i class="fas fa-angle-double-right ml-3"></i></button>
        </div>
    </div>
</form>
</div>

<div class="card">
<div class="card-body">
    <div class="table-responsive">
    <table id="tb-lap-pembelian" class="table table-sm text-sm p-2">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th width="9%" class="text-center">Tgl.PO</th>
            <th class="text-center">Nomor PO</th>
            <th class="text-center">Supplier</th>
            <th width="7%" class="text-center">Status</th>
            <th width="9%" class="text-center">J.Tempo</th>
            <th class="text-center">Qty</th>
            <th class="text-center">Total</th>
            <th class="text-center">Hutang</th>
            <th class="text-center">Keterangan</th>
            <th width="8%">&nbsp;</th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Total</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </tfoot>
    </table>
    </div>
</div>
</div>
<div class="card">
    <div class="card-body no-print">
        <div class="row">
        <div class="col-sm-3 ml-4">
            <ul class="list-unstyled">
            <li class="lead">RESUME LAPORAN</li>
            <li><small class="text-muted">Dari : <span id="s-awal"></span> s.d <span id="s-akhir"></span></small></li>
            </ul>
        </div>
        <div class="col-sm-2">
            <ul class="list-unstyled">
            <li>Total Transaksi</li>
            <li>Total Item <small>(pcs)</small></li>
            </ul>
        </div>
        <div class="col-sm-2">
            <ul class="list-unstyled">
            <li id="total-transaksipo" style="font-weight: bold;"></li>
            <li id="total-qtybayar" style="font-weight: bold;"></li>
            </ul>
        </div>
        <div class="col-sm-2">
            <ul class="list-unstyled">
            <li>Total Pembelian <small>(Rp)</small></li>
            <li>Total Hutang <small>(Rp)</small></li>
            </ul>
        </div>
        <div class="col-sm-2">
            <ul class="list-unstyled">
            <li class="text-right" id="total-rpbayar" style="font-weight: bold;"></li>
            <li class="text-right" id="total-hutangbayar" style="font-weight: bold;"></li>
            </ul>
        </div>
        </div>
    </div>
</div>
<!-- BEGIN MODAL -->
<div class="modal fade" id="modal-bayar">
<div class="modal-dialog">
<div class="modal-content">
<form role="form" id="f-bayar" name="f-bayar" class="form-horizontal" autocomplete="off">
<div class="modal-header bg-info">
  <h4 class="modal-title">Pembayaran PO</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
      <div class="card-body">
        <div class="form-group row">
          <label for="bayar-nomorpo" class="col-sm-4 col-form-label">Nomor PO</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="bayar-nomorpo" name="bayar-nomorpo" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label for="tagihan-nomorpo" class="col-sm-4 col-form-label">Tagihan</label>
          <div class="col-sm-8">
            <input type="text" class="form-control text-right" id="tagihan-nomorpo" name="tagihan-nomorpo" readonly>
          </div>
        </div>
        <div class="form-group row">
            <label for="tgl-bayar" class="col-sm-4 col-form-label">Tgl.Bayar</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="tgl-bayar" name="tgl-bayar" data-zdp_readonly_element="false" required />
            </div>
        </div>
        <div class="form-group row">
            <label for="bayar-via" class="col-sm-4 col-form-label">Cara Bayar</label>
            <div class="col-sm-8">
            <select class="form-control" name="bayar-via" id="bayar-via">
                <option value="CASH">CASH</option>
                <option value="TRANSFER">TRANSFER</option>
                <option value="LAINNYA">LAINNYA</option>
            </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="notes-bayar" class="col-sm-4 col-form-label">Notes</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="notes-bayar" name="notes-bayar" placeholder="Bank|No.Rek|A.n" />
            </div>
        </div>
        <div class="form-group row">
            <label for="jml-bayar" class="col-sm-4 col-form-label">Jumlah Bayar</label>
            <div class="col-sm-8">
              <input type="text" class="form-control text-right" id="jml-bayar" name="jml-bayar" readonly />
            </div>
        </div>
      </div>
</div>
<div class="modal-footer right-content-between">
<button type="button" class="btn btn-primary" id="btn-simpan-bayar">Simpan</button>
<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>
</form>
</div>
</div>
</div>
<!-- END MODAL -->
</div>
</section>
<script>
$(function(){
    let today = new Date()
    let dd = String(today.getDate()).padStart(2, '0')
    let mm = String(today.getMonth() + 1).padStart(2, '0') //January is 0!
    let yyyy = today.getFullYear()
    today = dd + '-' + mm + '-' + yyyy

    $('#tgl-awal').Zebra_DatePicker({
         format:'d-m-Y',
         pair:$('#tgl-akhir')
    })

    $('#tgl-akhir').Zebra_DatePicker({
         format:'d-m-Y',
         direction:true
    })

    fetch_lap_beli(today,today)
    fetch_total(today,today)

    $('#modal-bayar').on('shown.bs.modal',function(){
       set_date($('#tgl-bayar'))
    })

    $('#btn-proses-lap').click(function(e){
        e.preventDefault()
        let awal = $('#tgl-awal').val()
        let akhir = $('#tgl-akhir').val()
        let lunas = $('#filter-lunas').val()
        $('#tb-lap-pembelian').DataTable().destroy()
        fetch_lap_beli(awal,akhir,lunas)
        fetch_total(awal,akhir)
    })

    $(document).on('click','.btn-bayar',function(e){
        e.preventDefault()
        let id = $(this).attr('id')
        let tagihan = ($(this).attr('data-total')=='')?'0':formatCurrency($(this).attr('data-total'))
        $('#bayar-nomorpo').val(id)
        $('#tagihan-nomorpo').val(tagihan)
        $('#jml-bayar').val(tagihan)
    })

    $('#btn-simpan-bayar').click(function(e){
      e.preventDefault()
      let form = $('#f-bayar')
      let fail = false
      let fail_log = ''
      let name
      form.find('input').each(function(){
         if(!$(this).prop('required')){
         }else{
            if(!$(this).val()){
              fail=true
              name=$(this).attr('name')
              fail_log += '['+ name +'] HARUS DI ISI!'+'</br>'
            }
         }
      })
      if (!fail) {
            $.post(service_url+'s_laporan.php',{
              token:'update_bayarpo',
              data:form.serialize()
            },function(data){
                if (data.status==true){
                  toastr.success('SUKSES INPUT DATA!')
                  $('#f-bayar')[0].reset()
                  window.setTimeout(function(){
                        window.location.href = 'index.php?action=rpu_lap_pembelian'
                    },1500)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
        } else {
            toastr.warning(fail_log)
        }
    })

    function fetch_total(aw,bw){
        let total = $.post(service_url+'s_laporan.php',{
            token:'totalan',
            awal:aw,
            akhir:bw
            },function(data){
            if(data.status==true){
                $('#total-rpbayar').text(data.total)
                $('#total-qtybayar').text(data.total_qty)
                $('#total-hutangbayar').text(data.hutang)
                $('#total-transaksipo').text(data.countr)
                $('#s-awal').text(aw)
                $('#s-akhir').text(bw)
            }
        },'json')
    }

    function fetch_lap_beli(aw,ak,arg='99') {
        $.ajax({
                url: service_url+'s_laporan.php',
                method: 'POST',
                dataType: 'json',
                data:{
                        token:'lap_pembelian',
                        awal:aw,
                        akhir:ak,
                        arg:arg
                }
            }).done(function(data){
                var tb = $('#tb-lap-pembelian').DataTable({
                    dom:'fBt<"bottom"l>p',
                    aaData: data,
                    processing:true,
                    paginationType: "full_numbers",
                    lengthMenu: [
                          [10, 25, 50, 100],
                          [10, 25, 50, 100]
                    ],
                    language: {
                        "search"  : "Cari: ",
                        "paginate":{
                            "first"   : "Awal ",
                            "last"    : "Akhir ",
                            "previous": "",
                            "next"    : "",
                        },
                        "lengthMenu": " _MENU_ baris "
                    },
                    columns: [
                        { "data": "tgl_awal","class":"d-none t-awal","render":function(data,type,row){return formatDmy(data);},},
                        { "data": "tgl_akhir","class":"d-none t-akhir","render":function(data,type,row){return formatDmy(data);},},
                        { "data": "tanggal_pembuatan","class":"text-center","render":function(data,type,row){return formatDmy(data);},},
                        { "data": "nomor_po","class":"text-center" },
                        { "data": "supplier" },
                        { "data": "status_bayar","class":"text-center","render":function(data,type,row){return lunasBadge(data)},},
                        { "data": "tanggal_jatuh_tempo","class":"text-center","render":function(data,type,row){return formatDmy(data);},},
                        { "data": "total_produk","class":"text-center" },
                        { "data": "grand_total","class":"text-right","render": function (data,type,row){
                                return formatCurrency(data);
                            },},
                        { "data": "hutang","class":"text-right","render": function (data,type,row){
                                return formatCurrency(data);
                            },},
                        { "data": "keterangan","class":"pl-3" },
                        { "data": "status_bayar","class":"text-center",
                                "render":function(data,type,row){
                                    return lunasButton(data,row.nomor_po,row.grand_total)
                                },},
                    ],
                    buttons: [
                        {
                            extend:'print',
                            footer:true,
                            autoPrint:true,
                            title:'',
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8,9,10],
                                modifier: {
                                    page: 'current',
                                }
                            },
                            customize: function(win) {
                                var tawal = $(win.document.body).find('td.t-awal')
                                var taw = tawal[0].innerText
                                var takhir = $(win.document.body).find('td.t-akhir')
                                var tah = takhir[0].innerText
                                $(win.document.body)
                                    .css('font-size','10pt')
                                    .prepend('<h5 class="text-center">Rekap Pembelian (PO)</h5><p class="text-center">Periode: '+taw+' s.d '+tah+'</p>');
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size','inherit');
                            }
                        },
                    ],
                    footerCallback: function(row,data,start,end,display) {
                        var api = this.api(), data;
                        var colNumber = [7,8,9];
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                    i.replace(/[, ₹]|(\.\d{2})/g, "") * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };
                        for (i = 0; i < colNumber.length; i++) {
                            var colNo = colNumber[i];
                            var total2 = api
                                    .column(colNo,{page:'current'})
                                    .data()
                                    .reduce(function (a,b) {
                                        return intVal(a) + intVal(b);
                                    }, 0);
                            $(api.column(colNo).footer()).html(formatCurrency(total2));
                        }
                      },
                });
            });
    }

})
</script>


