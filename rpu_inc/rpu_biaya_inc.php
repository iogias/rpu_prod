<?php
if (!defined('WEB_ROOT')) {
    exit;
}
?>
<section class="content">
<div class="container-fluid">
<div class="row">
  <div class="col-md-5">
    <div class="card card-gray">
    <div class="card-header">
      <h5 class="card-title">Kategori Biaya</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="tb-group-biaya" class="table table-sm table-striped text-sm">
            <thead class="text-center">
              <th>Kode</th>
              <th>Nama</th>
              <th>Keterangan</th>
            </thead>
            <tbody>
              <?php
              $kb=RpuKatalog::getAllKategoriBiaya();
              for($y=0;$y<count($kb);$y++){?>
              <tr>
                <td><strong><?php echo $kb[$y]['kode'];?></strong></td>
                <td><strong><?php echo $kb[$y]['nama'];?></strong></td>
                <td class="text-muted"><?php echo $kb[$y]['keterangan'];?></td>
              </tr>
             <?php } ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
          <div class="form-group row">
            <label for="tgl-awal-biaya" class="col-form-label">Periode</label>
            <div class="col-sm-3">
            <input value="<?php echo $tglnow;?>" type=text class="form-control" id="tgl-awal-biaya" name="tgl-awal-biaya" data-zdp_readonly_element="false" required>
            </div>
            <div class="col-sm-3">
            <input value="<?php echo $tglnow;?>" type=text class="form-control" id="tgl-akhir-biaya" name="tgl-akhir-biaya" data-zdp_readonly_element="false" required>
            </div>
            <div class="col-sm-2">
            <button type="button" id="btn-proses-biaya" class="btn btn-info">PROSES<i class="fas fa-angle-double-right ml-2"></i></button>
            </div>
            <div class="col-sm-3 text-right">
            <button id="tambah-biaya" type="button" class="btn btn-primary btn-tambah" data-toggle="modal" data-target="#modal-biaya">
            <i class="fas fa-plus mr-2"></i>TAMBAH
            </button>
            </div>
          </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body p-3">
        <div class="table-responsive">
        <table id="tb-biaya" class="table table-sm text-sm">
        <thead align="center">
          <tr>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>Tanggal</th>
          <th width="4%">Group</th>
          <th>Nama</th>
          <th class="text-center">Nominal</th>
          <th>Notes</th>
          <th>No.Ref</th>
          <th>&nbsp;</th>
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
  </div>
</div>
<div class="row">
<div class="col-md-5">
  <div class="card card-block card-navy">
    <div class="card-header">
    <h5 class="card-title">Jenis Biaya</h5>
    <div class="card-tools">
      <button id="tambah-jb" type="button" class="btn btn-primary btn-sm btn-tambah" data-toggle="modal" data-target="#modal-jb">
        <i class="fas fa-plus mr-2"></i>Tambah
      </button>
    </div>
    </div>
    <div class="card-body p-2">
      <div class="table-responsive">
        <table id="tb-jenis-biaya" class="table table-sm table-striped text-sm">
        <thead align="center">
          <tr>
          <th width="3%">Group</th>
          <th>Nama Biaya</th>
          <th>Status</th>
          <th>&nbsp;</th>
          </tr>
        </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-md-7">
  <div class="card">
    <div class="card-body">
    <div class="row">
    <div class="col-sm-6 ml-2">
        <ul class="list-unstyled">
        <li class="lead">RESUME BIAYA</li>
        <li><small class="text-muted">Dari : <span id="s-awal-biaya"></span> s.d <span id="s-akhir-biaya"></span></small></li>
        </ul>
    </div>
    <div class="col-sm-2">
        <ul class="list-unstyled">
        <li>Total Transaksi</li>
        <li>Total Biaya <small>(Rp)</small></li>
        </ul>
    </div>
    <div class="col-sm-2">
        <ul class="list-unstyled">
        <li id="total-trx-biaya" style="font-weight: bold;"></li>
        <li id="total-nominal-biaya" style="font-weight: bold;"></li>
        </ul>
    </div>
    </div>
  </div>
  </div>
