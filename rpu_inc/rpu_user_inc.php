<?php
if (!defined('WEB_ROOT')) {
    exit;
}
if(isset($_SESSION['username']) && isset($_SESSION['id'])){
$session_us = $_SESSION['username'];
$session_id = $_SESSION['id'];
// $user = RpuUser::get_user($session_id);
// print_r($user);
}
?>
<section class="content">
<div class="container-fluid">
<div class="row">
  <div class="col-sm-12">
    <div class="card">
    <div class="card-body">
    <div class="table-responsive">
    <table id="tb-staff" class="table table-sm table-striped">
      <thead align="center">
        <tr>
          <th>Nama Depan</th>
          <th>Nama Belakang</th>
          <th>Alamat</th>
          <th>HP</th>
          <th>Bagian</th>
          <th>Akses</th>
          <th>Status</th>
          <th width="10%">&nbsp;</th>
        </tr>
      </thead>
    </table>
    </div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="card">
<form role="form" id="f-staff" name="f-staff" class="form-staff" autocomplete="off">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5"><p class="lead">Data Staff</p></div>
      <div class="col-sm-7 text-right">
        <button type="button" class="btn btn-primary btn-form-staff btn-sm" id="btn-tambah-staff">
          <i class="fas fa-plus mr-2"></i>Tambah
        </button>
        <button type="button" class="btn btn-default btn-form-staff btn-sm" id="btn-batal-staff">
          <i class="fas fa-times mr-2"></i>Batal
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <input type="hidden" class="form-control" id="id-staff" name="id-staff">
        <div class="form-group">
        <label for="nama-depan" class="col-form-label">Nama Depan</label>
        <input type="text" class="form-control form-staff" id="nama-depan" name="nama-depan" minlength="2" maxlength="20" required>
        </div>
        <div class="form-group">
        <label for="nama-belakang" class="col-form-label">Nama Belakang</label>
        <input type="text" class="form-control form-staff" id="nama-belakang" name="nama-belakang" maxlength="30">
        </div>
        <div class="form-group">
        <label for="alamat-staff" class="col-form-label">Alamat</label>
        <input type="text" class="form-control form-staff" id="alamat-staff" name="alamat-staff" maxlength="100">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
        <label for="hp-staff" class="col-form-label">HP</label>
        <input type="text" class="form-control form-staff" id="hp-staff" name="hp-staff" maxlength="20" required>
        </div>
        <div class="form-group">
        <label for="group-staff" class="col-form-label">Group</label>
        <?php $gstaff = RpuUser::get_staff_group();?>
        <select name="group-staff" id="group-staff" class="form-control form-staff">
        <?php for($g=0;$g<count($gstaff);$g++){
          $idg = $gstaff[$g]['id'];
          ?>
          <option value="<?php echo $idg;?>"><?php echo $gstaff[$g]['nama'];?></option>
        <?php } ?>
        </select>
        </div>
        <div class="form-group">
            <label for="status-staff" class="col-form-label">Status</label>
            <select class="form-control form-staff" name="status-staff" id="status-staff">
                <option value="1">Aktif</option>
                <option value="0">Non-Aktif</option>
            </select>
        </div>
<!--         <div class="form-group clearfix">
          <label for="akses-ya" class="col-form-label d-inline">Akses</label>
          <div class="icheck-primary d-inline ml-2">
            <input type="radio" name="akses" checked id="akses-tidak" class="form-control form-user-staff">
            <label for="akses-tidak">Tidak</label>
          </div>
          <div class="icheck-primary d-inline ml-2">
            <input type="radio" name="akses" id="akses-ya" class="form-control form-user-staff">
            <label for="akses-ya">Ya</label>
          </div>
        </div> -->
      </div>
    </div>
  </div>
  <div class="card-footer text-right">
    <button type="button" class="btn btn-success btn-staff-form" id="btn-staff-update" disabled>Update</button>
    <button type="button" class="btn btn-primary btn-staff-form" id="btn-staff-simpan" disabled>Simpan</button>
  </div>
