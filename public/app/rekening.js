const akunUrl = window.location.origin + '/rekening/akun/';
const kelompokUrl = window.location.origin + '/rekening/kelompok/';
const jenisUrl = window.location.origin + '/rekening/jenis/';
const objekUrl = window.location.origin + '/rekening/objek/';
const rincianObjekUrl = window.location.origin + '/rekening/rincian-objek/';
var user = {};
var akun = {};
var kelompok = {};
var jenis = {};
var objek = {};

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

$(function () {
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
     * Rekening Akun
     */
    $('#btnTambahAkun').on('click', function (e) {
        e.preventDefault();
        $('#modalFormAkun').text('Form Tambah Akun');
        $('#btnSimpanUbahAkun').hide();
        $('#btnSimpanAkun').show();
        $('#formAkun').trigger('reset');
        $('#modalAkun').modal('show');
    });

    $('#btnSimpanAkun').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: akunUrl + 'store',
            dataType: 'json',
            data: $('#formAkun').serialize(),
            success: function (data) {
                if (data.status) {
                    html = '';
                    html = '<tr id="rowAkun' + data.data.id + '"><td id="rowKodeAkun' + data.data.id + '">' + data.data.kode + '</td><td><a href="#" id="rowNamaAkun' + data.data.id + '" onclick="goToKelompokTab(\'' + data.data.id + '\')">' + data.data.nama_akun + '</a></td>' +
                        '<td><button class="btn btn-xs btn-outline-warning" onclick="editAkun(\'' + data.data.id + '\')" data-update-url="' + akunUrl + 'show/' + data.data.id + '" data-akun-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-xs btn-outline-danger" id="btnDeleteAkun' + data.data.id + '" onclick="deleteAkun(' + data.data.id + ')" data-delete-url="' + akunUrl + 'delete/' + data.data.id + '" data-akun-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#tableAkun').prepend(html);
                    successSwal('Berhasil menambah data akun.');
                    $('#modalAkun').modal('hide');
                } else {
                    showError(data.error, 'formAkun');
                }
            },
        });
    });

    /**
     * Rekening Kelompok
     */
    $('#btnTambahKelompok').on('click', function (e) {
        e.preventDefault();
        $('#modalFormKelompok').text('Form Tambah kelompok');
        $('#btnSimpanUbahKelompok').hide();
        $('#btnSimpanKelompok').show();
        $('#formKelompok').trigger('reset');
        $('#modalKelompok').modal('show');
        $('#akunId').val(akun.id);
        $('#akunKelompok').val(akun.kode + '. ' + akun.nama_akun);
    });

    $('#btnSimpanKelompok').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: kelompokUrl + 'store',
            dataType: 'json',
            data: $('#formKelompok').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#tableKelompok').show();
                    $('#noDataKelompok').hide();

                    html = '';
                    html = '<tr id="rowKelompok' + data.data.id + '"><td>' + akun.kode + '</td><td id="rowKodeKelompok' + data.data.id + '">' + data.data.kode + '</td><td><a href="#" id="rowNamaKelompok' + data.data.id + '" onclick="goToJenisTab(\'' + data.data.id + '\')">' + data.data.nama_kelompok + '</a></td>' +
                        '<td><button class="btn btn-xs btn-outline-warning" onclick="editKelompok(\'' + data.data.id + '\')" data-update-url="' + kelompokUrl + 'show/' + data.data.id + '" data-kelompok-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-xs btn-outline-danger" id="btnDeleteKelompok' + data.data.id + '" onclick="deleteKelompok(' + data.data.id + ')" data-delete-url="' + kelompokUrl + 'delete/' + data.data.id + '" data-kelompok-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#tableKelompokBody').prepend(html);
                    successSwal('Berhasil menambah data kelompok.');
                    $('#modalKelompok').modal('hide');
                } else {
                    showError(data.error, 'formKelompok');
                }
            },
        });
    });

    /**
     * Rekening Jenis
     */
    $('#btnTambahJenis').on('click', function (e) {
        e.preventDefault();
        $('#modalFormJenis').text('Form Tambah Jenis');
        $('#btnSimpanUbahJenis').hide();
        $('#btnSimpanJenis').show();
        $('#formJenis').trigger('reset');
        $('#modalJenis').modal('show');
        $('#kelompokId').val(kelompok.id);
        $('#kelompokJenis').val(akun.kode + '.' + kelompok.kode + '. ' + kelompok.nama_kelompok);
    });

    $('#btnSimpanJenis').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: jenisUrl + 'store',
            dataType: 'json',
            data: $('#formJenis').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#tableJenis').show();
                    $('#noDataJenis').hide();

                    html = '';
                    html = '<tr id="rowJenis' + data.data.id + '"><td>' + akun.kode + '</td><td>' + kelompok.kode + '</td><td id="rowKodeJenis' + data.data.id + '">' + data.data.kode + '</td><td><a href="#" id="rowNamaJenis' + data.data.id + '" onclick="goToObjekTab(\'' + data.data.id + '\')">' + data.data.nama_jenis + '</a></td>' +
                        '<td><button class="btn btn-xs btn-outline-warning" onclick="editJenis(\'' + data.data.id + '\')" data-update-url="' + jenisUrl + 'show/' + data.data.id + '" data-jenis-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-xs btn-outline-danger" id="btnDeleteJenis' + data.data.id + '" onclick="deleteJenis(' + data.data.id + ')" data-delete-url="' + jenisUrl + 'delete/' + data.data.id + '" data-jenis-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#tableJenisBody').prepend(html);
                    successSwal('Berhasil menambah data jenis.');
                    $('#modalJenis').modal('hide');
                } else {
                    showError(data.error, 'formJenis');
                }
            },
        });
    });

    /**
     * Rekening Objek
     */
    $('#btnTambahObjek').on('click', function (e) {
        e.preventDefault();
        $('#modalFormObjek').text('Form Tambah Objek');
        $('#btnSimpanUbahObjek').hide();
        $('#btnSimpanObjek').show();
        $('#formObjek').trigger('reset');
        $('#modalObjek').modal('show');
        $('#jenisId').val(jenis.id);
        $('#jenisObjek').val(akun.kode + '.' + kelompok.kode + '.' + jenis.kode + '. ' + jenis.nama_jenis);
    });

    $('#btnSimpanObjek').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: objekUrl + 'store',
            dataType: 'json',
            data: $('#formObjek').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#tableObjek').show();
                    $('#noDataObjek').hide();

                    html = '';
                    html = '<tr id="rowObjek' + data.data.id + '"><td>' + akun.kode + '</td><td>' + kelompok.kode + '</td><td>' + jenis.kode + '</td><td id="rowKodeObjek' + data.data.id + '">' + data.data.kode + '</td><td><a href="#" id="rowNamaObjek' + data.data.id + '" onclick="goToRincianObjekTab(\'' + data.data.id + '\')">' + data.data.nama_obyek + '</a></td>' +
                        '<td><button class="btn btn-xs btn-outline-warning" onclick="editObjek(\'' + data.data.id + '\')" data-update-url="' + objekUrl + 'show/' + data.data.id + '" data-objek-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-xs btn-outline-danger" id="btnDeleteObjek' + data.data.id + '" onclick="deleteObjek(' + data.data.id + ')" data-delete-url="' + objekUrl + 'delete/' + data.data.id + '" data-objek-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#tableObjekBody').prepend(html);
                    successSwal('Berhasil menambah data objek.');
                    $('#modalObjek').modal('hide');
                } else {
                    showError(data.error, 'formObjek');
                }
            },
        });
    });

    /**
     * Rekening Rincian Objek
     */
    $('#btnTambahRincianObjek').on('click', function (e) {
        e.preventDefault();
        $('#modalFormRincianObjek').text('Form Tambah Rincian Objek');
        $('#btnSimpanUbahRincianObjek').hide();
        $('#btnSimpanRincianObjek').show();
        $('#formRincianObjek').trigger('reset');
        $('#modalRincianObjek').modal('show');
        $('#obyekId').val(objek.id);
        $('#objekRincianObjek').val(akun.kode + '.' + kelompok.kode + '.' + jenis.kode + '.' + objek.kode + '. ' + objek.nama_obyek);
    });

    $('#btnSimpanRincianObjek').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: rincianObjekUrl + 'store',
            dataType: 'json',
            data: $('#formRincianObjek').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#tableRincianObjek').show();
                    $('#noDataRincianObjek').hide();

                    html = '';
                    html = '<tr id="rowRincianObjek' + data.data.id + '"><td>' + akun.kode + '</td><td>' + kelompok.kode + '</td><td>' + jenis.kode + '</td><td>' + objek.kode + '</td><td id="rowKodeRincianObjek' + data.data.id + '">' + data.data.kode + '</td><td id="rowNamaRincianObjek' + data.data.id + '">' + data.data.nama_rincian_obyek + '</td>' +
                        '<td><button class="btn btn-xs btn-outline-warning" onclick="editRincianObjek(\'' + data.data.id + '\')" data-update-url="' + rincianObjekUrl + 'show/' + data.data.id + '" data-rincian-objek-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-xs btn-outline-danger" id="btnDeleteRincianObjek' + data.data.id + '" onclick="deleteRincianObjek(' + data.data.id + ')" data-delete-url="' + rincianObjekUrl + 'delete/' + data.data.id + '" data-rincian-objek-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#tableRincianObjekBody').prepend(html);
                    successSwal('Berhasil menambah data rincian objek.');
                    $('#modalRincianObjek').modal('hide');
                } else {
                    showError(data.error, 'formRincianObjek');
                }
            },
        });
    });
});

