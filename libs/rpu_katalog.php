<?php
if (!defined('WEB_ROOT')) {
    exit;
}

class RpuKatalog {

    public static function selectRowById($table,$id){
        $sql = "SELECT * FROM $table WHERE id ='" . $id . "'";
        $param = array('id' => $id);
        return DbHandler::getRow($sql, $param);
    }

    public static function delRowById($table,$id){
        $sql = "DELETE FROM $table WHERE id ='" . $id . "'";
        $param = array('id' => $id);
        DbHandler::cExecute($sql,$param);
    }

    public static function getAll($table,$arg='99'){
        $sql = "SELECT * FROM $table\n";
        if($arg!='99'){
            $sql .= "WHERE status='".$arg."'";
        }
        $param = array('status'=>$arg);
        return DbHandler::getAll($sql,$param);
    }

    public static function getLastId($table){
        $sql = "SELECT MAX(id) FROM $table";
        return DbHandler::getOne($sql);
    }

    public static function getAllOutletJoinCustomer($arg='99'){
        $sql = "SELECT c.*,cg.nama AS nama_g FROM tb_outlet c JOIN tb_customer cg ON cg.id=c.customer_id\n";
        if($arg!='99'){
            $sql .="WHERE c.status='".$arg."'";
        }
        $param = array('status'=>$arg);
        return DbHandler::getAll($sql,$param);
    }

    public static function getAllModulInc(){
        $sql = "SELECT * FROM tb_modul_inc WHERE status=1";
        return DbHandler::getAll($sql);
    }
    public static function getAllSupplier(){
        $sql = "SELECT * FROM tb_supplier ORDER BY id DESC";
        return DbHandler::getAll($sql);
    }
    public static function getNamaSupplier($nama){
        $sql = "SELECT id,nama FROM tb_supplier WHERE nama LIKE '%$nama%' AND status=1";
        $param = array('nama' => $nama);
        return DbHandler::getAll($sql,$param);
    }

    public static function getNamaOutlet($nama){
        $sql = "SELECT id,nama FROM tb_outlet WHERE nama LIKE '%$nama%' AND status=1";
        $param = array('nama' => $nama);
        return DbHandler::getAll($sql,$param);
    }

    public static function getNamaStaff($nama){
        $sql = "SELECT id,nama FROM tb_staff WHERE nama LIKE '%$nama%' AND status=1";
        $param = array('nama' => $nama);
        return DbHandler::getAll($sql,$param);
    }

    public static function getNamaProduk($nama,$cek){
        $sql = "SELECT p.id,p.nama,p.kode_produk,p.harga_beli
        FROM tb_produk p WHERE p.nama LIKE '%$nama%' AND p.status=1\n";
        if($cek!=NULL){//if(count($cek)!=0){
            $sql .="AND p.kode_produk NOT IN ('".implode("','",$cek)."')";
        }
        $params = array('nama'=>$nama);
        return DbHandler::getAll($sql,$params);
    }

    public static function getNamaJual($nama,$cek){
        $sql = "SELECT p.id,p.nama_jual,p.kode_produk,p.harga_jual,p.stok_ready,p.terjual,p.lainnya FROM tb_produk p
                WHERE p.nama_jual LIKE '%$nama%' AND p.nama_jual!='' AND p.status=1\n";
        if($cek!=NULL){//if(count($cek)!=0){
            $sql .="AND p.kode_produk NOT IN ('".implode("','",$cek)."')";
        }
        $params = array('nama_jual'=>$nama);
        return DbHandler::getAll($sql,$params);
    }

    public static function getIdProduk($kode){
        $sql = "SELECT id,nama,kode_produk FROM tb_produk WHERE kode_produk LIKE '%$kode%' AND status=1";
        $param = array('kode_produk' => $kode);
        return DbHandler::getAll($sql,$param);
    }

    public static function getSupplierById($id){
        $sql = "SELECT * FROM tb_supplier WHERE id ='" . $id . "'";
        $param = array('id' => $id);
        return DbHandler::getRow($sql, $param);
    }

    public static function getAllCustomer(){
        $sql = "SELECT * FROM tb_customer";
        return DbHandler::getAll($sql);
    }

