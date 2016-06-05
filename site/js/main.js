/**
 * Created by Seb on 2016-03-08.
 */
$(document).ready(function () {
    $('.dropdown-toggle').dropdown();

    loadListeners();
});

function loadListeners() {
    $('#submit').on('click',function () {
            loadListED($('#DID'),$('#DName'));
            console.log('submit clicked');
        }
    );

    $('.month').on('click',function () {
        loadListAP($(this).attr('id'), $(this).parent().parent().attr('id'));
    })

    $('#iSubmit').on('click',function () {
        loadInv($('#InvNb'));
    })

    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Employee Listeners       ++
    // +++++++++++++++++++++++++++++++++++++
    
    $('#EESubmit').on('click',function () {
        loadEdit($('#EID').val());
    })

    $('#EEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#EdSubmit').on('click',function () {
        console.log('EdSubmit clicked');
        saveEditEmployee('edit');
    })

    $('#EdASubmit').on('click',function () {
        console.log('EdASubmit clicked');
        saveEditEmployee('add');
    })

    $('#EdDSubmit').on('click',function () {
        console.log('EdDSubmit clicked');
        saveEditEmployee('del');
    })


    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Dependant Listeners       ++
    // +++++++++++++++++++++++++++++++++++++

    $('.DedSubmit').on('click',function () {
        saveEditDependant('edit',$(this).parent().parent().data('i'),$(this).parent().parent().data('sin'));
    })

    $('#DedASubmit').on('click',function () {
        saveEditDependant('add','');
    })

    $('.DedDSubmit').on('click',function () {
        saveEditDependant('del',$(this).parent().parent().data('i'),$(this).parent().parent().data('sin'));
    })
    

    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Customer Listeners       ++
    // +++++++++++++++++++++++++++++++++++++

    $('#CESubmit').on('click',function () {
        loadEdit($('#CName').val());
    })

    $('#CEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#CdSubmit').on('click',function () {
        console.log('CdSubmit clicked');
        saveEditCustomer('edit');
    })

    $('#CdASubmit').on('click',function () {
        console.log('CdASubmit clicked');
        saveEditCustomer('add');
    })

    $('#CdDSubmit').on('click',function () {
        console.log('CdDSubmit clicked');
        saveEditCustomer('del');
    })

    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Department Listeners     ++
    // +++++++++++++++++++++++++++++++++++++

    $('#DESubmit').on('click',function () {
        loadEdit($('#DID').val());
    })

    $('#DEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#DdSubmit').on('click',function () {
        saveEditDepartment('edit');
    })

    $('#DdASubmit').on('click',function () {
        saveEditDepartment('add');
    })

    $('#DdDSubmit').on('click',function () {
        saveEditDepartment('del');
    })


    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Orders Listeners         ++
    // +++++++++++++++++++++++++++++++++++++

    $('#OESubmit').on('click',function () {
        loadEdit($('#OID').val());
    })

    $('#OEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#OdSubmit').on('click',function () {
        saveEditOrder('edit');
    })

    $('#OdASubmit').on('click',function () {
        saveEditOrder('add');
    })

    $('#OdDSubmit').on('click',function () {
        saveEditOrder('del');
    })


    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Item Listeners          ++
    // +++++++++++++++++++++++++++++++++++++

    $('#ItESubmit').on('click',function () {
        loadEdit($('#IID').val());
    })

    $('#ItEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#ItdSubmit').on('click',function () {
        saveEditItem('edit');
    })

    $('#ItdASubmit').on('click',function () {
        saveEditItem('add');
    })

    $('#ItdDSubmit').on('click',function () {
        saveEditItem('del');
    })


    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Shipment Listeners       ++
    // +++++++++++++++++++++++++++++++++++++

    $('#SESubmit').on('click',function () {
        loadEdit($('#OID').val());
    })

    $('#SEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#SdSubmit').on('click',function () {
        saveEditShipment('edit');
    })

    $('#SdASubmit').on('click',function () {
        saveEditShipment('add');
    })

    $('#SdDSubmit').on('click',function () {
        saveEditShipment('del');
    })

    // +++++++++++++++++++++++++++++++++++++
    // ++   Edit Inventory Listeners      ++
    // +++++++++++++++++++++++++++++++++++++

    $('#IESubmit').on('click',function () {
        loadEdit($('#LotNb').val());
    })

    $('#IEASubmit').on('click',function () {
        loadEdit('add');
    })

    $('#IdSubmit').on('click',function () {
        saveEditInventory('edit');
    })

    $('#IdASubmit').on('click',function () {
        saveEditInventory('add');
    })

    $('#IdDSubmit').on('click',function () {
        saveEditInventory('del');
    })
    
}//-------------------



$(".nav a").on("click", function(){
    $(".nav").find(".active").removeClass("active");
    $(this).parent().addClass("active");
});

/**/