function successSwal(message) {
    return Swal.fire(
        {
            title: "Berhasil!",
            text: message,
            type: "success",
            onOpen: () => Swal.getConfirmButton().focus(),
        }
    );
}

function showError(error, form) {
    $.each(error, function (key, value) {
        $('#' + form).find($('input[name=' + key + ']')).addClass('is-invalid');
        $('#' + form).find($('#' + key + '_feedback')).text(value);
    });
}

/**
 * Rekening Akun
 */
function editAkun(id) {
    $('#modalFormAkun').text('Form Ubah Akun');
    $('#btnSimpanAkun').hide();
    $('#btnSimpanUbahAkun').show();
    $('#formAkun').trigger('reset');

    $.ajax({
        type: 'GET',
        url: akunUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#kodeAkun').val(data.data.kode);
            $('#namaAkun').val(data.data.nama_akun);
            $('#aliasAkun').val(data.data.alias);
            $('#akunId').remove();
            $('#formAkun').append('<input type="hidden" name="akun_id" id="akunId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalAkun').modal('show');
}

function updateAkun(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: akunUrl + 'update/' + $('#akunId').val(),
        dataType: 'json',
        data: $('#formAkun').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalAkun').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: akunUrl + 'show/' + $('#akunId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeAkun' + data.data.id).text(data.data.kode);
                        $('#rowNamaAkun' + data.data.id).text(data.data.nama_akun);
                    },
                });
                successSwal('Berhasil mengubah data akun.');
            } else {
                showError(data.error, 'formAkun')
            }
        },
    });
}

