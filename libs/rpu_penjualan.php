<?php
if (!defined('WEB_ROOT')) {
    exit;
}
class RpuPenjualan{
    private static function no_urut($tgl){
        $sql = "SELECT MAX(no_urut) FROM tb_penjualan
                WHERE tanggal_pembuatan='".$tgl."'";
        $param = array('tanggal_pembuatan'=>$tgl);
        return DbHandler::getOne($sql, $param);
    }

    private static function kode_inv($tgl,$no){
        $no_urut = self::zerofill($no);
        $kode_tgl = str_replace("-","", $tgl);
        $format = 'INV-'.$kode_tgl.'-'.$no_urut;
        return $format;
    }

    private static function zeroFill($num, $zerofill = 4) {
        return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
    }

    public static function inv_baru($tgl){
        $r = self::no_urut($tgl);
        $nomor_inv = '';
        $nourut = 1;
        if ($r==0){
            $nourut = $nourut;
            $kode = self::kode_inv($tgl,1);
            $nomor_inv = $kode;
        } else {
            $sql = "SELECT id,no_urut,tanggal_pembuatan FROM tb_penjualan
                WHERE tanggal_pembuatan='".$tgl."' ORDER BY no_urut DESC LIMIT 1";
            $param = array('tanggal_pembuatan'=>$tgl);
            $row = DbHandler::getRow($sql, $param);
            $tgl = strval($row['tanggal_pembuatan']);
            $no = (int)$row['no_urut'] + 1;
            $kode = self::kode_inv($tgl,$no);
            $nomor_inv = $kode;
            $nourut = $no;
        }
        $nomor = array('nomor_inv'=>$nomor_inv,'no_urut'=>$nourut);
        return $nomor;
    }

    public static function get_inv($id){
        $sql = "SELECT b.*,s.id AS id_outlet,s.nama AS noutlet,s.alamat AS alamat,s.telepon AS tlp,f.nama AS nstaff,f2.nama AS nsales FROM tb_penjualan b
                JOIN tb_outlet s ON s.id=b.id_outlet
                JOIN tb_staff f ON f.id=b.id_pembuat
                JOIN tb_staff f2 ON f2.id=b.id_sales
                WHERE b.nomor_inv='".$id."'";
        $param = array('nomor_inv' => $id);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_detail_inv($id){
        $sql = "SELECT d.id,d.kode_produk,d.produk_qty,
                d.produk_berat,d.harga_jual,d.subtotal_berat,
                d.per,d.subtotal,p.nama,p.stok_ready,p.terjual,p.lainnya FROM tb_penjualan_detail d
                JOIN tb_produk p ON p.kode_produk=d.kode_produk
                WHERE d.nomor_inv='".$id."'";
        $param = array('nomor_inv' => $id);
        return DbHandler::getAll($sql,$param);
    }

    public static function getNomorInv($nomor){
        $sql = "SELECT id,nomor_inv FROM tb_penjualan WHERE nomor_inv LIKE '%$nomor%' ORDER BY id DESC LIMIT 4";
        $param = array('nomor_inv' => $nomor);
        return DbHandler::getAll($sql,$param);
    }

    public static function insert_inv_baru($data){
        $tgl_buat = ymd($data['tgl-buat-inv']);
        $tgl_order = ymd($data['tgl-terima-inv']);
        $tgl_jt = ymd($data['tgl-jt-inv']);
        $subtotal_inv = to_int_koma($data['td-subtotal-inv']);
        $ongkir = to_int_koma($data['td-ongkir-inv']);
        $diskon_rp = to_int_koma($data['td-diskon-rp']);
        $grand_total = to_int_koma($data['td-total-inv']);
        $kurang = to_int_koma($data['td-pengurangan-inv']);
        $keterangan=  filter_var($data['keterangan-inv'],FILTER_SANITIZE_STRING);
        $keterangan = trim($keterangan);

        $sql = "INSERT INTO tb_penjualan(
                                id,nomor_inv,no_urut,
                                tanggal_pembuatan,jam_pembuatan,
                                tanggal_order,cara_bayar,
                                tanggal_jatuh_tempo,
                                total_produk,total_berat,total_jumlah,
                                grand_total,pengurangan,diskon,diskon_rp,ongkir,
                                id_outlet,id_pembuat,id_sales,keterangan)
                    VALUES(NULL,
                    '".$data['nomor-inv']."',
                    '".$data['no-urut']."',
                    '".$tgl_buat."',
                    CURRENT_TIME(),
                    '".$tgl_order."',
                    '".$data['cara-bayar']."',
                    '".$tgl_jt."',
                    '".$data['td-total-qtypcs-inv']."',
                    '".$data['td-total-qtykg-inv']."',
                    '".$subtotal_inv."',
                    '".$grand_total."',
                    '".$kurang."',
                    '".$data['td-diskon-inv']."',
                    '".$diskon_rp."',
                    '".$ongkir."',
                    '".$data['outlet-id']."',
                    '".$data['user-login-id']."',
                    '".$data['staff-id']."',
                    '".$keterangan."')";
        $params = array(
                    'nomor_inv'=>$data['nomor-inv'],
                    'no_urut'=>$data['no-urut'],
                    'tanggal_pembuatan'=>$tgl_buat,
                    'tanggal_order'=>$tgl_order,
                    'cara_bayar'=>$data['cara-bayar'],
                    'tanggal_jatuh_tempo'=>$tgl_jt,
                    'total_produk'=>$data['td-total-qtypcs-inv'],
                    'total_berat'=>$data['td-total-qtykg-inv'],
                    'total_jumlah'=>$subtotal_inv,
                    'grand_total'=>$grand_total,
                    'pengurangan'=>$kurang,
                    'diskon'=>$data['td-diskon-inv'],
                    'diskon_rp'=>$diskon_rp,
                    'ongkir'=>$ongkir,
                    'id_outlet'=>$data['outlet-id'],
                    'id_pembuat'=>$data['user-login-id'],
                    'id_sales'=>$data['staff-id'],
                    'keterangan'=>$keterangan
                );
        return DbHandler::cExecute($sql,$params);
    }

