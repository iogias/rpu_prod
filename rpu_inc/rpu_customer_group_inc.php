<?php
if (!defined('WEB_ROOT')) {
    exit;
}?>

<section class="content">
<div class="container-fluid">
<div class="card card-primary card-outline">
  <div class="card-header">
    <div class="row">
    <div class="col-sm-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Status</span>
        </div>
        <select name="filter-status" id="filter-status" class="form-control">
          <option value="99">SEMUA</option>
          <option value="1">AKTIF</option>
          <option value="0">NON-AKTIF</option>
        </select>
      </div>
    </div>
    <div class="col-sm-6">
      <p>&nbsp;</p>
    </div>
    <div class="col-sm-3 text-right">
    <button id="tambah-customergroup" type="btn" class="btn btn-primary btn-tambah" data-toggle="modal" data-target="#modal-customergroup">
      <i class="fas fa-plus mr-2"></i>Tambah Group Customer
    </button>
    </div>
  </div>
  </div>
<div class="card-body">
<div class="row">
<div class="col-md-12">
<div class="table-responsive">
  <table id="tb-customergroup" class="table table-sm table-striped">
  <thead>
  <tr>
    <th>Nama</th>
    <th>Keterangan</th>
    <th>Status</th>
    <th width="15%" class="text-center">&nbsp;</th>
  </tr>
  </thead>
  </table>
</div>
</div>
</div>
</div>
<div class="modal fade" id="modal-customergroup">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header bg-info">
  <h4 class="modal-title">Group Customer</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <form class="form-horizontal" id="f-customergroup" name="f-customergroup" autocomplete="off">
      <div class="card-body">
        <div class="form-group row">
          <label for="nama" class="col-sm-2 col-form-label">Nama*</label>
          <div class="col-sm-8">
            <input type="hidden" class="form-control" id="id-customergroup" name="id-customergroup">
            <input type="text" class="form-control" id="nama" name="nama" minlength="2" maxlength="50" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="keterangan" id="keterangan" maxlength="80">
          </div>
        </div>
        <div class="form-group row">
          <label for="status" class="col-sm-2 col-form-label">Status</label>
          <div class="col-sm-5">
            <select class="form-control" name="status" id="status">
              <option value="1">Aktif</option>
              <option value="0">Non-Aktif</option>
            </select>
          </div>
        </div>
      </div>
    </form>
</div>
<div class="modal-footer right-content-between">
<button type="button" class="btn btn-success btn-form" id="btn-customergroup-update">Update</button>
<button type="button" class="btn btn-primary btn-form" id="btn-customergroup-simpan">Simpan</button>
<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<script>
$(function () {
    let param = 'customergroup'
    fetch_customergroup()
    function fetch_customergroup(arg='99'){
        let datatable = $('#tb-'+param).DataTable({
            'autoWidth': false,
            'processing': true,
            'serverside': true,
            'ajax':{
                url:service_url+'s_katalog.php',
                type:'POST',
                data:{token:param,args:arg}
            }
        })
    }

    $('#filter-status').change(function(e){
      let vale = $(this).val()
      $('#tb-'+param).DataTable().destroy()
      fetch_customergroup(vale)
    })

    $('.btn-tambah').click(function(){
      let idx=$(this).attr('id').replace('tambah-','')
      if(idx=='customergroup'){
        $('#f-'+idx)[0].reset()
        $('#btn-'+param+'-simpan').removeAttr('disabled')
        $('#btn-'+param+'-update').attr('disabled','disabled')
      } // window.location.href='index.php?action='+id
    })

  $(document).on('click','.edit-'+param,function(e){
    e.preventDefault()
    let idx = $(this).attr('id').replace('edit-','')
    let ids = parseInt(idx)
    $('#id-customergroup').val(ids)
    $('#btn-'+param+'-update').removeAttr('disabled')
    $('#btn-'+param+'-simpan').attr('disabled','disabled')
    $.post(service_url+'s_update.php',{
        token:param,
        data:ids
    },function(data){
        if(data.status==true){
            $('#f-'+param).find('input,select').each(function(){
                $('#nama').val(data.nama)
                $('#keterangan').val(data.keterangan)
                $('#status').val(data.sts)
            })
        }
    },'json')
  })

  $(document).on('click','.del-'+param,function(e){
    e.preventDefault()
    let idx = $(this).attr('id').replace('del-','')
    let ids = parseInt(idx)
    console.log(ids)
    if (confirm("Hapus data ini?")){
        $.post(service_url+'s_delete.php',{
            token:param,
            data:ids
        },function(data){
            if(data.status==true){
                toastr.success('SUKSES HAPUS DATA!')
                $('#tb-'+param).DataTable().destroy()
                fetch_customergroup()
            }
        },'json')
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
                  toastr.success('SUKSES INPUT DATA! [nama] : '+res.nama)
                  $('#f-'+param)[0].reset()
                  $('#tb-'+param).DataTable().destroy()
                  fetch_customergroup()
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
                    $('#tb-'+param).DataTable().destroy()
                    fetch_customergroup()
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          }
        } else {
            toastr.warning(fail_log)
        }
    })
})
</script>
