<?php
if (!defined('WEB_ROOT')) {
    exit;
}
?>
<!DOCTYPE html>
    <head>
        <?php if (isset($pageTitle)) { ?>
            <title><?php echo $pageTitle; ?></title>
        <?php } else { ?>
            <title>Aplikasi RPU</title>
        <?php } ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/plugins/fontawesome-free/css/all.min.css'; ?>" />
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css';?>">
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/plugins/icheck-bootstrap/icheck-bootstrap.min.css';?>">
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/plugins/datatables-buttons/css/buttons.bootstrap4.min.css';?>">
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/plugins/toastr/toastr.min.css';?>">
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/adminlte.min.css'; ?>" />
        <link rel="stylesheet" href="<?php echo BASE_URI . 'themes/css/metallic/zebra_datepicker.min.css'; ?>" />
        <script src="<?php echo BASE_URI . 'themes/plugins/jquery/jquery.min.js'; ?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/bootstrap/js/bootstrap.bundle.min.js'; ?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables-buttons/js/dataTables.buttons.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables-buttons/js/buttons.bootstrap4.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables-buttons/js/buttons.html5.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables-buttons/js/buttons.print.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/datatables-buttons/js/buttons.colVis.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/inputmask/min/jquery.inputmask.bundle.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'themes/plugins/toastr/toastr.min.js';?>"></script>
        <script src="<?php echo BASE_URI . 'js/adminlte.min.js'; ?>"></script>
        <script src="<?php echo BASE_URI . 'js/zebra_datepicker.min.js'; ?>"></script>
        <script src="<?php echo BASE_URI . 'js/rpu_init.js'; ?>"></script>
    </head>


