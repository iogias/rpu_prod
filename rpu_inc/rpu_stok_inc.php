<?php
if (!defined('WEB_ROOT')) {
    exit;
}

?>
<section class="content">
<div class="container-fluid">
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">FREZZER</h2>
        <div class="card-tools">
          <button type="button" class="btn btn-primary" id="tambah-container"><i class="fas fa-plus mr-2"></i>Tambah Frezzer</button>
        </div>
      </div>
      <div class="card-body p-3">
      <div class="table-responsive">
        <table id="tb-container" class="table table-sm table-striped text-sm">
            <thead class="text-center">
              <th width="2%">Kode</th>
              <th>Nama</th>
              <th width="35%">&nbsp;</th>
            </thead>
        </table>
      </div>
    </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
    <div class="table-responsive">
    <table id="tb-stok" class="table table-sm table-striped text-sm">
    <thead class="text-center">
    <tr>
      <th>Kode</th>
      <th>Produk</th>
      <th>Frezzer</th>
      <th class="text-center">Jumlah</th>
      <th class="text-center">&nbsp;</th>
    </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Total</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </tfoot>
    </table>
    </div>
    </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <button type="button" class="btn btn-info btn-form" id="btn-tambah-fr-produk"><i class="fas fa-plus mr-2"></i>Tambah</button>
        </div>
      </div>
      <div class="card-body">
        <form class="form-horizontal" id="f-frezzer" name="f-frezzer" autocomplete="off">
          <div class="card-body">
            <div class="form-group row">
              <label for="frezzer" class="col-sm-3 col-form-label">Frezzer</label>
              <div class="col-sm-8">
                <select type="text" class="form-control select-f" id="frezzer" name="frezzer" readonly>
                <option value="00">-- PILIH --</option>
                <?php
                  $frezzer=RpuKatalog::getAll('tb_container');
                  foreach ($frezzer as $fr){ ?>
                    <option value="<?php echo $fr['id'];?>">
                    <?php echo $fr['nama'];?>
                    </option>
                <?php  } ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="jumlah" class="col-sm-3 col-form-label">Produk</label>
              <div class="col-sm-8">
                <input type="hidden" id="id-produk-fr" />
                <select type="text" class="form-control select-f" id="produk-frezzer" name="produk-frezzer" readonly>
                  <option data-stok="00" value="00">-- PILIH --</option>
                <?php
                  $produk=RpuKatalog::getAllProdukJoin(1);
                  foreach ($produk as $item){
                    $qt=RpuKatalog::produk_on_frezzer($item['kode_produk']);
                    $qt = ($qt=='')?0:$qt;
                    $stok = (int) $item['stok_ready'] - (int) $qt;
                    ?>
                    <option data-stok="<?php echo $stok;?>" value="<?php echo $item['kode_produk'];?>">
                    <?php echo $item['nama_jual'];?>
                    </option>
                <?php  } ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="jumlah" name="jumlah" readonly />
              </div>
              <label for="stok-ready" class="col-sm-2 col-form-label">Stok tersisa</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="stok-ready" name="stok-ready" readonly />
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card-footer text-right">
      <button type="button" class="btn btn-success btn-form" id="btn-produk-fr-update" disabled>Update</button>
      <button type="button" class="btn btn-primary btn-form" id="btn-produk-fr-simpan" disabled>Simpan</button>
      <button type="button" class="btn btn-default btn-form" id="btn-produk-fr-tutup" disabled>Tutup</button>
      </div>
    </div>
</div>

