const urusanUrl = window.location.origin + '/organisasi/urusan/';
const bidangUrl = window.location.origin + '/organisasi/bidang/';
const unitUrl = window.location.origin + '/organisasi/unit/';
const subUnitUrl = window.location.origin + '/organisasi/subunit/';
var user = {};
const confirmDelete = {
    title: 'Apakah Anda yakin?',
    text: "Data yang telah dihapus tidak dapat dikembalikan!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, hapus sekarang!',
    cancelButtonText: 'Tidak, batal hapus!',
    reverseButtons: true
};

var urusan = {};
var bidang = {};
var unit = {};

$(function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET', url: window.location.origin + '/user-info', success: function (data) {
            user = data;
        },
    });

    /**
     * Urusan
     */
    $('#btnTambahUrusan').on('click', function (e) {
        e.preventDefault();
        $('#modalFormUrusan').text('Form Tambah Urusan');
        $('#btnSimpanUrusan').show();
        $('#btnSimpanUbahUrusan').hide();
        $('#formUrusan').trigger('reset');
        $('#modalUrusan').modal('show');
    });

    $('#btnSimpanUrusan').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: urusanUrl + 'store',
            dataType: 'json',
            data: $('#formUrusan').serialize(),
            success: function (data) {
                if (data.status) {
                    html = '';
                    html = '<tr id="rowUrusan' + data.data.id + '"><td id="rowKode' + data.data.id + '">' + data.data.kode + '</td><td><a href="#" id="rowNama' + data.data.id + '" onclick="goToBidangTab(\'' + data.data.id + '\')">' + data.data.nama_urusan + '</a></td>' +
                        '<td><button class="btn btn-xs btn-outline-warning" onclick="editUrusan(\'' + data.data.id + '\')" data-update-url="' + urusanUrl + 'show/' + data.data.id + '" data-urusan-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-xs btn-outline-danger" id="btnDeleteUrusan' + data.data.id + '" onclick="deleteUrusan(' + data.data.id + ')" data-delete-url="' + urusanUrl + 'delete/' + data.data.id + '" data-urusan-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#tableUrusan').prepend(html);
                    successSwal('Berhasil menambah data urusan.');
                    $('#modalUrusan').modal('hide');
                } else {
                    showError(data.error, 'formUrusan');
                }
            },
        });
    });

    /**
     * Bidang
     */
    $('#btnTambahBidang').on('click', function (e) {
        e.preventDefault();
        $('#modalFormBidang').text('Form Tambah Bidang');
        $('#btnSimpanBidang').show();
        $('#btnSimpanUbahBidang').hide();
        $('#formBidang').trigger('reset');

        $('#modalBidang').modal('show');
        $('#urusanId').val(urusan.id);
        $('#urusanBidang').val(urusan.kode + '...' + urusan.nama_urusan);
    });

    $('#btnSimpanBidang').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: bidangUrl + 'store',
            dataType: 'json',
            data: $('#formBidang').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#noDataBidang').hide();
                    $('#tableBidang').show();
                    html = '';
                    html += '<tr id="rowBidang' + data.data.id + '">';
                    html += '<td>' + urusan.kode + '</td>';
                    html += '<td id="rowKodeBidang' + data.data.id + '">' + data.data.kode + '</td>';
                    html += '<td><a href="#" id="rowNamaBidang' + data.data.id + '" onclick="goToUnitTab(\'' + data.data.id + '\')">' + data.data.nama_bidang + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editBidang(\'' + data.data.id + '\')" id="btnEditBidang' + data.data.id + '" data-update-url="" data-bidang-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteBidang' + data.data.id + '" data-delete-url="' + bidangUrl + 'delete/' + data.data.id + '" data-bidang-id="' + data.data.id + '" onclick="deleteBidang(' + data.data.id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                    $('#tableBidangBody').prepend(html);
                    successSwal('Berhasil menambah data bidang.');
                    $('#modalBidang').modal('hide');
                } else {
                    showError(data.error, 'formBidang');
                }
            },
        });
    });

    /**
     * Unit
     */
    $('#btnTambahUnit').on('click', function (e) {
        e.preventDefault();
        $('#modalFormUnit').text('Form Tambah Unit');
        $('#btnSimpanUnit').show();
        $('#btnSimpanUbahUnit').hide();
        $('#formUnit').trigger('reset');

        $('#modalUnit').modal('show');
        $('#bidangId').val(bidang.id);
        $('#bidangUnit').val(bidang.kode + '...' + bidang.nama_bidang);
    });

    $('#btnSimpanUnit').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: unitUrl + 'store',
            dataType: 'json',
            data: $('#formUnit').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#noDataUnit').hide();
                    $('#tableUnit').show();
                    html = '';
                    html += '<tr id="rowUnit' + data.data.id + '">';
                    html += '<td>' + urusan.kode + '</td>';
                    html += '<td>' + bidang.kode + '</td>';
                    html += '<td id="rowKodeUnit' + data.data.id + '">' + data.data.kode + '</td>';
                    html += '<td><a href="#" id="rowNamaUnit' + data.data.id + '" onclick="goToSubUnitTab(\'' + data.data.id + '\')">' + data.data.nama_unit + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editUnit(\'' + data.data.id + '\')" id="btnEditUnit' + data.data.id + '" data-update-url="" data-unit-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteUnit' + data.data.id + '" data-delete-url="' + unitUrl + 'delete/' + data.data.id + '" data-unit-id="' + data.data.id + '" onclick="deleteUnit(' + data.data.id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                    $('#tableUnitBody').prepend(html);
                    successSwal('Berhasil menambah data unit.');
                    $('#modalUnit').modal('hide');
                } else {
                    showError(data.error, 'formUnit');
                }
            },
        });
    });

    /**
     * Unit
     */
    $('#btnTambahSubUnit').on('click', function (e) {
        e.preventDefault();
        $('#modalFormSubUnit').text('Form Tambah Sub Unit');
        $('#btnSimpanSubUnit').show();
        $('#btnSimpanUbahSubUnit').hide();
        $('#formSubUnit').trigger('reset');

        $('#modalSubUnit').modal('show');
        $('#unitId').val(unit.id);
        $('#unitSubUnit').val(unit.kode + '...' + unit.nama_unit);
    });

    $('#btnSimpanSubUnit').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: subUnitUrl + 'store',
            dataType: 'json',
            data: $('#formSubUnit').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#noDataSubUnit').hide();
                    $('#tableSubUnit').show();
                    html = '';
                    html += '<tr id="rowSubUnit' + data.data.id + '">';
                    html += '<td>' + urusan.kode + '</td>';
                    html += '<td>' + bidang.kode + '</td>';
                    html += '<td>' + unit.kode + '</td>';
                    html += '<td id="rowKodeSubUnit' + data.data.id + '">' + data.data.kode + '</td>';
                    html += '<td id="rowNamaSubUnit' + data.data.id + '">' + data.data.nama_sub_unit + '</td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editSubUnit(\'' + data.data.id + '\')" id="btnEditSubUnit' + data.data.id + '" data-update-url="" data-unit-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteSubUnit' + data.data.id + '" data-delete-url="' + subUnitUrl + 'delete/' + data.data.id + '" data-unit-id="' + data.data.id + '" onclick="deleteSubUnit(' + data.data.id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                    $('#tableSubUnitBody').prepend(html);
                    successSwal('Berhasil menambah data unit.');
                    $('#modalSubUnit').modal('hide');
                } else {
                    showError(data.error, 'formSubUnit');
                }
            },
        });
    });

});

