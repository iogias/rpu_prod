<?php
if (!defined('WEB_ROOT')) {
    exit;
}
class RpuPembelian{
    // public static function getAllPO(){

    // }

    private static function no_urut($tgl){
        $sql = "SELECT COUNT(no_urut) FROM tb_pembelian
                WHERE tanggal_pembuatan='".$tgl."'";
        $param = array('tanggal_pembuatan'=>$tgl);
        return DbHandler::getOne($sql, $param);
    }

    private static function kode_po($tgl,$no){
        //$no = (int)$no+1;
        $no_urut = self::zerofill($no);
        $kode_tgl = str_replace("-","", $tgl);
        $format = 'PO-'.$kode_tgl.'-'.$no_urut;
        return $format;
    }

    private static function zeroFill($num, $zerofill = 4) {
        return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
    }

    public static function get_po($id){
        $sql = "SELECT b.*,s.nama AS nsupplier,f.nama AS staff_terima,f2.nama AS staff_buat FROM tb_pembelian b
                JOIN tb_supplier s ON s.id=b.id_supplier
                JOIN tb_staff f ON f.id=b.id_penerima
                JOIN tb_staff f2 ON f2.id=b.id_pembuat
                WHERE b.nomor_po='".$id."'";
        $param = array('nomor_po' => $id);
        return DbHandler::getRow($sql,$param);
    }

    public static function get_detail_po($id){
        $sql = "SELECT d.id,d.kode_produk,d.produk_qty,
                d.produk_berat,d.harga_beli,d.subtotal_berat,
                d.per,d.subtotal,p.nama FROM tb_pembelian_detail d
                JOIN tb_produk p ON p.kode_produk=d.kode_produk
                WHERE d.nomor_po='".$id."'";
        $param = array('nomor_po' => $id);
        return DbHandler::getAll($sql,$param);
    }

    public static function po_baru($tgl){
        $r = self::no_urut($tgl);
        $nomor_po = '';
        $nourut = 1;
        if ($r==0){
            $nourut = $nourut;
            $kode = self::kode_po($tgl,1);
            $nomor_po = $kode;
        } else {
            $sql = "SELECT id,no_urut,tanggal_pembuatan FROM tb_pembelian
                WHERE tanggal_pembuatan='".$tgl."' ORDER BY no_urut DESC LIMIT 1";
            $param = array('tanggal_pembuatan'=>$tgl);
            $row = DbHandler::getRow($sql, $param);
            $tgl = strval($row['tanggal_pembuatan']);
            $no = (int)$row['no_urut'] + 1;
            $kode = self::kode_po($tgl,$no);
            $nomor_po = $kode;
            $nourut = $no;
        }
        $nomor = array('nomor_po'=>$nomor_po,'no_urut'=>$nourut);
        return $nomor;
    }

