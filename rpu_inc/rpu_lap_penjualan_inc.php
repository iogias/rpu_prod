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
        <label class="col-sm-2 col-form-label" for="tgl-awal-inv">Dari</label>
        <div class="col-sm-10">
        <input value="<?php echo $tglnow;?>" type="text" class="form-control" id="tgl-awal-inv" name="tgl-awal-inv" data-zdp_readonly_element="false" required>
        </div>
        </div>
        </div>
        <div class="col-sm-2">
        <div class="form-group row">
        <label class="col-sm-2 col-form-label text-center" for="tgl-akhir-inv">s.d</label>
        <div class="col-sm-10">
        <input value="<?php echo $tglnow;?>" type="text" class="form-control" id="tgl-akhir-inv" name="tgl-akhir-inv" data-zdp_readonly_element="false" required>
        </div>
        </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group row">
            <label class="col-sm-5 col-form-label text-center" for="filter-lunas-inv">Status Lunas</label>
            <div class="col-sm-7">
            <select class="form-control" id="filter-lunas-inv">
                <option value="99">SEMUA</option>
                <option value="lunas">LUNAS</option>
                <option value="belum">BELUM</option>
            </select>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <button type="button" id="btn-proses-lap-inv" class="btn btn-info">PROSES<i class="fas fa-angle-double-right ml-3"></i></button>
        </div>
    </div>
</form>
</div>

<div class="card">
<div class="card-body">
<div class="table-responsive">
<table id="tb-lap-penjualan" class="table table-hover table-sm text-sm p-2">
    <thead class="text-center">
        <tr>
            <th width="9%">Tgl.Inv</th>
            <th>INVOICE</th>
            <th>Outlet</th>
            <th width="7%">Sts.Lunas</th>
            <th width="9%">J.Tempo</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Piutang</th>
            <th>Keterangan</th>
            <th width="12%">&nbsp;</th>
        </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
        <tr>
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
<div class="card-body">
<div class="row">
    <div class="col-sm-3 ml-4">
        <ul class="list-unstyled">
        <li class="lead">RESUME LAPORAN</li>
        <li><small class="text-muted">Dari : <span id="s-awal-inv"></span> s.d <span id="s-akhir-inv"></span></small></li>
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
        <li id="total-transaksi-inv" style="font-weight: bold;"></li>
        <li id="total-qtybayar-inv" style="font-weight: bold;"></li>
        </ul>
    </div>
    <div class="col-sm-2">
        <ul class="list-unstyled">
        <li>Total Penjualan <small>(Rp)</small></li>
        <li>Total Piutang <small>(Rp)</small></li>
        </ul>
    </div>
    <div class="col-sm-2">
        <ul class="list-unstyled">
        <li class="text-right" id="total-rpbayar-inv" style="font-weight: bold;"></li>
        <li class="text-right" id="total-hutangbayar-inv" style="font-weight: bold;"></li>
        </ul>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="modal-bayar-inv">
<div class="modal-dialog">
<div class="modal-content">
<form id="f-bayar-inv" name="f-bayar-inv" class="form-horizontal" autocomplete="off">
    <div class="modal-header bg-info">
      <h4 class="modal-title">Pembayaran Invoice</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="card-body">
        <div class="form-group row">
          <label for="bayar-nomorinv" class="col-sm-4 col-form-label">Invoice</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="bayar-nomorinv" name="bayar-nomorinv" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label for="tagihan-nomor-inv" class="col-sm-4 col-form-label">Tagihan</label>
          <div class="col-sm-8">
            <input type="text" class="form-control text-right" id="tagihan-nomor-inv" name="tagihan-nomor-inv" readonly>
          </div>
        </div>
        <div class="form-group row">
            <label for="tgl-bayar-inv" class="col-sm-4 col-form-label">Tgl.Bayar</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="tgl-bayar-inv" name="tgl-bayar-inv" data-zdp_readonly_element="false" required />
            </div>
        </div>
        <div class="form-group row">
            <label for="bayar-via-inv" class="col-sm-4 col-form-label">Cara Bayar</label>
            <div class="col-sm-8">
            <select class="form-control" name="bayar-via-inv" id="bayar-via-inv">
                <option value="CASH">CASH</option>
                <option value="TRANSFER">TRANSFER</option>
                <option value="LAINNYA">LAINNYA</option>
            </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="notes-bayar-inv" class="col-sm-4 col-form-label">Notes</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="notes-bayar-inv" name="notes-bayar-inv" placeholder="Bank|No.Rek|A.n" />
            </div>
        </div>
        <div class="form-group row">
            <label for="jml-bayar-inv" class="col-sm-4 col-form-label">Jumlah Bayar</label>
            <div class="col-sm-8">
              <input type="text" class="form-control text-right" id="jml-bayar-inv" name="jml-bayar-inv" readonly />
            </div>
        </div>
      </div>
    </div>
    <div class="modal-footer right-content-between">
        <button type="button" class="btn btn-primary" id="btn-simpan-bayar-inv">Simpan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    </div>