function deleteAkun(id) {
    var url = $('#btnDeleteAkun' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Data akun telah dihapus.');
                    $('#rowAkun' + id).remove();
                },
            });
        }
    })
}

function goToKelompokTab(akunId) {
    $('#content-akun-tab').removeClass('active');
    $('#akun').removeClass(['active show']);
    $('#content-kelompok-tab').addClass('active');
    $('#kelompok').addClass('active show');
    getDataKelompok(akunId);
}


/**
 * Rekening Kelompok
 */
function getDataKelompok(akunId) {
    var html = '';

    $('#btnTambahKelompok').show();
    $('#tableKelompok').hide();
    $('#noDataKelompok').hide();
    $('#kelompokLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: kelompokUrl + 'by-akun/' + akunId,
        dataType: 'json',
        success: function (data) {
            $('#tableKelompokBody').html('');
            html = '';
            if (data.data.length > 0) {
                akun = data.data[0].akun;
                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowKelompok' + data.data[i].id + '">';
                    html += '<td>' + data.data[i].akun.kode + '</td>';
                    html += '<td id="rowKodeKelompok' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td><a href="#" id="rowNamaKelompok' + data.data[i].id + '" onclick="goToJenisTab(\'' + data.data[i].id + '\')">' + data.data[i].nama_kelompok + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editKelompok(\'' + data.data[i].id + '\')" id="btnEditKelompok' + data.data[i].id + '" data-update-url="" data-kelompok-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteKelompok' + data.data[i].id + '" data-delete-url="' + kelompokUrl + 'delete/' + data.data[i].id + '" data-kelompok-id="' + data.data[i].id + '" onclick="deleteKelompok(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }
                $('#kelompokLoader').html('');
                $('#tableKelompok').show();
                $('#tableKelompokBody').html(html);
            } else {
                $('#noDataKelompok').show();
                $.ajax({
                    type: 'GET',
                    url: akunUrl + 'show/' + akunId,
                    dataType: 'json',
                    success: function (data) {
                        akun = data.data;
                    },
                });
                $('#kelompokLoader').html('');
            }
        },
    });
}

