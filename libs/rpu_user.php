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

    public static function get_all_staff_join(){
        $sql = "SELECT s.*,g.nama as group_staff FROM tb_staff s JOIN tb_staff_group g ON g.id=s.id_staff_group";
        return DbHandler::getAll($sql);
    }

    public static function get_user_staff($arg='99'){
        // $sql = "SELECT u.id AS id_user,s.id AS id_staff,u.id_group_user,s.id_staff_group,s.status,
        //         s.nama,s.hp,u.username,gs.nama AS group_staff,gu.nama AS group_user
        //         FROM tb_user u
        //         RIGHT JOIN tb_staff s ON s.id=u.id_staff
        //         LEFT JOIN tb_user_group gu ON gu.id=u.id_group_user
        //         LEFT JOIN tb_staff_group gs ON gs.id=s.id_staff_group\n";
        // if($arg!='99'){
        //     $sql .= "WHERE s.status='".$arg."'\n";
        // }
        // $sql .="ORDER BY s.nama ASC";
        // $param = array('status'=>$arg);
        // return DbHandler::getAll($sql,$param);
    }

    public static function get_staff_byId($id){
        $sql = "SELECT s.*,g.nama as group_staff FROM tb_staff s JOIN tb_staff_group g ON g.id=s.id_staff_group
                WHERE s.id='".$id."'";
        $param = array('id'=>$id);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_user_byId($id,$ids){
        $sql = "SELECT u.id AS id_user,u.username,u.id_group_user,u.id_staff,u.status AS status_user,
                s.nama,s.belakang,s.alamat,s.hp,s.id_staff_group,s.status AS status_staff
                FROM tb_user u
                RIGHT JOIN tb_staff s ON s.id=u.id_staff
                LEFT JOIN tb_user_group gu ON gu.id=u.id_group_user
                LEFT JOIN tb_staff_group gs ON gs.id=s.id_staff_group
                WHERE u.id='".$id."' AND u.id_staff='".$ids."' GROUP BY u.id";
        $params = array('id'=>$id,'id_staff'=>$ids);
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

    public static function new_user($data,$id) {
        $uname = strtolower(trim($data['user-name']));
        $pwd = self::pwd_hash(trim($data['password-user']));
        $sql = "INSERT INTO tb_user(id,username,password,id_group_user,id_staff,status)
            VALUES(NULL,
            '".$uname."',
            '".$pwd."',
            '".$data['group-user']."',
            '".$id."',
            '".$data['status-user']."')";
        $params = array(
                    'username'=>$uname,
                    'password'=>$pwd,
                    'id_group_user'=>$data['group-user'],
                    'id_staff'=>$id,
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

    public static function update_user($data,$id) {
        $pwd = self::pwd_hash(trim($data['password-user']));
        $sql = "UPDATE tb_user SET
                password='".$pwd."',
                id_group_user='".$data['group-user']."',
                id_staff='".$data['group-staff']."',
                status='".$data['status-user']."' WHERE id='".$id."'";
        $params = array(
                    'password'=>$pwd,
                    'id_group_user'=>$data['group-user'],
                    'id_staff'=>$data['group-staff'],
                    'status'=>$data['status-user'],
                    'id'=>$id
                );
        return DbHandler::cExecute($sql, $params);
    }


}