function successSwal(message) {
    return Swal.fire(
        'Berhasil!',
        message,
        'success'
    );
}

function showError(error, form) {
    $.each(error, function (key, value) {
        $('#' + form).find($('input[name=' + key + ']')).addClass('is-invalid');
        $('#' + form).find($('#' + key + '_feedback')).text(value);
    });
}

/**
 * Urusan
 */
function editUrusan(id) {
    $('#modalFormUrusan').text('Form Ubah Urusan');
    $('#btnSimpanUrusan').hide();
    $('#btnSimpanUbahUrusan').show();
    $('#formUrusan').trigger('reset');

    $.ajax({
        type: 'GET',
        url: urusanUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#kodeUrusan').val(data.data.kode);
            $('#namaUrusan').val(data.data.nama_urusan);
            $('#urusanId').remove();
            $('#formUrusan').append('<input type="hidden" name="urusan_id" id="urusanId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalUrusan').modal('show');
}

function updateUrusan(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: urusanUrl + 'update/' + $('#urusanId').val(),
        dataType: 'json',
        data: $('#formUrusan').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalUrusan').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: urusanUrl + 'show/' + $('#urusanId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKode' + data.data.id).text(data.data.kode);
                        $('#rowNama' + data.data.id).text(data.data.nama_urusan);
                    },
                });
                successSwal('Berhasil mengubah data urusan.');
            } else {
                showError(data.error, 'formUrusan')
            }
        },
    });
}

