const sbThnUrl = window.location.origin + '/sb/t/';
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
const lockWarningMessage = 'STRUKTUR MURNI SUDAH TERKUNCI, Anda tidak dapat menghapus tahun pembahasan.';

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btnTambahKertasKerja').on('click', function (e) {
        e.preventDefault();
        $('#modalFormKertasKerja').text('Form Tambah Kertas Kerja');
        $('#btnSimpanKertasKerja').show();
        $('#btnSimpanUbahKertasKerja').hide();
        $('#formKertasKerja').trigger('reset');
        $('#modalKertasKerja').modal('show');
    });

    $('#btnSimpanKertasKerja').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: sbThnUrl + 'store',
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

function warningSwal(message) {
    return Swal.fire(
        'Peringatan!',
        message,
        'warning'
    );
}

function fecthTahun() {
    $.ajax({
        type: 'GET',
        url: sbThnUrl + 'fetch-tahun',
        dataType: 'json',
        success: function (data) {
            var html = '';
            if (data.data.length > 0) {
                for (var i = 0; i < data.data.length; i++) {
                    html += '<tr id="rowKertasKerja' + data.data[i].id + '">';
                    html += '<td id="rowTahun' + data.data[i].id + '">' + data.data[i].tahun + '</td>';

                    if (data.data[i].status_murni == 0) {
                        html += '<td id="rowStatus' + data.data[i].id + '"><span class="badge badge-info">Terbuka</span></td>';
                    } else {
                        html += '<td id="rowStatus' + data.data[i].id + '"><span class="badge badge-warning">Terkunci</span></td>';
                    }

                    if (data.data[i].deskripsi !== null) {
                        html += '<td id="rowDeskripsi' + data.data[i].id + '">' + data.data[i].deskripsi + '</td>';
                    } else {
                        html += '<td id="rowDeskripsi' + data.data[i].id + '">-</td>';
                    }
                    html += '<td>';
                    html += '<div class="btn-group btn-group-xs">';
                    html += '<input type="hidden" value="' + data.data[i].status_murni + '" id="statusMurni' + data.data[i].id + '">';
                    html += '<button class="btn btn-outline-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bars"></i></button>';
                    html += '<div class="dropdown-menu dropdown-menu-right">';
                    html += '<a href="' + sbThnUrl + data.data[i].id + '/kertas-kerja/murni" class="dropdown-item" type="button">Struktur Murni</a>';
                    html += '<a href="' + sbThnUrl + data.data[i].id + '/kertas-kerja/perubahan" class="dropdown-item" type="button">Struktur Perubahan</a>';
                    html += '<button class="dropdown-item" type="button" id="btnHapusKertasKerja" data-id="' + data.data[i].id + '" onclick="deleteKertasKerja(' + data.data[i].id + ')">Hapus</button>';
                    html += '</div></div>';
                    html += '</td>';
                    html += '</tr>';
                }
                $('#tableKertasKerjaBody').html(html);
            } else {
                $('#tableKertasKerjaBody').html('<tr><td colspan="3" class="text-center">tidak ada data</td></tr>');
            }
        },
    });
}

function deleteKertasKerja(id) {
    if ($('#statusMurni' + id).val() == 1) {
        warningSwal(lockWarningMessage)
        return false;
    }

    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: sbThnUrl + 'delete/' + id,
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        fecthTahun();
                        successSwal('Berhasil menghapus data kertas kerja.');
                        // $('#rowKertasKerja' + id).remove();
                    }
                },
            });
        }
    })
}