    public static function insert_po_baru($data){

        $tgl_buat = ymd($data['tgl-buat-po']);
        $tgl_terima = ymd($data['tgl-terima-po']);
        $tgl_jt = ymd($data['tgl-jt-po']);
        $subtotal_po = to_int_koma($data['td-subtotal-po']);
        $ongkir = to_int_koma($data['td-ongkir-po']);
        $grand_total = to_int_koma($data['td-total-po']);
        $kurang = to_int_koma($data['td-pengurangan-po']);
        $keterangan=  filter_var($data['keterangan-po'],FILTER_SANITIZE_STRING);
        $keterangan = trim($keterangan);

        $sql_po = "INSERT INTO tb_pembelian(
                                id,nomor_po,no_urut,
                                tanggal_pembuatan,jam_pembuatan,
                                tanggal_terima,cara_bayar,
                                tanggal_jatuh_tempo,
                                total_produk,total_berat,total_jumlah,
                                grand_total,pengurangan,diskon,ongkir,
                                id_supplier,id_penerima,id_pembuat,keterangan)
                    VALUES(NULL,
                    '".$data['nomor-po']."',
                    '".$data['no-urut']."',
                    '".$tgl_buat."',
                    CURRENT_TIME(),
                    '".$tgl_terima."',
                    '".$data['cara-bayar']."',
                    '".$tgl_jt."',
                    '".$data['td-total-qty-pcs']."',
                    '".$data['td-total-qty-kg']."',
                    '".$subtotal_po."',
                    '".$grand_total."',
                    '".$kurang."',
                    '".$data['td-diskon-po']."',
                    '".$ongkir."',
                    '".$data['supplier-id']."',
                    '".$data['staff-id']."',
                    '".$data['user-login-id']."',
                    '".$keterangan."')";
        $params = array(
                    'nomor_po'=>$data['nomor-po'],
                    'no_urut'=>$data['no-urut'],
                    'tanggal_pembuatan'=>$tgl_buat,
                    'tanggal_terima'=>$tgl_terima,
                    'cara_bayar'=>$data['cara-bayar'],
                    'tanggal_jatuh_tempo'=>$tgl_jt,
                    'total_produk'=>$data['td-total-qty-pcs'],
                    'total_berat'=>$data['td-total-qty-kg'],
                    'total_jumlah'=>$subtotal_po,
                    'grand_total'=>$grand_total,
                    'pengurangan'=>$kurang,
                    'diskon'=>$data['td-diskon-po'],
                    'ongkir'=>$ongkir,
                    'id_supplier'=>$data['supplier-id'],
                    'id_penerima'=>$data['staff-id'],
                    'id_pembuat'=>$data['user-login-id'],
                    'keterangan'=>$keterangan
                );
        return DbHandler::cExecute($sql_po,$params);
    }

    public static function insert_po_detail($po,$value){
            $harga_items = to_int_koma($value['harga']);
            $subtotal_items = to_int_koma($value['subtotal']);
            $sql = "INSERT INTO tb_pembelian_detail(id,nomor_po,kode_produk,produk_qty,produk_berat,harga_beli,subtotal_berat,per,subtotal)
            VALUES(NULL,
                '".$po."',
                '".$value['kode']."',
                '".$value['qty']."',
                '".$value['berat']."',
                '".$harga_items."',
                '".$value['subberat']."',
                '".$value['per']."',
                '".$subtotal_items."')";
            $params = array(
                        'nomor_po'=>$po,
                        'kode_produk'=>$value['kode'],
                        'produk_qty'=>$value['qty'],
                        'produk_berat'=>$value['berat'],
                        'harga_beli'=>$harga_items,
                        'subtotal_berat'=>$value['subberat'],
                        'per'=>$value['per'],
                        'subtotal'=>$subtotal_items
                    );
            return DbHandler::cExecute($sql,$params);
    }

    public static function getNomorPo($nomor){
        $sql = "SELECT id,nomor_po FROM tb_pembelian WHERE nomor_po LIKE '%$nomor%' ORDER BY id DESC LIMIT 4";
        $param = array('nomor_po' => $nomor);
        return DbHandler::getAll($sql,$param);
    }

    public static function update_po($data){

        $tgl_buat = ymd($data['tgl-buat-po']);
        $tgl_terima = ymd($data['tgl-terima-po']);
        $tgl_jt = ymd($data['tgl-jt-po']);
        $subtotal_po = to_int_koma($data['td-subtotal-po']);
        $ongkir = to_int_koma($data['td-ongkir-po']);
        $grand_total = to_int_koma($data['td-total-po']);
        $kurang = to_int_koma($data['td-pengurangan-po']);
        $keterangan=  filter_var($data['keterangan-po'],FILTER_SANITIZE_STRING);
        $keterangan = trim($keterangan);

        $sql_po = "UPDATE tb_pembelian
                    SET tanggal_terima='".$tgl_terima."',
                    cara_bayar='".$data['cara-bayar']."',
                    tanggal_jatuh_tempo='".$tgl_jt."',
                    total_produk='".$data['td-total-qty-pcs']."',
                    total_berat='".$data['td-total-qty-kg']."',
                    total_jumlah='".$subtotal_po."',
                    grand_total='".$grand_total."',
                    pengurangan='".$kurang."',
                    diskon='".$data['td-diskon-po']."',
                    ongkir='".$ongkir."',
                    id_supplier='".$data['supplier-id']."',
                    id_penerima='".$data['staff-id']."',
                    id_pembuat='".$data['user-login-id']."',
                    keterangan='".$keterangan."'
                    WHERE nomor_po='".$data['nomor-po']."'";
        $params = array(
                    'tanggal_terima'=>$tgl_terima,
                    'cara_bayar'=>$data['cara-bayar'],
                    'tanggal_jatuh_tempo'=>$tgl_jt,
                    'total_produk'=>$data['td-total-qty-pcs'],
                    'total_berat'=>$data['td-total-qty-kg'],
                    'total_jumlah'=>$subtotal_po,
                    'grand_total'=>$grand_total,
                    'pengurangan'=>$kurang,
                    'diskon'=>$data['td-diskon-po'],
                    'ongkir'=>$ongkir,
                    'id_supplier'=>$data['supplier-id'],
                    'id_penerima'=>$data['staff-id'],
                    'id_pembuat'=>$data['user-login-id'],
                    'keterangan'=>$keterangan,
                    'nomor_po'=>$data['nomor-po']
                );
        return DbHandler::cExecute($sql_po,$params);
    }

    public static function update_po_detail($po,$value){
        $id = (int)$value['id'];
        $harga_items = to_int_koma($value['harga']);
        $subtotal_items = to_int_koma($value['subtotal']);
        $sql = "UPDATE tb_pembelian_detail
                SET kode_produk='".$value['kode']."',
                produk_qty='".$value['qty']."',
                produk_berat='".$value['berat']."',
                harga_beli='".$harga_items."',
                subtotal_berat='".$value['subberat']."',
                per='".$value['per']."',
                subtotal='".$subtotal_items."'
                WHERE id='".$id."' AND nomor_po='".$po."'";
        $params = array(
                    'kode_produk'=>$value['id'],
                    'produk_qty'=>$value['qty'],
                    'produk_berat'=>$value['berat'],
                    'harga_beli'=>$harga_items,
                    'subtotal_berat'=>$value['subberat'],
                    'per'=>$value['per'],
                    'subtotal'=>$subtotal_items,
                    'id'=>$id,
                    'nomor_po'=>$po
                );
        return DbHandler::cExecute($sql,$params);
    }

    public static function get_lap_po($awal,$akhir,$arg='99'){
        $sql = "SELECT b.nomor_po,b.tanggal_pembuatan,b.cara_bayar,b.tanggal_jatuh_tempo,b.total_produk,
                b.grand_total,b.keterangan,s.nama AS supplier,
                (CASE WHEN b.cara_bayar='LUNAS' || o.status_bayar='lunas' THEN 'Lunas' ELSE 'Belum' END) AS status_bayar,
                (CASE WHEN b.cara_bayar='LUNAS' || o.status_bayar='lunas' THEN 0 ELSE b.grand_total END) AS hutang,
                '$awal' AS tgl_awal, '$akhir' AS tgl_akhir
                FROM tb_pembelian b
                JOIN tb_supplier s ON s.id=b.id_supplier
                LEFT JOIN tb_log_pembelian o ON o.nomor_po=b.nomor_po
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

    public static function get_totalan($awal,$akhir){
        $sql = "SELECT SUM(CASE WHEN b.cara_bayar='TEMPO' THEN b.grand_total WHEN b.cara_bayar='CASH' THEN - b.grand_total ELSE 0 END) hutang,
                COUNT(b.id) AS countr,SUM(b.total_produk) as total_qty,
                SUM(b.grand_total) AS total_rp,SUM(o.jml_bayar) AS terbayar FROM tb_pembelian b
                LEFT JOIN tb_log_pembelian o ON o.nomor_po=b.nomor_po
                WHERE b.tanggal_pembuatan BETWEEN '".$awal."' AND '".$akhir."'";
        $param = array('tanggal_pembuatan' => $awal);
        return DbHandler::getRow($sql,$param);
    }

    public static function new_bayar($data){
        $tgl_bayar = ymd($data['tgl-bayar']);
        $jml_bayar = to_int_koma($data['jml-bayar']);
        //$sisa_bayar = ($data['sisa-bayar']=='0')?0:to_int_koma($data['sisa-bayar']);
        $status = 'lunas';//((int)$sisa_bayar==0)?'lunas':'belum';
        $bank=trim($data['notes-bayar']);
        $sql = "INSERT INTO tb_log_pembelian(id,nomor_po,status_bayar,tgl_bayar,bayar_via,bank,jml_bayar)
        VALUES (NULL,
        '".$data['bayar-nomorpo']."',
        '".$status."',
        '".$tgl_bayar."',
        '".$data['bayar-via']."',
        '".$bank."',
        '".$jml_bayar."'
        )";
        $params = array(
            'nomor_po'=>$data['bayar-nomorpo'],
            'status_bayar'=>$status,
            'tgl_bayar'=>$tgl_bayar,
            'bayar_via'=>$data['bayar-via'],
            'bank'=>$bank,
            'jml_bayar'=>$jml_bayar
        );
        return DbHandler::cExecute($sql,$params);
    }

    public static function get_retur_po(){
        $sql = "SELECT r.*,s.nama AS nama FROM tb_retur_pembelian r
                JOIN tb_pembelian b ON b.nomor_po=r.nomor_po
                LEFT JOIN tb_supplier s ON s.id=b.id_supplier";
        //$param = array('nomor_po' => $nomor);
        return DbHandler::getAll($sql);
    }

}
