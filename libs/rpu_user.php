<?php
if (!defined('WEB_ROOT')) {
    exit;
}

class RpuUser {
    private static function cek_user($user,$pass){
        $sql = "SELECT password FROM tb_user WHERE username='".$user."' AND status=1";
        $param = array('username'=>$user);
        $password = DbHandler::getOne($sql, $param);
        if(password_verify($pass, $password)){
            return $password;
        } else {
            return FALSE;
        }
    }

    public static function cek_user_exist($username=null,$id=null){
        $username = strtolower($username);
        $sql = "SELECT id,username FROM tb_user WHERE username='".$username."' OR id='".$id."'";
        $params = array('id'=>$id,'username'=>$username);
        return DbHandler::getRow($sql,$params);
    }

    public static function user_login($user,$pass){
        $valid_p = self::cek_user($user,$pass);
        if($valid_p){
            $update = "UPDATE tb_user SET status_login=1 WHERE username='".$user."'";
            $param_u = array('username' =>$user);
            DbHandler::cExecute($update,$param_u);
            $sql = "SELECT * FROM tb_user WHERE username='".$user."' AND password='".$valid_p."'";
            $param = array('username'=>$user,'password'=>$valid_p);
            return DbHandler::getRow($sql, $param);
        } else {
            $msg = "false";
            return $msg;
        }
    }

    public static function get_user($id){
        $sql = "SELECT * FROM tb_user WHERE id='".$id."'";
        $param = array('id'=>$id);
        return DbHandler::getRow($sql, $param);
    }

    public static function get_all_staff(){
        $sql = "SELECT * FROM tb_staff";
        return DbHandler::getAll($sql);
    }

    public static function get_staff_group(){
        $sql = "SELECT * FROM tb_staff_group WHERE status=1";
        return DbHandler::getAll($sql);
    }

    public static function get_user_group(){
        $sql = "SELECT * FROM tb_user_group WHERE status=1";
        return DbHandler::getAll($sql);
    }

    public static function get_all_staff_join($arg='99'){
        $sql = "SELECT s.*,u.id AS id_user,g.nama as group_staff,u.status AS u_status
                FROM tb_staff s
                JOIN tb_staff_group g ON g.id=s.id_staff_group
                LEFT JOIN tb_user u ON u.id_staff=s.id\n";
        if($arg!='99'){
            $sql .= "WHERE s.status='".$arg."'";
        }
        $param = array('status'=>$arg);
        return DbHandler::getAll($sql,$param);
    }

    public static function update_status_akses_staff($id){
        $sql = "UPDATE tb_staff SET
                akses=1
                WHERE id='".$id."'";
        $params = array(
                    'id'=>$id
                );
        return DbHandler::cExecute($sql, $params);

    }

    public static function get_staff_byId($id){
        $sql = "SELECT s.*,g.nama as group_staff FROM tb_staff s JOIN tb_staff_group g ON g.id=s.id_staff_group
                WHERE s.id='".$id."'";
        $param = array('id'=>$id);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_user_byId($id){
        $sql = "SELECT u.*,g.nama AS group_user,s.nama AS nama_staff
                FROM tb_user u
                JOIN tb_user_group g ON g.id=u.id_group_user
                JOIN tb_staff s ON s.id=u.id_staff
                WHERE u.id='".$id."'";
        $params = array('id'=>$id);
        return DbHandler::getRow($sql,$params);
    }

    public static function new_staff($data) {
        $depan = strtoupper($data['nama-depan']);
        $belakang = strtoupper($data['nama-belakang']);
        $sql = "INSERT INTO tb_staff(id,nama,belakang,alamat,hp,id_staff_group,status)
            VALUES(NULL,
            '".$depan."',
            '".$belakang."',
            '".$data['alamat-staff']."',
            '".$data['hp-staff']."',
            '".$data['group-staff']."',
            '".$data['status-staff']."')";
        $params = array(
                    'nama'=>$depan,
                    'belakang'=>$belakang,
                    'alamat'=>$data['alamat-staff'],
                    'hp'=>$data['hp-staff'],
                    'id_staff_group'=>$data['group-staff'],
                    'status'=>$data['status-staff']
                );
        return DbHandler::cExecute($sql, $params);
    }

    private static function pwd_hash($string){
       $pass = password_hash($string, PASSWORD_DEFAULT);
       return $pass;
    }

    public static function new_user($data) {
        $uname = strtolower(trim($data['user-name']));
        $pwd = self::pwd_hash(trim($data['password-user']));
        $sql = "INSERT INTO tb_user(id,username,password,id_group_user,id_staff,status)
            VALUES(NULL,
            '".$uname."',
            '".$pwd."',
            '".$data['group-user']."',
            '".$data['id-user']."',
            '".$data['status-user']."')";
        $params = array(
                    'username'=>$uname,
                    'password'=>$pwd,
                    'id_group_user'=>$data['group-user'],
                    'id_staff'=>$data['id-user'],
                    'status'=>$data['status-user']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_staff($data) {
        $depan = strtoupper($data['nama-depan']);
        $belakang = strtoupper($data['nama-belakang']);
        $sql = "UPDATE tb_staff SET
                nama='".$depan."',
                belakang='".$belakang."',
                alamat='".$data['alamat-staff']."',
                hp='".$data['hp-staff']."',
                id_staff_group='".$data['group-staff']."',
                status='".$data['status-staff']."'
                WHERE id='".$data['id-staff']."'";
        $params = array(
                    'nama'=>$depan,
                    'belakang'=>$belakang,
                    'alamat'=>$data['alamat-staff'],
                    'hp'=>$data['hp-staff'],
                    'id_staff_group'=>$data['group-staff'],
                    'status'=>$data['status-staff'],
                    'id'=>$data['id-staff']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_user($data) {
        $pwd = self::pwd_hash(trim($data['password-user-baru']));
        $sql = "UPDATE tb_user SET
                password='".$pwd."',
                id_group_user='".$data['group-user']."',
                status='".$data['status-user']."' WHERE id='".$data['id-user']."'";
        $params = array(
                    'password'=>$pwd,
                    'id_group_user'=>$data['group-user'],
                    'status'=>$data['status-user'],
                    'id'=>$data['id-user']
                );
        return DbHandler::cExecute($sql, $params);
    }


}
