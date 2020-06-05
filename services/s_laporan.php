<?php
require_once '../libs/config.php';
require_once '../libs/rpu_pembelian.php';
require_once '../libs/rpu_penjualan.php';
require_once '../libs/rpu_katalog.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='lap_pembelian'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $arg = $_POST['arg'];
    $rows=RpuPembelian::get_lap_po($awal,$akhir,$arg);
    echo json_encode($rows);
} else if (isset($_POST['token']) && $_POST['token']=='totalan'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $rows=RpuPembelian::get_totalan($awal,$akhir);
    $hutang = $rows['hutang']-$rows['terbayar'];
    $hutang = ($hutang==0)?'0':money_simple($hutang);
    echo '{"status":true,"hutang":"'.$hutang.'","countr":"'.$rows['countr'].'","total_qty":"'.$rows['total_qty'].'","total":"'.money_simple($rows['total_rp']).'"}';
} else if (isset($_POST['token']) && $_POST['token']=='update_bayarpo'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuPembelian::new_bayar($data);
    $sql="SELECT nomor_po FROM tb_log_pembelian ORDER BY id DESC LIMIT 1";
    $pid =  DbHandler::getOne($sql);
    if($pid){
        echo '{"status":true}';
    }
} else if (isset($_POST['token']) && $_POST['token']=='lap_penjualan'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $arg = $_POST['arg'];
    $rows=RpuPenjualan::get_lap_inv($awal,$akhir,$arg);
    echo json_encode($rows);
} else if (isset($_POST['token']) && $_POST['token']=='totalan_inv'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $rows=RpuPenjualan::get_totalan_inv($awal,$akhir);
    $hutang = $rows['hutang']-$rows['terbayar'];
    $hutang = ($hutang==0)?'0':money_simple($hutang);
    echo '{"status":true,"hutang":"'.$hutang.'","countr":"'.$rows['countr'].'","total_qty":"'.$rows['total_qty'].'","total":"'.money_simple($rows['total_rp']).'"}';
} else if (isset($_POST['token']) && $_POST['token']=='update_bayarinv'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuPenjualan::new_bayar_inv($data);
    $sql="SELECT nomor_inv FROM tb_log_penjualan ORDER BY id DESC LIMIT 1";
    $pid =  DbHandler::getOne($sql);
    if($pid){
        echo '{"status":true}';
    }
} else if (isset($_POST['token']) && $_POST['token']=='items_laku'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $jual_bersih = RpuPenjualan::get_totalan_inv($awal,$akhir);
    $jual_kotor = RpuPenjualan::get_penjualan_kotor($awal,$akhir);
    $bb = 0;
    $bv = 0;
    $tb = 0;
    $tbv = 0;

    $biaya = RpuKatalog::getAllBiayaSum($awal,$akhir);
    if(!$biaya){
        $biaya['nominal']=0;
    }
    $biaya_variabel = RpuKatalog::get_total_bv($awal,$akhir);
    if($biaya_variabel){
        $bv = $biaya_variabel['nominal'];
    }
    $total_biaya = RpuKatalog::get_totalan_biaya($awal,$akhir);
    if($total_biaya['total']!=NULL){
        $tb = $total_biaya['total'];
    }
    $total_bv = RpuKatalog::get_totalan_bv($awal,$akhir);
    if($total_bv['total']!=NULL){
        $tbv = $total_bv['total'];
    }
    $item_terjual = RpuKatalog::get_total_perproduk_laku($awal,$akhir);
    //$untung = RpuKatalog::get_total_produk_laku($awal,$akhir);
    echo '{
    "total_bv":'.json_encode($tbv).',
    "total_biaya":'.json_encode($tb).',
    "biaya":'.json_encode($biaya).',
    "jual_kotor":'.json_encode($jual_kotor).',
    "jual_bersih":'.json_encode($jual_bersih).',
    "biayav":'.json_encode($bv).'}';
} else {
    die('NO DATA PASSED');
}