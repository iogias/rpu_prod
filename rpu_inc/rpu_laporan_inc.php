<?php
if (!defined('WEB_ROOT')) {
    exit;
}?>
<section class="content">
<form id="f-laporan" name="f-laporan">
<div class="container-fluid">
<div class="card">
<div class="card-header">
<div class="row">
    <div class="col-sm-3">
        <select name="filter-lap" id="filter-lap" class="form-control" required>
            <option value="">Pilih Laporan</option>
            <option value="1">PENJUALAN</option>
            <option value="2">PEMBELIAN</option>
            <option value="3">PRODUK</option>
            <option value="4">PEMBAYARAN</option>
        </select>
    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <div class="input-group-prepend">
            <span class="input-group-text">Dari</span>
            </div>
            <input type=text class="form-control" id="tgl-awal" name="tgl-awal" data-zdp_readonly_element="false" required>
            <div class="input-group-append">
            <span class="input-group-text">s.d</span>
            </div>
            <input type=text class="form-control" id="tgl-akhir" name="tgl-akhir" data-zdp_readonly_element="false" required>
        </div>
    </div>
    <div class="col-sm-4">
        <button type="button" id="btn-proses-lap" class="btn btn-info">PROSES<i class="fas fa-angle-double-right ml-3"></i></button>
    </div>
</div>
</div>
<div class="card-body">
    <div class="table-responsive" id="div-table">
        <thead>

        </thead>
    </div>
</div>
</div>
</div>
</form>
</section>
<script>
$(function(){
    set_date($('#tgl-awal'))
    set_date($('#tgl-akhir'))
    $('#btn-proses-lap').click(function(e){
        e.preventDefault()
        let idlap = $('#filter-lap').val()
        let awal = $('#tgl-awal').val()
        let akhir = $('#tgl-akhir').val()
        let form = $('#f-laporan')
        let fail = false
        let fail_log = ''
        let name

        form.find('input,select').each(function(){
            if(!$(this).prop('required')){
            }else{
                if(!$(this).val()){
                  fail=true
                  name=$(this).attr('name')
                  fail_log += '['+ name +'] HARUS DI ISI!'+'</br>'
                }
             }
        })
        if(!fail){
            if(idlap=='1'){
                console.log('helo')
            }

        } else {
            toastr.error(fail_log)
        }
    })

    function get_laporan(arg){
        html='<tr>'
        html= '<th></th>'
        html +='</tr>'
    }

    // fetch_data('pembelian')
    // function fetch_data(param){
    //    let datatable = $('#tb-'+param).DataTable({
    //         'autoWidth': false,
    //         'processing': true,
    //         'serverside': true,
    //         'ajax':{
    //             url:service_url+'s_laporan.php',
    //             type:'POST',
    //             data:{token:param},
    //         },
    //     })
    // }
})
</script>
