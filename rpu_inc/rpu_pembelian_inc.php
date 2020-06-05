<?php
if (!defined('WEB_ROOT')) {
    exit;
}
//session_unset();
if (isset($_GET['nomorpo']) && $_GET['nomorpo'] !='') {
    $_SESSION['pembelian'] = $_GET['nomorpo'];
    $po = $_SESSION['pembelian'];
} else if (isset($_SESSION['pembelian'])) {
    $po = $_SESSION['pembelian'];
} else {
    $po = '';
}
$beli = RpuPembelian::get_po($po);
$token_po = ($po == '') ? 'falsy' : $beli['id'];
$tgl_buat = ($beli['tanggal_pembuatan']=='') ? $tglnow : dmy($beli['tanggal_pembuatan']);
$username = ($beli['staff_buat']=='') ? $session_us : $beli['staff_buat'];
$userid = ($beli['id_pembuat']=='') ? $session_id : $beli['id_pembuat'];
$tgl_terima =($beli['tanggal_terima']=='') ? '' : dmy($beli['tanggal_terima']);
$tgl_jt =($beli['tanggal_jatuh_tempo']=='') ? '' : dmy($beli['tanggal_jatuh_tempo']);
$diskon = ($beli['diskon']==0) ? '' : $beli['diskon'];
$pengurangan = ($beli['pengurangan']==0) ? '' : money_simple($beli['pengurangan']);
$ongkir = ($beli['ongkir']==0) ? '' : money_simple($beli['ongkir']);
?>
<section class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="card card-secondary card-outline">
<form id="f-po" name="f-po" class="form-horizontal f-form" autocomplete="off">
            <div class="card-header">
                <div class="row">
                    <div class="col-3">
                    <div class="input-group">
                    <input type="text" class="form-control" name="cari-po" id="cari-po">
                    <div class="input-group-append">
                    <button type="btn" id="btn-cari-po" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                    </div>
                    <ul id="list-po" class="list-group"></ul>
                    </div>
                    <div class="col-3">
                        <p>&nbsp;</p>
                    </div>
                    <div class="col-6 text-right">
                        <button id="tambah-po" type="btn" class="btn btn-primary btn-tambah">
                        <i class="fas fa-plus mr-2"></i>PO BARU
                        </button>
                        <button id="btn-batal-po" type="btn" class="btn btn-danger">
                        <i class="fas fa-times mr-2"></i>BATAL
                        </button>
                        <button id="btn-edit-po" type="btn" class="btn btn-warning">
                        <i class="fas fa-pencil-alt mr-2"></i>EDIT
                        </button>
                    </div>
                </div>
            </div> <!--end card header-->
            <div class="card-body">
                <input type="hidden" class="form-control" name="token-po" id="token-po" value="<?php echo $token_po;?>" />
                <input type="hidden" class="form-control form-po" name="nomor-po" id="nomor-po" value="<?php echo $po;?>" />
                <input type="hidden" class="form-control form-po" name="user-login-id" id="user-login-id" value="<?php echo $userid;?>"/>
                <input type="hidden" class="form-control form-po" name="no-urut" id="no-urut" />
                <input type="hidden" value="<?php echo $tgl_buat;?>" class="form-control form-po" name="tgl-buat-po" id="tgl-buat-po"/>
                <div class="row">
                    <div class="col-sm-12">
                        <h5>Nomor <i class="fas fa-hashtag mr-2"></i><span id="text-nomorpo" class="text-danger"><?php echo $po;?></span>
                        <small class="float-right"> , Oleh : <span><?php echo $username;?></span></small>
                        <small class="float-right">Tanggal Buat : <?php echo $tgl_buat;?></small>
                        </h5>
                    </div>
                </div>
            <hr />
            <div class="row">
                <div class="col-sm-3">
                    <label for="supplier-nama" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Supplier</label>
                    <input type="text" class="form-control form-po input-cari" name="nama-supplier" id="nama-supplier" value="<?php echo $beli['nsupplier'];?>" required />
                    <input type="hidden" class="form-control form-po" name="supplier-id" id="supplier-id" value="<?php echo $beli['id_supplier'];?>"/>
                    <ul id="list-supplier" class="list-group"></ul>
                </div>
                <div class="col-sm-3">
                    <label for="staff-nama" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Penerima</label>
                    <input type="text" class="form-control form-po input-cari" name="nama-staff" id="nama-staff" value="<?php echo $beli['staff_terima'];?>" required />
                    <input type="hidden" class="form-control form-po" name="staff-id" id="staff-id" value="<?php echo $beli['id_penerima'];?>"/>
                    <ul id="list-staff" class="list-group"></ul>
                </div>
                <div class="col-sm-2">
                    <label for="tgl-terima-po" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Tgl.Terima</label>
                    <input id="tgl-terima-po" name="tgl-terima-po" type="text" class="form-control form-po tanggal-po" data-zdp_readonly_element="false" value="<?php echo $tgl_terima;?>">
                </div>
                <div class="col-sm-2">
                    <label for="cara_bayar" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Status Bayar</label>
                   <!--  <select id="cara-bayar" name="cara-bayar" class="form-control form-po">
                        <option value="">PILIH</option>
                        <option value="LUNAS">LUNAS</option>
                        <option value="TEMPO">TEMPO</option>
                    </select> -->
                    <input list="cara-bayar" name="cara-bayar" class="form-control form-po" value="<?php echo $beli['cara_bayar'];?>" required>
                    <datalist id="cara-bayar">
                        <option value="LUNAS" />
                        <option value="TEMPO" />
                    </datalist>
                </div>
                <div class="col-sm-2">
                    <label for="tgl-jt-po" class="col-form-label"><i class="fas fa-angle-right text-danger mr-2"></i>Jatuh Tempo</label>
                    <input type="text" id="tgl-jt-po" name="tgl-jt-po" class="form-control form-po tanggal-po" data-zdp_readonly_element="false" value="<?php echo $tgl_jt;?>" required />
                </div>
            </div> <!--end row -->
            <br />
            <div class="row">
                <div class="col-sm-2">
                    <p class="lead"><i class="fas fa-th text-secondary mr-2"></i>Detail Pembelian</p>
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-success" id="list-produk-po" data-toggle="modal" data-target="#modal-tbproduk-po">
                    <i class="fas fa-search mr-2"></i>List Produk
                    </button>
                    <button type="button" class="btn btn-primary" id="tambah-produk" data-toggle="modal" data-target="#modal-produk-po" disabled>
                    <i class="fas fa-plus mr-2"></i>Produk Baru
                    </button>
                </div>
                <div class="col-sm-2">
                    <p>&nbsp;</p>
                </div>
                <div class="col-sm-4 text-right">
                    <button type="button" class="btn btn-info" id="tambah-detail-po" disabled>
                    <i class="fas fa-plus mr-2"></i>Tambah Item
                    </button>
                </div>
            </div>
            <div class="row">
            <div class="col-sm-12">
            <div class="table-responsive">
            <table class="table table-sm table-striped table-head-fixed text-nowrap p-1" id="tb-detail-po">
                <thead class="bg-gray">
                    <td style="width:3%;">&nbsp;</td>
                    <td style="width:10%;"><div class="text-center">Kode</div></td>
                    <td style="width:22%;"><div class="text-center">Produk</div></td>
                    <td style="width:12%;"><div class="text-center">@Harga Beli</div></td>
                    <td style="width:5%;"><div class="text-center">Qty <small>(pcs)</small></div></td>
                    <td><div class="text-center">@Avg.Berat <small>(kg)</small></div></td>
                    <td><div class="text-center">Subtotal Berat <small>(kg)</small></div></td>
                    <td style="width:6%;"><div class="text-center">Sat</div></td>
                    <td style="width:15%;"><div class="text-center">Subtotal <small>(Rp)</small></div></td>
                </thead>
                <tbody>
                    <?php
                    $produk = RpuPembelian::get_detail_po($po);
                    for($nn=0;$nn<count($produk);$nn++){?>
                    <tr class="det-tr-row-produk" id="row-<?php echo $nn;?>">
                    <td>
                    <button disabled type="button" class="btn btn-danger btn-sm btn-flat m-0 btn-hapus-row" id="row-<?php echo $nn;?>">
                        <i class="fas fa-times"></i>
                    </button>
                    </td>
                    <td>
                        <input value="<?php echo $produk[$nn]['kode_produk'];?>" type="text" class="form-control-plaintext p-0 m-0" name="det-td-kode-produk" id="td-kode-produk-<?php echo $nn;?>" placeholder="R" readonly />
                        <input value="<?php echo $produk[$nn]['id'];?>" type="hidden" class="form-control" name="det-td-id-produk" id="td-id-produk-<?php echo $nn;?>" />
                    </td>
                    <td>
                        <input value="<?php echo $produk[$nn]['nama'];?>" type="text" class="form-control-plaintext p-0 m-0 form-po nama-prod" name="det-td-nama-produk" id="td-nama-produk-<?php echo $nn;?>" required/>
                        <ul id="list-produk-nama-<?php echo $nn;?>" class="list-group"></ul>
                    </td>
                    <td>
                        <input value="<?php echo money_simple($produk[$nn]['harga_beli']);?>" type="text" class="form-control-plaintext p-0 m-0 text-right form-po format-uang harga-qty" name="det-td-harga-produk" id="td-hargaprodukpo-<?php echo $nn;?>" placeholder="0" required/>
                    </td>
                    <td>
                        <input value="<?php echo $produk[$nn]['produk_qty'];?>" type="number" min="1" class="form-control-plaintext p-0 m-0 text-center form-po harga-qty" name="det-td-qty-pcs" id="td-qtypcs-<?php echo $nn;?>" placeholder="0" required />
                    </td>
                    <td>
                        <input value="<?php echo desimal($produk[$nn]['produk_berat']);?>" type="number" min="0" step="0.01" class="form-control-plaintext form-po p-0 m-0 text-center harga-qty" name="det-td-qty-kg" id="td-qtykg-<?php echo $nn;?>" placeholder="0.0" />
                    </td>
                    <td>
                        <input value="<?php echo desimal($produk[$nn]['subtotal_berat']);?>" type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtot-kg" id="td-subtotkg-<?php echo $nn;?>" placeholder="R" readonly />
                    </td>

                    <td class="pl-3">
                        <?php $per = ($produk[$nn]['per']=='') ? 'QTY' : $produk[$nn]['per'];?>
                        <input value="<?php echo $per;?>" list="td-perkalian-<?php echo $nn;?>" id="tdl-perkalian-<?php echo $nn;?>" name="det-td-perkalian" class="form-control-plaintext p-0 m-0 form-po perkalian text-center" />
                        <datalist id="td-perkalian-<?php echo $nn;?>">
                        <option value="QTY" />
                        <option value="KG" />
                        </datalist>
                    </td>
                    <td class="pr-3">
                        <input value="<?php echo money_simple($produk[$nn]['subtotal']);?>" type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtotal-produk" id="td-subtotalproduk-<?php echo $nn;?>" placeholder="R" readonly />
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
                    <textarea name="keterangan-po" id="keterangan-po" class="form-control form-po" rows="6" placeholder="Notes ... (max.200 char)"><?php echo trim($beli['keterangan']);?></textarea>
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
                        <input value="<?php echo $beli['total_produk'];?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-total-qty-pcs" id="td-total-qty-pcs" readonly />
                    </td>
                    </tr>
                    <tr>
                        <th>Total Berat <small>(kg)</small></th>
                        <td class="pr-3">
                        <input value="<?php echo $beli['total_berat'];?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-total-qty-kg" id="td-total-qty-kg" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th>Subtotal <small>(Rp)</small></th>
                        <td class="pr-3">
                        <input value="<?php echo money_simple($beli['total_jumlah']);?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-subtotal-po" id="td-subtotal-po" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th>Diskon <small>(%)</small></th>
                        <td>
                        <input value="<?php echo $diskon;?>" placeholder="0" type="number" min="0" max="100" class="extra-input-po form-control-plaintext p-0 m-0 text-right form-po" name="td-diskon-po" id="td-diskon-po" />
                        </td>
                    </tr>
                     <tr>
                        <th>Pengurangan <small>(Rp)</small></th>
                        <td class="pr-3">
                        <input value="<?php echo $pengurangan;?>" placeholder="0" type="text" class="extra-input-po form-control-plaintext p-0 m-0 text-right form-po format-uang" name="td-pengurangan-po" id="td-pengurangan-po" />
                        </td>
                    </tr>
                    <tr>
                        <th>Ongkos Kirim & Lain2 <small>(Rp)</small></th>
                        <td class="pr-3">
                        <input value="<?php echo $ongkir;?>" placeholder="0" type="text" class="extra-input-po form-control-plaintext p-0 m-0 text-right form-po format-uang" name="td-ongkir-po" id="td-ongkir-po" />
                        </td>
                    </tr>
                    <tr>
                        <th><div class="text-danger">Total Bayar <small>(Rp)</small><br /><small class="text-muted text-default">Subtotal - Diskon - Pengurangan + Ongkir/Lain2</div></small></th>
                        <td class="pr-3">
                        <input value="<?php echo money_simple($beli['grand_total']);?>" placeholder="R" type="text" class="form-control-plaintext p-0 m-0 text-right" name="td-total-po" id="td-total-po" readonly />
                        </td>
                    </tr>
                    </table>
                  </div>
                </div>
            </div>
            </div><!--end card body-->
            <div class="card-footer text-right">
                <button id="btn-simpan-po" type="btn" class="btn btn-success btn-simpan" disabled>
                <i class="fas fa-save mr-2"></i>SIMPAN
                </button>
                <button id="btn-update-po" type="btn" class="btn btn-warning" disabled>
                <i class="fas fa-check mr-2"></i>UPDATE
                </button>
                <button id="btn-print-po" type="btn" class="btn btn-secondary print-this">
                <i class="fas fa-print mr-2"></i>PRINT
                </button>
            </div>
    </div>
</form>
</div>
</div>
<!-- MODAL TABLE -->
<div class="modal fade" id="modal-tbproduk-po">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header bg-info">
  <h4 class="modal-title">List Produk</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
<div class="table-responsive">
    <table id="tb-produk-po" class="table table-sm text-sm table-striped">
        <thead class="text-center">
            <tr>
                <th width="10%">Kode</th>
                <th>Nama</th>
                <th width="15%" class="text-center">Harga Beli</th>
                <th width="15%">Kategori</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
    </table>
</div>
</div>
</div>
</div>
</div>
<!-- END MODAL TABLE -->
<?php $kg=RpuKatalog::getAllKategoriProduk(); ?>
<div class="modal fade" id="modal-produk-po">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header bg-info">
  <h4 class="modal-title">Produk</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <form id="f-produk-po" name="f-produk-po" class="form-horizontal">
      <div class="card-body">
          <p><i class="icon fas fa-exclamation-triangle mr-2 text-warning"></i>Kode Produk di Generate Otomatis oleh Sistem</p>
        <div class="form-group row">
          <label for="nama-produk-po" class="col-sm-4 col-form-label">Nama*</label>
          <div class="col-sm-8">
            <input type="text" class="form-control form-produk-po" id="nama-produk-po" name="nama-produk-po" minlength="2" maxlength="50" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="hargabeli-produk-po" class="col-sm-4 col-form-label">Harga Beli*</label>
          <div class="col-sm-8">
            <input type="text" class="form-control form-produk-po format-uang" id="hargabeli-produk-po" name="hargabeli-produk-po" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="kategori-produk-po" class="col-sm-4 col-form-label">Kategori</label>
          <div class="col-sm-5">
            <select class="form-control form-produk-po" name="kategori-produk-po" id="kategori-produk-po">
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
          <label for="status-produk-po" class="col-sm-4 col-form-label">Status</label>
          <div class="col-sm-5">
            <select class="form-control form-produk-po" name="status-produk-po" id="status-produk-po">
              <option value="1">Aktif</option>
              <option value="0">Non-Aktif</option>
            </select>
          </div>
        </div>
      </div>
</form>
</div>
<div class="modal-footer right-content-between">
<button type="button" class="btn btn-primary" id="btn-simpan-produk-po">Simpan</button>
<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>
</div>
</div>
</div>
</div>
</section>
<script>
$(function(){
    let param = 'pembelian'
    disable_form()

    function disable_form(){
        let d_form = $('#f-po').find('.form-po').each(function(){
            $(this).prop('disabled',true)
        })
    }

    function enable_form(){
        let d_form = $('#f-po').find('.form-po').each(function(){
            $(this).prop('disabled',false)
        })
    }

    set_date($('.tanggal-po'))

    // $('#cari-po').inputmask({
// "mask":"PO-99999999-9999"
// })

    $('#cari-po').keyup(function(e){
        e.preventDefault()
        let query = $(this).val()
        $('#list-po').css('display','block')
        if (query.length>=2){
            $.ajax({
                url:service_url+'s_search.php',
                method:'POST',
                data:{
                    token:'cari_po',
                    query:query},
                success:function(data){
                    $('#list-po').html(data)
                }
            })
        }
        if (query.length==0){
            $('#list-po').css('display','none')
        }
    })

    $(document).on('click','.po-search',function(e){
        e.preventDefault()
        let nomor = $(this).text()
        $('#cari-po').val(nomor)
        $('#list-po').css('display','none')
    })

    $(document).on('click','#btn-cari-po',function(e){
        e.preventDefault()
        let nomor = $('#cari-po').val()
        if (nomor!=''){
            $.post(service_url+'s_pembelian.php',{
                token:'get_po',
                data : nomor
            },function(data){
                if(data.status==true){
                    window.location.href='index.php?action=rpu_pembelian&nomorpo='+nomor
                    $('#cari-po').val('')
                } else {
                    toastr.info('NOMOR : '+nomor+' TIDAK DITEMUKAN!')
                    return false
                }
            },'json')
        } else {
            toastr.info('NOMOR PO TIDAK BOLEH KOSONG!')
            $('#cari-po').focus()
        }
    })

    $('.input-cari').keyup(function(e){
        e.preventDefault()
        let id = $(this).attr('id').replace('nama-','')
        let query = $('#nama-'+id).val()
        $('#list-'+id).css('display','block')
        if (query.length>=2){
            $.ajax({
                url:service_url+'s_search.php',
                method:'POST',
                data:{
                    token:id,
                    query:query},
                success:function(data){
                    $('#list-'+id).html(data)
                }
            })
        }
        if (query.length==0){
            $('#list-'+id).css('display','none')
        }
    })

    $('#nama-staff').blur(function(e){
        e.preventDefault()
        //let id = $(this).attr('id').replace('nama-','')
        let query = $(this).val()
        if (query.length>0){
            $.post(service_url+'s_search.php',{
                    token:'cek_exist',
                    table:'staff',
                    query:query
                },
                function(data){
                    // $('#list-'+id).html(data)
                    if(data.status==false){
                        toastr.error('NAMA STAFF BELUM TERDAFTAR, HARAP DIDAFTAR DAHULU')
                    }
                },'json')
        }
    })

    $(document).on('click','.gsearch',function(e){
        e.preventDefault()
        let nama = $(this).text()
        let id = $(this).attr('id').split('-')
        $('#nama-'+id[0]).val(nama)
        $('#'+id[0]+'-id').val(id[1])
        $('#list-'+id[0]).css('display','none')
    })

    $('#btn-batal-po').click(function(e){
        e.preventDefault()
        let nom = $('#text-nomorpo').text()
        if (nom!=''){
            $.post(service_url+'s_pembelian.php',{
                token:'batal_po',
                data : nom
            },function(data){
                if(data.status==true){
                    $('#f-po')[0].reset()
                    $('#text-nomorpo').text('')
                    $("#tb-detail-po tbody").empty()
                    window.location.href = 'index.php?action=rpu_pembelian'
                }
            },'json')
        } else {
            toastr.info('TIDAK ADA YANG DIBATALKAN!')
            return false
        }
    })

    $('#btn-edit-po').click(function(e){
        e.preventDefault()
        let nomr = $('#text-nomorpo').text()
        if(nomr==''){
            toastr.info('SILAHKAN PILIH NOMOR PO!')
        } else {
            enable_form()
            enable_btn($('#btn-update-po'))
            disable_btn($(this))
            disable_btn($('#btn-simpan-po'))
        }
    })

    $('#tambah-po').click(function(e){
        e.preventDefault()
        let nomr = $('#text-nomorpo').text()
        if(nomr==''){
            enable_form()
            enable_btn($('#tambah-detail-po'))
            enable_btn($('#tambah-produk'))
            disable_btn($(this))
            $.post(service_url+'s_pembelian.php',{
                token:param
            },function(data){
                if(data.status==true){
                    $('#nomor-po').val(data.nomor)
                    $('#text-nomorpo').text(data.nomor)
                    $('#no-urut').val(data.nourut)
                    $('#nama-supplier').focus()
                    //nomorp = data.nomor
                }
            },'json')
        } else {
            toastr.info('NOMOR PO SUDAH TERISI, MOHON DIBATALKAN DAHULU!')
        }
    })

    $('#tambah-detail-po').click(function(e){
        e.preventDefault()
        enable_btn($('#btn-simpan-po'))
        let jml_row  = $('#tb-detail-po tbody tr').length
        let idNum = jml_row + 1;
        let rowId = 'row-' + idNum;
        let id = $('#text-nomorpo').text()
        let html = '<tr class="det-tr-row-produk" id="'+rowId+'">'
        html +='<td>'+
        '<button type="button" class="btn btn-danger btn-sm btn-flat m-0 btn-hapus-row" id="'+rowId+'">'+
        '<i class="fas fa-times"></i></button>'+
        '</td>'
        html +='<td><input type="text" class="form-control-plaintext p-0 m-0" name="det-td-kode-produk" id="td-kode-produk-'+idNum+'" placeholder="R" readonly /></td>'
        html +='<td><input type="text" class="form-control-plaintext p-0 m-0 nama-prod" name="det-td-nama-produk" id="td-nama-produk-'+idNum+'" required/>'+
                '<ul id="list-produk-nama-'+idNum+'" class="list-group"></ul></td>'
        html +='<td><input type="text" class="form-control-plaintext p-0 m-0 text-right format-uang harga-qty" name="det-td-harga-produk" id="td-hargaprodukpo-'+idNum+'" placeholder="0" required/></td>'
        html +='<td><input type="number" min="1" class="form-control-plaintext p-0 m-0 text-center harga-qty" name="det-td-qty-pcs" id="td-qtypcs-'+idNum+'" placeholder="0" required /></td>'
        html +='<td><input type="number" min="0" step="0.01" class="form-control-plaintext p-0 m-0 text-center harga-qty" name="det-td-qty-kg" id="td-qtykg-'+idNum+'" placeholder="0.0" /></td>'
        html +='<td><input type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtot-kg" id="td-subtotkg-'+idNum+'" placeholder="R" readonly /></td>'
        html +='<td class="pl-3"><input list="td-perkalian-'+idNum+'" id="tdl-perkalian-'+idNum+'" name="det-td-perkalian" class="form-control-plaintext p-0 m-0 form-po perkalian" />'+
                '<datalist id="td-perkalian-'+idNum+'"><option value="QTY" /><option value="KG" /></datalist></td>'
        html +='<td class="pr-3"><input type="text" class="form-control-plaintext p-0 m-0 text-right" name="det-td-subtotal-produk" id="td-subtotalproduk-'+idNum+'" placeholder="R" readonly /></td>'
        html +='</tr>'

        $('#tb-detail-po tbody').append(html)
        $('#td-nama-produk').focus()

    })


    $(document).on('keyup','.nama-prod',function(){
        let rowid = $(this).attr('id')
        let idrow = rowid.replace('td-nama-produk-','')
        let query = $('#'+rowid).val()
        let cek = cek_produk()
        if(cek.length!=0){
            cek = JSON.stringify(cek)
        }else{
             cek = ''
        }
        if (query.length>=1){
            $.ajax({
                url:service_url+'s_search.php',
                method:'POST',
                data:{
                    token:'nama_produk',
                    query:query,
                    counter:idrow,
                    'cek':cek
                    },
                success:function(data){
                    $('#list-produk-nama-'+idrow).css('display','block')
                    $('#list-produk-nama-'+idrow).html(data)
                }
            })
        }
        if (query.length==0){
            $('#list-produk-nama-'+idrow).css('display','none')
        }
    })

     $(document).on('click','.tdsearch',function(){
        let nama = $(this).text()
        let id = $(this).attr('id').split('-')
        let kode = id[1]
        let idrx = id[2]
        let harga = id[3]
        harga = (harga=='0') ? '' : formatCurrency(harga)
        $('#td-nama-produk-'+idrx).val(nama)
        $('#td-kode-produk-'+idrx).val(kode)
        $('#td-hargaprodukpo-'+idrx).val(harga)
        if(id[3]=='0'){
            $('#td-hargaprodukpo-'+idrx).removeAttr('readonly')
        } else if(id[3]!='0') {
            $('#td-hargaprodukpo-'+idrx).attr('readonly','readonly')
        }
        $('#list-produk-nama-'+idrx).css('display','none')
    })

    $(document).on('keyup','.format-uang',function(){
        let nilai = $(this)
        format_uang(nilai)
    })

    $(document).on('change','.perkalian',function(){
        let idm = $(this).attr('id')
        let idp = idm.split('-')
        let va = $('#tdl-perkalian-'+idp[2]).val()
        update_subtotal(va,idp[2])
        update_nilai()
    })

    $(document).on('blur','.harga-qty',function(){
        let idm = $(this).attr('id')
        let idp = idm.split('-')
        let va = $('#td-perkalian-'+idp[2]).val()
        update_subtotal(va,idp[2])
        update_nilai()
    })

    $(document).on('blur','.extra-input-po',function(){
        let sbt = $('#td-subtotal-po').val()
        sbt = formatNormal(sbt)
        sbt = ($('#td-subtotal-po').val() == '') ? 0 : sbt
        let dis = ($('#td-diskon-po').val() == '') ? 0 : $('#td-diskon-po').val()
        let ong = $('#td-ongkir-po').val()
        ong = formatNormal(ong)
        ong = ($('#td-ongkir-po').val() == '') ? 0 : ong
        let kurang = $('#td-pengurangan-po').val()
        kurang = formatNormal(kurang)
        kurang = ($('#td-pengurangan-po').val() == '') ? 0 : kurang
        update_total(sbt,dis,ong,kurang)
    })

    $(document).on('click','#btn-simpan-produk-po',function(e){
      e.preventDefault()
      let form = $('#f-produk-po')
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
            $.post(service_url+'s_tambah.php',{
              token:'new_produk',
              data:form.serialize()
            },function(data){
                if (data.status==true){
                  toastr.success('SUKSES INPUT DATA!')
                  form[0].reset()
                  // disable_form()
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
        } else {
            toastr.warning(fail_log)
        }
    })

    $(document).on('click', '.btn-hapus-row', function(e) {
        $(this).closest('tr').remove()
        $('#td-diskon-po').val('')
         $('#td-pengurangan-po').val('')
        $('#td-ongkir-po').val('')
        $('#td-total-po').val('')
        update_nilai()
    })

    $(document).on('change', '.select-sts', function(e) {
        let kode=$(this).data('id')
        let sts = $(this).val()
        $.post(service_url+'s_update.php',{
              token:'update_sts_produk',
              kode:kode,
              sts:sts
            },function(data){
                if (data.status==true){
                  toastr.success('SUKSES UPDATE DATA!')
                  $('#tb-produk-po').DataTable().destroy()
                  fetch_produk()
                 } else{
                  toastr.error('ERROR INPUT DATA!')
                 }
            },'json')
    })

    $(document).on('click','#btn-simpan-po',function(e){
      e.preventDefault()
      simpan_update('simpan-po')
    })

    $(document).on('click','#btn-update-po',function(e){
        e.preventDefault()
        simpan_update('update-po')
    })

    $('#modal-tbproduk-po').on('shown.bs.modal',function(){
        $('#tb-produk-po').DataTable().destroy()
        fetch_produk()
    })

    $('#btn-print-po').click(function(e){
        e.preventDefault()
        let nomor = $('#text-nomorpo').text()
        if (nomor!=''){
            window.open('print/print_po.php?nomorpo='+nomor, '_blank')
        } else {
            toastr.info('NOMOR PO TIDAK BOLEH KOSONG!')
        }
    })

    function update_subtotal(arg,num){
        let harga_qty = 0
        let berat_qty = 0
        let harga_kg = 0
        let harga = $('#td-hargaprodukpo-'+num).val()
        let qty = $('#td-qtypcs-'+num).val()
        let avgkg = $('#td-qtykg-'+num).val()

        qty = parseFloat(qty)
        avgkg = parseFloat(avgkg)

        harga = formatNormal(harga)

        harga_qty = parseFloat(harga) * qty
        harga_qty = formatCurrency(harga_qty)

        berat_qty = avgkg * qty
        berat_qty = berat_qty.toFixed(2).toString()
        berat_qty = (isNaN(berat_qty)) ? '0' : berat_qty

        harga_kg = parseFloat(harga) * parseFloat(berat_qty)
        harga_kg = formatCurrency(harga_kg)
        $('#td-subtotkg-'+num).val(berat_qty)

        if(arg=='QTY'){
            $('#td-subtotalproduk-'+num).val(harga_qty)
        }
        if (arg=='KG') {
            $('#td-subtotalproduk-'+num).val(harga_kg)
        }
    }


    function update_nilai(){
        let qtyitem = 0
        let subberat = 0
        let subtotal = 0
        let subt = 0
        let diskon = 0
        let ongkir = 0
        let total = 0
        let kurang = 0
        $('#tb-detail-po > tbody > tr').each(function(){
            let qty = $(this).find('input[name="det-td-qty-pcs"]').val()
            let sub = $(this).find('input[name="det-td-subtotal-produk"]').val()
            sub = formatNormal(sub)
            let berat = $(this).find('input[name="det-td-subtot-kg"]').val()
            if (qty!=''){
                qty = parseFloat(qty)
                qtyitem += qty
            } else {
                return false
            }
            if (sub!=''){
                sub = parseInt(sub)
                subtotal += sub
            } else {
                return false
            }
            if (berat!=''){
                berat = parseFloat(berat)
                subberat += berat
            } else {
                return false
            }
        })
        let castberat = subberat.toFixed(2).toString()
        $('#td-total-qty-pcs').val(qtyitem)
        $('#td-total-qty-kg').val(castberat)
        subtotal = formatCurrency(subtotal)
        $('#td-subtotal-po').val(subtotal)
        let sbbt = formatNormal(subtotal)
        diskon = ($('#td-diskon-po').val() == '') ? 0 : $('#td-diskon-po').val()
        ongkir = ($('#td-ongkir-po').val() == '') ? 0 : formatNormal($('#td-ongkir-po').val())
        kurang = ($('#td-pengurangan-po').val() == '') ? 0 : formatNormal($('#td-pengurangan-po').val())
        update_total(sbbt,diskon,ongkir,kurang)
    }

    function update_total(subt,diskon,ongkir,kurang){
        subt = parseFloat(subt)
        diskon = parseFloat(diskon)
        ongkir = parseFloat(ongkir)
        kurang = parseFloat(kurang)
        diskon = subt*(diskon/100)
        total = (subt - diskon) - kurang + ongkir
        total = formatCurrency(total)
        $('#td-total-po').val(total)
    }

    function simpan_update_po(form,url,token,items){
        items = typeof items !== 'undefined' ? JSON.stringify(items) : ''
        let fail = false
        let fail_log = ''
        let name
        let msg = (token=='new-po') ? 'SUKSES SIMPAN DATA!' : 'SUKSES UPDATE DATA!'

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
                $.post(service_url+url,{
                  token:token,
                  data:form.serialize(),
                  'items':items
                },function(data){
                    if (data.status==true){
                        toastr.success(msg)
                        window.setTimeout(function(){
                            window.location.href = 'index.php?action=rpu_pembelian'
                        },1500)
                     } else{
                      toastr.error('ERROR INPUT DATA!')
                     }
                },'json')
            } else {
                toastr.warning(fail_log)
            }
        }

    function simpan_update(el){
        let thisel = $('#btn-'+el)
        let items
        let form = $('#f-po')
        let url = 's_pembelian.php'
        let token = 'new_po'
        let jr =$('#tb-detail-po tbody tr').length
        let nomorp = $('#text-nomorpo').text()

        if (el=='simpan-po'){
            token = 'new_po'
        } else if (el=='update-po'){
            token = 'update_po'
        } else {
            return false
        }

        if(nomorp == ''){
            toastr.info('NOMOR PO TIDAK BOLEH KOSONG!')
            return false
          } else if (jr == 0){
            toastr.info('HARAP MASUKKAN DETAIL ITEMS!')
            return false
          } else {
            items = detail_items()
            simpan_update_po(form,url,token,items)
          }
    }

    function cek_produk(){
        let obj2 = []
        $('#tb-detail-po > tbody > tr').each(function(){
            let id_item = $(this).find('input[name="det-td-kode-produk"]').val()
            if (id_item!=''){
                obj2.push(id_item)
            }
        })
        return obj2
    }

    function detail_items(){
        let obj = []
        $('#tb-detail-po > tbody > tr').each(function(){
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

    function fetch_produk() {
        $.ajax({
                url: service_url+'s_katalog.php',
                method: 'POST',
                dataType: 'json',
                data:{token:'produk_2'}
            }).done(function(data){
                let tb = $('#tb-produk-po').DataTable({
                    //dom:'<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"p>>',
                    aaData: data,
                    processing:true,
                    autoWidth:false,
                    info:false,
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
                        { "data": "kode_produk",},
                        { "data": "nama" },
                        { "data": "harga_beli","class":"text-right","render": function (data,type,row){
                                return formatCurrency(data);
                            },},
                        { "data": "kategori","class":"pl-3" },
                        { "data": "status","class":"text-center","render":function(data,type,row){
                                    return dropBadge(data,row.id)
                            },},
                    ],
                });
            });
    }

})
// END JQUERY //
</script>

