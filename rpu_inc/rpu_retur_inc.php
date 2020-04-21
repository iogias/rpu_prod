<?php
if (!defined('WEB_ROOT')) {
    exit;
}
$kg=RpuKatalog::getAllKategoriProduk();
?>
<section class="content">
<div class="container-fluid">
<div class="row">
    <div class="col-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <div class="row">
          <div class="col-6">
            <h4>Pembelian</h4>
          </div>
          <div class="col-6">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Status</span>
            </div>
            <select name="filter-status" id="filter-status" class="form-control">
              <option value="99">SEMUA</option>
              <option value="1">AKTIF</option>
              <option value="0">NON-AKTIF</option>
            </select>
          </div>
          </div>
      </div>
      </div>
    <div class="card-body">
    <div class="row">
    <div class="col-md-12">
    <div class="table-responsive">
      <table id="tb-retur-pembelian" class="table table-sm table-striped text-sm">
      <thead class="text-center">
      <tr>
        <th>Nomor</th>
        <th>Kode</th>
        <th>Supplier</th>
        <th>Retur</th>
        <th>Nominal</th>
        <th>Tgl.Retur</th>
        <th width="4%" class="text-center">&nbsp;</th>
      </tr>
      </thead>
      </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <div class="col-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-3">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Status</span>
            </div>
            <select name="filter-status" id="filter-status" class="form-control">
              <option value="99">SEMUA</option>
              <option value="1">AKTIF</option>
              <option value="0">NON-AKTIF</option>
            </select>
          </div>
        </div>
      </div>
      </div>
    <div class="card-body">
    <div class="row">
    <div class="col-md-12">
    <div class="table-responsive">
      <table id="tb-produk" class="table table-sm table-striped text-sm">
      <thead class="text-center">
      <tr>
        <th>Kode</th>
        <th>Nama Beli</th>
        <th>Nama Jual</th>
        <th>Harga Beli</th>
        <th>Harga Jual</th>
        <th>Stok Ready</th>
        <th>Terjual</th>
        <th>Lainnya</th>
        <th>Total</th>
        <th>Kategori</th>
        <th width="5%">Status</th>
        <th width="4%" class="text-center">&nbsp;</th>
      </tr>
      </thead>
      </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

</div>

</div>
</div>
</section>
<script>
$(function () {
    fetch_retur('retur-pembelian')
    function fetch_retur(param){
        let param2 = param.split('-')
        $('#tb-'+param).DataTable({
            'autoWidth': false,
            'processing': true,
            'serverside': true,
            'ajax':{
                url:service_url+'s_'+param2[1]+'.php',
                type:'POST',
                data:{token:param},
            }
        })
    }
})
</script>