</form>
</div>
</div>
<div class="col-md-6">
<div class="card">
<form role="form" id="f-user" name="f-user" autocomplete="off">
  <div class="card-body">
        <div class="row">
        <div class="col-sm-5"><p class="lead">Data User Akses</p></div>
        <div class="col-sm-7 text-right">
          <button type="button" class="btn btn-default btn-user-form btn-sm" id="btn-batal-user">
            <i class="fas fa-times mr-2"></i>Batal
          </button>
        </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
            <label for="user-name" class="col-form-label">Username</label>
            <input type="text" class="form-control form-user" id="user-name" name="user-name" minlength="2" maxlength="10">
            </div>
            <div class="form-group">
            <label for="password-user-lama" class="col-form-label d-none">Password Lama</label>
            <input type="password" class="form-control form-user d-none" id="password-user-lama" name="password-user-lama" maxlength="30">
            </div>
            <div class="form-group">
            <label for="password-user" class="col-form-label" id="password-user">Password</label>
            <input type="password" class="form-control form-user" id="password-user" name="password-user" maxlength="30">
            </div>
            <div class="form-group">
            <label for="password-user-re" class="col-form-label">Ulangi Password</label>
            <input type="password" class="form-control form-user" id="password-user-re" name="password-user-re" maxlength="30">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
            <label for="staff-user" class="col-form-label">Nama Staff</label>
            <input type="hidden" class="form-control form-user" id="id-user" name="id-user">
            <input type="text" class="form-control form-user" id="staff-user" name="staff-user" readonly />
            </div>
            <div class="form-group">
            <label for="group-user" class="col-form-label">Group</label>
            <?php $guser = RpuUser::get_user_group();?>
            <select name="group-user" id="group-user" class="form-control form-user">
            <?php for($u=0;$u<count($guser);$u++){
              $idu = $guser[$u]['id'];
              ?>
              <option value="<?php echo $idu;?>"><?php echo $guser[$u]['nama'];?></option>
            <?php } ?>
            </select>
            </div>
            <div class="form-group">
              <label for="status-user" class="col-form-label">Status</label>
              <select class="form-control form-user" name="status-user" id="status-user">
                  <option value="1">Aktif</option>
                  <option value="0">Non-Aktif</option>
              </select>
            </div>
          </div><!--col-6-->
        </div><!--row-->
    </div>
    <div class="card-footer text-right">
    <button type="button" class="btn btn-success btn-user-form" id="btn-user-update" disabled>Update</button>
    <button type="button" class="btn btn-primary btn-user-form" id="btn-user-simpan" disabled>Simpan</button>
    </div>
  </div>