function loadListED(id, name) {

    console.log('loadListED');
    var url = window.location.href;
    var ret;
    console.log('id' + id + id.val());
    console.log('name' + name + name.val());
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            DID: id.val(),
            DName: name.val()
        },
        success: function (a, b) {
            //console.log('ajax succeded');
            //console.log('a');
            //console.log(a);
            ret = a;
            //console.log(b);

            //var jsonObj = JSON.parse(jsonReturn);
            //var table = jsonObj.res;
            $('.sec').html(ret);

            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            //console.log('ajax failed');
            //console.log(a);
            //console.log(b);
            //console.log(c);
            alert('an error happend please retry');
            //console.log('ajax call ended');
        }
    });
}

function loadListAP(month, year) {

    console.log('loadListED');
    var url = window.location.href;
    var ret;
    console.log('month' + month);
    console.log('year' + year);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            month: month,
            year: year
        },
        success: function (a, b) {
            //console.log('ajax succeded');
            //console.log('a');
            //console.log(a);
            ret = a;
            //console.log(b);
            $('.sec').html(ret);
            //console.log('ajax call ended');
        },
        error: function (a, b, c) {
            //console.log('ajax failed');
            //console.log(a);
            //console.log(b);
            //console.log(c);
            alert('an error happend please retry');
            //console.log('ajax call ended');
        }
    });
}

function loadInv(inum) {

    console.log('loadInv');
    var url = window.location.href;
    var ret;
    console.log('inum' + inum + inum.val());
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            inum: inum.val()
        },
        success: function (a, b) {
            //console.log('ajax succeded');
            //console.log('a');
            //console.log(a);
            ret = a;
            //console.log(b);
            $('.sec').html(ret);
            //console.log('ajax call ended');
        },
        error: function (a, b, c) {
            //console.log('ajax failed');
            //console.log(a);
            //console.log(b);
            //console.log(c);
            alert('an error happend please retry');
            //console.log('ajax call ended');
        }
    });
}

function loadEdit(id) {

    console.log('loadEdit');
    var url = window.location.href;
    console.log('id' + id);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            edid: id
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
            loadListeners();
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
            loadListeners();
        }
    });
}

function saveEditEmployee(action) {
    
    console.log(action);
    
    var eid = $('#eid').html();
    var ename = $('#EName').val();
    var eAddress = $('#EAddress').val();
    var edob = $('#EDoB').val();
    var epos = $('#EPos').val();
    var ephone = $('#EPhone').val();
    var esin = $('#ESIN').val();
                    
    console.log('saveEdit');
    var url = window.location.href;
    
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            eid: eid,
            ename: ename,
            eAddress: eAddress,
            edob: edob,
            epos: epos,
            ephone: ephone,
            esin: esin
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditCustomer(action) {

    console.log(action);

    var cnamekey = $('#cNameKey').html();
    var cname = $('#CName').val();
    var cAddress = $('#CAddress').val();
    var cphone = $('#CPhone').val();

    console.log('saveEditEmployee');
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            cnamekey: cnamekey,
            cname: cname,
            cAddress: cAddress,
            cphone: cphone,
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditDepartment(action) {

    console.log(action);

    var did = $('#DID').html();
    var dname = $('#DName').val();
    var droom = $('#DRoom').val();
    var dphone1 = $('#DPhone1').val();
    var dphone2 = $('#DPhone2').val();
    var dfax = $('#DFax').val();
    
    console.log('saveEditEmployee');
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            did: did,
            dname: dname,
            droom: droom,
            dphone1: dphone1,
            dphone2: dphone2,
            dfax: dfax
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditOrder(action) {

    console.log(action);

    var oid = $('#OID').html();
    var ocname = $('#OCName').val();
    var odate = $('#ODate').val();

    console.log('saveEditEmployee');
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            oid: oid,
            ocname: ocname,
            odate: odate,
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditItem(action) {

    console.log(action);

    var iid = $('#IID').html();
    var iname = $('#ItName').val();

    console.log('saveEditEmployee');
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            iid: iid,
            iname: iname
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditShipment(action) {

    console.log(action);

    var oid = $('#OID').html();
    var soid = $('#SOID').val();
    var sdate = $('#SDate').val();

    console.log('saveEditEmployee');
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            oid: oid,
            soid: soid,
            sdate: sdate,
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditInventory(action) {

    console.log(action);

    var lotnb = $('#LotNb').html();
    var sku = $('#SKU').val();
    var dom = $('#DOM').val();
    var nbi = $('#NBI').val();
    var up = $('#UP').val();

    console.log('saveEditEmployee');
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            action: action,
            lotnb: lotnb,
            sku: sku,
            dom: dom,
            nbi: nbi,
            up: up
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}

function saveEditDependant(daction, i, sin) {

    console.log(daction);

    var eid = $('#eid').html();
    var dname = $('#DName'+i).val();
    var ddob = $('#DDoB' + i).val();
    var dsin = $('#DSIN'+i).val();
    
    var url = window.location.href;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            daction: daction,
            eid: eid,
            sin: sin,
            dname: dname,
            ddob: ddob,
            dsin: dsin
        },
        success: function (a, b) {
            console.log('ajax succeded');
            console.log(a);
            console.log(b);
            $('.sec').html(a);
            console.log('ajax call ended');
        },
        error: function (a, b, c) {
            console.log('ajax failed');
            console.log(a);
            console.log(b);
            console.log(c);
            alert('an error happened please retry');
            console.log('ajax call ended');
        }
    });
}