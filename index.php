<?php
require_once 'libs/config.php';
require_once 'inc/header_inc.php';

if(!isset($_SESSION['username']) && !isset($_SESSION['id'])){
    header("Location: /rpu_prod/login.php");
} else {
  $session_us = $_SESSION['username'];
  $session_id = $_SESSION['id'];
}

$modul = RpuKatalog::getAllModulInc();
$tglnow = date('d-m-Y');
$hari = hari_ini();
?>
<body class="hold-transition sidebar-mini text-sm">
<div class="wrapper">
    <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-grey navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user mr-2"></i>
          <span id="#text-user"><?php echo $session_us;?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item" id="btn-keluar">
            <p class="text-sm"><i class="fas fa-sign-out-alt mr-1"></i>Logout</p>
          </a>
          <div class="dropdown-divider"></div>
           <a href="#" class="dropdown-item" id="btn-user-profile">
            <p class="text-sm"><i class="fas fa-cog mr-1"></i>Profile</p>
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
<?php require_once 'rpu_inc/rpu_aside_left_inc.php';?>
<div class="content-wrapper">
<?php
   if (isset($_GET['action'])){
        for ($i = 0; $i < count($modul); $i++) {
            $path = $modul[$i]['path'];
            $nama = $modul[$i]['nama'];
            $judul = $modul[$i]['judul'];
            if ($_GET['action']=='rpu_'.$nama){ ?>
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-12">
                      <h4 class="text-center"><?php echo $judul;?></h4>
                    </div>
                  </div>
                </div><!-- /.container-fluid -->
              </section>
            <?php require_once $path.'.php';
            }
        }
   } else{ ?>
            <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-12">
                      <h4 class="text-center">Beranda</h4>
                    </div>
                  </div>
                </div><!-- /.container-fluid -->
            </section>
     <?php  require_once 'rpu_inc/rpu_beranda_inc.php';
   } ?>

<div class="modal fade" id="modal-print" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'inc/footer_inc.php'; ?>

