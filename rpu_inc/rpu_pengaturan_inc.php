<?php
if (!defined('WEB_ROOT')) {
    exit;
}
$set = RpuKatalog::get_settings();
?>
<section class="content">
<div class="container-fluid">
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="card-tools">
            <button type="btn" class="btn btn-success btn-form" id="btn-update-settings">
                <i class="fas fa-check mr-2"></i> UPDATE
            </button>
        </div>
    </div>
    <div class="card-body">
        <form class="form-horizontal" id="f-settings" name="f-settings" autocomplete="off">
        <div class="row">
        <div class="col-md-6">
        <p class="lead"><i class="fas fa-tag mr-3"></i>Identitas Usaha</p>
        <div class="form-group row">
          <label for="nama-usaha" class="col-sm-3 col-form-label">Nama Usaha</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['id'];?>" type="hidden" class="form-control" id="id-usaha" name="id-usaha">
            <input value="<?php echo $set['nama_usaha'];?>" type="text" class="form-control" id="nama-usaha" name="nama-usaha" minlength="2" maxlength="50" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="alamat-usaha" class="col-sm-3 col-form-label">Alamat</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['alamat_usaha'];?>" type="text" class="form-control" id="alamat-usaha" name="alamat-usaha" minlength="2" maxlength="150" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="tlp-usaha" class="col-sm-3 col-form-label">Telepon</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['tlp_usaha'];?>" type="text" class="form-control" id="tlp-usaha" name="tlp-usaha" minlength="2" maxlength="20" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="email-usaha" class="col-sm-3 col-form-label">Email</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['email'];?>" type="email" class="form-control" id="email-usaha" name="email-usaha" minlength="2" maxlength="50">
          </div>
        </div>
        <div class="form-group row">
          <label for="bank-usaha" class="col-sm-3 col-form-label">Bank</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['bank'];?>" type="text" class="form-control" id="bank-usaha" name="bank-usaha" minlength="2" maxlength="20" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="no-rek" class="col-sm-3 col-form-label">No. Rek</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['no_rek'];?>" type="text" class="form-control" id="no-rek" name="no-rek" minlength="2" maxlength="20" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="atas-nama" class="col-sm-3 col-form-label">Atas Nama</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['an_rek'];?>" type="text" class="form-control" id="atas-nama" name="atas-nama" minlength="2" maxlength="50" required>
          </div>
        </div>
        </div>
        <div class="col-md-6">
        <p class="lead"><i class="fas fa-tag mr-3"></i>Identitas Pemilik</p>
        <div class="form-group row">
          <label for="nama-pemilik" class="col-sm-3 col-form-label">Nama</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['nama_pemilik'];?>" type="text" class="form-control" id="nama-pemilik" name="nama-pemilik" minlength="2" maxlength="50" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="alamat-pemilik" class="col-sm-3 col-form-label">Alamat</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['alamat_pemilik'];?>" type="text" class="form-control" id="alamat-pemilik" name="alamat-pemilik" minlength="2" maxlength="150">
          </div>
        </div>
         <div class="form-group row">
          <label for="hp-pemilik" class="col-sm-3 col-form-label">HP</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['hp'];?>" type="text" class="form-control" id="hp-pemilik" name="hp-pemilik" minlength="2" maxlength="20" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
          <div class="col-sm-8">
            <input value="<?php echo $set['npwp'];?>" type="text" class="form-control" id="npwp" name="npwp" minlength="2" maxlength="40">
          </div>
        </div>
        </div>
        </div>
        </form>
    </div>

</div>
</div>
</section>
<script>
$(function () {
    let param = 'settings'
    $('#btn-update-settings').click(function(e){
      e.preventDefault()
      let id = $('#id-usaha').val()
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
            $.post(service_url+'s_update.php',{
              token:'update_'+param,
              data:form.serialize()
            },function(res){
                if (res.status==true){
                    toastr.success('SUKSES UPDATE DATA!')
                 } else{
                    toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
        } else {
            toastr.warning(fail_log)
        }
    })

})
</script>
