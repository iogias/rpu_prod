<?php
require_once '../libs/config.php';
require_once '../libs/rpu_katalog.php';
header('Content-type: application/json');

if (isset($_POST['token']) && $_POST['token']=='kategoriproduk'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    RpuKatalog::delRowById($table,$id);
    echo '{"status":true}';
}else if (isset($_POST['token']) && $_POST['token']=='supplier'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    RpuKatalog::delRowById($table,$id);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='customer'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    RpuKatalog::delRowById($table,$id);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='outlet'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    RpuKatalog::delRowById($table,$id);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='customergroup'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    RpuKatalog::delRowById($table,$id);
    echo '{"status":true}';
} else if (isset($_POST['token']) && $_POST['token']=='biaya'){
    $id = (int) $_POST['data'];
    $table = 'tb_'.$_POST['token'];
    RpuKatalog::delRowById($table,$id);
    echo '{"status":true}';
} else {
    die('NO DATA PASSED');
}