function editKelompok(id) {
    $('#modalFormKelompok').text('Form Ubah Kelompok');
    $('#btnSimpanKelompok').hide();
    $('#btnSimpanUbahKelompok').show();
    $('#formKelompok').trigger('reset');

    $.ajax({
        type: 'GET',
        url: kelompokUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#akunId').val(akun.id);
            $('#akunKelompok').val(akun.kode + '. ' + akun.nama_akun);
            $('#kodeKelompok').val(data.data.kode);
            $('#namaKelompok').val(data.data.nama_kelompok);
            $('#kelompokId').remove();
            $('#formKelompok').append('<input type="hidden" name="kelompok_id" id="kelompokId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalKelompok').modal('show');
}

function updateKelompok(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: kelompokUrl + 'update/' + $('#kelompokId').val(),
        dataType: 'json',
        data: $('#formKelompok').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalKelompok').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: kelompokUrl + 'show/' + $('#kelompokId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeKelompok' + data.data.id).text(data.data.kode);
                        $('#rowNamaKelompok' + data.data.id).text(data.data.nama_kelompok);
                    },
                });
                successSwal('Berhasil mengubah data kelompok.');
            } else {
                showError(data.error, 'formKelompok')
            }
        },
    });
}

function deleteKelompok(id) {
    var url = $('#btnDeleteKelompok' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data kelompok');
                    $('#rowKelompok' + id).remove();
                },
            });
        }
    })
}

function goToJenisTab(kelompokId) {
    $('#content-kelompok-tab').removeClass('active');
    $('#kelompok').removeClass(['active show']);
    $('#content-jenis-tab').addClass('active');
    $('#jenis').addClass('active show');
    getDataJenis(kelompokId);
}

/**
 * Rekening Jenis
 */
