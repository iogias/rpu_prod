<?php
require_once '../libs/config.php';
require_once '../libs/rpu_katalog.php';
header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='new_supplier'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_supplier($data);
    $sql="SELECT nama FROM tb_supplier ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_kategoriproduk'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_kategoriproduk($data);
    $sql="SELECT nama FROM tb_kategori_produk ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }
}  else if(isset($_POST['token']) && $_POST['token']=='new_customer'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_customer($data);
    $sql="SELECT nama FROM tb_customer ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_outlet'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_outlet($data);
    $sql="SELECT nama FROM tb_outlet ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_customergroup'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_customergroup($data);
    $sql="SELECT nama FROM tb_customergroup ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }

} else if(isset($_POST['token']) && $_POST['token']=='new_produk'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_produk($data);
    $sql="SELECT nama FROM tb_produk ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_sj'){
    $dt = $_POST['data'];
    $nmr = $_POST['nomor'];
    parse_str($dt,$data);
    $sql="SELECT nomor_sj FROM tb_surat_jalan WHERE nomor_inv='".$nmr."'";
    $param = array('nomor_inv'=>$nmr);
    $sj =  DbHandler::getOne($sql,$param);
    if($sj){
        echo '{"status":false,"sj":"'.$sj.'"}';
    } else {
        RpuKatalog::new_sj($data,$nmr);
        echo '{"status":true}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_biaya'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_biaya($data);
    echo '{"status":true}';

} else if(isset($_POST['token']) && $_POST['token']=='new_staff'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuUser::new_staff($data);
    $sql="SELECT nama FROM tb_staff ORDER BY id DESC LIMIT 1";
    $nama = DbHandler::getOne($sql);
    if($nama){
       echo '{"status":true,"nama":"'.$nama.'"}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_user'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuUser::new_user($data);
    $sql="SELECT username FROM tb_user ORDER BY id DESC LIMIT 1";
    $nama = DbHandler::getOne($sql);
    if($nama){
        RpuUser::update_status_akses_staff($data['id-user']);
        echo '{"status":true,"nama":"'.$nama.'"}';
    }

} else if(isset($_POST['token']) && $_POST['token']=='akses_staff'){
    $ids = $_POST['data'];
    $sql="SELECT id,nama FROM tb_staff WHERE id='".$ids."'";
    $nama = DbHandler::getRow($sql);
    if($nama){
       echo '{"status":true,"staff_id":"'.$nama['id'].'","staff_nama":"'.$nama['nama'].'"}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='new_jb'){
    $dt = $_POST['data'];
    parse_str($dt,$data);
    RpuKatalog::new_jenis_biaya($data);
    $sql="SELECT nama FROM tb_jenis_biaya ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true,"nama":"'.$nama.'"}';
    }
}  else if(isset($_POST['token']) && $_POST['token']=='frezzer'){
    $idf = $_POST['idf'];
    $nama = $_POST['nama'];
    RpuKatalog::new_frezzer($idf,$nama);
    $sql="SELECT nama FROM tb_container ORDER BY id DESC LIMIT 1";
    $nama =  DbHandler::getOne($sql);
    if ($nama){
        echo '{"status":true}';
    }
} else if(isset($_POST['token']) && $_POST['token']=='produk_frezzer'){
    $idf = $_POST['idf'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty'];
    RpuKatalog::insert_frezzer($idf,$idp,$qty);
    echo '{"status":true}';
}
else {
    die ('NO DATA PASSED');
}