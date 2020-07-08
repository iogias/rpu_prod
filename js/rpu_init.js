const service_url='./services/'
const validnum = /^[0-9]+$/
const validalphanum = /^[a-z0-9]+$/i
$(function(){

    toastr.options = {
        'positionClass': 'toast-top-center',
        'showDuration': '2000',
        'hideDuration': '2000',
        'timeOut': '2000',
        'extendedTimeOut': '2000',
    }

    $('.a-link-menu-nav,.btn-batal').click(function(){
        let id=$(this).attr('id').replace('get_','')
        window.location.href='index.php?action='+id
    })

    $('#btn-masuk').click(function(e){
        e.preventDefault()
        let pengguna = $('#pengguna').val()
        let rahasia = $('#rahasia').val()
        let form = $('#f-login')
        let fail = false
        let fail_log = ''
        let name
        form.find('input').each(function(){
            if(!$(this).prop('required')){
             }else{
                if(!$(this).val()){
                  fail=true
                  name=$(this).attr('name')
                  fail_log += '['+ name +'] HARUS DI ISI!'+'</br>'
                }
             }
        })
            if (!fail) {
                $.ajax({
                    url:service_url+'s_login.php',
                    method:'POST',
                    dataType:'json',
                    data:{
                        token:'login',
                        datalog:form.serialize()
                    },
                    beforeSend:function(){
                        $('.spinme').removeClass('d-none')
                        $('.spinme').addClass('d-flex')
                    },
                    complete:function(){
                        $('.spinme').removeClass('d-flex')
                        $('.spinme').addClass('d-none')
                    },
                    success:function(result){
                        if (result.status==true){
                            form[0].reset()
                            window.location.href="/rpu_prod/index.php"
                         } else{
                            form[0].reset()
                            toastr.error('Kesalahan pada username / password!')
                         }
                    }
                })
        } else {
            toastr.warning(fail_log)
        }
    })

    $('#btn-keluar').click(function(e){
        e.preventDefault()
        let user = $('#text-user').text()
        $.get(service_url+'s_login.php',{
              token:'logout',
              data:user
            },function(data){
                if (data.status==true){
                    window.location.href="/rpu_prod/login.php"
                 } else{
                  toastr.error('Gagal Logout!')
                 }
        },'json')
    })
})

function format_uang(el){
    el.inputmask('decimal', {
        'alias': 'numeric',
        'groupSeparator': ',',
        'autoGroup': true,
        'digits': 0,
        'radixPoint': '.',
        'digitsOptional': false,
        'allowMinus': false
    })
}

function formatCurrency(num) {
    num = (isNaN(num)||num==null) ? '0' : num
    num = num.toString().replace(/\$|\,/g,'')
    sign = (num == (num = Math.abs(num)))
    num = Math.floor(num*100+0.50000000001)
    cents = num%100
    num = Math.floor(num/100).toString()
    if(cents<10)
        cents = "0" + cents
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
        num = num.substring(0,num.length-(4*i+3))+','+
        num.substring(num.length-(4*i+3));
    return (((sign)?'':'-') + num)
}

function formatNormal(num){
    num = num.toString().replace(/\$|\,/g,'')
    if(isNaN(num))
        num = "0"
    return num
}

function disable_btn(ele){
    ele.prop('disabled',true)
}

function enable_btn(ele){
    ele.prop('disabled',false)
}

function set_date(ele){
    ele.Zebra_DatePicker({
        format:'d-m-Y'
    })
}

function hariIni(){
    let today = new Date()
    let dd = String(today.getDate()).padStart(2, '0')
    let mm = String(today.getMonth() + 1).padStart(2, '0') //January is 0!
    let yyyy = today.getFullYear()
    today = dd + '-' + mm + '-' + yyyy
    return today
}

function formatDmy(str){
    str = str.split('-')
    let tgl = str[2]+'-'+str[1]+'-'+str[0]
    return tgl
}

function statusBadge(sts){
    let status = (sts=='1')?'<span class="badge badge-success">Aktif</span>':'<span class="badge badge-danger">Non-Aktif</span>';
    return status
}

