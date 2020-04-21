const service_url='./services/'
const validnum = /^[0-9]+$/

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
    //$hutang = ($po["cara_bayar"]=='LUNAS'||$po["status_bayar"]=='lunas')?'0':$po["grand_total"];
}

function lunasButton(lunas,po,total){
    //let dis = ($po["cara_bayar"]=='LUNAS'||$po["status_bayar"]=='lunas')?'disabled':'enabled';
    let dis = (lunas=='Lunas')?'disabled':'enabled';
    let btncontrol = '<button type="button" data-total="'+total+'" data-toggle="modal" data-target="#modal-bayar" class="btn btn-primary btn-sm btn-bayar" id="'+po+'" '+dis+'>'+
            '<i class="fas fa-handshake mr-2"></i>Bayar</button>'
    return btncontrol
    //$hutang = ($po["cara_bayar"]=='LUNAS'||$po["status_bayar"]=='lunas')?'0':$po["grand_total"];
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

function dt_print( e, dt, button, config, has_child_rows ) {
    var data = dt.buttons.exportData( config.exportOptions );
    var addRow = function ( d, tag, child_row ) {
        var str = '<tr>';

        for ( var i = 0, ien = d.length; i < ien; i++ ) {
            if (has_child_rows && ! child_row && i === 0) continue;
            str += '<' + tag + '>' + d[i] + '</' + tag + '>';
        }

        return str + '</tr>';
    };

    var addSubTable = function ( subtable, colspan ) {
        var str = '<tr class="innertable-row">'
                +     '<td class="innertable-row" colspan="' + colspan + '">';

        // If there is no subtable, there must be other child row content, display that
        if ( subtable.length == 0 ) {
            str += '<div style="text-align: left">'
            str += $( dt.row( row_idx ).child() ).children().children().html();
            return str + '<div></td></tr>';
        }

        // Add header row
        var headers = subtable.find('tr').first().find('th,td');

        str += '<table class="dataTable no-footer">'
            +    '<thead><tr>';

        for ( var i = 0; i < headers.length; i++ ) {
            str += '<th>' + headers.eq(i).text() + '</th>';
        }

        str += '</tr></thead><tbody>';

        // Add body rows
        subtable.find('tbody').children('tr').each(function(index, tr) {
            var lines = $('td', tr).map(function(index, td) {
                return $(td).text();
            });
            str += addRow( lines, 'td', true );
        });

        return str + '</tbody></table></td></tr>';
    };

    // Construct a table for printing
    var html = '<table class="' + dt.table().node().className + '">';

    if ( config.header ) {
        html += '<thead>' + addRow( data.header, 'th' ) + '</thead>';
    }

    html += '<tbody>';
    for ( var i = 0, ien = data.body.length; i < ien; i++ ) {
        html += addRow( data.body[i], 'td' );

        if ( has_child_rows ) {
            var row_idx = data.body[i][0];
            if ( dt.row( row_idx ).child() && dt.row( row_idx ).child.isShown() ) {
                html += addSubTable( $( dt.row( row_idx ).child() ).find( 'table:visible' ), data.body[0].length );
            }
        }
    }
    html += '</tbody>';

    if ( config.footer && data.footer ) {
        html += '<tfoot>' + addRow( data.footer, 'th' ) +'</tfoot>';
    }

    // Open a new window for the printable table
    var win = window.open();
    var title = config.title;

    if ( typeof title === 'function' ) {
        title = title();
    }

    if ( title.indexOf( '*' ) !== -1 ) {
        title = title.replace( '*', $('title').text() );
    }

    win.document.close();

    // Inject the title and also a copy of the style and link tags from this
    // document so the table can retain its base styling. Note that we have
    // to use string manipulation as IE won't allow elements to be created
    // in the host document and then appended to the new window.
    var head = '<title>'+title+'</title>';
    $('style, link').each( function() {
        head += _styleToAbs( this );
    });

    try {
        win.document.head.innerHTML = head; // Work around for Edge
    }
    catch (e) {
        $( win.document.head ).html( head ); // Old IE
    }


    // Inject the table and other surrounding information
    win.document.body.innerHTML =
          '<h1>' + title + '</h1>'
        + '<div>'
        +    (typeof config.message === 'function' ?
                config.message( dt, button, config ) :
                config.message
            )
        + '</div>'
        + html;


    $( win.document.body ).addClass('dt-print-view');

    $('img', win.document.body).each( function ( i, img ) {
        img.setAttribute( 'src', _relToAbs( img.getAttribute('src') ));
    });

    if ( config.customize ) {
        config.customize( win );
    }

    setTimeout( function() {
        if ( config.autoPrint ) {
            win.print();
            win.close();
        }
    }, 250 );
}




