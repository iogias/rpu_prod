<?php
require_once '../libs/config.php';
require_once '../libs/rpu_katalog.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='kategoriproduk'){
    $kid = (int) $_POST['data'];
    $items = RpuKatalog::getKategoriProdukById($kid);
    echo '{"status":true,"nama":"'.$items['nama'].'","keterangan":"'.$items['keterangan'].'","sts":"'.$items['status'].'"}';
} else if (isset($_POST['token']) && $_POST['token']=='update_kategoriproduk'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_kategoriproduk($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='supplier'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    $items = RpuKatalog::selectRowById($table,$id);
    echo '{
        "status":true,
        "nama":"'.$items['nama'].'",
        "alamat":"'.$items['alamat'].'",
        "telepon":"'.$items['telepon'].'",
        "pic":"'.$items['pic'].'",
        "hp":"'.$items['hp'].'",
        "keterangan":"'.$items['keterangan'].'",
        "sts":"'.$items['status'].'"}';
} else if (isset($_POST['token']) && $_POST['token']=='update_supplier'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_supplier($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='customer'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    $items = RpuKatalog::selectRowById($table,$id);
    $tg = date('d-m-Y',strtotime($items['tgl_daftar']));
    echo '{
        "status":true,
        "nama":"'.$items['nama'].'",
        "alamat":"'.$items['alamat'].'",
        "telepon":"'.$items['telepon'].'",
        "hp":"'.$items['hp'].'",
        "tgl_daftar":"'.$tg.'",
        "customer_group_id":"'.$items['customer_group_id'].'",
        "sts":"'.$items['status'].'"}';
} else if (isset($_POST['token']) && $_POST['token']=='update_customer'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_customer($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='outlet'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    $items = RpuKatalog::selectRowById($table,$id);
    $tg = date('d-m-Y',strtotime($items['tgl_daftar']));
    echo '{
        "status":true,
        "nama":"'.$items['nama'].'",
        "alamat":"'.$items['alamat'].'",
        "telepon":"'.$items['telepon'].'",
        "tgl_daftar":"'.$tg.'",
        "customer_id":"'.$items['customer_id'].'",
        "sts":"'.$items['status'].'"}';
} else if(isset($_POST['token']) && $_POST['token']=='update_outlet'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_outlet($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='customergroup'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    $items = RpuKatalog::selectRowById($table,$id);
    echo '{
        "status":true,
        "nama":"'.$items['nama'].'",
        "keterangan":"'.$items['keterangan'].'",
        "sts":"'.$items['status'].'"}';
} else if(isset($_POST['token']) && $_POST['token']=='update_customergroup'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_customergroup($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='produk'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    $items = RpuKatalog::getAllProdukJoinById($id);
    $harga_jual=$items['harga_jual']==0 ? '' : money_simple($items["harga_jual"]);
    $hpp=$items['hpp']==0 ? '' : money_simple($items["hpp"]);
    $qty_beli=RpuKatalog::qty_produk_beli($items["kode_produk"]);
    $qty_jual=RpuKatalog::qty_produk_jual($items["kode_produk"]);
    $qty_ready=$qty_beli-$qty_jual-$items['lainnya'];
    $nama_jual = ($items['nama_jual']=='nama_jual')?$items['nama']:$items['nama_jual'];
    echo '{
        "status":true,
        "kode":"'.$items['kode_produk'].'",
        "nama":"'.$items['nama'].'",
        "nama_jual":"'.$nama_jual.'",
        "harga_beli":"'.money_simple($items['harga_beli']).'",
        "harga_jual":"'.$harga_jual.'",
        "hpp":"'.$hpp.'",
        "stok_ready":"'.money_simple($qty_ready).'",
        "stok_terjual":"'.money_simple($qty_jual).'",
        "stok_lainnya":"'.$items['lainnya'].'",
        "kategoriproduk_id":"'.$items['kategoriproduk_id'].'",
        "sts":"'.$items['status'].'"}';
} else if(isset($_POST['token']) && $_POST['token']=='update_produk'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_produk($data);
    echo '{"status":true}';
} else if(isset($_POST['token']) && $_POST['token']=='update_settings'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_settings($data);
    echo '{"status":true}';
}  else if (isset($_POST['token']) && $_POST['token']=='biaya'){
    $id = $_POST['data'];
    $items = RpuKatalog::get_biaya_byId($id);
    echo '{"status":true,"biaya":'.json_encode($items).'}';
} else if (isset($_POST['token']) && $_POST['token']=='update_biaya'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_biaya($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='staff'){
    $idstaff = $_POST['data'];
    $staff = RpuUser::get_staff_byId($idstaff);
    echo '{"status":true,"staff":'.json_encode($staff).'}';
} else if(isset($_POST['token']) && $_POST['token']=='update_staff'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuUser::update_staff($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='jb'){
    $idjb = $_POST['data'];
    $jb = RpuKatalog::get_jb_byId($idjb);
    echo '{"status":true,"jb":'.json_encode($jb).'}';
} else if (isset($_POST['token']) && $_POST['token']=='update_jb'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::update_jb($data);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='kjb'){
    $dt = $_POST['data'];
    $jbi=RpuKatalog::getAllJbbyKode($dt);
    echo json_encode($jbi);
} else {
    die ('NO DATA PASSED');
}