function getDataJenis(kelompokId) {
    var html = '';

    $('#btnTambahJenis').show();
    $('#tableJenis').hide();
    $('#noDataJenis').hide();
    $('#jenisLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: jenisUrl + 'by-kelompok/' + kelompokId,
        dataType: 'json',
        success: function (data) {
            $('#tableJenisBody').html('');
            html = '';
            if (data.data.length > 0) {
                kelompok = data.data[0].kelompok;
                akun = data.data[0].kelompok.akun;
                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowJenis' + data.data[i].id + '">';
                    html += '<td>' + akun.kode + '</td>';
                    html += '<td>' + kelompok.kode + '</td>';
                    html += '<td id="rowKodeJenis' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td><a href="#" id="rowNamaJenis' + data.data[i].id + '" onclick="goToObjekTab(\'' + data.data[i].id + '\')">' + data.data[i].nama_jenis + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editJenis(\'' + data.data[i].id + '\')" id="btnEditJenis' + data.data[i].id + '" data-update-url="" data-jenis-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteJenis' + data.data[i].id + '" data-delete-url="' + jenisUrl + 'delete/' + data.data[i].id + '" data-jenis-id="' + data.data[i].id + '" onclick="deleteJenis(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }
                $('#jenisLoader').html('');
                $('#tableJenis').show();
                $('#tableJenisBody').html(html);
            } else {
                $('#noDataJenis').show();
                $.ajax({
                    type: 'GET',
                    url: kelompokUrl + 'show/' + kelompokId,
                    dataType: 'json',
                    success: function (data) {
                        kelompok = data.data;
                        akun = data.data.akun;
                    },
                });
                $('#jenisLoader').html('');
            }
        },
    });
}

function editJenis(id) {
    $('#modalFormJenis').text('Form Ubah Jenis');
    $('#btnSimpanJenis').hide();
    $('#btnSimpanUbahJenis').show();
    $('#formJenis').trigger('reset');

    $.ajax({
        type: 'GET',
        url: jenisUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#kelompokId').val(kelompok.id);
            $('#kelompokJenis').val(akun.kode + '.' + kelompok.kode + '. ' + kelompok.nama_kelompok);
            $('#kodeJenis').val(data.data.kode);
            $('#namaJenis').val(data.data.nama_jenis);
            $('#jenisId').remove();
            $('#formJenis').append('<input type="hidden" name="jenis_id" id="jenisId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalJenis').modal('show');
}

function updateJenis(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: jenisUrl + 'update/' + $('#jenisId').val(),
        dataType: 'json',
        data: $('#formJenis').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalJenis').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: jenisUrl + 'show/' + $('#jenisId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeJenis' + data.data.id).text(data.data.kode);
                        $('#rowNamaJenis' + data.data.id).text(data.data.nama_jenis);
                    },
                });
                successSwal('Berhasil mengubah data jenis.');
            } else {
                showError(data.error, 'formJenis')
            }
        },
    });
}

function deleteJenis(id) {
    var url = $('#btnDeleteJenis' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data jenis');
                    $('#rowJenis' + id).remove();
                },
            });
        }
    })
}

function goToObjekTab(jenisId) {
    $('#content-jenis-tab').removeClass('active');
    $('#jenis').removeClass(['active show']);
    $('#content-objek-tab').addClass('active');
    $('#objek').addClass('active show');
    getDataObjek(jenisId);
}

/**
 * Rekening Objek
 */
