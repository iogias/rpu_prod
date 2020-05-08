<?php
require_once '../libs/config.php';
require_once '../libs/rpu_katalog.php';
require_once '../libs/rpu_pembelian.php';
//header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='supplier'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $supplier=RpuKatalog::getNamaSupplier($keyword);
    $data='';
    foreach($supplier as $row){
        $id = $row["id"];
        $data .= '<li class="list-group-item contsearch">
            <a href="javascript:void(0)" class="gsearch nav-link" id="supplier-'.$id.'">'.$row["nama"].'</a></li>';
    }
    echo $data;
 } else if (isset($_POST['token']) && $_POST['token']=='staff'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $staff=RpuKatalog::getNamaStaff($keyword);
    $data='';
    foreach($staff as $row){
        $id = $row['id'];
        $data .= '<li class="list-group-item contsearch">
            <a href="javascript:void(0)" class="gsearch nav-link" id="staff-'.$id.'">'.$row["nama"].'</a></li>';
    }
    echo $data;

 } else if (isset($_POST['token']) && $_POST['token']=='outlet'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $staff=RpuKatalog::getNamaOutlet($keyword);
    $data='';
    foreach($staff as $row){
        $id = $row["id"];
        $data .= '<li class="list-group-item contsearch">
            <a href="javascript:void(0)" class="gsearch nav-link" id="outlet-'.$id.'">'.$row["nama"].'</a></li>';
    }
    echo $data;
} else if (isset($_POST['token']) && $_POST['token']=='nama_produk'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $co = $_POST['counter'];
    $cek = json_decode(stripslashes($_POST['cek']),true);
    $produk=RpuKatalog::getNamaProduk($keyword,$cek);
    $data='';
    foreach($produk as $row){
        $id = $row["id"];
        $kode = $row["kode_produk"];
        $harga = $row["harga_beli"];
        $data .= '<li class="list-group-item contsearch">
            <a href="javascript:void(0)" class="tdsearch nav-link" id="kode-'.$kode.'-'.$co.'-'.$harga.'">'.$row["nama"].'</a></li>';
    }
    echo $data;
} else if (isset($_POST['token']) && $_POST['token']=='nama_jual'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $co = $_POST['counter'];
    $cek = json_decode(stripslashes($_POST['cek']),true);
    $produk=RpuKatalog::getNamaJual($keyword,$cek);
    $data='';
    foreach($produk as $row){
        $qty_beli=RpuKatalog::qty_produk_beli($row["kode_produk"]);
        $qty_jual=RpuKatalog::qty_produk_jual($row["kode_produk"]);
        $qty_ready=$qty_beli-$qty_jual-$row['lainnya'];
        $id = $row["id"];
        $kode = $row["kode_produk"];
        $harga = $row["harga_jual"];
        $data .= '<li class="list-group-item contsearch">
            <a href="javascript:void(0)" class="tdsearch-inv nav-link" id="kode-'.$kode.'-'.$co.'-'.$harga.'-'.$qty_ready.'">
            '.$row["nama_jual"].'</a>
            </li>';
    }
    echo $data;
} else if (isset($_POST['token']) && $_POST['token']=='cari_po'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $nomor=RpuPembelian::getNomorPo($keyword);
    $data='';
    foreach($nomor as $row){
        $id = $row["nomor_po"];
        $data .= '<li class="list-group-item contsearch"><a href="javascript:void(0)" class="po-search nav-link">'.$id.'</a></li>';
    }
    echo $data;
}   else if (isset($_POST['token']) && $_POST['token']=='cari_inv'){
    $key = trim($_POST['query']);
    $keyword = strtoupper($key);
    $nomor=RpuPenjualan::getNomorInv($keyword);
    $data='';
    foreach($nomor as $row){
        $id = $row["nomor_inv"];
        $data .= '<li class="list-group-item contsearch"><a href="javascript:void(0)" class="inv-search nav-link">'.$id.'</a></li>';
    }
    echo $data;
} else if (isset($_POST['token']) && $_POST['token']=='cek_exist'){
    $key = trim($_POST['query']);
    $table = "tb_".$_POST['table'];
    $row=RpuKatalog::getNameOnTable($table,$key);
    if($row){
        echo '{"status":true}';
    } else {
        echo '{"status":false}';
    }
}
