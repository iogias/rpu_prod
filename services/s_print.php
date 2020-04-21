<?php
require_once '../libs/config.php';
require_once '../libs/rpu_katalog.php';
require_once '../libs/rpu_pembelian.php';
require_once '../libs/rpu_penjualan.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='print'){
    $data = $_POST['data'];
    $row = RpuPembelian::get_po($data);
    $items = RpuPembelian::get_detail_po($data);
    echo '{"status":true,"nomor":"'.$data.'","row":'.json_encode($row).',"items":'.json_encode($items).'}';
}
    // $tgl_buat = dmy($row['tanggal_pembuatan']);
    // $username = $row['staff_buat'];
    // $userid = $row['id_pembuat'];
    // $tgl_terima =dmy($row['tanggal_terima']);
    // $tgl_jt =dmy($row['tanggal_jatuh_tempo']);
    // $diskon = $row['diskon'];
    // $pengurangan = money_simple($row['pengurangan']);
    // $ongkir = money_simple($row['ongkir']);


