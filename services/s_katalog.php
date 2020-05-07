<?php
require_once '../libs/config.php';
require_once '../libs/rpu_katalog.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='supplier'){
    $arg = $_POST['token'];
    $table = "tb_".$_POST['token'];
    $param = $_POST['args'];
    $supplier=RpuKatalog::getAll($table,$param);
    $data=array();
    for ($i = 0; $i < count($supplier); $i++) {
        $sub=array();
        $status = ($supplier[$i]['status']==1) ? '<span class="badge badge-success">'.'Aktif'.'</span>' : '<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $sub[] = $supplier[$i]["nama"];
        $sub[] = $supplier[$i]["alamat"];
        $sub[] = $supplier[$i]["telepon"];
        $sub[] = $supplier[$i]["pic"];
        $sub[] = $supplier[$i]["hp"];
        $sub[] = $supplier[$i]["keterangan"];
        $sub[] = $status;
        $sub[]='<div class="text-center"><button type="button" data-toggle="modal" data-target="#modal-'.$arg.'" class="btn btn-warning btn-sm edit-'.$arg.'" id="edit-'.$supplier[$i]["id"].'">
                <i class="fas fa-pencil-alt mr-2"></i>Edit</button>
                <button type="button" class="btn btn-danger btn-sm del-'.$arg.'" id="del-'.$supplier[$i]["id"].'">
                <i class="fas fa-trash-alt mr-2"></i>Hapus</button></div>';
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);

} else if(isset($_POST['token']) && $_POST['token']=='customer'){
    $arg = $_POST['token'];
    $param = $_POST['args'];
    $customer=RpuKatalog::getAllCustomerJoinGroup($param);
    $data=array();
    for ($i = 0; $i < count($customer); $i++) {
        $sub=array();
        $tgl = strtotime($customer[$i]["tgl_daftar"]);
        $tgl_d = date('d-m-Y',$tgl);
        $status = ($customer[$i]['status']==1) ? '<span class="badge badge-success">'.'Aktif'.'</span>' : '<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $sub[] = $customer[$i]["nama"];
        $sub[] = $customer[$i]["alamat"];
        $sub[] = $customer[$i]["telepon"];
        $sub[] = $customer[$i]["hp"];
        $sub[] = $tgl_d;
        $sub[] = $customer[$i]["nama_g"];
        $sub[] = $status;
        $sub[]='<div class="text-center"><button type="button" data-toggle="modal" data-target="#modal-'.$arg.'" class="btn btn-warning btn-sm edit-'.$arg.'" id="edit-'.$customer[$i]["id"].'">
                <i class="fas fa-pencil-alt mr-2"></i>Edit</button>
                <button type="button" class="btn btn-danger btn-sm del-'.$arg.'" id="del-'.$customer[$i]["id"].'">
                <i class="fas fa-trash-alt mr-2"></i>Hapus</button></div>';
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);
} else if(isset($_POST['token']) && $_POST['token']=='outlet'){
    $arg = $_POST['token'];
    $param = $_POST['args'];
    $outlet=RpuKatalog::getAllOutletJoinCustomer($param);
    $data=array();
    for ($i = 0; $i < count($outlet); $i++) {
        $sub=array();
        $tgl = strtotime($outlet[$i]["tgl_daftar"]);
        $tgl_d = date('d-m-Y',$tgl);
        $status = ($outlet[$i]['status']==1) ? '<span class="badge badge-success">'.'Aktif'.'</span>' : '<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $sub[] = $outlet[$i]["nama"];
        $sub[] = $outlet[$i]["alamat"];
        $sub[] = $outlet[$i]["telepon"];
        $sub[] = $tgl_d;
        $sub[] = $outlet[$i]["nama_g"];
        $sub[] = $status;
        $sub[]='<div class="text-center"><button type="button" data-toggle="modal" data-target="#modal-'.$arg.'" class="btn btn-warning btn-sm edit-'.$arg.'" id="edit-'.$outlet[$i]["id"].'">
                <i class="fas fa-pencil-alt mr-2"></i>Edit</button>
                <button type="button" class="btn btn-danger btn-sm del-'.$arg.'" id="del-'.$outlet[$i]["id"].'">
                <i class="fas fa-trash-alt mr-2"></i>Hapus</button></div>';
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);
} else if(isset($_POST['token']) && $_POST['token']=='kategoriproduk'){
    $arg = $_POST['token'];
    $param = $_POST['args'];
    $katproduk=RpuKatalog::getAllKategoriProduk($param);
    $data=array();
    for ($i = 0; $i < count($katproduk); $i++) {
        $sub=array();
        $status = ($katproduk[$i]['status']==1) ? '<span class="badge badge-success">'.'Aktif'.'</span>' : '<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $sub[] = $katproduk[$i]["nama"];
        $sub[] = $katproduk[$i]["keterangan"];
        $sub[] = $status;
        $sub[]='<div class="text-center"><button type="button" data-toggle="modal" data-target="#modal-'.$arg.'" class="btn btn-warning btn-sm edit-'.$arg.'" id="edit-'.$katproduk[$i]["id"].'">
                <i class="fas fa-pencil-alt mr-2"></i>Edit</button>
                <button type="button" class="btn btn-danger btn-sm del-'.$arg.'" id="del-'.$katproduk[$i]["id"].'">
                <i class="fas fa-trash-alt mr-2"></i>Hapus</button></div>';
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);
} else if(isset($_POST['token']) && $_POST['token']=='produk'){
    $arg = $_POST['token'];
    $param = $_POST['arg'];
    $param2 = $_POST['arg2'];
    $produk=RpuKatalog::getAllProdukJoin($param,$param2);
    echo json_encode($produk);
} else if(isset($_POST['token']) && $_POST['token']=='customergroup'){
    $arg = $_POST['token'];
    $param = $_POST['args'];
    $table = "tb_".$_POST['token'];
    $custgroup=RpuKatalog::getAll($table,$param);
    $data=array();
    for ($i = 0; $i < count($custgroup); $i++) {
        $sub=array();
        $status = ($custgroup[$i]['status']==1) ? '<span class="badge badge-success">'.'Aktif'.'</span>' : '<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $sub[] = $custgroup[$i]["nama"];
        $sub[] = $custgroup[$i]["keterangan"];
        $sub[] = $status;
        $sub[]='<div class="text-center"><button type="button" data-toggle="modal" data-target="#modal-'.$arg.'" class="btn btn-warning btn-sm edit-'.$arg.'" id="edit-'.$custgroup[$i]["id"].'">
                <i class="fas fa-pencil-alt mr-2"></i>Edit</button>
                <button type="button" class="btn btn-danger btn-sm del-'.$arg.'" id="del-'.$custgroup[$i]["id"].'">
                <i class="fas fa-trash-alt mr-2"></i>Hapus</button></div>';
        $data[]=$sub;
    }
    $res = array(
        "data"=>$data
    );
    echo json_encode($res);
} else if (isset($_POST['token']) && $_POST['token']=='biaya'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $rows=RpuKatalog::getAllBiaya($awal,$akhir);
    echo json_encode($rows);
} else if (isset($_POST['token']) && $_POST['token']=='totalan_biaya'){
    $awal = ymd($_POST['awal']);
    $akhir = ymd($_POST['akhir']);
    $rows=RpuKatalog::get_all_totalan_biaya($awal,$akhir);
    $nominal = ($rows['nominal']==0)?'0':money_simple($rows['nominal']);
    echo '{"status":true,"nominal":"'.$nominal.'","countr":"'.$rows['countr'].'"}';
} else if(isset($_POST['token']) && $_POST['token']=='jenis_biaya'){
    $rows=RpuKatalog::getAllJenisBiaya();
    $data = array();
    foreach ($rows as $jb) {
        $sub=array();
        $status = ($jb['status']==1) ? '<span class="badge badge-success">'.'Aktif'.'</span>' : '<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $sub[] = '<div class="text-center">'.$jb["kode_kategori"].'</div>';
        $sub[] = '<div>'.$jb["nama"].'</div>';
        $sub[] = '<div class="text-center">'.$status.'</div>';
        $sub[]='<div class="text-center">
                <button type="button" data-toggle="modal" data-target="#modal-jb" class="btn btn-warning btn-sm edit-jb" id="edit-'.$jb["id"].'">
                <i class="fas fa-pencil-alt mr-2"></i>Edit</button></div>';
        $data[]=$sub;
    }
    $res = array("data"=>$data);
    echo json_encode($res);
} else if (isset($_POST['token']) && $_POST['token']=='stok'){
    // $awal = ymd($_POST['awal']);
    // $akhir = ymd($_POST['akhir']);
    $rows=RpuKatalog::getAllStokProdukInContainer();
    echo json_encode($rows);
} else if (isset($_POST['token']) && $_POST['token']=='container'){
    $rows=RpuKatalog::getAll('tb_container');
    $data = array();
    foreach ($rows as $container) {
        $sub=array();
        $sub[] = '<div class="text-center">'.$container["id"].'</div>';
        $sub[] = '<div contenteditable class="text-center update-fr" data-column="'.$container["nama"].'" data-id="'.$container["id"].'">'.$container["nama"].'</div>';
        $sub[]='<div class="text-right">
                <button type="button" class="btn-del-fr btn btn-danger btn-sm" title="Hapus freezer" data-id="'.$container["id"].'">
                <i class="fas fa-trash-alt"></i></button>
                </div>';
        $data[]=$sub;
    }
    $res = array("data"=>$data);
    echo json_encode($res);

} else {
    die('NO DATA PASSED');
}
