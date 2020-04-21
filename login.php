
<?php
require_once 'libs/config.php';
require_once 'inc/header_inc.php';

if(isset($_SESSION['username'])&&isset($_SESSION['id'])){
    header("Location: index.php");
}
?>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- SPINNER -->
        <div class="d-none justify-content-center spinme">
          <div class="spinner-border" role="status">
          <span class="sr-only">Loading...</span>
          </div>
        </div>
      <!-- SPINNER -->
  <div class="login-logo">
    Aplikasi RPU
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

      <p class="login-box-msg">Selamat Datang</p>
      <form action="#" method="post" name="f-login" id="f-login">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="pengguna" id="pengguna" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="rahasia" id="rahasia" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="float-sm-right">
          <button type="submit" class="btn btn-info btn-block" id="btn-masuk">Masuk</button>
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<footer>
  <small>Versi 1.0 &copy; 2020</small>
</footer>
</div>
</div>
</body>
</html>
<?php DbHandler::cClose(); ?>
