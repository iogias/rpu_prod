<?php
if (!defined('WEB_ROOT')) {
    exit;
}
?>
</div>
<footer class="main-footer no-print">
    <div class="float-right d-none d-sm-block">
      <small>Version</small> <?php echo $app['versi'];?>
    </div>
    <small>Copyright &copy; 2020 <?php echo $app['nama'];?></small>
</footer>
</div>
</body>
</html>
<?php DbHandler::cClose(); ?>
