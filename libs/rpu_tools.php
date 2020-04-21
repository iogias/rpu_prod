<?php
if (!defined('WEB_ROOT')) {
    exit;
}

function money_rp($angka){
    $hasil = "Rp " . number_format($angka,2,',','.');
    return $hasil;
}

function money_non_rp($angka){
    $hasil = number_format($angka,2,',','.');
    return $hasil;
}

function money_simple($angka){
    $hasil = number_format($angka,-1,'',',');
    return $hasil;
}

// function ribuan($angka){
//     $hasil = number_format($angka,-1,'','.');
//     return $hasil;
// }

function desimal($angka){
    $hasil = number_format($angka,2,'.','');
    return $hasil;
}

// function untuk menampilkan nama hari ini dalam bahasa indonesia
// di buat oleh malasngoding.com

function hari_ini(){
    $hari = date("D");
    switch($hari){
        case 'Sun':
            $hari_ini = "Minggu";
        break;

        case 'Mon':
            $hari_ini = "Senin";
        break;

        case 'Tue':
            $hari_ini = "Selasa";
        break;

        case 'Wed':
            $hari_ini = "Rabu";
        break;

        case 'Thu':
            $hari_ini = "Kamis";
        break;

        case 'Fri':
            $hari_ini = "Jumat";
        break;

        case 'Sat':
            $hari_ini = "Sabtu";
        break;

        default:
            $hari_ini = "Tidak di ketahui";
        break;
    }
    return  $hari_ini;
}

function ymd ($date){
    $tgl = date('Y-m-d',strtotime($date));
    return $tgl;
}

function dmy ($date){
    $tgl = date('d-m-Y',strtotime($date));
    return $tgl;
}

function to_int_koma($arg){
    $arg = str_replace(',','',$arg);
    $arg = (int) $arg;
    return $arg;
}

function to_int_titik($arg){
    $arg = str_replace('.','',$arg);
    $arg = (int) $arg;
    return $arg;
}