</form>
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

    $('#tgl-awal-inv').Zebra_DatePicker({
         format:'d-m-Y',
         pair:$('#tgl-akhir-inv')
    })

    $('#tgl-akhir-inv').Zebra_DatePicker({
         format:'d-m-Y',
         direction:true
    })

    fetch_lap_jual(today,today)
    fetch_total_inv(today,today)

    $('#modal-bayar-inv').on('shown.bs.modal',function(){
       set_date($('#tgl-bayar-inv'))
    })

    $('#btn-proses-lap-inv').click(function(e){
        e.preventDefault()
        let awal = $('#tgl-awal-inv').val()
        let akhir = $('#tgl-akhir-inv').val()
        let lunas = $('#filter-lunas-inv').val()
        $('#tb-lap-penjualan').DataTable().destroy()
        fetch_lap_jual(awal,akhir,lunas)
        fetch_total_inv(awal,akhir)
    })
    $(document).on('click','.btn-print-inv-lap',function(e){
        e.preventDefault()
        let idv = $(this).attr('id')
        if(idv!=''){
            window.open('print/print_inv.php?nomorinv=' +idv, '_blank')
        }
    })
    $(document).on('click','.btn-bayar-inv',function(e){
        e.preventDefault()
        let id = $(this).attr('id')
        let tagihan = ($(this).attr('data-total-inv')=='')?'0':formatCurrency($(this).attr('data-total-inv'))
        $('#bayar-nomorinv').val(id)
        $('#tagihan-nomor-inv').val(tagihan)
        $('#jml-bayar-inv').val(tagihan)
    })

    $('#btn-simpan-bayar-inv').click(function(e){
      e.preventDefault()
      let form = $('#f-bayar-inv')
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
              token:'update_bayarinv',
              data:form.serialize()
            },function(data){
                if (data.status==true){
                  toastr.success('SUKSES INPUT DATA!')
                  $('#f-bayar-inv')[0].reset()
                  window.setTimeout(function(){
                        window.location.href = 'index.php?action=rpu_lap_penjualan'
                    },1500)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
        } else {
            toastr.warning(fail_log)
        }
    })

    function fetch_total_inv(aw,bw){
        let total = $.post(service_url+'s_laporan.php',{
            token:'totalan_inv',
            awal:aw,
            akhir:bw
            },function(data){
            if(data.status==true){
                $('#total-rpbayar-inv').text(data.total)
                $('#total-qtybayar-inv').text(data.total_qty)
                $('#total-hutangbayar-inv').text(data.hutang)
                $('#total-transaksi-inv').text(data.countr)
                $('#s-awal-inv').text(aw)
                $('#s-akhir-inv').text(bw)
            }
        },'json')
    }

    function fetch_lap_jual(aw,ak,arg='99') {
        $.ajax({
                url: service_url+'s_laporan.php',
                method: 'POST',
                dataType: 'json',
                data:{
                        token:'lap_penjualan',
                        awal:aw,
                        akhir:ak,
                        arg:arg
                }
            }).done(function(data){
                let tgawal = aw
                let tgakhir = ak
                let judul='Rekap Invoice periode '+tgawal+' s.d '+tgakhir
                let tb = $('#tb-lap-penjualan').DataTable({
                    dom:'<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"p>>',
                    aaData: data,
                    processing:true,
                    scrollCollapse: true,
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

                        { "data": "tanggal_pembuatan","class":"text-center","render":function(data,type,row){return formatDmy(data);},},
                        { "data": "nomor_inv","class":"text-center","render":function(data,type,row){return getLinkInv(data);},},
                        { "data": "outlet" },
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
                                    return lunasButtonInv(data,row.nomor_inv,row.grand_total)
                                },},
                    ],
                    buttons: [
                        {
                            extend:'excelHtml5',
                            className:'btn-success',
                            text:'<i class="fa fa-file-excel"></i> EXCEL',
                            footer:true,
                            title:function(){
                                return judul
                            },
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8],
                                modifier: {
                                    page: 'current',
                                }
                            },
                        },
                        {
                            extend:'pdfHtml5',
                            className:'btn-danger',
                            text:'<i class="fa fa-file-pdf"></i> PDF',
                            footer:true,
                            title:function(){
                                return judul
                            },
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8],
                                modifier: {
                                    page: 'current',
                                }
                            },
                        },
                        {
                            extend:'print',
                            footer:true,
                            autoPrint:true,
                            text:'<i class="fa fa-print"></i> PRINT',
                            title:'',
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8],
                                modifier: {
                                    page: 'current',
                                }
                            },
                            customize: function(win) {
                                $(win.document.body)
                                    .css('font-size','10pt')
                                    .prepend('<h5 class="text-center">Rekap Invoice</h5><p class="text-center">Periode: '+tgawal+' s.d '+tgakhir+'</p>');
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size','inherit');
                            }
                        },
                    ],
                    footerCallback: function(row,data,start,end,display) {
                        var api = this.api(), data;
                        var colNumber = [5,6,7];
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
