<?php
require_once '../libs/config.php';
if (!isset($_GET['nomorpo']) || $_GET['nomorpo'] =='') {
   die ('Parameter tidak ditemukan');
} else if(isset($_GET['nomorpo'])){
  $po=$_GET['nomorpo'];
  $poex = RpuPembelian::get_po($po);
  if(!$poex){
    die ('PO belum disimpan, harap disimpan dahulu');
  }

} else {
    $po=$_GET['nomorpo'];
}
$beli = RpuPembelian::get_po($po);
$tgl_buat = ($beli['tanggal_pembuatan']=='') ? $tglnow : dmy($beli['tanggal_pembuatan']);
$username = ($beli['staff_buat']=='') ? $session_us : $beli['staff_buat'];
$tgl_terima =($beli['tanggal_terima']=='') ? '' : dmy($beli['tanggal_terima']);
$tgl_jt =($beli['tanggal_jatuh_tempo']=='') ? '' : dmy($beli['tanggal_jatuh_tempo']);
$diskon = ($beli['diskon']==0) ? '0' : $beli['diskon'];
$pengurangan = ($beli['pengurangan']==0) ? '0' : money_simple($beli['pengurangan']);
$ongkir = ($beli['ongkir']==0) ? '0' : money_simple($beli['ongkir']);
$set = RpuKatalog::get_settings();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Aplikasi RPU | Print PO</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/adminlte.min.css'; ?>" />
  <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/print.css'; ?>" />
</head>
<body>
<div class="wrapper">
  <section class="invoice">
    <div class="row invoice-info">
         <div class="col-sm-12 invoice-col text-center"><h4>PEMBELIAN (PO)</h4></div>
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
          <strong>Nomor : <?php echo $po;?></strong><br>
          Tanggal PO : <?php echo $tgl_buat;?> <br>
          Pembuat PO : <?php echo $username;?><br>
          Supplier : <?php echo $beli['nsupplier']?>
        </address></div>
       <div class="col-sm-4 invoice-col">
        <address>
          Bayar : <?php echo $beli['cara_bayar'];?> <br>
          Jatuh Tempo : <?php echo $tgl_jt;?> <br>
          Tgl.Terima Barang : <?php echo $tgl_terima; ?><br>
          Penerima Barang : <?php echo $beli['staff_terima']; ?><br>
        </address>
        </div>
        <div class="table-responsive">
        <table class="table table-sm" id="tb-detail-po">
            <thead class="text-center">
                <td>Kode</td>
                <td>Produk</td>
                <td>@Harga Beli</td>
                <td>Qty <small>(pcs)</small></td>
                <td>@Avg.Berat <small>(kg)</small></td>
                <td>Subtotal Berat <small>(kg)</small></td>
                <td>Sat</td>
                <td>Subtotal <small>(Rp)</small></td>
            </thead>
            <tbody>
                <?php
                $produk = RpuPembelian::get_detail_po($po);
                for($nn=0;$nn<count($produk);$nn++){?>
                <tr>
                <td class="text-center"><?php echo $produk[$nn]['kode_produk'];?></td>
                <td><?php echo $produk[$nn]['nama'];?></td>
                <td class="text-right"><?php echo money_simple($produk[$nn]['harga_beli']);?></td>
                <td class="text-center"><?php echo $produk[$nn]['produk_qty'];?></td>
                <td class="text-center"><?php echo desimal($produk[$nn]['produk_berat']);?></td>
                <td class="text-center"><?php echo desimal($produk[$nn]['subtotal_berat']);?></td>
                <td class="text-center"><?php echo $produk[$nn]['per'];?></td>
                <td class="text-right pr-3"><?php echo money_simple($produk[$nn]['subtotal']);?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
        </div>
        <br />
        <div class="row invoice-info">
        <div class="col-sm-3 invoice-col">
        <address>
          Bank : <?php echo $set['bank'];?><br>
          No.Rek : <?php echo $set['no_rek'];?><br>
          A.n : <?php echo $set['an_rek'];?><br>
          NPWP : <?php echo $set['npwp'];?>
        </address>
        </div>
        <div class="col-sm-4 invoice-col">
            Keterangan
            <address>
            <?php echo trim($beli['keterangan']);?>
            </address>
        </div>
        <div class="col-sm-5 invoice-col">
          <div class="table-responsive">
            <table class="table table-sm text-right no-border">
            <tr>
                <td style="width:60%">Total Item <small>(pcs)</small></td>
                <td class="pr-3"><?php echo $beli['total_produk'];?></td>
            </tr>
            <tr>
                <td>Total Berat <small>(kg)</small></td>
                <td class="pr-3"><?php echo $beli['total_berat'];?></td>
            </tr>
            <tr>
                <td>Subtotal <small>(Rp)</small></td>
                <td class="pr-3"><?php echo money_simple($beli['total_jumlah']);?></td>
            </tr>
            <tr>
                <td>Diskon <small>(%)</small></td>
                <td class="pr-3"><?php echo $diskon;?>
            </tr>
             <tr>
                <td>Pengurangan <small>(Rp)</small></td>
                <td class="pr-3"><?php echo $pengurangan;?></td>
            </tr>
            <tr>
                <td>Ongkos Kirim & Lain2 <small>(Rp)</small></td>
                <td class="pr-3"><?php echo $ongkir;?></td>
            </tr>
            <tr>
                <td>Total Bayar <small>(Rp)</small></td>
                <td class="pr-3"><?php echo money_simple($beli['grand_total']);?>
            </tr>
            </table>
          </div>
        </div>
  </section>
</div>
<script type="text/javascript">
 window.addEventListener("load", window.print());
</script>
</body>
</html>