</form>
</div>
</div>
</section>
<script>
$(function(){
    let p_staff = 'staff'
    let p_user = 'user'

    disable_frm_users(p_staff,'.form-'+p_staff)
    disable_frm_users(p_user,'.form-'+p_user)
    fetch_staff()

    function disable_frm_users(param,cls){
        $('#f-'+param).find(cls).each(function(){
            $(this).prop('disabled',true)
        })
    }

    function enable_frm_users(param,cls){
        $('#f-'+param).find(cls).each(function(){
            $(this).prop('disabled',false)
        })
    }

    function fetch_staff(arg='99'){
        let datatable = $('#tb-'+p_staff).DataTable({
            'autoWidth': false,
            'processing': true,
            'serverside': true,
            'sortable':true,
            'ajax':{
                url:service_url+'s_login.php',
                type:'POST',
                data:{token:p_staff,args:arg}
            }
        })
    }

    $('#btn-tambah-staff').click(function(e){
      e.preventDefault()
      enable_frm_users(p_staff,'.form-'+p_staff)
      enable_btn($('#btn-staff-simpan'))
    })

    $('#btn-batal-staff').click(function(e){
      e.preventDefault()
      $('#f-'+p_staff)[0].reset()
      disable_frm_users(p_staff,'.form-'+p_staff)
      disable_btn($('.btn-staff-form'))
      enable_btn($('#btn-tambah-staff'))
    })

    $('#btn-batal-user').click(function(e){
      e.preventDefault()
      $('#f-'+p_user)[0].reset()
      disable_frm_users(p_user,'.form-'+p_user)
      disable_btn($('.btn-user-form'))
      //enable_btn($('#btn-tambah-staff'))
    })

    // $('input[name="akses"]').click(function(){
    //   let ida=$(this).attr('id')
    //   if(ida=='akses-ya'){
    //     $(this).val('on')
    //     // enable_form_user('.form-user')
    //     // required_form_user('.form-user')
    //   } else {
    //     $(this).val('off')
    //     // disable_form_user('.form-user')
    //     // unrequired_form_user('.form-user')
    //   }
    // })

    // $('#password-user-re').blur(function(){
    //   let pwd = $('#password-user').val()
    //   let thispwd = $(this).val()
    //   if(thispwd!==pwd){
    //     toastr.error('Password tidak cucok')
    //   }
    // })

    $('.btn-staff-form').click(function(e){
      e.preventDefault()
      let id=$(this).attr('id')
      let idx = id.replace('btn-staff'+'-','')
      let form = $('#f-'+p_staff)
      let fail = false
      let fail_log = ''
      let name
      console.log(idx[1])
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
              token:'new_'+p_staff,
              data:form.serialize()
            },function(data){
                if (data.status==true){
                  toastr.success('SUKSES INPUT DATA! : ['+data.nama+']')
                  $('#f-'+p_staff)[0].reset()
                  window.setTimeout(function(){
                    window.location.href = 'index.php?action=rpu_user'
                  },1500)
                  // $('#tb-'+p_staff).DataTable().destroy()
                  // fetch_staff()
                  // disable_frm_users(p_staff,'.form-'+p_staff)
                  // disable_btn($('.btn-staff-form'))
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          } else if(idx=='update'){
            $.post(service_url+'s_update.php',{
              token:'update_'+p_staff,
              data:form.serialize()
            },function(data){
                if (data.status==true){
                    toastr.success('SUKSES UPDATE DATA!')
                    $('#f-'+p_staff)[0].reset()
                    window.setTimeout(function(){
                      window.location.href = 'index.php?action=rpu_user'
                    },1500)

                    // $('#tb-'+p_staff).DataTable().destroy()
                    // fetch_staff()
                    // disable_form_user('.form-user-staff')
                    // disable_form_user('.form-user')
                    // disable_btn($('.btn-user-form'))
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
          }
        } else {
            toastr.warning(fail_log)
        }
    })

  $(document).on('click','.tambah-akses-'+p_staff,function(e){
    e.preventDefault()
    let idx = $(this).attr('id').split('-')
    let ids = parseInt(idx[1])
    enable_frm_users(p_user,'.form-'+p_user)
    enable_btn($('#btn-user-simpan'))
    $.post(service_url+'s_tambah.php',{
            token:'akses_'+p_staff,
            data:ids
            },function(data){
              if(data.status==true){
                $('#id-user').val(data.staff_id)
                $('#staff-user').val(data.staff_nama)

              }
            },'json')
  })

  $(document).on('click','.edit-'+p_staff,function(e){
      e.preventDefault()
      let idx = $(this).attr('id').split('-')
      let id_staff = parseInt(idx[1])
      $.post(service_url+'s_update.php',{
          token:p_staff,
          data:id_staff,
      },function(data){
          if(data.status==true){
            enable_frm_users(p_staff,'.form-'+p_staff)
            disable_btn($('#btn-tambah-staff'))
            enable_btn($('#btn-staff-update'))
            $('#id-staff').val(data.staff.id)
            $('#nama-depan').val(data.staff.nama)
            $('#nama-belakang').val(data.staff.belakang)
            $('#alamat-staff').val(data.staff.alamat)
            $('#hp-staff').val(data.staff.hp)
            $('#group-staff').val(data.staff.id_staff_group)
            $('#status-staff').val(data.staff.status)

              // $('#f-'+param).find('input,select').each(function(){
              //     $('#id-staff').val(data.staff.id_staff)

              //     $('#staff-cred').val(data.staff.id_staff)
              //     if(data.staff.username==null){
              //       $('#akses-ya').prop('checked',false)
              //     } else {
              //       $('#akses-ya').prop('checked',true)
              //       $('#staff-cred').val(data.staff.id_staff)
              //       $('#id-user').val(data.staff.id_user)
              //       $('#user-name').val(data.staff.username)
              //       $('#user-name').prop('readonly',true)
              //       $('#password-user').val('data.staff.username')
              //       $('#password-user').prop('readonly',true)
              //       $('#password-user-re').val('data.staff.username')
              //       $('#password-user-re').prop('readonly',true)
              //       $('#group-user').val(data.staff.id_group_user)
              //       $('#status-user').val(data.staff.status_user)
              //     }
              // })
          }
      },'json')
  })

  // $(document).on('click','.del-'+param,function(e){
  //   e.preventDefault()
  //   let idx = $(this).attr('id').replace('del-','')
  //   let ids = parseInt(idx)
  //   console.log(ids)
  //   if (confirm("Hapus data ini?")){
  //       $.post(service_url+'s_delete.php',{
  //           token:param,
  //           data:ids
  //       },function(data){
  //           if(data.status==true){
  //               toastr.success('SUKSES HAPUS DATA!')
  //               $('#tb-'+param).DataTable().destroy()
  //               fetch_user()
  //           }
  //       },'json')
  //       }
  // })



    // function required_form_user(cls){
    //     let d_form = $('#f-user-staff').find(cls).each(function(){
    //         $(this).prop('required',true)
    //     })
    // }
    // function unrequired_form_user(cls){
    //     let d_form = $('#f-user-staff').find(cls).each(function(){
    //         $(this).prop('required',false)
    //     })
    // }
})
</script>
