<?php
if (!isset($_GET['nomorinv']) || $_GET['nomorinv'] =='') {
   die ('Parameter tidak ditemukan');
} else {
    $inv=$_GET['nomorinv'];
}
require_once '../libs/config.php';
$sql="SELECT nomor_sj,tgl_kirim,ekspedisi FROM tb_surat_jalan WHERE nomor_inv='".$inv."'";
$param = array('nomor_inv'=>$inv);
$sj =  DbHandler::getRow($sql,$param);
if(!$sj){
  die ('Surat Jalan belum dibuat, mohon di buat terlebih dahulu');
}
$jual = RpuPenjualan::get_inv($inv);
$tglbuatinv = ($jual['tanggal_pembuatan']=='') ? $tglnow : dmy($jual['tanggal_pembuatan']);
$username = ($jual['nstaff']=='') ? $session_us : $jual['nstaff'];
$tgl_order =($jual['tanggal_order']=='') ? '' : dmy($jual['tanggal_order']);
$tgljtinv =($jual['tanggal_jatuh_tempo']=='') ? '' : dmy($jual['tanggal_jatuh_tempo']);
$disk_inv = ($jual['diskon']==0) ? '0' : $jual['diskon'];
$kur_inv = ($jual['pengurangan']==0) ? '0' : money_simple($jual['pengurangan']);
$ongkir_inv = ($jual['ongkir']==0) ? '0' : money_simple($jual['ongkir']);
$set = RpuKatalog::get_settings();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Aplikasi RPU | Print Surat Jalan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/adminlte.min.css'; ?>" />
  <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/print.css'; ?>" />
</head>
<body>
<div class="wrapper">
  <section class="invoice">
    <div class="row invoice-info">
         <div class="col-sm-12 invoice-col text-center"><h4>SURAT JALAN</h4></div>
    </div>
    <hr class="mt-0" />
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <h5><?php echo $set['nama_usaha'];?></h5>
          <address>
          Alamat<br>
          <?php echo $set['alamat_usaha'];?><br>
          Tlp : <?php echo $set['tlp_usaha'];?> / <?php echo $set['hp'];?><br>
          Email : <?php echo $set['email'];?>
        </address>
      </div>
      <div class="col-sm-4 invoice-col">
        <address>
          <strong>Nomor : <?php echo $sj['nomor_sj'];?></strong><br>
          Invoice : <?php echo $inv;?><br>
          Tanggal Kirim : <?php echo dmy($sj['tgl_kirim']);?> <br>
          Ekspedisi : <?php echo $sj['ekspedisi']?>
        </address>
      </div>
      <div class="col-sm-4 invoice-col">
        <address>
          Outlet : <?php echo trim($jual['noutlet']);?><br>
          Alamat : <?php echo trim($jual['alamat']);?><br>
          Telepon : <?php echo trim($jual['tlp']);?>
        </address>
      </div>
        <div class="table-responsive">
        <table class="table table-sm" id="tb-detail-inv">
            <thead class="text-center">
                <td>Kode</td>
                <td>Produk</td>
                <td>Qty <small>(pcs)</small></td>
                <td>@Avg.Berat <small>(kg)</small></td>
                <td>Subtotal Berat <small>(kg)</small></td>
            </thead>
            <tbody>
                <?php
                $prod = RpuPenjualan::get_detail_inv($inv);
                for($vi=0;$vi<count($prod);$vi++){?>
                <tr>
                <td class="text-center"><?php echo $prod[$vi]['kode_produk'];?></td>
                <td><?php echo $prod[$vi]['nama'];?></td>
                <td class="text-center"><?php echo $prod[$vi]['produk_qty'];?></td>
                <td class="text-center"><?php echo desimal($prod[$vi]['produk_berat']);?></td>
                <td class="text-center"><?php echo desimal($prod[$vi]['subtotal_berat']);?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
        </div>
        <br />
        <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            Keterangan
            <address>
            <?php echo trim($jual['keterangan']);?>
            </address>
        </div>
        <div class="col-sm-2 invoice-col">
            <p>Tgl. Terima : </p>
        </div>
        <div class="col-sm-2 invoice-col">
            <p>Penerima : </p>
        </div>
        <div class="col-sm-4 invoice-col">
          <div class="table-responsive">
            <table class="table table-sm text-right no-border">
            <tr>
                <td style="width:50%">Total Item <small>(pcs)</small></td>
                <td class="pr-5"><?php echo $jual['total_produk'];?></td>
            </tr>
            <tr>
                <td>Total Berat <small>(kg)</small></td>
                <td class="pr-5"><?php echo desimal($jual['total_berat']);?></td>
            </tr>
            </table>
          </div>
        </div>
        </div>
  </section>
</div>
<script type="text/javascript">
 window.addEventListener("load", window.print());
</script>
</body>
</html>