function getDataObjek(jenisId) {
    var html = '';

    $('#btnTambahObjek').show();
    $('#tableObjek').hide();
    $('#noDataObjek').hide();
    $('#objekLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: objekUrl + 'by-jenis/' + jenisId,
        dataType: 'json',
        success: function (data) {
            $('#tableObjekBody').html('');
            html = '';
            if (data.data.length > 0) {
                jenis = data.data[0].jenis;
                kelompok = data.data[0].jenis.kelompok;
                akun = data.data[0].jenis.kelompok.akun;
                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowObjek' + data.data[i].id + '">';
                    html += '<td>' + akun.kode + '</td>';
                    html += '<td>' + kelompok.kode + '</td>';
                    html += '<td>' + jenis.kode + '</td>';
                    html += '<td id="rowKodeObjek' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td><a href="#" id="rowNamaObjek' + data.data[i].id + '" onclick="goToRincianObjekTab(\'' + data.data[i].id + '\')">' + data.data[i].nama_obyek + '</a></td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editObjek(\'' + data.data[i].id + '\')" id="btnEditObjek' + data.data[i].id + '" data-update-url="" data-objek-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteObjek' + data.data[i].id + '" data-delete-url="' + objekUrl + 'delete/' + data.data[i].id + '" data-objek-id="' + data.data[i].id + '" onclick="deleteObjek(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }
                $('#objekLoader').html('');
                $('#tableObjek').show();
                $('#tableObjekBody').html(html);
            } else {
                $('#noDataObjek').show();
                $.ajax({
                    type: 'GET',
                    url: jenisUrl + 'show/' + jenisId,
                    dataType: 'json',
                    success: function (data) {
                        jenis = data.data;
                        kelompok = data.data.kelompok;
                        akun = data.data.kelompok.akun;
                    },
                });
                $('#objekLoader').html('');
            }
        },
    });
}

function editObjek(id) {
    $('#modalFormObjek').text('Form Ubah Objek');
    $('#btnSimpanObjek').hide();
    $('#btnSimpanUbahObjek').show();
    $('#formObjek').trigger('reset');

    $.ajax({
        type: 'GET',
        url: objekUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#jeniskId').val(jenis.id);
            $('#jenisObjek').val(akun.kode + '.' + kelompok.kode + '.' + jenis.kode + '. ' + jenis.nama_jenis);
            $('#kodeObjek').val(data.data.kode);
            $('#namaObjek').val(data.data.nama_obyek);
            $('#objekId').remove();
            $('#formObjek').append('<input type="hidden" name="objek_id" id="objekId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalObjek').modal('show');
}

function updateObjek(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: objekUrl + 'update/' + $('#objekId').val(),
        dataType: 'json',
        data: $('#formObjek').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalObjek').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: objekUrl + 'show/' + $('#objekId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeObjek' + data.data.id).text(data.data.kode);
                        $('#rowNamaObjek' + data.data.id).text(data.data.nama_obyek);
                    },
                });
                successSwal('Berhasil mengubah data objek.');
            } else {
                showError(data.error, 'formObjek')
            }
        },
    });
}

function deleteObjek(id) {
    var url = $('#btnDeleteObjek' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data objek');
                    $('#rowObjek' + id).remove();
                },
            });
        }
    })
}

function goToRincianObjekTab(objekId) {
    $('#content-objek-tab').removeClass('active');
    $('#objek').removeClass(['active show']);
    $('#content-rincian-objek-tab').addClass('active');
    $('#rincian-objek').addClass('active show');
    getDataRincianObjek(objekId);
}

/**
 * Rekening Rincian Objek
 */