    public static function getAllCustomerJoinGroup($arg='99'){
        $sql = "SELECT c.*,cg.nama AS nama_g FROM tb_customer c JOIN tb_customergroup cg ON cg.id=c.customer_group_id\n";
        if($arg!='99'){
            $sql .= "WHERE c.status='".$arg."'";
        }
        $param = array('status'=>$arg);
        return DbHandler::getAll($sql,$param);
    }
    public static function getAllKategoriProduk($arg='99'){
        $sql = "SELECT * FROM tb_kategori_produk\n";
        if($arg!='99'){
            $sql .= "WHERE status='".$arg."'";
        }
        $param = array('status'=>$arg);
        return DbHandler::getAll($sql,$param);
    }

    public static function getAllProdukJoin($arg='99',$arg2='00'){
        $sql = "SELECT pr.id,pr.kode_produk,pr.nama,pr.nama_jual,qbeli.beli AS qty_beli,COALESCE(qjual.jual,0) AS qty_jual,
                pr.harga_beli,pr.harga_jual,pr.hpp,pr.lainnya,kg.nama AS kategori,pr.status,qbeli.nomor_po,
                (qbeli.beli-COALESCE(qjual.jual,0)-COALESCE(pr.lainnya,0)) AS stok_ready
                FROM
                (SELECT SUM(b.produk_qty) AS beli,b.kode_produk,b.harga_beli,b.nomor_po FROM tb_pembelian_detail b GROUP BY b.kode_produk) AS qbeli
                LEFT JOIN
                (SELECT SUM(COALESCE(j.produk_qty,0)) AS jual,j.kode_produk FROM tb_penjualan_detail j GROUP BY j.kode_produk) AS qjual
                ON qbeli.kode_produk=qjual.kode_produk
                LEFT JOIN tb_produk pr ON pr.kode_produk=qbeli.kode_produk
                JOIN tb_kategori_produk kg ON kg.id=pr.kategoriproduk_id\n";
        if($arg2!='00' && $arg!='99'){
            $sql .= "WHERE pr.kategoriproduk_id='".(int)$arg2."'\n";
            $sql .= "AND pr.status='".(int)$arg."'\n";
        } else if ($arg2!='00' || $arg!='99'){
            $sql .= "WHERE pr.kategoriproduk_id='".(int)$arg2."'\n";
            $sql .= "OR pr.status='".(int)$arg."'\n";
        }
        // if($arg!='99'){
        //     $sql .= "AND pr.status='".(int)$arg."'\n";
        // }
        $sql .= "ORDER BY pr.nama ASC";
        $params = array('status'=>$arg,'kategoriproduk_id'=>$arg2);
        return DbHandler::getAll($sql,$params);
    }

    public static function getAllProdukJoin2(){
        $sql = "SELECT pr.id,pr.kode_produk,pr.nama,pr.nama_jual,pr.harga_beli,pr.status,kg.nama AS kategori
                FROM tb_produk pr
                JOIN tb_kategori_produk kg ON kg.id=pr.kategoriproduk_id ORDER BY pr.nama ASC";
        //$params = array('status'=>$arg,'kategoriproduk_id'=>$arg2);
        return DbHandler::getAll($sql);
    }

    public static function getAllProdukJoinById($id){
        $sql = "SELECT p.id,p.kode_produk,p.nama,p.nama_jual,p.harga_beli,p.hpp,p.kategoriproduk_id,
                p.harga_jual,p.lainnya,p.status,s.nama AS supplier,k.nama AS kategori\n";
        $sql .= "FROM tb_produk p JOIN tb_pembelian_detail d ON d.kode_produk=p.kode_produk\n";
        $sql .= "LEFT JOIN tb_kategori_produk k ON k.id=p.kategoriproduk_id\n";
        $sql .= "LEFT JOIN tb_pembelian b ON b.nomor_po=d.nomor_po\n";
        $sql .= "LEFT JOIN tb_supplier s ON s.id=b.id_supplier\n";
        $sql .= "WHERE p.id='".$id."' GROUP BY p.kode_produk";
        $param = array('id'=>$id);
        return DbHandler::getRow($sql,$param);
    }

