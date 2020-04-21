<?php
require_once '../libs/config.php';
require_once '../libs/rpu_penjualan.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token'] == 'penjualan') {
    $tgl = date ('Y-m-d');
    $nomor = RpuPenjualan::inv_baru($tgl);
    $_SESSION['penjualan'] = $nomor['nomor_inv'];
    echo '{"status":true,"nomor":"'.$nomor['nomor_inv'].'","nourut":"'.$nomor['no_urut'].'"}';
} else if (isset($_POST['token']) && $_POST['token'] == 'get_inv') {
    $num = $_POST['data'];
    $_SESSION['penjualan'] = $num;
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token'] == 'new_inv') {
    $dt = $_POST['data'];
    parse_str($dt,$data);
    $inv = $data['nomor-inv'];
    $items = json_decode(stripslashes($_POST['items']),true);
    RpuPenjualan::insert_inv_baru($data);
    $sql="SELECT nomor_inv FROM tb_penjualan ORDER BY id DESC LIMIT 1";
    $inv_db =  DbHandler::getOne($sql);
    if ($inv_db == $inv){
        foreach($items as $value){
            RpuPenjualan::insert_inv_detail($inv,$value);
        }
        // foreach($items as $value){
        //     RpuKatalog::update_stok_produk_jual($value);
        // }
        $_SESSION['penjualan'] = '';
        echo '{"status":true}';
    }
} else if (isset($_POST['token']) && $_POST['token'] == 'update_inv'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    $inv = $data['nomor-inv'];
    $items = json_decode(stripslashes($_POST['items']),true);
    RpuPenjualan::update_inv($data);
    $sql="SELECT nomor_inv FROM tb_penjualan WHERE nomor_inv='".$inv."'";
    $inv_db =  DbHandler::getOne($sql);
    if ($inv_db == $inv){
        foreach($items as $value){
            RpuPenjualan::update_inv_detail($inv,$value);
        }
        $_SESSION['penjualan'] = '';
        echo '{"status":true}';
    }

} else if (isset($_POST['token']) && $_POST['token'] == 'batal_inv') {
    $dt = $_POST['data'];
    if($dt==$_SESSION['penjualan']){
        $_SESSION['penjualan']='';
        echo '{"status":true}';
    }
} else if(isset($_POST['token']) && $_POST['token'] == 'detail_inv'){
    $inv = $_POST['nomor'];
    $vitems = RpuPenjualan::get_detail_inv($inv);
    $data=array();
    for ($vi = 0; $vi < count($vitems); $vi++) {
        $sub=array();
        $sub[] = '<button type="button" class="btn btn-danger btn-sm btn-flat m-0 btn-hapus-row" id="row-'.$vitems[$vi]["id"].'"><i class="fas fa-times"></i></button>';
        $sub[] = $vitems[$vi]["kode_produk"];
        $sub[] = $vitems[$vi]["nama"];
        $sub[] = $vitems[$vi]["produk_qty"];
        $sub[] = $vitems[$vi]["produk_berat"];
        $sub[] = $vitems[$vi]["harga_jual"];
        $sub[] = $vitems[$vi]["subtotal_berat"];
        $sub[] = $vitems[$vi]["subtotal"];
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($vitems);
}
// else if(isset($_GET['nomorpo']) && $_GET['nomorpo'] != '') {
//     $nomor = $_GET['nomorpo'];
//     $_SESSION['pembelian'] = $nomor;
//     $result = RpuPenjualan::get_po($nomor);
//     header('Location: ' . $_SESSION['pembelian']);
// }


