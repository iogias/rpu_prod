<?php
require_once '../libs/config.php';
require_once '../libs/rpu_user.php';
//header('Content-type: application/json');
if (isset($_POST['token']) && $_POST['token']=='login'){
    $dt = $_POST['datalog'];
    parse_str($dt,$data);
    if (isset($data['pengguna'])&&isset($data['rahasia'])){
        $user = strtolower($data['pengguna']);
        $password = $data['rahasia'];
        $row = RpuUser::user_login($user,$password);
        if($row=='false'){
            echo '{"status":false}';
        } else{
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            echo '{"status":true}';
        }
    }

} else if(isset($_GET['token']) && $_GET['token']=='logout'){
    $data = $_GET['data'];
    $sql = "UPDATE tb_user SET last_login=NOW(),status_login=0 WHERE username='".$data."'";
    $param = array('username' => $data);
    DBHandler::cExecute($sql,$param);
    session_unset();
    session_destroy();
    echo '{"status":true}';
}  else if(isset($_POST['token']) && $_POST['token']=='staff'){
    $param = $_POST['args'];
    //$rows = RpuUser::get_user_staff($param);
    $rows = RpuUser::get_all_staff_join($param);
    foreach($rows as $user){
        $sub=array();
        //$iduser = ($user['id_user']=='')?'0':$user['id_user'];
        //$idstaff = $user['id_staff'];
        //$akses = ($user['username']==NULL)?'<span class="badge badge-danger">'.'No'.'</span>':'<span class="badge badge-primary">'.'Yes'.'</span>';
        $akses = ($user['akses']==1)?'<span class="badge badge-primary">'.'Yes'.'</span>':'<span class="badge badge-danger">'.'No'.'</span>';
        $status = ($user['status']==1)?'<span class="badge badge-success">'.'Aktif'.'</span>':'<span class="badge badge-secondary">'.'Non-Aktif'.'</span>';
        $disable = ($user['akses']==1)?'disabled':'enabled';
        $disable2 = ($user['akses']==0)?'disabled':'enabled';
        $sub[] = $user["nama"];
        $sub[] = $user["belakang"];
        $sub[] = $user["alamat"];
        $sub[] = $user["hp"];
        $sub[] = $user["group_staff"];
        $sub[] = '<div class="text-center">'.$akses.'</div>';
        $sub[] = '<div class="text-center">'.$status.'</div>';
        $sub[]='<div class="text-center">
                <button title="Tambah akses" type="button" class="btn btn-primary btn-sm tambah-akses-staff" id="tambah-'.$user['id'].'" '.$disable.'>
                <i class="fas fa-user-plus"></i></button>
                <button title="Edit akses" type="button" class="btn btn-danger btn-sm edit-user" id="uedit-'.$user['id'].'" '.$disable2.'>
                <i class="fas fa-user-edit"></i></button>
                <button title="Edit staff" type="button" class="btn btn-warning btn-sm edit-staff" id="edit-'.$user['id'].'">
                <i class="fas fa-pencil-alt"></i></button></div>';
        $data[]=$sub;
    }
    $res = array("data"=>$data);
    echo json_encode($res);
} else {
    exit;
}
