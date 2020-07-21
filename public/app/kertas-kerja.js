const sbTgl = window.location.origin + '/sb-tgl/';
const sbUrl = window.location.origin + '/kertas-kerja/';
const pendapatanUrl = window.location.origin + '/sb-tgl/pendapatan/';
const belanjaUrl = window.location.origin + '/sb-tgl/belanja/';
const confirmDelete = {
    title: 'Apakah Anda yakin?',
    text: "Data yang telah dihapus tidak dapat dikembalikan dan Anda akan kehilangan HISTORY dari kertas kerja Anda!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, hapus sekarang!',
    cancelButtonText: 'Tidak, batal hapus!',
    reverseButtons: true
};
let oldNominal = 0;
let tanggalKertasKerja = 0;
let tanggalIDKertasKerja = 0;
let jenisPembahasan = $('#jenisPembahasan').val();
const lockWarningMessage = 'STRUKTUR TERKUNCI, Anda tidak dapat menambah ataupun mengubah struktur.';

$(function () {
    $('#tanggal').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Ming",
                "Sen",
                "Sel",
                "Rab",
                "Kam",
                "Jum",
                "Sab"
            ],
            "monthNames": [
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Augustus",
                "September",
                "Oktober",
                "November",
                "Desember"
            ],
            "firstDay": 1
        },
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btnTambahSbTanggal').on('click', function (e) {
        e.preventDefault();
        if ($('#statusMurni').val() == 1) {
            warningSwal(lockWarningMessage)
            return false;
        }

        $('#modalFormTanggal').text('Form Tambah Kertas Kerja');
        $('#btnSimpanTanggal').show();
        $('#btnSimpanUbahTanggal').hide();

        $('#tahunId').val($(this).data('tahun-id'));
        $('#modalTanggal').modal('show');
    });

    $('#btnSimpanTanggal').on('click', function (e) {
        e.preventDefault();
        var html = '';
        $.ajax({
            type: 'POST',
            url: sbTgl + 'store',
            dataType: 'json',
            data: $('#formTanggal').serialize(),
            success: function (data) {
                console.log(data)
                if (data.status) {
                    $('#btnDeleteTanggal').remove();
                    html = '';
                    html += ' <div class="btn-group btn-group-xs" role="group" id="tglKertasKerja' + data.data.id + '">';
                    html += '<a href="' + window.location.origin + '/sb/t/' + data.data.sd_tahun_id + '/kertas-kerja/' + jenisPembahasan + '/d/' + data.data.id + '/list' + '" class="btn btn-xs btn-outline-dark btn-kertas-kerja" id="btnFetchKertasKerja' + data.data.id + '">' + myDateFormat(data.data.tanggal) + '</a>';
                    html += '<button type="button" class="btn btn-xs btn-outline-danger" id="btnDeleteTanggal" data-tanggal-id="' + data.data.id + '" onclick="deleteTanggalKertasKerja(' + data.data.id + ')"><i class="fa fa-times"></i></button>';
                    html += '</div>';

                    $('#buttonBar').append(html);

                    successSwal('Berhasil menambah tanggal kertas kerja.');
                    $('#modalTanggal').modal('hide');
                } else {
                    showError(data.error, 'formTanggal');
                }
            },
            error: function (xhr) {
                errorSwal(xhr.responseJSON.message);
            }

        }, 'json');
    });

});

function successSwal(message) {
    return Swal.fire(
        'Berhasil!',
        message,
        'success'
    );
}

function errorSwal(message) {
    return Swal.fire(
        'Gagal!',
        message,
        'error'
    );
}

function warningSwal(message) {
    return Swal.fire(
        'Peringatan!',
        message,
        'warning'
    );
}

function showError(error, form) {
    $.each(error, function (key, value) {
        $('#' + form).find($('input[name=' + key + ']')).addClass('is-invalid');
        $('#' + form).find($('#' + key + '_feedback')).text(value);
    });
}

function myDateFormat(param) {
    var date = param;
    var d = new Date(date.toString().split("/").reverse().join("-"));
    var dd = ('0' + d.getDate()).slice(-2);
    var mm = ('0' + (d.getMonth() + 1)).slice(-2);
    var yy = d.getFullYear();

    return (dd + '/' + mm + '/' + yy);
}

function formatRupiah(angka) {
    return AutoNumeric.format(angka, {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
    })
}

function deleteTanggalKertasKerja(id) {
    if ($('#statusMurni').val() == 1) {
        warningSwal(lockWarningMessage)
        return false;
    }

    Swal.fire(confirmDelete).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: sbTgl + 'delete/' + id,
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        successSwal(data.message);
                        $(".btn-kertas-kerja-g:nth-last-of-type(2)").append('<button type="button" class="btn btn-xs btn-outline-danger" id="btnDeleteTanggal" onclick="deleteTanggalKertasKerja(' + $(".btn-kertas-kerja-g:nth-last-of-type(2)").data('tanggal-id') + ')"><i class="fa fa-times"></i></button>');
                        $('#tglKertasKerja' + id).remove();
                    } else {
                        errorSwal(data.message)
                    }
                },
            });
        }
    })
}

/*
Kinci dan Buka Kertas Kerja
 */

$('#btnKunciKertasKerja').on('click', function (event) {
    $.ajax({
        type: 'GET',
        url: window.location.origin + '/sb/t/kertas-kerja/kunci-struktur-murni/' + $(this).data('id'),
        dataType: 'json',
        success: function (data) {
            if (data.status) {
                $('#btnKunciKertasKerja').hide();
                $('#btnBukaKertasKerja').show();
                $('#statusMurni').val(1)
                $('#lockStatus').text('Terkunci')
                successSwal(data.message)
            }
        }
    });
})

$('#btnBukaKertasKerja').on('click', function (event) {
    $.ajax({
        type: 'GET',
        url: window.location.origin + '/sb/t/kertas-kerja/buka-struktur-murni/' + $(this).data('id'),
        dataType: 'json',
        success: function (data) {
            if (data.status) {
                $('#btnBukaKertasKerja').hide();
                $('#btnKunciKertasKerja').show();
                $('#statusMurni').val(0)
                $('#lockStatus').text('Terbuka')
                successSwal(data.message)
            }
        }
    });
})