</div>
</div>
<!---BEGIN MODAL--->
<div class="modal fade" id="modal-biaya">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header bg-info">
    <h4 class="modal-title">Biaya</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" id="f-biaya" name="f-biaya" autocomplete="off">
        <div class="card-body">
          <div class="form-group row">
            <input type="hidden" class="form-control" id="id-biaya" name="id-biaya">
            <label for="group-biaya" class="col-sm-4 col-form-label">Kategori</label>
            <div class="col-sm-8">
              <select type="text" class="form-control" name="group-biaya" id="group-biaya" required>
                <option value="0">--Pilih--</option>
                <?php
                $kb3=RpuKatalog::getAllKategoriBiaya();
                for($xz=0;$xz<count($kb3);$xz++){?>
                <option value="<?php echo $kb3[$xz]['kode'];?>"><?php echo $kb3[$xz]['nama'];?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="group-jb-biaya" class="col-sm-4 col-form-label">Nama Biaya</label>
            <div class="col-sm-8">
              <select type="text" class="form-control" name="group-jb-biaya" id="group-jb-biaya" required>
                <?php
                $bi = RpuKatalog::getAllJenisBiaya();
                for($b=0;$b<count($bi);$b++){ ?>
                  <option value="<?php echo $bi[$b]['id'];?>"><?php echo $bi[$b]['nama'];?></option>
              <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="nominal-biaya" class="col-sm-4 col-form-label">Nominal</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="nominal-biaya" id="nominal-biaya" required />
            </div>
          </div>
          <div class="form-group row">
            <label for="tgl-biaya" class="col-sm-4 col-form-label">Tanggal</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="tgl-biaya" id="tgl-biaya" data-zdp_readonly_element="false" required />
            </div>
          </div>
          <div class="form-group row">
            <label for="keterangan-biaya" class="col-sm-4 col-form-label">Notes</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="keterangan-biaya" id="keterangan-biaya" />
            </div>
          </div>
          <div class="form-group row">
            <label for="referensi-biaya" class="col-sm-4 col-form-label">No.Referensi</label>
            <div class="col-sm-8">
              <input placeholder="No.Nota / No.Kwitansi dsb." type="text" class="form-control" name="referensi-biaya" id="referensi-biaya" />
            </div>
          </div>
        </div>
      </form>
  </div>
  <div class="modal-footer right-content-between">
    <button type="button" class="btn btn-success btn-form" id="btn-biaya-update">Update</button>
    <button type="button" class="btn btn-primary btn-form" id="btn-biaya-simpan">Simpan</button>
  </div>
</div>
</div>
</div>
<!---END MODAL--->

<!---BEGIN MODAL--->
<div class="modal fade" id="modal-jb">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header bg-info">
    <h4 class="modal-title">Jenis Biaya</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" id="f-jb" name="f-jb" autocomplete="off">
        <div class="card-body">
          <div class="form-group row">
            <label for="kategori-biaya" class="col-sm-4 col-form-label">Kategori</label>
            <div class="col-sm-8">
              <select type="text" class="form-control" name="kategori-biaya" id="kategori-biaya" required>
                <?php
                $kb2=RpuKatalog::getAllKategoriBiaya();
                for($xy=0;$xy<count($kb2);$xy++){?>
                <option value="<?php echo $kb2[$xy]['kode'];?>"><?php echo $kb2[$xy]['nama'];?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="nama-jb" class="col-sm-4 col-form-label">Nama Biaya*</label>
            <div class="col-sm-8">
              <input type="hidden" class="form-control" id="id-jb" name="id-jb">
              <input type="text" class="form-control" id="nama-jb" name="nama-jb" minlength="2" maxlength="50" required />
            </div>
          </div>
          <div class="form-group row">
          <label for="status-jb" class="col-sm-4 col-form-label">Status</label>
          <div class="col-sm-5">
            <select class="form-control" name="status-jb" id="status-jb">
              <option value="1">Aktif</option>
              <option value="0">Non-Aktif</option>
            </select>
          </div>
        </div>
        </div>
      </form>
  </div>
  <div class="modal-footer right-content-between">
    <button type="button" class="btn btn-success btn-form-jb" id="btn-jb-update">Update</button>
    <button type="button" class="btn btn-primary btn-form-jb" id="btn-jb-simpan">Simpan</button>
  </div>
</div>
</div>
</div>
<!---END MODAL--->
</div>
</section>
<script>
$(function () {
    let param ='biaya'
    let today = new Date()
    let dd = String(today.getDate()).padStart(2, '0')
    let mm = String(today.getMonth() + 1).padStart(2, '0') //January is 0!
    let yyyy = today.getFullYear()
    today = dd + '-' + mm + '-' + yyyy

    let tb_jb = $('#tb-jenis-biaya').DataTable({
            'autoWidth': false,
            'serverside': true,
            'sortable':true,
            'info':false,
            'lengthMenu': [
                      [5, 10, 25, 50, 100],
                      [5, 10, 25, 50, 100]
                ],
            'ajax':{
                url:service_url+'s_katalog.php',
                type:'POST',
                data:{token:'jenis_biaya'}
            }
    })

     $('#tgl-awal-biaya').Zebra_DatePicker({
         format:'d-m-Y',
         pair:$('#tgl-akhir-biaya')
    })

    $('#tgl-akhir-biaya').Zebra_DatePicker({
         format:'d-m-Y',
         direction:true
    })

    fetch_biaya(today,today)
    fetch_total_biaya(today,today)

    $('#modal-biaya').on('shown.bs.modal',function(){
       set_date($('#tgl-biaya'))
    })

    $('#btn-proses-biaya').click(function(e){
        e.preventDefault()
        let awal = $('#tgl-awal-biaya').val()
        let akhir = $('#tgl-akhir-biaya').val()
        $('#tb-'+param).DataTable().destroy()
        fetch_biaya(awal,akhir)
        fetch_total_biaya(awal,akhir)
    })

    $('.btn-tambah').click(function(){
      let idx=$(this).attr('id').replace('tambah-','')
      if(idx=='biaya'){
        $('#f-'+idx)[0].reset()
        // let jbval = $('#group-jb-biaya')
        // jbval.empty()
        $('#btn-'+param+'-simpan').removeAttr('disabled')
        $('#btn-'+param+'-update').attr('disabled','disabled')
      } else if(idx='jb'){
        $('#f-'+idx)[0].reset()
        disable_btn($('#btn-'+idx+'-update'))
      }
    })

  $(document).on('keyup','#nominal-biaya',function(){
      let nilai = $(this)
      format_uang(nilai)
  })

  $(document).on('change','#group-biaya',function(){
    let idj=$(this).val()
    $.post(service_url+'s_update.php',{
        token:'kjb',
        data:idj
    },function(data){
        if(data){
          let options=''
          for(var i=0;i<data.length;i++){
              options += '<option value="'+data[i].jid+'">'+data[i].nama+'</option>'
          }
          $('#group-jb-biaya').html(options)
        }
    })
  })

  $(document).on('click','.edit-jb',function(e){
    e.preventDefault()
    let idx = $(this).attr('id').replace('edit-','')
    let ids = parseInt(idx)
    $('#id-jb').val(ids)
    enable_btn($('#btn-jb-update'))
    disable_btn($('#btn-jb-simpan'))
    $.post(service_url+'s_update.php',{
        token:'jb',
        data:ids
    },function(data){
        if(data.status==true){
            $('#f-jb').find('input,select').each(function(){
                $('#kategori-biaya').val(data.jb.kode)
                $('#nama-jb').val(data.jb.nama)
                $('#status-jb').val(data.jb.status)
            })
        }
    },'json')
  })

  $(document).on('click','.edit-'+param,function(e){
    e.preventDefault()
    let idx = $(this).attr('id').replace('edit-','')
    let ids = parseInt(idx)
    $('#id-biaya').val(ids)
    $('#btn-'+param+'-update').removeAttr('disabled')
    $('#btn-'+param+'-simpan').attr('disabled','disabled')
    $.post(service_url+'s_update.php',{
        token:param,
        data:ids
    },function(data){
        if(data.status==true){
            $('#f-'+param).find('input,select').each(function(){
              $('#group-biaya').val(data.biaya.kode_kategori)
              $('#group-jb-biaya').val(data.biaya.jenis_id)
              $('#tgl-biaya').val(data.biaya.tanggal)
              $('#nominal-biaya').val(data.biaya.nominal)
              $('#keterangan-biaya').val(data.biaya.notes)
              $('#referensi-biaya').val(data.biaya.noref)
            })
        }
    },'json')
  })

  $(document).on('click','.del-'+param,function(e){
    e.preventDefault()
    let idx = $(this).attr('id').replace('del-','')
    let ids = parseInt(idx)

    if (confirm("Hapus data ini?")){
        $.post(service_url+'s_delete.php',{
            token:param,
            data:ids
        },function(data){
            if(data.status==true){
                toastr.success('SUKSES HAPUS DATA!')
                window.setTimeout(function(){
                    window.location.href = 'index.php?action=rpu_biaya'
                },1500)
            }
        },'json')
        }
  })

  $('.btn-form-jb').click(function(e){
      e.preventDefault()
      let id=$(this).attr('id')
      let idx = id.replace('btn-jb-','')
      let form = $('#f-jb')
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
          if (idx=='simpan'){
            $.post(service_url+'s_tambah.php',{
              token:'new_jb',
              data:form.serialize()
            },function(res){
                if (res.status==true){
                  toastr.success('SUKSES INPUT DATA! [nama] : '+res.nama)
                  $('#f-jb')[0].reset()
                    window.setTimeout(function(){
                        window.location.href = 'index.php?action=rpu_biaya'
                    },1500)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          } else if(idx=='update'){
            $.post(service_url+'s_update.php',{
              token:'update_jb',
              data:form.serialize()
            },function(res){
                if (res.status==true){
                    toastr.success('SUKSES UPDATE DATA!')
                    $('#f-jb')[0].reset()
                    window.setTimeout(function(){
                        window.location.href = 'index.php?action=rpu_biaya'
                    },1500)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          }
        } else {
            toastr.warning(fail_log)
        }
    })

  $('.btn-form').click(function(e){
      e.preventDefault()
      let id=$(this).attr('id')
      let idx = id.replace('btn-'+param+'-','')
      let form = $('#f-'+param)
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
          if (idx=='simpan'){
            $.post(service_url+'s_tambah.php',{
              token:'new_'+param,
              data:form.serialize()
            },function(res){
                if (res.status==true){
                  toastr.success('SUKSES INPUT DATA!')
                  $('#f-'+param)[0].reset()
                    window.setTimeout(function(){
                        window.location.href = 'index.php?action=rpu_biaya'
                    },1500)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          } else if(idx=='update'){
            $.post(service_url+'s_update.php',{
              token:'update_'+param,
              data:form.serialize()
            },function(res){
                if (res.status==true){
                    toastr.success('SUKSES UPDATE DATA!')
                    window.setTimeout(function(){
                        window.location.href = 'index.php?action=rpu_biaya'
                    },1500)
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          }
        } else {
            toastr.warning(fail_log)
        }
    })

  function fetch_total_biaya(aw,bw){
        let total = $.post(service_url+'s_katalog.php',{
            token:'totalan_biaya',
            awal:aw,
            akhir:bw
            },function(data){
            if(data.status==true){
                $('#total-nominal-biaya').text(data.nominal)
                $('#total-trx-biaya').text(data.countr)
                $('#s-awal-biaya').text(aw)
                $('#s-akhir-biaya').text(bw)
            }
        },'json')
  }

  function fetch_biaya(aw,ah) {
    $.ajax({
            url: service_url+'s_katalog.php',
            method: 'POST',
            dataType: 'json',
            data:{
                    token:'biaya',
                    awal:aw,
                    akhir:ah,
            }
        }).done(function(data){
            var tb = $('#tb-biaya').DataTable({
                dom:'fBt<"bottom"l>p',
                aaData: data,
                processing:true,
                scrollCollapse: true,
                paginationType: "full_numbers",
                lengthMenu: [
                      [5, 10, 25, 50, 100],
                      [5, 10, 25, 50, 100]
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
                    { "data": "tgl_awal","class":"d-none t-awal-inv","render":function(data,type,row){return formatDmy(data);},},
                    { "data": "tgl_akhir","class":"d-none t-akhir-inv","render":function(data,type,row){return formatDmy(data);},},
                    { "data": "tanggal","class":"text-center","render":function(data,type,row){return formatDmy(data);},},
                    { "data": "kode_kategori"},
                    { "data": "nama_biaya"},
                    { "data": "nominal","class":"text-right","render": function (data,type,row){
                            return formatCurrency(data);
                        },},
                    { "data": "notes","class":"pl-3" },
                    { "data": "noref","class":"pl-3" },
                    { "data": "id","class":"text-center",
                            "render":function(data,type,row){
                                return edButton(data)
                            },},
                ],
                buttons: [
                    {
                        extend:'print',
                        footer:true,
                        autoPrint:true,
                        title:'',
                        exportOptions: {
                            columns:[0,1,2,3,4,5,6,7,8],
                            modifier: {
                                page: 'current',
                            }
                        },
                        customize: function(win) {
                            var tawal = $(win.document.body).find('td.t-awal-inv')
                            var taw = tawal[0].innerText
                            var takhir = $(win.document.body).find('td.t-akhir-inv')
                            var tah = takhir[0].innerText
                            $(win.document.body)
                                .css('font-size','10pt')
                                .prepend('<h5 class="text-center">Rekap Biaya</h5><p class="text-center">Periode: '+taw+' s.d '+tah+'</p>');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size','inherit');
                        }
                    },
                ],
                footerCallback: function(row,data,start,end,display) {
                    var api = this.api(), data;
                    var colNumber = [5];
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[, ₹]|(\.\d{2})/g, "") * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    for (i = 0; i < colNumber.length; i++) {
                        var colNo = colNumber[i];
                        var total = api
                                .column(colNo,{page:'current'})
                                .data()
                                .reduce(function (a,b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                        $(api.column(colNo).footer()).html(formatCurrency(total));
                    }
                  },
            });
        });
    }
})
</script>

