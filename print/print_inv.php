<?php
if (!isset($_GET['nomorinv']) || $_GET['nomorinv'] =='') {
    die ('Parameter tidak ditemukan');
} else {
    $inv=$_GET['nomorinv'];
}
require_once '../libs/config.php';
$jual = RpuPenjualan::get_inv($inv);
$tglbuatinv = ($jual['tanggal_pembuatan']=='') ? $tglnow : dmy($jual['tanggal_pembuatan']);
$username = ($jual['nstaff']=='') ? $session_us : $jual['nstaff'];
$tgl_order =($jual['tanggal_order']=='') ? '' : dmy($jual['tanggal_order']);
$tgljtinv =($jual['cara_bayar']=='LUNAS') ? '-' : dmy($jual['tanggal_jatuh_tempo']);
$disk_inv = ($jual['diskon']==0) ? '0' : $jual['diskon'];
$kur_inv = ($jual['pengurangan']==0) ? '0' : money_simple($jual['pengurangan']);
$ongkir_inv = ($jual['ongkir']==0) ? '0' : money_simple($jual['ongkir']);
$diskon_rp = ($jual['diskon_rp']==0) ? '0' : money_simple($jual['diskon_rp']);
$set = RpuKatalog::get_settings();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Aplikasi RPU | Print Invoice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/adminlte.min.css'; ?>" />
  <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/print.css'; ?>" />
</head>
<body>
<div class="wrapper">
  <section class="invoice">
    <div class="row invoice-info">
         <div class="col-sm-12 invoice-col text-center"><h4>INVOICE</h4></div>
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
          <strong>Nomor : <?php echo $inv;?></strong><br>
          Tanggal Invoice : <?php echo $tglbuatinv;?> <br>
          Pembuat Invoice : <?php echo $username;?><br>
          Outlet : <?php echo $jual['noutlet']?>
        </address></div>
       <div class="col-sm-4 invoice-col">
        <address>
          Bayar : <?php echo $jual['cara_bayar'];?> <br>
          Jatuh Tempo : <?php echo $tgljtinv;?> <br>
          Tgl.Order : <?php echo $tgl_order; ?><br>
          Sales : <?php echo $jual['nsales']; ?><br>
        </address>
        </div>
        <div class="table-responsive">
        <table class="table table-sm" id="tb-detail-inv">
            <thead class="text-center">
                <td>Kode</td>
                <td>Produk</td>
                <td>@Harga</td>
                <td>Qty <small>(pcs)</small></td>
                <td>@Avg.Berat <small>(kg)</small></td>
                <td>Subtotal Berat <small>(kg)</small></td>
                <td>Sat</td>
                <td>Subtotal <small>(Rp)</small></td>
            </thead>
            <tbody>
                <?php
                $prod = RpuPenjualan::get_detail_inv($inv);
                for($vi=0;$vi<count($prod);$vi++){?>
                <tr>
                <td class="text-center"><?php echo $prod[$vi]['kode_produk'];?></td>
                <td><?php echo $prod[$vi]['nama'];?></td>
                <td class="text-right"><?php echo money_simple($prod[$vi]['harga_jual']);?></td>
                <td class="text-center"><?php echo $prod[$vi]['produk_qty'];?></td>
                <td class="text-center"><?php echo desimal($prod[$vi]['produk_berat']);?></td>
                <td class="text-center"><?php echo desimal($prod[$vi]['subtotal_berat']);?></td>
                <td class="text-center"><?php echo $prod[$vi]['per'];?></td>
                <td class="text-right pr-3"><?php echo money_simple($prod[$vi]['subtotal']);?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
        </div>
        <br />
        <div class="row invoice-info">
<!--         <div class="col-sm-2 invoice-col">
        <address>
          Bank : <?php //echo $set['bank'];?><br>
          No.Rek : <?php //echo $set['no_rek'];?><br>
          A.n : <?php //echo $set['an_rek'];?><br>
          NPWP : <?php //echo $set['npwp'];?>
        </address>
        </div> -->
        <div class="col-sm-3 invoice-col">
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
        <div class="col-sm-2">
           <p>&nbsp;</p>
        </div>
        <div class="col-sm-3 invoice-col text-right">
          <div class="table-responsive">
            <table class="table table-sm text-right no-border">
            <tr>
                <td style="width:60%">Total Item <small>(pcs)</small></td>
                <td class="pr-3"><?php echo $jual['total_produk'];?></td>
            </tr>
            <tr>
                <td>Total Berat <small>(kg)</small></td>
                <td class="pr-3"><?php echo $jual['total_berat'];?></td>
            </tr>
            <tr>
                <td>Subtotal <small>(Rp)</small></td>
                <td class="pr-3"><?php echo money_simple($jual['total_jumlah']);?></td>
            </tr>
            <tr>
                <td>Diskon <small>(%)</small></td>
                <td class="pr-3"><?php echo $disk_inv;?>
            </tr>
             <tr>
                <td><small>(Rp) (-)</small></td>
                <td class="pr-3"><?php echo $kur_inv;?></td>
            </tr>
            <tr>
                <td>Diskon Rp <small>(Rp) (-)</small></td>
                <td class="pr-3"><?php echo $diskon_rp;?>
            </tr>
            <tr>
                <td>Ongkos Kirim & Lain2 <small>(Rp)</small></td>
                <td class="pr-3"><?php echo $ongkir_inv;?></td>
            </tr>
            <tr>
                <td>Total Bayar <small>(Rp)</small></td>
                <td class="pr-3"><?php echo money_simple($jual['grand_total']);?>
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