    public static function insert_inv_detail($inv,$value){
            $harga_items = to_int_koma($value['harga']);
            $subtotal_items = to_int_koma($value['subtotal']);
            $sql = "INSERT INTO tb_penjualan_detail(id,nomor_inv,kode_produk,produk_qty,produk_berat,harga_jual,subtotal_berat,per,subtotal)
            VALUES(NULL,
                '".$inv."',
                '".$value['kode']."',
                '".$value['qty']."',
                '".$value['berat']."',
                '".$harga_items."',
                '".$value['subberat']."',
                '".$value['per']."',
                '".$subtotal_items."')";
            $params = array(
                        'nomor_inv'=>$inv,
                        'kode_produk'=>$value['kode'],
                        'produk_qty'=>$value['qty'],
                        'produk_berat'=>$value['berat'],
                        'harga_jual'=>$harga_items,
                        'subtotal_berat'=>$value['subberat'],
                        'per'=>$value['per'],
                        'subtotal'=>$subtotal_items
                    );
            return DbHandler::cExecute($sql,$params);
    }

    public static function update_inv($data){

        $tgl_buat = ymd($data['tgl-buat-inv']);
        $tgl_order = ymd($data['tgl-terima-inv']);
        $tgl_jt = ymd($data['tgl-jt-inv']);
        $subtotal_inv = to_int_koma($data['td-subtotal-inv']);
        $ongkir = to_int_koma($data['td-ongkir-inv']);
        $grand_total = to_int_koma($data['td-total-inv']);
        $diskon_rp = to_int_koma($data['td-diskon-rp']);
        $kurang = to_int_koma($data['td-pengurangan-inv']);
        $keterangan=  filter_var($data['keterangan-inv'],FILTER_SANITIZE_STRING);
        $keterangan = trim($keterangan);

        $sql_po = "UPDATE tb_penjualan
                    SET tanggal_order='".$tgl_order."',
                    cara_bayar='".$data['cara-bayar']."',
                    tanggal_jatuh_tempo='".$tgl_jt."',
                    total_produk='".$data['td-total-qtypcs-inv']."',
                    total_berat='".$data['td-total-qtykg-inv']."',
                    total_jumlah='".$subtotal_inv."',
                    grand_total='".$grand_total."',
                    pengurangan='".$kurang."',
                    diskon='".$data['td-diskon-inv']."',
                    diskon_rp='".$diskon_rp."',
                    ongkir='".$ongkir."',
                    id_outlet='".$data['outlet-id']."',
                    id_pembuat='".$data['user-login-id']."',
                    id_sales='".$data['staff-id']."',
                    keterangan='".$keterangan."'
                    WHERE nomor_inv='".$data['nomor-inv']."'";
        $params = array(
                    'tanggal_order'=>$tgl_order,
                    'cara_bayar'=>$data['cara-bayar'],
                    'tanggal_jatuh_tempo'=>$tgl_jt,
                    'total_produk'=>$data['td-total-qtypcs-inv'],
                    'total_berat'=>$data['td-total-qtykg-inv'],
                    'total_jumlah'=>$subtotal_inv,
                    'grand_total'=>$grand_total,
                    'pengurangan'=>$kurang,
                    'diskon'=>$data['td-diskon-inv'],
                    'diskon_rp'=>$diskon_rp,
                    'ongkir'=>$ongkir,
                    'id_outlet'=>$data['outlet-id'],
                    'id_pembuat'=>$data['user-login-id'],
                    'id_sales'=>$data['staff-id'],
                    'keterangan'=>$keterangan,
                    'nomor_inv'=>$data['nomor-inv']
                );
        return DbHandler::cExecute($sql_po,$params);
    }