function deleteUrusan(id) {
    var url = $('#btnDeleteUrusan' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Data urusan telah dihapus.');
                    $('#rowUrusan' + id).remove();
                },
            });
        }
    })
}

function goToBidangTab(urusanId) {
    $('#content-urusan-tab').removeClass('active');
    $('#urusan').removeClass(['active show']);
    $('#content-bidang-tab').addClass('active');
    $('#bidang').addClass('active show');
    getDataBidang(urusanId);
}

/**
 * Bidang
 */
function getDataBidang(urusanId) {
    var html = '';

    $('#btnTambahBidang').show();
    $('#tableBidang').hide();
    $('#noDataBidang').hide();
    $('#bidangLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: bidangUrl + 'by-urusan/' + urusanId,
        dataType: 'json',
        success: function (data) {
            $('#tableBidangBody').html('');
            html = '';
            if (data.data.length > 0) {
                urusan = data.data[0].urusan;
                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowBidang' + data.data[i].id + '">';
                    html += '<td>' + data.data[i].urusan.kode + '</td>';
                    html += '<td id="rowKodeBidang' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td><a href="#" id="rowNamaBidang' + data.data[i].id + '" onclick="goToUnitTab(\'' + data.data[i].id + '\')">' + data.data[i].nama_bidang + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editBidang(\'' + data.data[i].id + '\')" id="btnEditBidang' + data.data[i].id + '" data-update-url="" data-bidang-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteBidang' + data.data[i].id + '" data-delete-url="' + bidangUrl + 'delete/' + data.data[i].id + '" data-bidang-id="' + data.data[i].id + '" onclick="deleteBidang(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }
                $('#bidangLoader').html('');
                $('#tableBidang').show();
                $('#tableBidangBody').html(html);
            } else {
                $('#noDataBidang').show();
                $.ajax({
                    type: 'GET',
                    url: urusanUrl + 'show/' + urusanId,
                    dataType: 'json',
                    success: function (data) {
                        urusan = data.data;
                    },
                });
                $('#bidangLoader').html('');

            }
        },
    });
}

