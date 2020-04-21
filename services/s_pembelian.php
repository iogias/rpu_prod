<?php
require_once '../libs/config.php';
require_once '../libs/rpu_pembelian.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token'] == 'pembelian') {
    $tgl = date ('Y-m-d');
    $nomor = RpuPembelian::po_baru($tgl);
    $_SESSION['pembelian'] = $nomor['nomor_po'];
    echo '{"status":true,"nomor":"'.$nomor['nomor_po'].'","nourut":"'.$nomor['no_urut'].'"}';
} else if (isset($_POST['token']) && $_POST['token'] == 'get_po') {
    $num = $_POST['data'];
    $_SESSION['pembelian'] = $num;
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token'] == 'new_po') {
    $dt = $_POST['data'];
    parse_str($dt,$data);
    $po = $data['nomor-po'];
    $items = json_decode(stripslashes($_POST['items']),true);
    RpuPembelian::insert_po_baru($data);
    $sql="SELECT nomor_po FROM tb_pembelian ORDER BY id DESC LIMIT 1";
    $po_db =  DbHandler::getOne($sql);
    if ($po_db == $po){
        foreach($items as $value){
            RpuPembelian::insert_po_detail($po,$value);
        }
        // foreach($items as $value){
        //     RpuKatalog::update_stok_produk($value);
        // }
        $_SESSION['pembelian'] = '';
        echo '{"status":true}';
    }
} else if (isset($_POST['token']) && $_POST['token'] == 'update_po'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    $po = $data['nomor-po'];
    $items = json_decode(stripslashes($_POST['items']),true);
    RpuPembelian::update_po($data);
    $sql="SELECT nomor_po FROM tb_pembelian WHERE nomor_po='".$po."'";
    $po_db =  DbHandler::getOne($sql);
    if ($po_db == $po){
        foreach($items as $value){
            RpuPembelian::update_po_detail($po,$value);
        }
        $_SESSION['pembelian'] = '';
        echo '{"status":true}';
    }

} else if (isset($_POST['token']) && $_POST['token'] == 'batal_po') {
    $dt = $_POST['data'];
    if($dt==$_SESSION['pembelian']){
        $_SESSION['pembelian']='';
        echo '{"status":true}';
    }
} else if(isset($_POST['token']) && $_POST['token'] == 'detail_po'){
    $po = $_POST['nomor'];
    $items = RpuPembelian::get_detail_po($po);
    $data=array();
    for ($i = 0; $i < count($items); $i++) {
        $sub=array();
        $sub[] = '<button type="button" class="btn btn-danger btn-sm btn-flat m-0 btn-hapus-row" id="row-'.$items[$i]["id"].'"><i class="fas fa-times"></i></button>';
        $sub[] = $items[$i]["kode_produk"];
        $sub[] = $items[$i]["nama"];
        $sub[] = $items[$i]["produk_qty"];
        $sub[] = $items[$i]["produk_berat"];
        $sub[] = $items[$i]["harga_beli"];
        $sub[] = $items[$i]["subtotal_berat"];
        $sub[] = $items[$i]["subtotal"];
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);
} else if(isset($_POST['token']) && $_POST['token'] == 'retur-pembelian'){
    $items = RpuPembelian::get_retur_po();

    $data=array();
    for ($i = 0; $i < count($items); $i++) {
        $sub=array();
        $sub[] = $items[$i]["nomor_po"];
        $sub[] = $items[$i]["kode_produk"];
        $sub[] = $items[$i]["nama"];
        $sub[] = $items[$i]["produk_qty"];
        $sub[] = $items[$i]["produk_rp"];
        $sub[] = $items[$i]["tgl_retur"];
        $sub[] = '<button type="button" class="btn btn-warning btn-sm" id="retur-'.$items[$i]["id"].'"><i class="fas fa-pencil-alt"></i></button>';
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);
} else {
    die('NO DATA PASSED');
}


