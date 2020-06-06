<?php
if (!defined('WEB_ROOT')) {
    exit;
}
//session_unset();
if (isset($_GET['nomorinv']) && $_GET['nomorinv'] !='') {
    $_SESSION['penjualan'] = $_GET['nomorinv'];
    $inv = $_SESSION['penjualan'];
} else if (isset($_SESSION['penjualan'])) {
    $inv = $_SESSION['penjualan'];
} else {
    $inv = '';
}
$jual = RpuPenjualan::get_inv($inv);
$token_inv = ($inv == '') ? 'falsy' : $jual['id'];
$tglbuatinv = ($jual['tanggal_pembuatan']=='') ? $tglnow : dmy($jual['tanggal_pembuatan']);
$username = ($jual['nstaff']=='') ? $session_us : $jual['nstaff'];
$userid = ($jual['id_pembuat']=='') ? $session_id : $jual['id_pembuat'];
$tgl_order =($jual['tanggal_order']=='') ? '' : dmy($jual['tanggal_order']);
$tgljtinv =($jual['tanggal_jatuh_tempo']=='') ? '' : dmy($jual['tanggal_jatuh_tempo']);
$disk_inv = ($jual['diskon']==0) ? '' : $jual['diskon'];
$kur_inv = ($jual['pengurangan']==0) ? '' : money_simple($jual['pengurangan']);
$ongkir_inv = ($jual['ongkir']==0) ? '' : money_simple($jual['ongkir']);
$diskon_rp = ($jual['diskon_rp']==0) ? '' : money_simple($jual['diskon_rp']);
?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <form id="f-inv" name="f-inv" class="form-horizontal" autocomplete="off">
            <div class="card-header bg-navy">
              <div class="row">
                <div class="col-3">
                  <div class="input-group">
                    <input type="text" class="form-control" name="cari-inv" id="cari-inv" data-inputmask-inputformat="INV-YYYYMMDD-UUUU" placeholder="INV-YYYYMMDD-UUUU" data-mask>
                    <div class="input-group-append">
                      <button type="btn" id="btn-cari-inv" class="btn btn-info"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                  <ul id="list-inv" class="list-group"></ul>
                </div>
                <div class="col-3">
                  <p>&nbsp;</p>
                </div>
                <div class="col-6 text-right">
                  <button id="tambah-inv" type="btn" class="btn btn-primary btn-tambah">
                    <i class="fas fa-plus mr-2"></i>INVOICE BARU
                  </button>
                  <button id="btn-batal-inv" type="btn" class="btn btn-danger">
                    <i class="fas fa-times mr-2"></i>BATAL
                  </button>
                  <button id="btn-edit-inv" type="btn" class="btn btn-warning">
                    <i class="fas fa-pencil-alt mr-2"></i>EDIT
                  </button>
                </div>
              </div>
            </div>
            <!--end card header-->
            <div class="card-body bg-light">
              <input type="hidden" class="form-control" name="token-inv" id="token-inv" value="<?php echo $token_inv;?>" />
              <input type="hidden" class="form-control form-inv" name="nomor-inv" id="nomor-inv" value="<?php echo $inv;?>" />
              <input type="hidden" class="form-control form-inv" name="user-login-id" id="user-login-id" value="<?php echo $userid;?>" />
              <input type="hidden" class="form-control form-inv" name="no-urut" id="no-urut" />
              <input type="hidden" value="<?php echo $tglbuatinv;?>" class="form-control form-inv" name="tgl-buat-inv" id="tgl-buat-inv" />
              <div class="row">
                <div class="col-sm-12">
                  <h5>INVOICE Nomor <i class="fas fa-hashtag mr-2"></i><span id="text-nomorinv" class="text-danger">
                      <?php echo $inv;?></span>
                    <small class="float-right"> , Oleh : <span>
                        <?php echo $username;?></span></small>
                    <small class="float-right">Tanggal Invoice :
                      <?php echo $tglbuatinv;?></small>
                  </h5>
                </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-sm-3">
                  <label for="outlet-nama" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Outlet</label>
                  <input type="text" class="form-control form-inv input-cari" name="nama-outlet" id="nama-outlet" value="<?php echo $jual['noutlet'];?>" required />
                  <input type="hidden" class="form-control form-inv" name="outlet-id" id="outlet-id" value="<?php echo $jual['id_outlet'];?>" />
                  <ul id="list-outlet" class="list-group"></ul>
                </div>
                <div class="col-sm-3">
                  <label for="staff-nama" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Sales</label>
                  <input type="text" class="form-control form-inv input-cari" name="nama-staff" id="nama-staff" value="<?php echo $jual['nsales'];?>" required />
                  <input type="hidden" class="form-control form-inv" name="staff-id" id="staff-id" value="<?php echo $jual['id_sales'];?>" />
                  <ul id="list-staff" class="list-group"></ul>
                </div>
                <div class="col-sm-2">
                  <label for="tgl-terima-inv" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Tgl.Order</label>
                  <input id="tgl-terima-inv" name="tgl-terima-inv" type="text" class="form-control form-inv tanggal-inv" data-zdp_readonly_element="false" value="<?php echo $tgl_order;?>">
                </div>
                <div class="col-sm-2">
                  <label for="cara_bayar" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Status Bayar</label>
                  <input list="cara-bayar" name="cara-bayar" class="form-control form-inv" value="<?php echo $jual['cara_bayar'];?>" required>
                  <datalist id="cara-bayar">
                    <option value="LUNAS" />
                    <option value="TEMPO" />
                  </datalist>
                </div>
                <div class="col-sm-2">
                  <label for="tgl-jt-inv" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Jatuh Tempo</label>
                  <input type="text" id="tgl-jt-inv" name="tgl-jt-inv" class="form-control form-inv tanggal-inv" data-zdp_readonly_element="false" value="<?php echo $tgljtinv;?>" required />
                </div>
              </div>
              <!--end row -->
              <br />
              <div class="row">
                <div class="col-sm-12">
                  <p class="lead">
                    <i class="fas fa-th text-secondary mr-2"></i>Detail Penjualan
                    <span class="float-right">
                      <button type="button" class="btn btn-info btn-sm" id="tambah-detail-inv" disabled>
                        <i class="fas fa-plus mr-2"></i>Tambah Item
                      </button>
                    </span>
                  </p>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped table-head-fixed text-nowrap p-1" id="tb-detail-inv">
                      <thead class="bg-lightblue">
                        <td style="width:3%;">&nbsp;</td>
                        <td style="width:10%;">
                          <div class="text-center">Kode</div>
                        </td>
                        <td style="width:22%;">
                          <div class="text-center">Produk</div>
                        </td>
                        <td style="width:4%;">
                          <div class="text-center">Stok</div>
                        </td>
                        <td style="width:12%;">
                          <div class="text-center">@Harga</div>
                        </td>
                        <td style="width:5%;">
                          <div class="text-center">Qty <small>(pcs)</small></div>
                        </td>
                        <td>
                          <div class="text-center">@Avg.Berat <small>(kg)</small></div>
                        </td>
                        <td>
                          <div class="text-center">Subtotal Berat <small>(kg)</small></div>
                        </td>
                        <td style="width:6%;">
                          <div class="text-center">Sat</div>
                        </td>
                        <td style="width:15%;">
                          <div class="text-center">Subtotal <small>(Rp)</small></div>
                        </td>
                      </thead>
                      <tbody>
                        <?php
                    $prod = RpuPenjualan::get_detail_inv($inv);
                    for($jj=0;$jj<count($prod);$jj++){?>
                        <tr class="det-tr-row-produk" id="row-<?php echo $jj;?>">
                          <td>
                            <button disabled type="button" class="btn btn-danger btn-sm btn-flat m-0 btn-hapus-row" id="row-<?php echo $jj;?>">
                              <i class="fas fa-times"></i>
                            </button>
                          </td>
                          <td>
                            <input value="<?php echo $prod[$jj]['kode_produk'];?>" type="text" class="form-control-plaintext p-0 m-0" name="det-td-kode-produk" id="td-kode-produk-<?php echo $jj;?>" placeholder="R" readonly />
                            <input value="<?php echo $prod[$jj]['id'];?>" type="hidden" class="form-control" name="det-td-id-produk" id="td-id-produk-<?php echo $jj;?>" />
                          </td>
                          <td>
                            <input value="<?php echo $prod[$jj]['nama'];?>" type="text" class="form-control-plaintext p-0 m-0 form-inv nama-prod" name="det-td-nama-produk" id="td-nama-produk-<?php echo $jj;?>" required />
                            <ul id="list-produk-nama-<?php echo $jj;?>" class="list-group"></ul>
                          </td>
                          <td>
                            <input value="<?php echo $prod[$jj]['stok_ready'];?>" type="text" class="form-control-plaintext p-0 m-0" name="det-td-stok-produk" id="td-stokproduk-<?php echo $jj;?>" readonly />
                          </td>
                          <td>
                            <input value="<?php echo money_simple($prod[$jj]['harga_jual']);?>" type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-harga-produk" id="td-hargaproduk-<?php echo $jj;?>" />
                          </td>
                          <td>
                            <input value="<?php echo $prod[$jj]['produk_qty'];?>" type="number" min="1" class="form-control-plaintext p-0 m-0 text-center form-inv harga-qty" name="det-td-qty-pcs" id="td-qtypcs-<?php echo $jj;?>" placeholder="0" required />
                          </td>
                          <td>
                            <input value="<?php echo desimal($prod[$jj]['produk_berat']);?>" type="number" min="0" step="0.01" class="form-control-plaintext form-inv p-0 m-0 text-center harga-qty" name="det-td-qty-kg" id="td-qtykg-<?php echo $jj;?>" placeholder="0.0" />
                          </td>
                          <td>
                            <input value="<?php echo desimal($prod[$jj]['subtotal_berat']);?>" type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtot-kg" id="td-subtotkg-<?php echo $jj;?>" placeholder="R" readonly />
                          </td>
                          <td class="pl-3">
                            <?php $perj = ($prod[$jj]['per']=='') ? 'QTY' : $prod[$jj]['per'];?>
                            <input value="<?php echo $perj;?>" list="td-perkalian-<?php echo $jj;?>" id="tdl-perkalian-<?php echo $jj;?>" name="det-td-perkalian" class="form-control-plaintext p-0 m-0 form-inv perkalian text-center" />
                            <datalist id="td-perkalian-<?php echo $jj;?>">
                              <option value="QTY" />
                              <option value="KG" />
                            </datalist>
                          </td>
                          <td class="pr-3">
                            <input value="<?php echo money_simple($prod[$jj]['subtotal']);?>" type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtotal-produk" id="td-subtotalproduk-<?php echo $jj;?>" placeholder="R" readonly />
                          </td>
                        </tr>
                        <?php }?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-5">
                  <label>Keterangan</label>
                  <textarea name="keterangan-inv" id="keterangan-inv" class="form-control form-inv" rows="6" placeholder="Notes ... (max.200 char)"><?php echo trim($jual['keterangan']);?></textarea>
                </div>
                <div class="col-1">
                  <p>&nbsp;</p>
                </div>
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table table-sm text-right">
                      <tr>
                        <th style="width:50%">Total Item <small>(pcs)</small></th>
                        <td class="pr-3">
                          <input value="<?php echo $jual['total_produk'];?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-total-qtypcs-inv" id="td-total-qtypcs-inv" readonly />
                        </td>
                      </tr>
                      <tr>
                        <th>Total Berat <small>(kg)</small></th>
                        <td class="pr-3">
                          <input value="<?php echo $jual['total_berat'];?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-total-qtykg-inv" id="td-total-qtykg-inv" readonly />
                        </td>
                      </tr>
                      <tr>
                        <th>Subtotal<small>(Rp)</small></th>
                        <td class="pr-3">
                          <input value="<?php echo money_simple($jual['total_jumlah']);?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-subtotal-inv" id="td-subtotal-inv" readonly />
                        </td>
                      </tr>
                      <tr>
                        <th>Diskon <small>(%)</small></th>
                        <td>
                          <input value="<?php echo $disk_inv;?>" placeholder="0" type="number" min="0" max="100" class="extra-input-inv form-control-plaintext p-0 m-0 text-right form-inv" name="td-diskon-inv" id="td-diskon-inv" />
                        </td>
                      </tr>
                      <tr>
                        <th><small>(Rp) (-)</small></th>
                        <td class="pr-3">
                          <input value="<?php echo $kur_inv;?>" placeholder="0" type="text" class="extra-input-inv form-control-plaintext p-0 m-0 text-right form-inv format-uang" name="td-pengurangan-inv" id="td-pengurangan-inv" readonly />
                        </td>
                      </tr>
                      <tr>
                        <th>Diskon Rp <small>(Rp) (-)</small></th>
                        <td class="pr-3">
                          <input value="<?php echo $diskon_rp;?>" placeholder="0" type="text" class="extra-input-inv form-control-plaintext p-0 m-0 text-right form-inv format-uang" name="td-diskon-rp" id="td-diskon-rp" />
                        </td>
                      </tr>
                      <tr>
                        <th>Ongkos Kirim & Lain2 <small>(Rp)</small></th>
                        <td class="pr-3">
                          <input value="<?php echo $ongkir_inv;?>" placeholder="0" type="text" class="extra-input-inv form-control-plaintext p-0 m-0 text-right form-inv format-uang" name="td-ongkir-inv" id="td-ongkir-inv" />
                        </td>
                      </tr>
                      <tr>
                        <th>
                          <div class="text-danger">Total Bayar <small>(Rp)</small><br /><small class="text-muted text-default">Subtotal - Diskon - Pengurangan + Ongkir/Lain2</div></small>
                        </th>
                        <td class="pr-3">
                          <input value="<?php echo money_simple($jual['grand_total']);?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-total-inv" id="td-total-inv" readonly />
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!--end card body-->
            <div class="card-footer">
              <div class="row">
                <div class="col-sm-6">
                  <button id="btn-surat-jln" type="btn" class="btn btn-info" data-toggle="modal" data-target="#modal-surat-jalan" disabled>
                    <i class="fas fa-plus mr-2"></i>Buat Surat Jalan
                  </button>
                  <button id="btn-print-suratjln" type="btn" class="btn btn-default">
                    <i class="fas fa-print mr-2"></i>Print Surat Jalan
                  </button>
                </div>
                <div class="col-sm-6 text-right">
                  <button id="btn-simpan-inv" type="btn" class="btn btn-success btn-simpan" disabled>
                    <i class="fas fa-save mr-2"></i>SIMPAN
                  </button>
                  <button id="btn-update-inv" type="btn" class="btn btn-warning" disabled>
                    <i class="fas fa-check mr-2"></i>UPDATE
                  </button>
                  <button id="btn-print-inv" type="btn" class="btn btn-secondary">
                    <i class="fas fa-print mr-2"></i>PRINT
                  </button>
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--end container-->
  <div class="modal fade" id="modal-surat-jalan">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title">Surat Jalan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="f-surat-jalan" name="f-surat-jalan" class="form-horizontal" autocomplete="off">
            <div class="card-body">
              <p><i class="icon fas fa-exclamation-triangle mr-2 text-warning"></i>Kode Surat Jalan di Generate Otomatis oleh Sistem</p>
              <div class="form-group">
                <label for="nama-ekspedisi" class="col-form-label">Nama Ekspedisi</label>
                <input type="text" class="form-control" id="nama-ekspedisi" name="nama-ekspedisi" minlength="2" maxlength="50" required />
                <label for="tgl-kirim" class="col-form-label">Tgl.Kirim</label>
                <input type="text" class="form-control" id="tgl-kirim" name="tgl-kirim" data-zdp_readonly_element="false" required />
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer right-content-between">
          <button type="button" class="btn btn-primary" id="btn-simpan-sj">Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
