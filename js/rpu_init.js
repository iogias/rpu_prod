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
    num = num.toString().replace(/\$|\,/g,'')
    if(isNaN(num))
        num = "0";
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

function formatDmy(str){
    str = str.split('-')
    let tgl = str[2]+'-'+str[1]+'-'+str[0]
    return tgl
}

function lunasBadge(lunas){
    let status_lunas = (lunas=='Lunas')?'<span class="badge badge-success">Lunas</span>':'<span class="badge badge-danger">Belum</span>';
    return status_lunas
}

function lunasButton(lunas,po,total){
    //let dis = ($po["cara_bayar"]=='LUNAS'||$po["status_bayar"]=='lunas')?'disabled':'enabled';
    let dis = (lunas=='Lunas')?'disabled':'enabled';
    let btncontrol = '<button type="button" data-total="'+total+'" data-toggle="modal" data-target="#modal-bayar" class="btn btn-primary btn-sm btn-bayar" id="'+po+'" '+dis+'>'+
            '<i class="fas fa-handshake mr-2"></i>Bayar</button>'
    return btncontrol
}

function edButton(id){
    let btn = '<button type="button" data-toggle="modal" data-target="#modal-biaya" class="btn btn-warning btn-sm edit-biaya" id="edit-'+id+'">'
        btn +='<i class="fas fa-pencil-alt"></i></button>'
        btn +='<button type="button" class="btn btn-danger btn-sm del-biaya" id="del-'+id+'"><i class="fas fa-trash-alt"></i></button>'
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