</div>
</section>
<script>
$(function () {
  let param = 'stok'
  fetch_container()
  fetch_stok()

   $('#tambah-container').click(function(e){
        e.preventDefault()
        let html = '<tr>'
        html +='<tr>'
        html +='<td class="text-center" contenteditable id=data1></td>'
        html +='<td class="text-center" contenteditable id=data2></td>'
        html +='<td class="text-right">'
        html +='<button id="btn-tambah-produk-frezzer" type="button" class="btn btn-sm btn-primary">Simpan</button>'
        html +='</td>'
        html +='</tr>'
        $('#tb-container tbody').append(html)
        $('#data1').focus()
    })

   $(document).on('blur','.update-fr',function(){
    let idr = $(this).data('id')
    let valu = $(this).text()
    $.post(service_url+'s_update.php',{
        token:param,
        idr:idr,
        nama:valu
    },function(data){
        if(data.status==true){
          toastr.success('SUKSES UPDATE DATA!')
          $('#tb-container').DataTable().destroy()
          fetch_container()
        } else{
          toastr.error('ERROR UPDATE DATA!')
        }
    },'json')

   })

   $(document).on('click','.btn-del-fr',function(){
    let idr = $(this).data('id')
    $.post(service_url+'s_delete.php',{
        token:'container',
        idr:idr
    },function(data){
        if(data.status==true){
          toastr.success('SUKSES DELETE DATA!')
          $('#tb-container').DataTable().destroy()
          fetch_container()
        } else{
          toastr.error('ERROR DELETE DATA!')
        }
    },'json')

   })

   $(document).on('click','#btn-tambah-produk-frezzer',function(e){
    e.preventDefault()
    let idf = $('#data1').text()
    let nama= $('#data2').text()
    if (idf!='' && nama!=''){
       $.post(service_url+'s_tambah.php',{
        token:'frezzer',
        idf:idf,
        nama:nama
       },function(data){
                if (data.status==true){
                  toastr.success('SUKSES INPUT DATA!')
                  $('#tb-container').DataTable().destroy()
                  fetch_container()
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
    } else {
      toastr.error('ID dan Nama harap diisi')
    }
  })

  $('#produk-frezzer').change(function(){
    // let id = $(this).val()
    let qty = $(this).find(':selected').data('stok')
    $('#stok-ready').val(qty)
  })

  $('#btn-produk-fr-tutup').click(function(e){
    e.preventDefault()
    $('#f-frezzer')[0].reset()
    $('#produk-frezzer').attr('readonly','readonly')
    $('#jumlah').attr('readonly','readonly')
    $('#frezzer').attr('readonly','readonly')
    disable_btn($('#btn-produk-fr-simpan'))
    disable_btn($(this))
  })

  $('#btn-tambah-fr-produk').click(function(e){
    e.preventDefault()
    $('#f-frezzer')[0].reset()
    $('#frezzer').removeAttr('readonly')
    $('#produk-frezzer').removeAttr('readonly')
    $('#jumlah').removeAttr('readonly')
    enable_btn($('#btn-produk-fr-simpan'))
    enable_btn($('#btn-produk-fr-tutup'))
    // $('#id-frezzer').val(idr)
    // $('#nama-frezzer').val(col)
  })

  $('#btn-produk-fr-simpan').click(function(e){
    e.preventDefault()
    let idf = $('#frezzer').val()
    let idp = $('#produk-frezzer').val()
    let stok = parseInt($('#stok-ready').val())
    let qty = parseInt($('#jumlah').val())
    if(idf!='00'){
      if(isNaN(qty) || qty>stok){
          toastr.error('Jumlah tidak boleh kosong dan tidak boleh melebihi stok')
      } else{
          $.post(service_url+'s_tambah.php',{
            token:'produk_frezzer',
            idf:idf,
            idp:idp,
            qty:qty
           },function(data){
                  if (data.status==true){
                    toastr.success('SUKSES INPUT DATA!')
                    $('#tb-'+param).DataTable().destroy()
                    fetch_stok()
                   } else{
                    toastr.error('ERROR INPUT DATA!')
                   }
            },'json')
      }
    } else{
      toastr.error('Pilih produk')
    }
  })

  $(document).on('click','.edit-produk-fr',function(e){
    e.preventDefault()
    let id = $(this).attr('id')
    let qty = 0
    $('#id-produk-fr').val(id)
    $.post(service_url+'s_update.php',{
            token:'p_frezzer',
            id:id,
           },function(data){
                if (data.status==true){
                  $('#frezzer').removeAttr('readonly')
                  $('#produk-frezzer').removeAttr('readonly')
                  $('#jumlah').removeAttr('readonly')
                  enable_btn($('#btn-produk-fr-update'))
                  enable_btn($('#btn-produk-fr-tutup'))
                  $('#frezzer').val(data.row.id_frezzer)
                  $('#produk-frezzer').val(data.row.kode_produk)
                  qty = $('#produk-frezzer').find(':selected').data('stok')
                  $('#stok-ready').val(qty)
                  $('#jumlah').val(data.row.jumlah)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
  })

  $('#btn-produk-fr-update').click(function(e){
    let id = $('#id-produk-fr').val()
    let idf = $('#frezzer').val()
    let idp = $('#produk-frezzer').val()
    let stok = parseInt($('#stok-ready').val())
    let qty = parseInt($('#jumlah').val())
    let total = qty + stok
    if(idf!='00'){
      if(isNaN(qty) || qty>total){
          toastr.error('Jumlah tidak boleh kosong dan tidak boleh melebihi stok')
      } else{
          $.post(service_url+'s_update.php',{
            token:'update_pr_frezzer',
            id:id,
            idf:idf,
            idp:idp,
            qty:qty
           },function(data){
                  if (data.status==true){
                    toastr.success('SUKSES UPDATE DATA!')
                    window.setTimeout(function(){
                            window.location.href = 'index.php?action=rpu_stok'
                        },1500)
                   } else{
                    toastr.error('ERROR UPDATE DATA!')
                   }
            },'json')
      }
    } else{
      toastr.error('Pilih produk')
    }
  })

  function fetch_container(){
    let dtb = $('#tb-container').DataTable({
      "dom":'ltp',
      "processing":true,
      "serverside":true,
      "lengthMenu": [
                      [15, 30, 50],
                      [15, 30, 50]
                    ],
      "ajax":{
                url: service_url+'s_katalog.php',
                method: 'POST',
                data:{token:'container'}
              }
    })
  }

  function fetch_stok() {
        $.ajax({
                url: service_url+'s_katalog.php',
                method: 'POST',
                dataType: 'json',
                data:{token:param}
            }).done(function(data){
                let judul = 'Rekap stok produk'
                let tb = $('#tb-'+param).DataTable({
                    dom:'<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"p>>',
                    aaData: data,
                    processing:true,
                    autoWidth:false,
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
                        { "data": "kode_produk","class":"text-center"},
                        { "data": "nama_produk",},
                        { "data": "nama_frezzer" },
                        { "data": "jumlah","class":"text-center","render": function (data,type,row){
                                return formatCurrency(data);
                            },},
                        { "data": "id","class":"text-right","render":function(data,type,row){
                                    return edButtonProdukFr(data)
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
                                columns:[1,2,3,4],
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
                                columns:[1,2,3,4],
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
                                columns:[1,2,3,4],
                                modifier: {
                                    page: 'current',
                                }
                            },
                            customize: function(win) {
                                $(win.document.body)
                                    .css('font-size','10pt')
                                    .prepend('<h5 class="text-center">Rekap Stok Produk</h5>');
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size','inherit');
                            }
                        },
                    ],
                    footerCallback: function(row,data,start,end,display) {
                        var api = this.api(), data;

                        var colNumber = [3];
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