$(function() {
  let param = 'penjualan'
  disable_form()

  function disable_form() {
    let d_form = $('#f-inv').find('.form-inv').each(function() {
      $(this).prop('disabled', true)
    })
  }

  function enable_form() {
    let d_form = $('#f-inv').find('.form-inv').each(function() {
      $(this).prop('disabled', false)
    })
  }

  set_date($('.tanggal-inv'))
  //$('.tanggal-po').inputmask({"placeholder":"dd-mm-yyyy"})

  $('#cari-inv').inputmask({
    "mask": "INV-99999999-9999"
  })

  $('#modal-surat-jalan').on('shown.bs.modal', function() {
    set_date($('#tgl-kirim'))
  })

  $(document).on('click', '#btn-simpan-sj', function() {
    let nomr = $('#text-nomorinv').text()
    if (nomr == '') {
      toastr.info('SILAHKAN PILIH NOMOR INVOICE!')
    } else {
      let fail = false
      let fail_log = ''
      let form = $('#f-surat-jalan')
      let name
      form.find('input').each(function() {
        if (!$(this).prop('required')) {} else {
          if (!$(this).val()) {
            fail = true
            name = $(this).attr('name')
            fail_log += '[' + name + '] HARUS DI ISI!' + '</br>'
          }
        }
      })
      if (!fail) {
        $.post(service_url + 's_tambah.php', {
          token: 'new_sj',
          nomor: nomr,
          data: form.serialize()
        }, function(data) {
          if (data.status == true) {
            toastr.success('SUKSES INPUT DATA!')
            $('#f-surat-jalan')[0].reset()
          } else {
            toastr.error('SURAT JALAN SUDAH DIBUAT! ' + data.sj)
          }
        }, 'json')
      } else {
        toastr.warning(fail_log)
      }
    }
  })

  $('#btn-surat-jln').click(function(e) {
    e.preventDefault()
  })

  $('#cari-inv').keyup(function(e) {
    e.preventDefault()
    let query = $(this).val()
    $('#list-inv').css('display', 'block')
    if (query.length >= 2) {
      $.ajax({
        url: service_url + 's_search.php',
        method: 'POST',
        data: {
          token: 'cari_inv',
          query: query
        },
        success: function(data) {
          $('#list-inv').html(data)
        }
      })
    }
    if (query.length == 0) {
      $('#list-inv').css('display', 'none')
    }
  })

  $(document).on('click', '.inv-search', function(e) {
    e.preventDefault()
    let nomor = $(this).text()
    $('#cari-inv').val(nomor)
    $('#list-inv').css('display', 'none')
  })

  $(document).on('click', '#btn-cari-inv', function(e) {
    e.preventDefault()
    let nomor = $('#cari-inv').val()
    if (nomor != '') {
      $.post(service_url + 's_penjualan.php', {
        token: 'get_inv',
        data: nomor
      }, function(data) {
        if (data.status == true) {
          window.location.href = 'index.php?action=rpu_pos&nomorinv=' + nomor
          $('#cari-inv').val('')
        } else {
          toastr.info('NOMOR : ' + nomor + ' TIDAK DITEMUKAN!')
          return false
        }
      }, 'json')
    } else {
      toastr.info('NOMOR INVOICE TIDAK BOLEH KOSONG!')
      $('#cari-inv').focus()
    }
  })

  $('.input-cari').keyup(function(e) {
    e.preventDefault()
    let id = $(this).attr('id').replace('nama-', '')
    let query = $('#nama-' + id).val()
    $('#list-' + id).css('display', 'block')
    if (query.length >= 2) {
      $.ajax({
        url: service_url + 's_search.php',
        method: 'POST',
        data: {
          token: id,
          query: query
        },
        success: function(data) {
          $('#list-' + id).html(data)
        }
      })
    }
    if (query.length == 0) {
      $('#list-' + id).css('display', 'none')
    }
  })

  // $('#nama-staff').blur(function(e){
  // e.preventDefault()
  // //let id = $(this).attr('id').replace('nama-','')
  // let query = $(this).val()
  // if (query.length>0){
  // $.post(service_url+'s_search.php',{
  // token:'cek_exist',
  // table:'staff',
  // query:query
  // },
  // function(data){
  // // $('#list-'+id).html(data)
  // if(data.status==false){
  // toastr.error('NAMA STAFF BELUM TERDAFTAR, HARAP DIDAFTAR DAHULU')
  // }
  // },'json')
  // }
  // })

  $(document).on('click', '.gsearch', function(e) {
    e.preventDefault()
    let nama = $(this).text()
    let id = $(this).attr('id').split('-')
    $('#nama-' + id[0]).val(nama)
    $('#' + id[0] + '-id').val(id[1])
    $('#list-' + id[0]).css('display', 'none')
  })

  $('#btn-batal-inv').click(function(e) {
    e.preventDefault()
    let nom = $('#text-nomorinv').text()
    if (nom != '') {
      $.post(service_url + 's_penjualan.php', {
        token: 'batal_inv',
        data: nom
      }, function(data) {
        if (data.status == true) {
          $('#f-inv')[0].reset()
          $('#text-nomorinv').text('')
          $("#tb-detail-inv tbody").empty()
          window.location.href = 'index.php?action=rpu_pos'
        }
      }, 'json')
    } else {
      toastr.info('TIDAK ADA YANG DIBATALKAN!')
      return false
    }
  })

  $('#btn-edit-inv').click(function(e) {
    e.preventDefault()
    let nomr = $('#text-nomorinv').text()
    if (nomr == '') {
      toastr.info('SILAHKAN PILIH NOMOR INVOICE!')
    } else {
      enable_form()
      enable_btn($('#btn-update-inv'))
      enable_btn($('#btn-surat-jln'))
      disable_btn($(this))
      disable_btn($('#btn-simpan-inv'))
    }
  })

  $('#tambah-inv').click(function(e) {
    e.preventDefault()
    let nomr = $('#text-nomorinv').text()
    if (nomr == '') {
      enable_form()
      enable_btn($('#tambah-detail-inv'))
      disable_btn($(this))
      $.post(service_url + 's_penjualan.php', {
        token: param
      }, function(data) {
        if (data.status == true) {
          $('#nomor-inv').val(data.nomor)
          $('#text-nomorinv').text(data.nomor)
          $('#no-urut').val(data.nourut)
          $('#nama-outlet').focus()
          //nomorp = data.nomor
        }
      }, 'json')
    } else {
      toastr.info('NOMOR INVOICE SUDAH TERISI, MOHON DIBATALKAN DAHULU!')
    }
  })

  $('#tambah-detail-inv').click(function(e) {
    e.preventDefault()
    enable_btn($('#btn-simpan-inv'))
    enable_btn($('#btn-surat-jln'))
    let jml_row = $('#tb-detail-inv tbody tr').length
    let idNum = jml_row + 1;
    let rowId = 'row-' + idNum;
    let id = $('#text-nomorinv').text()
    let html = '<tr class="det-tr-row-produk" id="' + rowId + '">'
    html += '<td>' +
      '<button type="button" class="btn btn-danger btn-sm btn-flat m-0 btn-hapus-row" id="' + rowId + '">' +
      '<i class="fas fa-times"></i></button>' +
      '</td>'
    html += '<td>' +
      '<input type="text" class="form-control-plaintext p-0 m-0" name="det-td-kode-produk" id="td-kode-produk-' + idNum + '" placeholder="R" readonly />' +
      '</td>'
    html += '<td><input type="text" class="form-control-plaintext p-0 m-0 nama-prod" name="det-td-nama-produk" id="td-nama-produk-' + idNum + '" required />' +
      '<ul id="list-produk-nama-' + idNum + '" class="list-group"></ul></td>'
    html += '<td><input type="text" class="form-control-plaintext p-0 m-0" name="det-td-stok-produk" id="td-stokproduk-' + idNum + '" readonly />' +
      '</td>'
    html += '<td><input type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-harga-produk" id="td-hargaproduk-' + idNum + '" readonly /></td>'
    html += '<td><input type="number" min="1" class="form-control-plaintext p-0 m-0 text-center harga-qty" name="det-td-qty-pcs" id="td-qtypcs-' + idNum + '" placeholder="0" required /></td>'
    html += '<td><input type="number" min="0" step="0.01" class="form-control-plaintext p-0 m-0 text-center harga-qty" name="det-td-qty-kg" id="td-qtykg-' + idNum + '" placeholder="0.0" /></td>'
    html += '<td><input type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtot-kg" id="td-subtotkg-' + idNum + '" placeholder="R" readonly /></td>'
    html += '<td class="pl-3"><input list="td-perkalian-' + idNum + '" id="tdl-perkalian-' + idNum + '" name="det-td-perkalian" class="form-control-plaintext p-0 m-0 form-inv perkalian" />' +
      '<datalist id="td-perkalian-' + idNum + '"><option value="QTY" /><option value="KG" /></datalist></td>'
    html += '<td class="pr-3"><input type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtotal-produk" id="td-subtotalproduk-' + idNum + '" placeholder="R" readonly /></td>'
    html += '</tr>'
    $('#tb-detail-inv tbody').append(html)
    $('#td-nama-produk-' + idNum).focus()
  })

  $('#btn-print-inv').click(function(e) {
    e.preventDefault()
    let nomor = $('#text-nomorinv').text()
    if (nomor != '') {
      window.open('print/print_inv.php?nomorinv=' + nomor, '_blank')
    } else {
      toastr.info('NOMOR INVOICE TIDAK BOLEH KOSONG!')
    }
  })

  $('#btn-print-suratjln').click(function(e) {
    e.preventDefault()
    let nomor = $('#text-nomorinv').text()
    if (nomor != '') {
      window.open('print/print_sj.php?nomorinv=' + nomor, '_blank')
    } else {
      toastr.info('NOMOR INVOICE TIDAK BOLEH KOSONG!')
    }
  })

  $(document).on('keyup', '.nama-prod', function() {
    let rowid = $(this).attr('id')
    let idrow = rowid.replace('td-nama-produk-', '')
    let query = $('#' + rowid).val()
    let cek = cek_produk()
    if (cek.length != 0) {
      cek = JSON.stringify(cek)
    } else {
      cek = ''
    }
    if (query.length >= 1) {
      $.ajax({
        url: service_url + 's_search.php',
        method: 'POST',
        data: {
          token: 'nama_jual',
          query: query,
          counter: idrow,
          'cek': cek
        },
        success: function(data) {
          $('#list-produk-nama-' + idrow).css('display', 'block')
          $('#list-produk-nama-' + idrow).html(data)
        }
      })
    }
    if (query.length == 0) {
      $('#list-produk-nama-' + idrow).css('display', 'none')
    }
  })

  $(document).on('click', '.tdsearch-inv', function() {
    let nama = $(this).text()
    let id = $(this).attr('id').split('-')
    let kode = id[1]
    let idrx = id[2]
    let harga = id[3]
    let stok = id[4]
    harga = (harga == '0') ? '' : formatCurrency(harga)
    $('#td-nama-produk-' + idrx).val(nama)
    $('#td-kode-produk-' + idrx).val(kode)
    $('#td-hargaproduk-' + idrx).val(harga)
    $('#td-stokproduk-' + idrx).val(stok)
    $('#list-produk-nama-' + idrx).css('display', 'none')
  })

  $(document).on('keyup', '.format-uang', function() {
    let nilai = $(this)
    format_uang(nilai)
  })

  $(document).on('change', '.perkalian', function() {
    let idm = $(this).attr('id')
    let idp = idm.split('-')
    let va = $('#tdl-perkalian-' + idp[2]).val()
    update_subtotal(va, idp[2])
    update_nilai()
  })

  $(document).on('blur', '.harga-qty', function() {
    let idm = $(this).attr('id')
    let idp = idm.split('-')
    let va = $('#td-perkalian-' + idp[2]).val()
    update_subtotal(va, idp[2])
    update_nilai()
  })

  $(document).on('blur', '.extra-input-inv', function() {
    let sbt = $('#td-subtotal-inv').val()
    sbt = formatNormal(sbt)
    sbt = ($('#td-subtotal-inv').val() == '') ? 0 : sbt
    let dis = ($('#td-diskon-inv').val() == '') ? 0 : $('#td-diskon-inv').val()
    let ong = $('#td-ongkir-inv').val()
    ong = formatNormal(ong)
    ong = ($('#td-ongkir-inv').val() == '') ? 0 : ong
    let kurang = (dis / 100) * sbt
    kurang = formatNormal(kurang)
    kurang = ($('#td-pengurangan-inv').val() == '') ? 0 : kurang
    let diskon_rp = $('#td-diskon-rp').val()
    diskon_rp = formatNormal(diskon_rp)
    diskon_rp = ($('#td-diskon-rp').val() == '') ? 0 : diskon_rp

    update_total(sbt, dis, ong, kurang, diskon_rp)
  })

  $(document).on('click', '.btn-hapus-row', function(e) {
    $(this).closest('tr').remove()
    $('#td-diskon-inv').val('')
    $('#td-pengurangan-inv').val('')
    $('#td-ongkir-inv').val('')
    $('#td-total-inv').val('')
    update_nilai()
  })

  $(document).on('click', '#btn-simpan-inv', function(e) {
    e.preventDefault()
    simpan_update('simpan-inv')
  })

  $(document).on('click', '#btn-update-inv', function(e) {
    e.preventDefault()
    simpan_update('update-inv')
  })

  function update_subtotal(arg, num) {
    let harga_qty = 0
    let berat_qty = 0
    let harga_kg = 0
    let harga = $('#td-hargaproduk-' + num).val()
    let qty = $('#td-qtypcs-' + num).val()
    let avgkg = $('#td-qtykg-' + num).val()
    let stok = $('#td-stokproduk-' + num).val()

    stok = parseFloat(stok)
    qty = parseFloat(qty)
    if (qty > stok) {
      toastr.warning('QTY TIDAK BOLEH LEBIH DARI JUMLAH STOK : ' + stok)
      $('#td-qtypcs-' + num).val('')
      $('#td-qtypcs-' + num).focus()
    }
    avgkg = parseFloat(avgkg)
    harga = formatNormal(harga)

    harga_qty = parseFloat(harga) * qty
    harga_qty = formatCurrency(harga_qty)

    berat_qty = avgkg * qty
    berat_qty = berat_qty.toFixed(2).toString()
    berat_qty = (isNaN(berat_qty)) ? '0' : berat_qty

    harga_kg = parseFloat(harga) * parseFloat(berat_qty)
    harga_kg = formatCurrency(harga_kg)
    $('#td-subtotkg-' + num).val(berat_qty)

    if (arg == 'QTY') {
      $('#td-subtotalproduk-' + num).val(harga_qty)
    } else if (arg == 'KG') {
      $('#td-subtotalproduk-' + num).val(harga_kg)
    } else {
      $('#td-subtotalproduk-' + num).val('')
    }
  }


  function update_nilai() {
    let qtyitem = 0
    let subberat = 0
    let subtotal = 0
    let subt = 0
    let diskon = 0
    let ongkir = 0
    let total = 0
    let kurang = 0
    let diskon_rp = 0
    $('#tb-detail-inv > tbody > tr').each(function() {
      let qty = $(this).find('input[name="det-td-qty-pcs"]').val()
      let sub = $(this).find('input[name="det-td-subtotal-produk"]').val()
      sub = formatNormal(sub)
      let berat = $(this).find('input[name="det-td-subtot-kg"]').val()
      if (qty != '') {
        qty = parseFloat(qty)
        qtyitem += qty
      } else {
        return false
      }
      if (sub != '') {
        sub = parseInt(sub)
        subtotal += sub
      } else {
        return false
      }
      if (berat != '') {
        berat = parseFloat(berat)
        subberat += berat
      } else {
        return false
      }
    })
    let castberat = subberat.toFixed(2).toString()
    $('#td-total-qtypcs-inv').val(qtyitem)
    $('#td-total-qtykg-inv').val(castberat)
    subtotal = formatCurrency(subtotal)
    $('#td-subtotal-inv').val(subtotal)
    let sbbt = formatNormal(subtotal)
    diskon = ($('#td-diskon-inv').val() == '') ? 0 : $('#td-diskon-inv').val()
    ongkir = ($('#td-ongkir-inv').val() == '') ? 0 : formatNormal($('#td-ongkir-inv').val())
    kurang = ($('#td-pengurangan-inv').val() == '') ? 0 : formatNormal($('#td-pengurangan-inv').val())
    diskon_rp = ($('#td-diskon-rp').val() == '') ? 0 : formatNormal($('#td-diskon-rp').val())
    update_total(sbbt, diskon, ongkir, kurang, diskon_rp)
  }

  function update_total(subt, diskon, ongkir, kurang, diskon_rp) {
    subt = parseFloat(subt)
    diskon = parseFloat(diskon)
    ongkir = parseFloat(ongkir)
    kurang = parseFloat(kurang)
    diskon_rp = parseFloat(diskon_rp)
    diskon = subt * (diskon / 100)
    total = (subt - diskon - diskon_rp) + ongkir
    total = formatCurrency(total)

    $('#td-pengurangan-inv').val(formatCurrency(diskon))
    $('#td-total-inv').val(total)
  }

  function simpan_update_inv(form, url, token, items) {
    items = typeof items !== 'undefined' ? JSON.stringify(items) : ''
    let fail = false
    let fail_log = ''
    let name
    let msg = (token == 'new_inv') ? 'SUKSES SIMPAN DATA!' : 'SUKSES UPDATE DATA!'

    form.find('input').each(function() {
      if (!$(this).prop('required')) {} else {
        if (!$(this).val()) {
          fail = true
          name = $(this).attr('name')
          fail_log += '[' + name + '] HARUS DI ISI!' + '</br>'
        }
      }
    })
    if (!fail) {
      $.post(service_url + url, {
        token: token,
        data: form.serialize(),
        'items': items
      }, function(data) {
        if (data.status == true) {
          toastr.success(msg)
          window.setTimeout(function() {
            window.location.href = 'index.php?action=rpu_pos'
          }, 1500)
        } else {
          toastr.error('ERROR INPUT DATA!')
        }
      }, 'json')
    } else {
      toastr.warning(fail_log)
    }
  }

  function simpan_update(el) {
    let thisel = $('#btn-' + el)
    let items
    let form = $('#f-inv')
    let url = 's_penjualan.php'
    let token = 'new_inv'
    let jr = $('#tb-detail-inv tbody tr').length
    let nomorp = $('#text-nomorinv').text()

    if (el == 'simpan-inv') {
      token = 'new_inv'
    } else if (el == 'update-inv') {
      token = 'update_inv'
    } else {
      return false
    }

    if (nomorp == '') {
      toastr.info('NOMOR INVOICE TIDAK BOLEH KOSONG!')
      return false
    } else if (jr == 0) {
      toastr.info('HARAP MASUKKAN DETAIL ITEMS!')
      return false
    } else {
      items = detail_items()
      simpan_update_inv(form, url, token, items)
    }
  }

  function cek_produk() {
    let obj2 = []
    $('#tb-detail-inv > tbody > tr').each(function() {
      let id_item = $(this).find('input[name="det-td-kode-produk"]').val()
      if (id_item != '') {
        obj2.push(id_item)
      }
    })
    return obj2
  }

  function detail_items() {
    let obj = []
    $('#tb-detail-inv > tbody > tr').each(function() {
      let data = {}
      let idr = $(this).attr('id')
      let id_prod = $(this).find('input[name="det-td-id-produk"]').val()
      let id_item = $(this).find('input[name="det-td-kode-produk"]').val()
      let harga_item = $(this).find('input[name="det-td-harga-produk"]').val()
      let qty_item = $(this).find('input[name="det-td-qty-pcs"]').val()
      let berat_item = $(this).find('input[name="det-td-qty-kg"]').val()
      let subtotal_berat = $(this).find('input[name="det-td-subtot-kg"]').val()
      let subtotal_item = $(this).find('input[name="det-td-subtotal-produk"]').val()
      let per_q = $(this).find('input[name="det-td-perkalian"]').val()

      data.id = id_prod
      data.kode = id_item
      data.harga = harga_item
      data.qty = qty_item
      data.berat = berat_item
      data.subberat = subtotal_berat
      data.per = per_q
      data.subtotal = subtotal_item
      obj.push(data)
    })
    return obj
  }

})
// END JQUERY //
</script>