function getDataRincianObjek(objekId) {
    var html = '';

    $('#btnTambahRincianObjek').show();
    $('#tableRincianObjek').hide();
    $('#noDataRincianObjek').hide();
    $('#rincianObjekLoader').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
    $.ajax({
        type: 'GET',
        url: rincianObjekUrl + 'by-objek/' + objekId,
        dataType: 'json',
        success: function (data) {
            $('#tableRincianObjekBody').html('');
            html = '';
            console.log(data)
            if (data.data.length > 0) {
                objek = data.data[0].obyek;
                jenis = data.data[0].obyek.jenis;
                kelompok = data.data[0].obyek.jenis.kelompok;
                akun = data.data[0].obyek.jenis.kelompok.akun;

                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowRincianObjek' + data.data[i].id + '">';
                    html += '<td>' + akun.kode + '</td>';
                    html += '<td>' + kelompok.kode + '</td>';
                    html += '<td>' + jenis.kode + '</td>';
                    html += '<td>' + objek.kode + '</td>';
                    html += '<td id="rowKodeRincianObjek' + data.data[i].id + '">' + data.data[i].kode + '</td>';
                    html += '<td id="rowNamaRincianObjek' + data.data[i].id + '">' + data.data[i].nama_rincian_obyek + '</td>';
                    if (user.role === 'superadmin') {
                        html += '<td>';
                        html += '<button class="btn btn-xs btn-outline-warning" onclick="editRincianObjek(\'' + data.data[i].id + '\')" id="btnEditRincianObjek' + data.data[i].id + '" data-update-url="" data-rincian-objek-id="' + data.data[i].id + '"><i class="fa fa-edit"></i></button> ';
                        html += '<button class="btn btn-xs btn-outline-danger" id="btnDeleteRincianObjek' + data.data[i].id + '" data-delete-url="' + rincianObjekUrl + 'delete/' + data.data[i].id + '" data-rincian-objek-id="' + data.data[i].id + '" onclick="deleteRincianObjek(' + data.data[i].id + ')"> <i class="fa fa-trash"></i></button>';
                        html += '</td>';
                    }
                    html += '</tr>';
                }
                $('#rincianObjekLoader').html('');
                $('#tableRincianObjek').show();
                $('#tableRincianObjekBody').html(html);
            } else {
                $('#noDataRincianObjek').show();
                $.ajax({
                    type: 'GET',
                    url: objekUrl + 'show/' + objekId,
                    dataType: 'json',
                    success: function (data) {
                        objek = data.data;
                        jenis = data.data.jenis;
                        kelompok = data.data.jenis.kelompok;
                        akun = data.data.jenis.kelompok.akun;
                    },
                });
                $('#rincianObjekLoader').html('');
            }
        },
    });
}

function editRincianObjek(id) {
    $('#modalFormRincianObjek').text('Form Ubah Rincian Objek');
    $('#btnSimpanRincianObjek').hide();
    $('#btnSimpanUbahRincianObjek').show();
    $('#formRincianObjek').trigger('reset');

    $.ajax({
        type: 'GET',
        url: rincianObjekUrl + 'show/' + id,
        dataType: 'json',
        success: function (data) {
            $('#objekId').val(objek.id);
            $('#objekRincianObjek').val(akun.kode + '.' + kelompok.kode + '.' + jenis.kode + '.' + objek.kode + '. ' + objek.nama_obyek);
            $('#kodeRincianObjek').val(data.data.kode);
            $('#namaRincianObjek').val(data.data.nama_rincian_obyek);
            $('#rincianObjekId').remove();
            $('#formRincianObjek').append('<input type="hidden" name="rincian_objek_id" id="rincianObjekId" value="' + data.data.id + '"/>');
        },
    });

    $('#modalRincianObjek').modal('show');
}

function updateRincianObjek(e) {
    e.preventDefault();
    $.ajax({
        type: 'PUT',
        url: rincianObjekUrl + 'update/' + $('#rincianObjekId').val(),
        dataType: 'json',
        data: $('#formRincianObjek').serialize(),
        success: function (data) {
            if (data.status) {
                $('#modalRincianObjek').modal('hide');
                $.ajax({
                    type: 'GET',
                    url: rincianObjekUrl + 'show/' + $('#rincianObjekId').val(),
                    dataType: 'json',
                    success: function (data) {
                        $('#rowKodeRincianObjek' + data.data.id).text(data.data.kode);
                        $('#rowNamaRincianObjek' + data.data.id).text(data.data.nama_rincian_obyek);
                    },
                });
                successSwal('Berhasil mengubah data rincian objek.');
            } else {
                showError(data.error, 'formRincianObjek')
            }
        },
    });
}

function deleteRincianObjek(id) {
    var url = $('#btnDeleteRincianObjek' + id).data('delete-url');
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    successSwal('Berhasil menghapus data rincian objek');
                    $('#rowRincianObjek' + id).remove();
                },
            });
        }
    })
}