    public static function getKategoriProdukById($id){
        $sql = "SELECT * FROM tb_kategori_produk WHERE id ='" . $id . "'";
        $param = array('id' => $id);
        return DbHandler::getRow($sql, $param);
    }
    public static function new_supplier($data) {
        $nama = strtoupper($data['nama']);
        $sql = "INSERT INTO tb_supplier(id,nama,alamat,telepon,pic,hp,keterangan,tgl_daftar)
            VALUES(NULL,
            '".$nama."',
            '".$data['alamat']."',
            '".$data['telepon']."',
            '".$data['pic']."',
            '".$data['hp']."',
            '".$data['keterangan']."',
            CURRENT_DATE())";
        $params = array(
                    'nama'=>$nama,
                    'alamat'=>$data['alamat'],
                    'telepon'=>$data['telepon'],
                    'pic'=>$data['pic'],
                    'hp'=>$data['hp'],
                    'keterangan'=>$data['keterangan']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_supplier($data) {
        $nama = strtoupper($data['nama']);
        $id = (int) $data['id-supplier'];
        $sql = "UPDATE tb_supplier SET nama = '".$nama."',
            alamat = '".$data['alamat']."',
            telepon = '".$data['telepon']."',
            pic = '".$data['pic']."',
            hp = '".$data['hp']."',
            keterangan = '".$data['keterangan']."',
            status = '".(int)$data['status']."'
            WHERE id ='".$id."'";
        $params = array(
                    'id'=>$id,
                    'nama'=>$nama,
                    'alamat'=>$data['alamat'],
                    'telepon'=>$data['telepon'],
                    'pic'=>$data['pic'],
                    'hp'=>$data['hp'],
                    'keterangan'=>$data['keterangan'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function new_kategoriproduk($data) {
        $nama = strtoupper($data['nama']);
        $sql = "INSERT INTO tb_kategori_produk(id,nama,keterangan,status)
            VALUES(NULL,
            '".$nama."',
            '".$data['keterangan']."',
            '".(int)$data['status']."')";
        $params = array(
                    'nama'=>$nama,
                    'keterangan'=>$data['keterangan'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_kategoriproduk($data) {
        $nama = strtoupper($data['nama']);
        $sql = "UPDATE tb_kategori_produk
                SET nama = '".$nama."',keterangan = '".$data['keterangan']."',status = '".(int)$data['status']."'
                WHERE id = '".(int)$data['id-kategoriproduk']."'";
        $params = array(
                    'id'=>$data['id-kategoriproduk'],
                    'nama'=>$nama,
                    'keterangan'=>$data['keterangan'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function new_customer($data) {
        $nama = strtoupper($data['nama']);
        $tg = date('Y-m-d',strtotime($data['tgl_daftar']));
        $sql = "INSERT INTO tb_customer(id,nama,alamat,telepon,hp,tgl_daftar,customer_group_id,status)
            VALUES(NULL,
            '".$nama."',
            '".$data['alamat']."',
            '".$data['telepon']."',
            '".$data['hp']."',
            '".$tg."',
            '".(int)$data['customer_group_id']."',
            '".(int)$data['status']."')";
        $params = array(
                    'nama'=>$nama,
                    'alamat'=>$data['alamat'],
                    'telepon'=>$data['telepon'],
                    'hp'=>$data['hp'],
                    'tgl_daftar'=>$tg,
                    'customer_group_id'=>$data['customer_group_id'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_customer($data) {
        $nama = strtoupper($data['nama']);
        $tg = date('Y-m-d',strtotime($data['tgl_daftar']));
        $sql = "UPDATE tb_customer
                SET nama = '".$nama."',
                alamat = '".$data['alamat']."',
                telepon = '".$data['telepon']."',
                hp = '".$data['hp']."',
                tgl_daftar = '".$tg."',
                customer_group_id = '".(int)$data['customer_group_id']."',
                status = '".(int)$data['status']."'
                WHERE id = '".(int)$data['id-customer']."'";

        $params = array(
                    'id'=>$data['id-customer'],
                    'nama'=>$nama,
                    'alamat'=>$data['alamat'],
                    'telepon'=>$data['telepon'],
                    'hp'=>$data['hp'],
                    'tgl_daftar'=>$tg,
                    'customer_group_id'=>$data['customer_group_id'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function new_outlet($data) {
        $nama = strtoupper($data['nama']);
        $tg = date('Y-m-d',strtotime($data['tgl_daftar']));
        $sql = "INSERT INTO tb_outlet(id,nama,alamat,telepon,tgl_daftar,customer_id,status)
            VALUES(NULL,
            '".$nama."',
            '".$data['alamat']."',
            '".$data['telepon']."',
            '".$tg."',
            '".(int)$data['customer_id']."',
            '".(int)$data['status']."')";
        $params = array(
                    'nama'=>$nama,
                    'alamat'=>$data['alamat'],
                    'telepon'=>$data['telepon'],
                    'tgl_daftar'=>$tg,
                    'customer_id'=>$data['customer_id'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_outlet($data) {
        $nama = strtoupper($data['nama']);
        $tg = date('Y-m-d',strtotime($data['tgl_daftar']));
        $sql = "UPDATE tb_outlet
                SET nama = '".$nama."',
                alamat = '".$data['alamat']."',
                telepon = '".$data['telepon']."',
                tgl_daftar = '".$tg."',
                customer_id='".$data['customer_id']."',
                status = '".(int)$data['status']."'
                WHERE id = '".(int)$data['id-outlet']."'";

        $params = array(
                    'id'=>$data['id-outlet'],
                    'nama'=>$nama,
                    'alamat'=>$data['alamat'],
                    'telepon'=>$data['telepon'],
                    'tgl_daftar'=>$tg,
                    'customer_id'=>$data['customer_id'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function new_customergroup($data) {
        $nama = strtoupper($data['nama']);
        $sql = "INSERT INTO tb_customergroup(id,nama,status,keterangan)
            VALUES(NULL,
            '".$nama."',
            '".(int)$data['status']."',
            '".$data['keterangan']."')";
        $params = array(
                    'nama'=>$nama,
                    'status'=>$data['status'],
                    'keterangan'=>$data['keterangan']
                );
        return DbHandler::cExecute($sql, $params);
    }

    private static function gen_kode_produk($productName,$kategori,$productID){
        $productCode = '';
        $productID = $productID + 1;
        $productID = self::enol_pad($productID);
        $y = explode(' ',$productName);
            foreach($y AS $k){
                $productCode .= strtoupper(substr($k,0,1));
            }
        return $productCode.$kategori.$productID;
    }

    private static function enol_pad($num, $zerofill = 4) {
        return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
    }

    public static function new_produk($data) {
        $nama = strtoupper($data['nama-produk-po']);
        $idx="SELECT id FROM tb_produk ORDER BY id DESC LIMIT 1";
        $id =  DbHandler::getOne($idx);
        $kode = self::gen_kode_produk($nama,(int)$data['kategori-produk-po'],$id);
        $hargabeli=to_int_koma($data['hargabeli-produk-po']);
        $sql = "INSERT INTO tb_produk(id,kode_produk,nama,kategoriproduk_id,harga_beli,status)
            VALUES(NULL,
            '".$kode."',
            '".$nama."',
            '".(int)$data['kategori-produk-po']."',
            '".$hargabeli."',
            '".(int)$data['status-produk-po']."')";
        $params = array(
                    'kode_produk'=>$kode,
                    'nama'=>$nama,
                    'kategoriproduk_id'=>$data['kategori-produk-po'],
                    'harga_beli'=>$hargabeli,
                    'status'=>$data['status-produk-po']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_customergroup($data) {
        $nama = strtoupper($data['nama']);
        $sql = "UPDATE tb_customergroup
                SET nama = '".$nama."',keterangan = '".$data['keterangan']."',status = '".(int)$data['status']."'
                WHERE id = '".(int)$data['id-customergroup']."'";

        $params = array(
                    'id'=>$data['id-customergroup'],
                    'nama'=>$nama,
                    'keterangan'=>$data['keterangan'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    private static function cek_stok_produk($id){
        $sql = "SELECT stok_ready,terjual,lainnya FROM tb_produk WHERE kode_produk='".$id."'";
        $params = array('kode_produk'=>$id);
        return DbHandler::getRow($sql,$params);
    }

    public static function update_stok_produk($value){
        $harga_items = to_int_koma($value['harga']);
        $qty = self::cek_stok_produk($value['kode']);
        $qty = $qty['stok_ready'] + (int)$value['qty'];
        $sql = "UPDATE tb_produk SET harga_beli='".$harga_items."', stok_ready='".$qty."' WHERE kode_produk='".$value['kode']."'";
        $params = array(
                    'harga_beli'=>$harga_items,
                    'stok_ready'=>$qty,
                    'kode_produk'=>$value['kode']
                );
        return DbHandler::cExecute($sql,$params);
    }

    public static function update_stok_produk_jual($value){
        $row = self::cek_stok_produk($value['kode']);
        $qty = $row['stok_ready'];
        $terjual = $row['terjual'];
        $terjual = $terjual + (int)$row['terjual'];
        if($qty!=0){
            $qty = $qty - (int)$value['qty'];
        }
        $sql = "UPDATE tb_produk SET stok_ready='".$qty."',terjual='".$terjual."' WHERE kode_produk='".$value['kode']."'";
        $params = array(
                    'stok_ready'=>$qty,
                    'terjual'=>$terjual,
                    'kode_produk'=>$value['kode']
                );
        return DbHandler::cExecute($sql,$params);
    }

    public static function update_produk($data) {
        $nama = strtoupper($data['nama-jual']);
        $harga = to_int_koma($data['harga-jual']);
        $harga_beli = to_int_koma($data['harga-beli']);
        $hpp = to_int_koma($data['hpp']);
        $tambah = ($data['stok-lain-tambah']=='')?0:$data['stok-lain-tambah'];
        $row = self::cek_stok_produk($data['kode']);
        $lainnya = $row['lainnya']+$tambah;
        $sql = "UPDATE tb_produk
                SET nama_jual='".$nama."',
                harga_beli ='".$harga_beli."',
                harga_jual ='".$harga."',
                hpp ='".$hpp."',
                stok_ready ='".$data['stok-ready']."',
                terjual ='".$data['stok-terjual']."',
                lainnya ='".$lainnya."',
                kategoriproduk_id='".(int)$data['kategoriproduk-id']."',
                status = '".(int)$data['status']."'
                WHERE id = '".(int)$data['id-produk']."'";
        $params = array(
                    'id'=>$data['id-produk'],
                    'nama_jual'=>$nama,
                    'stok_ready' =>$data['stok-ready'],
                    'terjual' =>$data['stok-terjual'],
                    'lainnya' =>$lainnya,
                    'harga_beli'=>$harga_beli,
                    'harga_jual'=>$harga,
                    'hpp'=>$hpp,
                    'kategoriproduk_id'=>$data['kategoriproduk-id'],
                    'status'=>$data['status']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_sts_produk($id,$sts) {
        $sql = "UPDATE tb_produk SET
                status = '".$sts."'
                WHERE id = '".$id."'";
        $params = array(
                    'id'=>$id,
                    'status'=>$sts
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function qty_produk_beli($kode){
        $sql = "SELECT SUM(produk_qty) AS produk_qty FROM tb_pembelian_detail WHERE kode_produk='".$kode."'";
        $param = array('kode_produk'=>$kode);
        return DbHandler::getOne($sql,$param);
    }

    public static function qty_produk_jual($kode){
        $sql = "SELECT SUM(produk_qty) AS produk_qty FROM tb_penjualan_detail WHERE kode_produk='".$kode."'";
        $param = array('kode_produk'=>$kode);
        return DbHandler::getOne($sql,$param);
    }

    public static function new_sj($data,$nomor) {
        $nsj = explode('-', $nomor);
        $pies = array('SJL',$nsj[1],$nsj[2]);
        $nosj = implode('-', $pies);
        $ekspedisi = strtoupper($data['nama-ekspedisi']);
        $tgl_kirim = ymd($data['tgl-kirim']);
        $penerima = '';
        $sql = "INSERT INTO tb_surat_jalan(id,nomor_sj,nomor_inv,tgl_kirim,ekspedisi)
                VALUES(NULL,
                '".$nosj."',
                '".$nomor."',
                '".$tgl_kirim."',
                '".$ekspedisi."')";
        $params = array(
                    'nomor_sj'=>$nosj,
                    'nomor_inv'=>$nomor,
                    'tgl_kirim'=>$tgl_kirim,
                    'ekspedisi'=>$ekspedisi,
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function get_settings(){
        $sql = "SELECT * FROM tb_settings";
        return DbHandler::getRow($sql);
    }

    public static function get_aplikasi(){
        $sql = "SELECT * FROM tb_aplikasi";
        return DbHandler::getRow($sql);
    }

    public static function update_settings($data){
        $sql = "UPDATE tb_settings
                SET nama_usaha='".$data['nama-usaha']."',
                alamat_usaha='".$data['alamat-usaha']."',
                tlp_usaha='".$data['tlp-usaha']."',
                email='".$data['email-usaha']."',
                nama_pemilik='".$data['nama-pemilik']."',
                alamat_pemilik='".$data['alamat-pemilik']."',
                hp='".$data['hp-pemilik']."',
                npwp='".$data['npwp']."',
                bank='".$data['bank-usaha']."',
                no_rek ='".$data['no-rek']."',
                an_rek='".$data['atas-nama']."'
                WHERE id = '".$data['id-usaha']."'";
        $params = array(
                    'nama_usaha'=>$data['nama-usaha'],
                    'alamat_usaha'=>$data['alamat-usaha'],
                    'tlp_usaha'=>$data['tlp-usaha'],
                    'email'=>$data['email-usaha'],
                    'nama_pemilik'=>$data['nama-pemilik'],
                    'alamat_pemilik'=>$data['alamat-pemilik'],
                    'hp'=>$data['hp-pemilik'],
                    'npwp'=>$data['npwp'],
                    'bank'=>$data['bank-usaha'],
                    'no_rek'=>$data['no-rek'],
                    'an_rek'=>$data['atas-nama'],
                    'id'=>$data['id-usaha']
                );
        return DbHandler::cExecute($sql,$params);
    }

    public static function getAllBiaya($awal,$akhir){
        $sql = "SELECT b.*,j.nama AS nama_biaya,k.nama AS nama_kategori,
                '$awal' AS tgl_awal, '$akhir' AS tgl_akhir
                FROM tb_biaya b
                JOIN tb_jenis_biaya j ON j.id=b.jenis_id
                JOIN tb_kategori_biaya k ON k.kode=b.kode_kategori
                WHERE b.tanggal BETWEEN '".$awal."' AND '".$akhir."'";
        $param = array('tanggal'=>$awal,'tanggal'=>$akhir);
        return DbHandler::getAll($sql,$param);
    }

    public static function getAllBiayaSum($awal,$akhir){
        $sql = "SELECT SUM(COALESCE(b.nominal,0)) AS nominal,j.nama AS nama,b.kode_kategori
                FROM tb_biaya b
                JOIN tb_jenis_biaya j ON j.id=b.jenis_id
                WHERE b.kode_kategori<>'BV' AND b.tanggal BETWEEN '".$awal."' AND '".$akhir."'
                GROUP BY b.jenis_id";
        $param = array('tanggal'=>$awal);
        return DbHandler::getAll($sql,$param);
    }

    public static function new_biaya($data) {
        $tanggal = ymd($data['tgl-biaya']);
        $nominal = to_int_koma($data['nominal-biaya']);
        $notes = trim($data['keterangan-biaya']);
        $noref = trim($data['referensi-biaya']);
        $sql = "INSERT INTO tb_biaya(id,kode_kategori,jenis_id,nominal,tanggal,notes,noref)
            VALUES(NULL,
            '".$data['group-biaya']."',
            '".$data['group-jb-biaya']."',
            '".$nominal."',
            '".$tanggal."',
            '".$notes."',
            '".$noref."')";
        $params = array(
                    'kode_kategori'=>$data['group-biaya'],
                    'jenis_id'=>$data['group-jb-biaya'],
                    'nominal'=>$nominal,
                    'tanggal'=>$tanggal,
                    'notes'=>$notes,
                    'noref'=>$noref
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function update_biaya($data) {
        $tanggal = ymd($data['tgl-biaya']);
        $nominal = to_int_koma($data['nominal-biaya']);
        $notes = trim($data['keterangan-biaya']);
        $noref = trim($data['referensi-biaya']);
        $sql = "UPDATE tb_biaya
        SET kode_kategori='".$data['group-biaya']."',jenis_id='".$data['group-jb-biaya']."',
        nominal='".$nominal."',tanggal='".$tanggal."',notes='".$notes."',noref='".$noref."'
        WHERE id='".$data['id-biaya']."'";
        $params = array(
                    'kode_kategori'=>$data['group-biaya'],
                    'jenis_id'=>$data['group-jb-biaya'],
                    'nominal'=>$nominal,
                    'tanggal'=>$tanggal,
                    'notes'=>$notes,
                    'noref'=>$noref,
                    'id'=>$data['id-biaya']
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function get_biaya_byId($id){
        $sql = "SELECT b.*,j.nama AS nama_j,k.nama AS nama_k
                FROM tb_biaya b
                JOIN tb_jenis_biaya j ON j.id=b.jenis_id
                JOIN tb_kategori_biaya k ON k.kode=b.kode_kategori WHERE b.id='".$id."'";
        $param = array('kode'=>$id);
        return DbHandler::getRow($sql,$param);
    }

    public static function getAllKategoriBiaya(){
        $sql = "SELECT * FROM tb_kategori_biaya";
        return DbHandler::getAll($sql);
    }

    public static function getAllJenisBiaya(){
        $sql = "SELECT * FROM tb_jenis_biaya";
        return DbHandler::getAll($sql);
    }

    public static function new_frezzer($id,$nm) {
        $nama = strtoupper($nm);
        $sql = "INSERT INTO tb_container(id,nama)
            VALUES('".$id."','".$nama."')";
        $params = array(
                    'id'=>$id,
                    'nama'=>$nama
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function insert_frezzer($idf,$idp,$qty) {
        $sql = "INSERT INTO tb_container_to_produk(id_frezzer,kode_produk,jumlah)
            VALUES('".$idf."','".$idp."','".$qty."')";
        $params = array(
                    'id_frezzer'=>$idf,
                    'kode_produk'=>$idp,
                    'jumlah'=>$qty
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function new_jenis_biaya($data) {
        $nama = ucfirst($data['nama-jb']);
        $sql = "INSERT INTO tb_jenis_biaya(id,nama,status,kode_kategori)
            VALUES(NULL,
            '".$nama."',
            '".$data['status-jb']."',
            '".$data['kategori-biaya']."')";
        $params = array(
                    'nama'=>$nama,
                    'status'=>$data['status-jb'],
                    'kode_kategori'=>$data['kategori-biaya'],
                );
        return DbHandler::cExecute($sql, $params);
    }

    public static function get_totalan_biaya($awal,$akhir){
        $sql = "SELECT SUM(COALESCE(nominal,0)) AS total
                FROM tb_biaya
                WHERE kode_kategori<>'BV' AND tanggal BETWEEN '".$awal."' AND '".$akhir."'";
        $param = array('tanggal' => $awal,'tanggal'=>$akhir);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_all_totalan_biaya($awal,$akhir){
        $sql = "SELECT SUM(COALESCE(nominal,0)) AS nominal, COUNT(id) AS countr
                FROM tb_biaya
                WHERE tanggal BETWEEN '".$awal."' AND '".$akhir."'";
        $param = array('tanggal' => $awal,'tanggal'=>$akhir);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_totalan_bv($awal,$akhir){
        $sql = "SELECT SUM(COALESCE(nominal,0)) AS total
                FROM tb_biaya
                WHERE kode_kategori='BV' AND tanggal BETWEEN '".$awal."' AND '".$akhir."'";
        $param = array('tanggal' => $awal,'tanggal'=>$akhir);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_total_bv($awal,$akhir){
        $sql = "SELECT SUM(COALESCE(b.nominal,0)) AS nominal,j.nama AS nama,b.kode_kategori
                FROM tb_biaya b
                JOIN tb_jenis_biaya j ON j.id=b.jenis_id
                WHERE b.kode_kategori='BV' AND b.tanggal BETWEEN '".$awal."' AND '".$akhir."'
                GROUP BY b.jenis_id";
        $param = array('tanggal'=>$awal,'tanggal'=>$akhir);
        return DbHandler::getAll($sql,$param);
    }

    public static function get_jb_byId($id){
        $sql = "SELECT j.*,k.kode AS kode,k.nama AS nama_k FROM tb_jenis_biaya j
                JOIN tb_kategori_biaya k ON k.kode=j.kode_kategori WHERE j.id='".$id."'";
        $param = array('id'=>$id);
        return DbHandler::getRow($sql,$param);
    }

    public static function getAllJbbyKode($id){
        $sql = "SELECT j.id AS jid,j.nama AS nama,k.id AS kid,k.kode AS kode
                FROM tb_jenis_biaya j JOIN tb_kategori_biaya k ON k.kode=j.kode_kategori WHERE k.kode='".$id."'";
        $param = array('kode'=>$id);
        return DbHandler::getAll($sql,$param);
    }

    public static function update_jb($data){
        $nama = ucfirst($data['nama-jb']);
        $sql = "UPDATE tb_jenis_biaya
                SET nama='".$data['nama-jb']."',status='".$data['status-jb']."',kode_kategori='".$data['kategori-biaya']."'
                WHERE id='".$data['id-jb']."'";
        $params = array(
            'id'=>$data['id-jb'],
            'nama'=>$nama,
            'status'=>$data['status-jb'],
            'kode_kategori'=>$data['kategori-biaya'],
            );
        return DbHandler::cExecute($sql,$params);
    }

    public static function get_total_produk_laku($awal,$akhir){
        $sql = "SELECT SUM(tb_untung.untung) AS total_untung,SUM(tb_untung.hpp) AS total_hpp
                FROM ((SELECT (p.hpp*d.produk_qty) AS hpp,((p.harga_jual-p.hpp)*SUM(d.produk_qty)) AS untung
                FROM tb_produk p JOIN tb_penjualan_detail d ON d.kode_produk=p.kode_produk
                JOIN tb_penjualan j ON j.nomor_inv=d.nomor_inv
                WHERE j.tanggal_pembuatan
                BETWEEN '".$awal."' AND '".$akhir."'
                GROUP BY p.kode_produk)) AS tb_untung";
        $param = array('tanggal_pembuatan' => $awal,'tanggal_pembuatan' => $akhir);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_total_perproduk_laku($awal,$akhir){
        $sql= "SELECT p.nama,(p.harga_jual)*SUM(d.produk_qty) AS sum_jual
                FROM tb_produk p JOIN tb_penjualan_detail d ON d.kode_produk=p.kode_produk
                JOIN tb_penjualan j ON j.nomor_inv=d.nomor_inv
                WHERE j.tanggal_pembuatan BETWEEN '".$awal."' AND '".$akhir."'
                GROUP BY p.kode_produk";
        $params = array('tanggal_pembuatan' => $awal,'tanggal_pembuatan' => $akhir);
        return DbHandler::getAll($sql,$params);
    }

    public static function getAllStokProdukInContainer(){
        $sql = "SELECT s.id,c.nama AS nama_frezzer,p.nama_jual AS nama_produk,SUM(s.jumlah) AS jumlah,s.kode_produk,s.id_frezzer
                FROM tb_container_to_produk s
                JOIN tb_container c ON c.id=s.id_frezzer
                JOIN tb_produk p ON p.kode_produk=s.kode_produk
                GROUP BY s.kode_produk";
        //$param = array('tanggal'=>$awal,'tanggal'=>$akhir);
        return DbHandler::getAll($sql);
    }

    public static function getAllStokProdukInContainerbyId($id){
        $sql = "SELECT s.id,c.nama AS nama_frezzer,p.nama_jual AS nama_produk,SUM(s.jumlah) AS jumlah,s.kode_produk,s.id_frezzer
                FROM tb_container_to_produk s
                JOIN tb_container c ON c.id=s.id_frezzer
                JOIN tb_produk p ON p.kode_produk=s.kode_produk
                WHERE s.id='".$id."'
                GROUP BY s.kode_produk";
        $param = array('id'=>$id);
        return DbHandler::getRow($sql,$param);
    }

    public static function update_frezzer($id,$nama){
        $nama = strtoupper($nama);
        $sql = "UPDATE tb_container
                SET nama='".$nama."'
                WHERE id='".$id."'";
        $params = array(
            'id'=>$id,
            'nama'=>$nama
            );
        return DbHandler::cExecute($sql,$params);
    }

    public static function produk_on_frezzer($id){
        $sql = "SELECT SUM(jumlah) AS jumlah FROM tb_container_to_produk WHERE kode_produk='".$id."'";
        $param = array('kode_produk'=>$id);
        return DbHandler::getOne($sql,$param);
    }

    public static function updateProdukInContainer($id,$idf,$idp,$qty){
        $sql = "UPDATE tb_container_to_produk
                SET id_frezzer='".$idf."',kode_produk='".$idp."',jumlah='".$qty."'
                WHERE id='".$id."'";
        $params = array(
            'id'=>$id,
            'id_frezzer'=>$idf,
            'kode_produk'=>$idp,
            'jumlah'=>$qty
            );
        return DbHandler::cExecute($sql,$params);
    }

    public static function getNameOnTable($table,$key){
        $sql = "SELECT nama FROM $table WHERE nama='".$key."'";
        $param = array('nama' => $key);
        return DbHandler::getOne($sql,$param);
    }

}