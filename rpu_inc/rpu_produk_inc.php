<?php
if (!defined('WEB_ROOT')) {
    exit;
}
$kg=RpuKatalog::getAllKategoriProduk();
?>
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
  </div>
  </div>
<div class="card-body">
<div class="row">
<div class="col-md-12">
<div class="table-responsive">
  <table id="tb-produk" class="table table-sm table-striped text-sm">
  <thead class="text-center">
  <tr>
    <th>Kode</th>
    <th>Nama Beli</th>
    <th>Nama Jual</th>
    <th width="9%">Harga Beli</th>
    <th width="9%">HPP</th>
    <th width="9%">Harga Jual</th>
    <th>Stok Ready</th>
    <th>Terjual</th>
    <th>Lainnya</th>
    <th>Total</th>
    <th>Kategori</th>
    <th width="5%">Status</th>
    <th width="4%" class="text-center">&nbsp;</th>
  </tr>
  </thead>
  </table>
</div>
</div>
</div>
</div>
<div class="modal fade" id="modal-produk">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header bg-info">
  <h4 class="modal-title">Produk</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <form class="form-horizontal" id="f-produk" name="f-produk" autocomplete="off">
      <div class="card-body">
        <div class="form-group row">
          <label for="kode" class="col-sm-2 col-form-label">Kode</label>
          <div class="col-sm-8">
            <input type="hidden" class="form-control" id="id-produk" name="id-produk">
            <input type="text" class="form-control" id="kode" name="kode" readonly />
          </div>
        </div>
        <div class="form-group row">
          <label for="nama" class="col-sm-2 col-form-label">Nama</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="nama" name="nama" readonly />
          </div>
        </div>
        <div class="form-group row">
          <label for="nama-jual" class="col-sm-2 col-form-label">Nama Jual*</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="nama-jual" name="nama-jual" minlength="2" maxlength="50" required />
          </div>
        </div>
        <div class="form-group row">
          <label for="harga-beli" class="col-sm-2 col-form-label">Harga Beli</label>
          <div class="input-group col-sm-5">
            <div class="input-group-prepend">
              <span class="input-group-text">Rp</span>
            </div>
            <input type="text" class="form-control text-right" name="harga-beli" id="harga-beli" readonly />
          </div>
        </div>
        <div class="form-group row">
          <label for="hpp" class="col-sm-2 col-form-label">HPP</label>
          <div class="input-group col-sm-5">
            <div class="input-group-prepend">
              <span class="input-group-text">Rp</span>
            </div>
            <input type="text" class="form-control text-right harga-produk" name="hpp" id="hpp" placeholder="0" required />
          </div>
          <div class="col-sm-5"><small class="text-muted">*jika kosong,akan diisi sesuai dg harga beli</small></div>
        </div>
        <div class="form-group row">
          <label for="harga-jual" class="col-sm-2 col-form-label">Harga Jual*</label>
          <div class="input-group col-sm-5">
            <div class="input-group-prepend">
              <span class="input-group-text">Rp</span>
            </div>
            <input type="text" class="form-control text-right harga-produk" name="harga-jual" id="harga-jual" placeholder="0" required />
          </div>
        </div>
        <div class="form-group row">
          <label for="stok-ready" class="col-sm-2 col-form-label">Stok Ready</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" name="stok-ready" id="stok-ready" readonly />
          </div>
          <div class="input-group col-sm-3">
            <div class="input-group-prepend">
              <span class="input-group-text">- Terjual</span>
            </div>
            <input type="text" class="form-control" name="stok-terjual" id="stok-terjual" readonly />
          </div>
          <div class="input-group col-sm-3">
            <div class="input-group-prepend">
              <span class="input-group-text">- Lainnya</span>
            </div>
            <input type="text" class="form-control" name="stok-lainnya" id="stok-lainnya" readonly />
          </div>
        </div>
        <div class="form-group row">
          <label for="stok-lain-tambah" class="col-sm-2 col-form-label">+ Lainnya</label>
          <div class="input-group col-sm-3">
          <div class="input-group-prepend">
              <span class="input-group-text">Qty</span>
          </div>
          <input type="number" min="0" class="form-control" name="stok-lain-tambah" id="stok-lain-tambah" placeholder="0" />
          </div>
          <small class="text-muted">*Nilai maksimal < (Stok Ready - Lainnya)</small>
        </div>
        <div class="form-group row">
          <label for="kategoriproduk-id" class="col-sm-2 col-form-label">Kategori</label>
          <div class="col-sm-5">
            <select class="form-control" name="kategoriproduk-id" id="kategoriproduk-id">
              <?php
              for ($z = 0; $z < count($kg); $z++) {
                $idk = $kg[$z]['id'];
                ?>
                <option value="<?php echo $idk;?>"><?php echo $kg[$z]['nama'];?></option>
              <?php } ?>
            </select>
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
<button type="button" class="btn btn-success btn-form" id="btn-produk-update">Update</button>
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
  let param = 'produk'
  fetch_produk()
  function fetch_produk(arg='99'){
      let datatable = $('#tb-'+param).DataTable({
          'autoWidth': false,
          'processing': true,
          'serverside': true,
          'ajax':{
              url:service_url+'s_katalog.php',
              type:'POST',
              data:{token:param,
                args:arg},
          }
      })
  }

  $('#filter-status').change(function(e){
    let vale = $(this).val()
    $('#tb-'+param).DataTable().destroy()
    fetch_produk(vale)
  })

  $(document).on('click','.edit-'+param,function(e){
    e.preventDefault()
    let idx = $(this).attr('id').replace('edit-','')
    let ids = parseInt(idx)
    let tersedia = 0
    let hpp = 0
    $('#id-produk').val(ids)
    $('#btn-'+param+'-update').removeAttr('disabled')
    $('#btn-'+param+'-simpan').attr('disabled','disabled')
    $.post(service_url+'s_update.php',{
        token:param,
        data:ids
    },function(data){
        if(data.status==true){
            $('#f-'+param).find('input,select').each(function(){
                $('#kode').val(data.kode)
                $('#nama').val(data.nama)
                $('#nama-jual').val(data.nama_jual)
                $('#harga-beli').val(data.harga_beli)
                hpp = (data.hpp==0)?data.harga_beli:data.hpp
                $('#hpp').val(hpp)
                $('#harga-jual').val(data.harga_jual)
                $('#stok-ready').val(data.stok_ready)
                $('#stok-terjual').val(data.stok_terjual)
                $('#stok-lainnya').val(data.stok_lainnya)
                tersedia = data.stok_ready-data.stok_lainnya
                $('#stok-lain-tambah').attr("max",tersedia)
                $('#kategoriproduk-id').val(data.kategoriproduk_id)
                $('#status').val(data.sts)
            })
        }
    },'json')
  })

  // $(document).on('blur','#stok-lain-tambah',function(){
  //   let ready = parseInt($('#stok-ready').val())
  //   let terjual = parseInt($('#stok-terjual').val())
  //   let lainnya = parseInt($('#stok-lainnya').val())
  //   let tambah = ($(this).val()=='') ? 0 : parseInt($(this).val())
  //   tambah += lainnya
  //   ready = ready - lainnya - tambah
  //   if(lainnya>ready || lainnya==''||isNaN(lainnya)){
  //     toastr.warning('Nilai harus lebih besar dari sblmnya & lebih kecil dari stok')
  //     return false
  //     // toastr.warning('QTY TIDAK BOLEH LEBIH DARI JUMLAH STOK : '+stok)
  //     //       $('#td-qtypcs-'+num).val('')
  //     //       $('#td-qtypcs-'+num).focus()
  //     // return false
  //   }else{
  //     $('#stok-ready').val(ready)
  //   }

  // })

  $(document).on('keyup','.harga-produk','',function(){
        let nilai = $(this)
        format_uang(nilai)
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
                fetch_produk()
            }
        },'json')
        }
  })

  $('.btn-form').click(function(e){
      e.preventDefault()
      let id=$(this).attr('id')
      let idx = id.replace('btn-'+param+'-','')
      let form = $('#f-'+param)
      let ready = parseInt($('#stok-ready').val())
      let terjual = parseInt($('#stok-terjual').val())
      let lainnya = parseInt($('#stok-lainnya').val())
      let tambah = parseInt($('#stok-lain-tambah').val())
      let fail = false
      let fail_log = ''
      let name
      ready = ready - (lainnya+tambah)
      form.find('input').each(function(){
         if(!$(this).prop('required')){
         }else{
            if(!$(this).val()){
              fail=true
              name=$(this).attr('name')
              fail_log += '['+ name +'] TIDAK BOLEH KOSONG!'+'</br>'
            }
            if(ready<0){
              fail=true
              fail_log = 'STOK TIDAK BOLEH MINUS!'
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
                  fetch_produk()
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
                    fetch_produk()
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
