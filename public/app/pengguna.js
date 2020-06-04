const manajemenPenggunaUrl = window.location.origin + '/manajemen-pengguna/';
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

    /**
     * Pengguna
     */

    $('#tablePenggunaLoader').append('<div class="d-flex justify-content-center"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    $.ajax({
        type: 'GET',
        url: manajemenPenggunaUrl + 'fetch',
        dataType: 'json',
        success: function (data) {
            var html = '';
            if (data) {
                for (var i = 0; i < data.data.data.length; i++) {
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td>' + data.data.data[i].nip + '</td>';
                    html += '<td>' + data.data.data[i].nama + '</td>';
                    html += '<td>' + data.data.data[i].username + '</td>';
                    html += '</tr>';
                }
                $('#tablePenggunaBody').html(html);
                $('#tablePenggunaLoader').html('');
            } else {
                $('#tablePenggunaLoader').html('<h3 class="text-center mt-3">Tidak ada data pengguna.</h3>');

            }
        },
    });

    $('#btnTambahPengguna').on('click', function (e) {
        e.preventDefault();
        $('#modalFormPengguna').text('Form Tambah Pengguna');
        $('#btnSimpanPengguna').show();
        $('#btnSimpanUbahPengguna').hide();
        $('#formPengguna').trigger('reset');
        $('#modalPengguna').modal('show');
    });

    $('#btnSimpanPengguna').on('click', function (e) {
        e.preventDefault();
        console.log('jj')
        var html = '';
        $.ajax({
            type: 'POST',
            url: manajemenPenggunaUrl + 'store',
            dataType: 'json',
            data: $('#formPengguna').serialize(),
            success: function (data) {
                if (data.status) {
                    // html = '';
                    // html = '<tr id="rowUrusan' + data.data.id + '"><td id="rowKode' + data.data.id + '">' + data.data.kode + '</td><td><a href="#" id="rowNama' + data.data.id + '" onclick="goToBidangTab(\'' + data.data.id + '\')">' + data.data.nama_urusan + '</a></td>' +
                    //     '<td><button class="btn btn-xs btn-outline-warning" onclick="editUrusan(\'' + data.data.id + '\')" data-update-url="' + urusanUrl + 'show/' + data.data.id + '" data-urusan-id="' + data.data.id + '"><i class="fa fa-edit"></i></button> ' +
                    //     '<button class="btn btn-xs btn-outline-danger" id="btnDeleteUrusan' + data.data.id + '" onclick="deleteUrusan(' + data.data.id + ')" data-delete-url="' + urusanUrl + 'delete/' + data.data.id + '" data-urusan-id="' + data.data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    // $('#tableUrusan').prepend(html);
                    // successSwal('Berhasil menambah data urusan.');
                    console.log(data);
                    $('#modalPengguna').modal('hide');
                } else {
                    showError(data.error, 'formPengguna');
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
 * Pengguna
 */
// function editUrusan(id) {
//     $('#modalFormUrusan').text('Form Ubah Urusan');
//     $('#btnSimpanUrusan').hide();
//     $('#btnSimpanUbahUrusan').show();
//     $('#formUrusan').trigger('reset');
//
//     $.ajax({
//         type: 'GET',
//         url: urusanUrl + 'show/' + id,
//         dataType: 'json',
//         success: function (data) {
//             $('#kodeUrusan').val(data.data.kode);
//             $('#namaUrusan').val(data.data.nama_urusan);
//             $('#urusanId').remove();
//             $('#formUrusan').append('<input type="hidden" name="urusan_id" id="urusanId" value="' + data.data.id + '"/>');
//         },
//     });
//
//     $('#modalUrusan').modal('show');
// }
//
// function updateUrusan(e) {
//     e.preventDefault();
//     $.ajax({
//         type: 'PUT',
//         url: urusanUrl + 'update/' + $('#urusanId').val(),
//         dataType: 'json',
//         data: $('#formUrusan').serialize(),
//         success: function (data) {
//             if (data.status) {
//                 $('#modalUrusan').modal('hide');
//                 $.ajax({
//                     type: 'GET',
//                     url: urusanUrl + 'show/' + $('#urusanId').val(),
//                     dataType: 'json',
//                     success: function (data) {
//                         $('#rowKode' + data.data.id).text(data.data.kode);
//                         $('#rowNama' + data.data.id).text(data.data.nama_urusan);
//                     },
//                 });
//                 successSwal('Berhasil mengubah data urusan.');
//             } else {
//                 showError(data.error, 'formUrusan')
//             }
//         },
//     });
// }
//
// function deleteUrusan(id) {
//     var url = $('#btnDeleteUrusan' + id).data('delete-url');
//     Swal.fire(confirmDelete).then((result) => {
//         if (result.value) {
//             $.ajax({
//                 type: 'GET',
//                 url: url,
//                 dataType: 'json',
//                 success: function (data) {
//                     successSwal('Data urusan telah dihapus.');
//                     $('#rowUrusan' + id).remove();
//                 },
//             });
//         }
//     })
// }
//
//