function editBidang(id) {
    $('#modalFormBidang').text('Form Ubah Bidang');
    $('#btnSimpanBidang').hide();
    $('#btnSimpanUbahBidang').show();
    $('#formBidang').trigger('reset');

    $.ajax({
        type: 'GET',
        url: bidangUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#urusanId').val(urusan.id);
            $('#urusanBidang').val(urusan.kode + '...' + urusan.nama_urusan);
            $('#kodeBidang').val(data.data.kode);
            $('#namaBidang').val(data.data.nama_bidang);
            $('#bidangId').remove();
            $('#formBidang').append('<input type="hidden" name="bidang_id" id="bidangId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalBidang').modal('show');
}

function updateBidang(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: bidangUrl + 'update/' + $('#bidangId').val(),
        dataType: 'json',
        data: $('#formBidang').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalBidang').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: bidangUrl + 'show/' + $('#bidangId').val(),
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        $('#rowKodeBidang' + data.data.id).text(data.data.kode);
                        $('#rowNamaBidang' + data.data.id).text(data.data.nama_bidang);
                    },
                });
                successSwal('Berhasil mengubah data urusan.');
            } else {
                showError(data.error, 'formUrusan')
            }
        },
    });
}

function deleteBidang(id) {
    var url = $('#btnDeleteBidang' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data bidang');
                    $('#rowBidang' + id).remove();
                },
            });
        }
    })
}

function goToUnitTab(bidangId) {
    $('#content-bidang-tab').removeClass('active');
    $('#bidang').removeClass(['active show']);
    $('#content-unit-tab').addClass('active');
    $('#unit').addClass('active show');
    getDataUnit(bidangId);
}

/**
 * Unit
 */
function getDataUnit(bidangId) {
    var html = '';

    $('#btnTambahUnit').show();
    $('#tableUnit').hide();
    $('#noDataUnit').hide();
    $('#unitLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: unitUrl + 'by-bidang/' + bidangId,
        dataType: 'json',
        success: function (data) {
            $('#tableUnitBody').html('');
            html = '';
            if (data.data.length > 0) {
                bidang = data.data[0].bidang;

                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowUnit' + data.data[i].id + '">';
                    html += '<td>' + bidang.urusan.kode + '</td>';
                    html += '<td>' + bidang.kode + '</td>';
                    html += '<td id="rowKodeUnit' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td><a href="#" id="rowNamaUnit' + data.data[i].id + '" onclick="goToSubUnitTab(\'' + data.data[i].id + '\')">' + data.data[i].nama_unit + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editUnit(\'' + data.data[i].id + '\')" id="btnEditUnit' + data.data[i].id + '" data-update-url="" data-unit-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteUnit' + data.data[i].id + '" data-delete-url="' + unitUrl + 'delete/' + data.data[i].id + '" data-unit-id="' + data.data[i].id + '" onclick="deleteUnit(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }

                $('#unitLoader').html('');
                $('#tableUnit').show();
                $('#tableUnitBody').html(html);
            } else {
                $('#noDataUnit').show();
                $.ajax({
                    type: 'GET',
                    url: bidangUrl + 'show/' + bidangId,
                    dataType: 'json',
                    success: function (data) {
                        bidang = data.data;
                        urusan = data.data.urusan;
                    },
                });
                $('#unitLoader').html('');

            }
        },
    });
}

function editUnit(id) {
    $('#modalFormUnit').text('Form Ubah Unit');
    $('#btnSimpanUnit').hide();
    $('#btnSimpanUbahUnit').show();
    $('#formUnit').trigger('reset');

    $.ajax({
        type: 'GET',
        url: unitUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#bidangId').val(bidang.id);
            $('#bidangUnit').val(bidang.kode + '...' + bidang.nama_bidang);
            $('#kodeUnit').val(data.data.kode);
            $('#namaUnit').val(data.data.nama_unit);
            $('#unitId').remove();
            $('#formUnit').append('<input type="hidden" name="unit_id" id="unitId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalUnit').modal('show');
}

function updateUnit(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: unitUrl + 'update/' + $('#unitId').val(),
        dataType: 'json',
        data: $('#formUnit').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalUnit').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: unitUrl + 'show/' + $('#unitId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeUnit' + data.data.id).text(data.data.kode);
                        $('#rowNamaUnit' + data.data.id).text(data.data.nama_unit);
                    },
                });
                successSwal('Berhasil mengubah data unit.');
            } else {
                showError(data.error, 'formUnit')
            }
        },
    });
}