function dropBadge(sts,id){
    let htm = '<select class="form-control-plaintext p-0 select-sts" name="status-pr" data-id='+id+'>'
    if (sts==1){
        htm +='<option value="1" selected>Aktif</option>'
        htm +='<option value="0">Non-Aktif</option>'
    } else {
        htm +='<option value="1">Aktif</option>'
        htm +='<option value="0" selected>Non-Aktif</option>'
    }
    // let slct = (sts=='1')?'true':'false'
    // let htm = '<select class="form-control-plaintext p-0 select-sts" name="status-pr" data-id='+id+'>'
    // htm +='<option value="1" selected='+slct+'>Aktif</option>'
    // htm +='<option value="0" selected='+slct+'>Non-Aktif</option>'
    htm +='</select>'
    return htm
}

function lunasBadge(lunas){
    let status_lunas = (lunas=='Lunas')?'<span class="badge badge-success">Lunas</span>':'<span class="badge badge-danger">Belum</span>';
    return status_lunas
}

function lunasButton(lunas,po,total){
    //let dis = ($po["cara_bayar"]=='LUNAS'||$po["status_bayar"]=='lunas')?'disabled':'enabled';
    let dis = (lunas=='Lunas')?'disabled':'enabled';
    let btncontrol = '<button type="button" data-total="'+total+'" data-toggle="modal" data-target="#modal-bayar" class="btn btn-primary btn-sm btn-bayar" id="'+po+'" '+dis+'>'+
            'Bayar</button>'
        btncontrol +='<button type="button" class="btn-print-po-lap btn btn-success btn-sm ml-2" id="'+po+'">Print</button>'
    return btncontrol
}

function lunasButtonInv(lunas,inv,total){
    //let dis = ($inv["cara_bayar"]=='LUNAS'||$inv["status_bayar"]=='lunas')?'disabled':'enabled';
    let dis = (lunas=='Lunas')?'disabled':'enabled';
    let btncontrol = '<button type="button" data-total-inv="'+total+'" data-toggle="modal" data-target="#modal-bayar-inv" class="btn btn-primary btn-sm btn-bayar-inv" id="'+inv+'" '+dis+'>'+
            'Bayar</button>'
        btncontrol +='<button type="button" class="btn-print-inv-lap btn btn-success btn-sm ml-2" id="'+inv+'">Print</button>'
    return btncontrol
}

function edButton(id){
    let btn = '<button type="button" data-toggle="modal" data-target="#modal-biaya" class="btn btn-warning btn-sm edit-biaya" id="edit-'+id+'">'
        btn +='<i class="fas fa-pencil-alt"></i></button>'
        btn +='<button type="button" class="btn btn-danger btn-sm del-biaya" id="del-'+id+'"><i class="fas fa-trash-alt"></i></button>'
    return btn
}

function edButtonProduk(id){
    let btn = '<button type="button" data-toggle="modal" data-target="#modal-produk" class="btn btn-warning btn-sm edit-produk" id="edit-'+id+'">'
        btn +='<i class="fas fa-pencil-alt"></i></button>'
    return btn
}

function edButtonProdukFr(id){
    let btn = '<button type="button" class="btn btn-warning btn-sm edit-produk-fr" id="'+id+'">'
        btn +='<i class="fas fa-pencil-alt"></i></button>'
    return btn
}

function titlePrint(){
    let awal = $(document).find('input#tgl-awal').val()
    return awal
}

function validAlphaNum(str){
    if(validalphanum.test(str)){
        return str
    }
}

function stokReady(beli,jual,lain){
    let ss = 0
    jual = (jual==null||isNaN(jual)) ? '0' : jual
    lain = (lain==null||isNaN(lain)) ? '0' : lain
    //if (jual != null) {
        ss = parseInt(beli) - parseInt(jual) - parseInt(lain)
    //} else {
        //ss = beli
    //}
    return formatCurrency(ss)
}

function getLinkInv(inv){
    if (inv!=''){
        return '<a href="index.php?action=rpu_pos&nomorinv='+inv+'">'+inv+'</a>'
    }
}

function getLinkPo(po){
    if (po!=''){
        return '<a href="index.php?action=rpu_pembelian&nomorpo='+po+'">'+po+'</a>'
    }
}

function returButton(id){
    let btn = '<button type="button" data-toggle="modal" data-target="#modal-retur-produk" class="btn btn-danger btn-sm retur-produk" id="retur-'+id+'">'
        btn +='<i class="fas fa-undo mr-2"></i>Retur</button>'
    return btn
}
