const tahunUrl = window.location.origin + '/tahun-rekening/';
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

let tahun = 0;

const confirmActivate = {
    title: 'Apakah Anda yakin?',
    text: "Anda akan mengaktifkan tahun rekening " + tahun + " yang akan menon-aktifkan tahun rekening yang lain.",
    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, aktifkan sekarang!',
    cancelButtonText: 'Tidak, batal aktifkan!',
    reverseButtons: true
};

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btnTambahTahunRekening').on('click', function (e) {
        e.preventDefault();
        $('#modalFormTahunRekening').text('Form Tambah Tahun Rekening');
        $('#btnSimpanTahunRekening').show();
        $('#btnSimpanUbahTahunRekening').hide();
        $('#formTahunRekening').trigger('reset');
        $('#modalTahunRekening').modal('show');
    });

    $('#btnSimpanTahunRekening').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: tahunUrl + 'store',
            dataType: 'json',
            data: $('#formTahunRekening').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#modalTahunRekening').modal('hide');
                    fecthTahun();
                    successSwal('Berhasil menambah data tahun rekening.');
                } else {
                    showError(data.error, 'formTahunRekening');
                }
            },
        });
    });

    $('#btnSimpanUbahTahunRekening').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'PUT',
            url: sbThnUrl + 'update/' + $('#tahunId').val(),
            dataType: 'json',
            data: $('#formKertasKerja').serialize(),
            success: function (data) {
                if (data.status) {
                    $('#modalKertasKerja').modal('hide');
                    fecthTahun();
                    successSwal('Berhasil menambah data urusan.');
                } else {
                    showError(data.error, 'formKertasKerja');
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

function fecthTahun() {
    $.ajax({
        type: 'GET',
        url: tahunUrl + 'fetch',
        dataType: 'json',
        success: function (data) {
            var html = '';
            $('#tableTahunRekeningBody').html('');
            if (data.data.length > 0) {
                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowTahunRekening' + data.data[i].id + '">';
                    html += '<td id="rowTahun' + data.data[i].id + '">' + data.data[i].tahun + '</td>';
                    if (data.data[i].deskripsi !== null) {
                        html += '<td id="rowDeskripsi' + data.data[i].id + '">' + data.data[i].deskripsi + '</td>';
                    } else {
                        html += '<td id="rowDeskripsi' + data.data[i].id + '">-</td>';
                    }

                    if (data.data[i].status === 1) {
                        html += '<td id="rowStatus' + data.data[i].id + '"><span class="badge badge-info">Aktif</span></td>';
                    } else {
                        html += '<td id="rowStatus' + data.data[i].id + '"><span class="badge badge-dark">Tidak Aktif</span></td>';
                    }

                    html += '<td>';
                    html += '<div class="btn-group btn-group-xs">';
                    html += '<button class="btn btn-outline-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bars"></i></button>';
                    html += '<div class="dropdown-menu dropdown-menu-right">';
                    // html += '<a href="" class="dropdown-item" type="button">Buka</a>';
                    if (data.data[i].status === 0) {
                        html += '<button class="dropdown-item" type="button" id="btnactivateTahunRekening" data-id="'+ data.data[i].id +'" onclick="activateTahunRekening(' + data.data[i].id + ')">Aktifkan</button>';
                    }
                    html += '<button class="dropdown-item" type="button" id="btnHapusTahunRekening" data-id="' + data.data[i].id + '" onclick="deleteTahunRekening(' + data.data[i].id + ')">Hapus</button>';
                    html += '</div></div>';
                    html += '</td>';
                    html += '</tr>';
                }
                $('#tableTahunRekeningBody').html(html);
            } else {
                $('#tableTahunRekeningBody').html('<tr><td colspan="3" class="text-center">tidak ada data</td></tr>');
            }
        },
    });
}

function editTahunRekening(id) {
    console.log(id)
}

function deleteTahunRekening(id) {
    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: tahunUrl + 'delete/' + id,
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        fecthTahun();
                        successSwal('Berhasil menghapus data tahun rekening.');
                        $('#rowTahunRekening' + id).remove();
                    }
                },
            });
        }
    })
}

function activateTahunRekening(id) {
    tahun = $('#rowTahun' + id).text();
    Swal.fire(confirmActivate).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'PUT',
                url: tahunUrl + 'activate/' + id,
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        fecthTahun();
                        successSwal('Berhasil mengaktifkan data tahun rekening.');
                        // $('#rowTahunRekening' + id).remove();
                    }
                },
            });
        }
    })
}