function deleteUnit(id) {
    var url = $('#btnDeleteUnit' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data unit');
                    $('#rowUnit' + id).remove();
                },
            });
        }
    })
}

function goToSubUnitTab(unitId) {
    $('#content-unit-tab').removeClass('active');
    $('#unit').removeClass(['active show']);
    $('#content-subunit-tab').addClass('active');
    $('#subunit').addClass('active show');
    getDataSubUnit(unitId);
}

/**
 * Sub Unit
 */
function getDataSubUnit(unitId) {
    var html = '';

    $('#btnTambahSubUnit').show();
    $('#tableSubUnit').hide();
    $('#noDataSubUnit').hide();
    $('#subUnitLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: subUnitUrl + 'by-unit/' + unitId,
        dataType: 'json',
        success: function (data) {
            $('#tableSubUnitBody').html('');
            html = '';
            if (data.data.length > 0) {
                unit = data.data[0].unit;
                bidang = data.data[0].unit.bidang;
                urusan = data.data[0].unit.bidang.urusan;

                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowSubUnit' + data.data[i].id + '">';
                    html += '<td>' + urusan.kode + '</td>';
                    html += '<td>' + bidang.kode + '</td>';
                    html += '<td>' + unit.kode + '</td>';
                    html += '<td id="rowKodeSubUnit' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td id="rowNamaSubUnit' + data.data[i].id + '">' + data.data[i].nama_sub_unit + '</td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editSubUnit(\'' + data.data[i].id + '\')" id="btnEditSubUnit' + data.data[i].id + '" data-update-url="" data-unit-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteSubUnit' + data.data[i].id + '" data-delete-url="' + subUnitUrl + 'delete/' + data.data[i].id + '" data-unit-id="' + data.data[i].id + '" onclick="deleteSubUnit(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }

                $('#subUnitLoader').html('');
                $('#tableSubUnit').show();
                $('#tableSubUnitBody').html(html);
            } else {
                $('#noDataSubUnit').show();
                $.ajax({
                    type: 'GET',
                    url: unitUrl + 'show/' + unitId,
                    dataType: 'json',
                    success: function (data) {
                        unit = data.data;
                        bidang = data.data.bidang;
                        urusan = data.data.bidang.urusan;
                    },
                });
                $('#subUnitLoader').html('');

            }
        },
    });
}

function editSubUnit(id) {
    $('#modalFormSubUnit').text('Form Ubah Sub Unit');
    $('#btnSimpanSubUnit').hide();
    $('#btnSimpanUbahSubUnit').show();
    $('#formSubUnit').trigger('reset');

    $.ajax({
        type: 'GET',
        url: subUnitUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#unitId').val(unit.id);
            $('#unitSubUnit').val(unit.kode + '...' + unit.nama_unit);
            $('#kodeSubUnit').val(data.data.kode);
            $('#namaSubUnit').val(data.data.nama_sub_unit);
            $('#subUnitId').remove();
            $('#formSubUnit').append('<input type="hidden" name="sub_unit_id" id="subUnitId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalSubUnit').modal('show');
}

function updateSubUnit(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: subUnitUrl + 'update/' + $('#subUnitId').val(),
        dataType: 'json',
        data: $('#formSubUnit').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalSubUnit').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: subUnitUrl + 'show/' + $('#subUnitId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeSubUnit' + data.data.id).text(data.data.kode);
                        $('#rowNamaSubUnit' + data.data.id).text(data.data.nama_sub_unit);
                    },
                });
                successSwal('Berhasil mengubah data sub unit.');
            } else {
                showError(data.error, 'formSubUnit')
            }
        },
    });
}

function deleteSubUnit(id) {
    var url = $('#btnDeleteSubUnit' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data unit');
                    $('#rowSubUnit' + id).remove();
                },
            });
        }
    })
}