    public static function update_inv_detail($inv,$value){
        $id = (int)$value['id'];
        $harga_items = to_int_koma($value['harga']);
        $subtotal_items = to_int_koma($value['subtotal']);
        $sql = "UPDATE tb_penjualan_detail
                SET kode_produk='".$value['kode']."',
                produk_qty='".$value['qty']."',
                produk_berat='".$value['berat']."',
                harga_jual='".$harga_items."',
                subtotal_berat='".$value['subberat']."',
                per='".$value['per']."',
                subtotal='".$subtotal_items."'
                WHERE id='".$id."' AND nomor_inv='".$inv."'";
        $params = array(
                    'kode_produk'=>$value['id'],
                    'produk_qty'=>$value['qty'],
                    'produk_berat'=>$value['berat'],
                    'harga_beli'=>$harga_items,
                    'subtotal_berat'=>$value['subberat'],
                    'per'=>$value['per'],
                    'subtotal'=>$subtotal_items,
                    'id'=>$id,
                    'nomor_inv'=>$inv
                );
        return DbHandler::cExecute($sql,$params);
    }

    public static function get_lap_inv($awal,$akhir,$arg='99'){
        $sql = "SELECT b.nomor_inv,b.tanggal_pembuatan,b.cara_bayar,
                b.tanggal_jatuh_tempo,b.total_produk,b.grand_total,b.keterangan,
                s.nama AS outlet,
                (CASE WHEN b.cara_bayar='LUNAS' || o.status_bayar='lunas' THEN 'Lunas' ELSE 'Belum' END) AS status_bayar,
                (CASE WHEN b.cara_bayar='LUNAS' || o.status_bayar='lunas' THEN 0 ELSE b.grand_total END) AS hutang,
                '$awal' AS tgl_awal, '$akhir' AS tgl_akhir
                FROM tb_penjualan b
                JOIN tb_outlet s ON s.id=b.id_outlet
                LEFT JOIN tb_log_penjualan o ON o.nomor_inv=b.nomor_inv
                WHERE b.tanggal_pembuatan BETWEEN '".$awal."' AND '".$akhir."'\n";
        if($arg!='99'&&$arg=='lunas'){
            $sql .= "AND b.cara_bayar='LUNAS' OR o.status_bayar='lunas'";
        } else if ($arg!='99'&&$arg=='belum'){
            $sql .= "AND b.cara_bayar='TEMPO' AND o.status_bayar IS NULL";
        }
        $param = array('tanggal_pembuatan' => $awal,
            'cara_bayar'=>$arg,
            'status_bayar'=>$arg);
        return DbHandler::getAll($sql,$param);
    }

    public static function get_totalan_inv($awal,$akhir){
        $sql = "SELECT SUM(CASE WHEN b.cara_bayar='TEMPO' THEN b.grand_total WHEN b.cara_bayar='CASH' THEN - b.grand_total ELSE 0 END) hutang,
                COUNT(b.id) AS countr,SUM(b.total_produk) as total_qty,
                SUM(b.grand_total) AS total_rp,SUM(o.jml_bayar) AS terbayar FROM tb_penjualan b
                LEFT JOIN tb_log_penjualan o ON o.nomor_inv=b.nomor_inv
                WHERE b.tanggal_pembuatan BETWEEN '".$awal."' AND '".$akhir."'";
        $param = array('tanggal_pembuatan' => $awal);
        return DbHandler::getRow($sql,$param);
    }

    public static function new_bayar_inv($data){
        $tgl_bayar = ymd($data['tgl-bayar-inv']);
        $jml_bayar = to_int_koma($data['jml-bayar-inv']);
        //$sisa_bayar = ($data['sisa-bayar']=='0')?0:to_int_koma($data['sisa-bayar']);
        $status = 'lunas';//((int)$sisa_bayar==0)?'lunas':'belum';
        $bank=trim($data['notes-bayar-inv']);
        $sql = "INSERT INTO tb_log_penjualan(id,nomor_inv,status_bayar,tgl_bayar,bayar_via,bank,jml_bayar)
        VALUES (NULL,
        '".$data['bayar-nomorinv']."',
        '".$status."',
        '".$tgl_bayar."',
        '".$data['bayar-via-inv']."',
        '".$bank."',
        '".$jml_bayar."'
        )";
        $params = array(
            'nomor_po'=>$data['bayar-nomorinv'],
            'status_bayar'=>$status,
            'tgl_bayar'=>$tgl_bayar,
            'bayar_via'=>$data['bayar-via-inv'],
            'bank'=>$bank,
            'jml_bayar'=>$jml_bayar
        );
        return DbHandler::cExecute($sql,$params);
    }

    public static function get_penjualan_kotor($awal,$akhir){
        $sql = "SELECT SUM(tbi.jual) AS total_rp,SUM(tbi.hpp) AS total_hpp
                FROM (
                SELECT (SUM(d.produk_qty)*d.harga_jual) AS jual,(SUM(d.produk_qty)*p.hpp) AS hpp
                FROM tb_penjualan_detail d
                JOIN tb_produk p ON p.kode_produk=d.kode_produk
                JOIN tb_penjualan j ON j.nomor_inv=d.nomor_inv
                WHERE j.tanggal_pembuatan BETWEEN '".$awal."' AND '".$akhir."'
                GROUP BY d.kode_produk) AS tbi";
        $params = array('tanggal_pembuatan' => $awal,'tanggal_pembuatan' => $akhir);
        return DbHandler::getRow($sql,$params);
    }

}
