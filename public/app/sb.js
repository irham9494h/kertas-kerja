const sbTgl = window.location.origin + '/sb-tgl/';
const sbUrl = window.location.origin + '/sb/t/kertas-kerja/d/';
const pendapatanUrl = window.location.origin + '/sb/t/kertas-kerja/d/';
const belanjaUrl = window.location.origin + '/sb/t/kertas-kerja/d/';
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

    $('#opdPendapatan').select2();
    $('#rekeningPendapatan').select2();
    $('#opdBelanja').select2();
    $('#rekeningBelanja').select2();
    $('#rekeningSumberDana').select2();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Disable scroll on input type number---------------
     */
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('wheel.disableScroll', function (e) {
            e.preventDefault()
        })
    });

    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('wheel.disableScroll')
    });
    /**
     * -------------------------------------------------------
     */
    newNominal = new AutoNumeric('#newNominal', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
    });

    formatPendapatan = new AutoNumeric('#nilaiPendapatan', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
    });

    formatBelanja = new AutoNumeric('#nilaiBelanja', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
    });

    /**
     * Pengguna
     */

    $('#btnTambahSbTanggal').on('click', function (e) {
        e.preventDefault();
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
                    html += '<button type="button" class="btn btn-xs btn-outline-dark btn-kertas-kerja" onclick="fetchKertasKerja(' + '\'' + data.data.tanggal + '\'' + ', ' + '\'' + data.data.id + '\'' + ')" id="btnFetchKertasKerja' + data.data.id + '">' + myDateFormat(data.data.tanggal) + '</button>';
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

function showError(error, form) {
    $.each(error, function (key, value) {
        $('#' + form).find($('input[name=' + key + ']')).addClass('is-invalid');
        $('#' + form).find($('#' + key + '_feedback')).text(value).addClass('d-block');
    });
}

$('#btnTambahPendapatan').on('click', function () {
    $("#formItemPendapatan").trigger("reset");
    $('#modalFormItemKertasKerja').text('Form Tambah Kertas Kerja Pendapatan');
    $('#pendapatanTanggalId').val($(this).data('pendapatan-tanggal-id'));
    $('#modalItemPendapatan').modal('show');
});

function myDateFormat(param) {
    var date = param;
    var d = new Date(date.toString().split("/").reverse().join("-"));
    var dd = ('0' + d.getDate()).slice(-2);
    var mm = ('0' + (d.getMonth() + 1)).slice(-2);
    var yy = d.getFullYear();

    return (dd + '/' + mm + '/' + yy);
}

function formatRupiah(angka) {
    // console.log(angka)
    return AutoNumeric.format(parseFloat(angka), {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalCharacterAlternative: '.',
    })
}

function deleteTanggalKertasKerja(id) {
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
Pendapatan
 */
$('#pendapatan-tab').on('click', function (e) {
    e.preventDefault();
    fetchKertasKerja(tanggalIDKertasKerja);
});

function fetchKertasKerja(tanggalId) {
    $('#cardKertasKerja').hide().show();
    $('#pendapatanContentTable').html('');
    $('#noDataPendapatan').hide();
    $('#itemKertasKerjaLoader').html('').append('<div class="d-flex justify-content-center"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    // $('#kertasKerjaTanggalTitle').text(myDateFormat(tanggal));
    $('#statusSumberDana').text('Total');
    $('#statusBelanja').hide();
    $('#btnTambahBelanja').hide().data('belanja-tanggal-id', tanggalId);
    $('#btnTambahPembiayaan').hide();
    $('#btnTambahPendapatan').show().data('pendapatan-tanggal-id', tanggalId);
    tanggalIDKertasKerja = tanggalId;
    // tanggalKertasKerja = tanggal;
    $('.btn-kertas-kerja').removeClass('btn-dark').addClass('btn-outline-dark');
    $('#btnFetchKertasKerja' + tanggalId).removeClass('btn-outline-dark').addClass('btn-dark');

    $.ajax({
        type: 'GET',
        url: pendapatanUrl + tanggalId + '/list/json',
        dataType: 'json',
        success: function (data) {
            console.log(data)
            $('#itemKertasKerjaLoader').html('');
            var html = '';
            var opd = '';
            var total = 0;

            if (data.data.length > 0) {
                $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
                opd = '';
                for (i = 0; i < data.opd.length; i++) {
                    opd += '<div class="d-flex p-2 opd">';
                    opd += '<p class="mb-0">' + data.opd[i].bidang.urusan.kode + '.' + data.opd[i].bidang.kode + '.' + data.opd[i].kode + ' ' + data.opd[i].nama_unit + '</p>';
                    opd += '<p class="mb-0 ml-auto" id="total' + data.opd[i].id + '">Rp. <span id="totalNilai' + data.opd[i].id + '">0</span></p>';
                    opd += '</div>';
                    opd += '<div class="mb-2 pb-0" id="opd' + data.opd[i].id + '">';
                    opd += '</div>';
                }
                $('#pendapatanContentTable').append(opd);

                for (i = 0; i < data.data.length; i++) {
                    total = 0;
                    html = '';
                    html += '<table class="w-100 table table-sm mb-0">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th>Uraian</th>';
                    html += '<th>Nilai</th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody id="tblOpd' + data.data[i].unit_id + '">';
                    for (j = 0; j < data.data[i].list_uraian.length; j++) {
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data[i].list_uraian[j].uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="item' + data.data[i].list_uraian[j].id + '" onclick="editItem(' + data.data[i].list_uraian[j].id + ',' + data.data[i].list_uraian[j].nilai + ', event)">' + formatRupiah(data.data[i].list_uraian[j].nilai) + '</a></td>';
                        html += '</tr>';
                        total += data.data[i].list_uraian[j].nilai;
                    }
                    html += '</tbody>';
                    html += '</table>';
                    $('#opd' + data.data[i].unit_id).html(html);
                    $('#totalNilai' + data.data[i].unit_id).html(formatRupiah(total)).attr('data-total' + data.data[i].unit_id, total);
                }

                $('#noDataPendapatan').hide();
                $('#tblPendapatan').show();
            } else {
                $('#tblPendapatan').hide();
                $('#bodyTblPendapatan').html('');
                $('#noDataPendapatan').show();

            }
        },
    });
}

$('#btnSimpanItemPendapatan').on('click', function (e) {
    e.preventDefault();
    var html = '';
    console.log($('#formItemPendapatan').serialize())
    formatPendapatan.unformat();
    $.ajax({
        type: 'POST',
        url: pendapatanUrl + 'store-pendapatan',
        dataType: 'json',
        data: $('#formItemPendapatan').serialize(),
        success: function (data) {
            var html = '';
            console.log(data)
            if (data.status) {
                if ($('#pendapatanContentTable').children().length > 0) {
                    if ($('#opd' + data.data.unit_id).children().length > 0) {
                        html = '';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" onclick="editItem(' + data.data.id + ', ' + data.data.nilai + ', event)">' + formatRupiah(parseFloat(data.data.nilai)) + '</a></td>';
                        html += '</tr>';
                        $('#tblOpd' + data.data.unit_id).append(html);
                        newTotal = parseFloat($('#totalNilai' + data.data.unit_id).data('total' + data.data.unit_id)) + parseFloat(data.data.nilai);
                        $('#totalNilai' + data.data.unit_id).html(formatRupiah(newTotal)).attr('data-total' + data.data.unit_id, newTotal);
                        $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
                    } else {
                        html = '';
                        html += '<table class="w-100 table table-sm mb-0">';
                        html += '<thead>';
                        html += '<tr>';
                        html += '<th>Uraian</th>';
                        html += '<th>Nilai</th>';
                        html += '</tr>';
                        html += '</thead>';
                        html += '<tbody id="tblOpd' + data.data.unit_id + '">';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="item' + data.data.id + '" onclick="editItem(' + data.data.id + ', ' + data.data.nilai + ', event)">' + formatRupiah(parseFloat(data.data.nilai)) + '</a></td>';
                        html += '</tr>';
                        html += '</tbody>';
                        html += '</table>';
                        $('#opd' + data.data.unit_id).html(html);
                        $('#totalNilai' + data.data.unit_id).html('Rp. ' + formatRupiah(data.data.nilai)).attr('data-total' + data.data.unit_id, data.data.nilai);
                        $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
                    }
                } else {
                    fetchKertasKerja($('#kertasKerjaTanggalTitle').text(), $('#pendapatanTanggalId').val());
                }

                $('#modalItemPendapatan').modal('hide');
                successSwal('Berhsail')
            } else {
                showError(data.error, 'formItemPendapatan');
            }
        },
    });
});

function editItem(id, nominal, e) {
    e.preventDefault();
    $('#kertasKerjaId').val(id);
    $('#updateNominalTanggalId').val(tanggalIDKertasKerja);
    newNominal.set(nominal);
    $('#modalNominal').modal('show')
}

$('#btnSimpanUbahNominal').on('click', function (e) {
    e.preventDefault();
    newNominal.unformat()
    var nominal = $('#newNominal').val()
    $.ajax({
        type: 'POST',
        url: sbUrl + 'update-nominal',
        dataType: 'json',
        data: {'new_nominal': nominal, 'uraian_id': $('#kertasKerjaId').val(), 'sd_tanggal_id': $('#updateNominalTanggalId').val()},
        success: function (data) {
            console.log(data)
            tot = new AutoNumeric('#totalNilai' + data.data.unit_id, {
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalCharacterAlternative: '.',
            });
            var oldTotal = 0;
            var selisih = 0;
            if (data.status) {
                successSwal(data.message);
                $('#modalNominal').modal('hide');
                oldTotal = tot.getNumber();
                if (oldNominal > data.data.nilai) {
                    selisih = oldNominal - nominal;
                    console.log('> ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal - selisih));
                    $('#totalNilai' + data.data.unit_id).html(formatRupiah(oldTotal - selisih)).attr('data-total', oldTotal - selisih);
                } else if (oldNominal < nominal) {
                    selisih = data.data.nilai - oldNominal;
                    console.log('< ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal + selisih));
                    $('#totalNilai' + data.data.unit_id).html(formatRupiah(oldTotal + selisih)).attr('data-total', oldTotal + selisih);
                }

                $('#item' + data.data.id).attr('onclick', 'editItem(' + data.data.id + ', ' + nominal + ', event)')
                    .text(formatRupiah(nominal));
                $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
            } else {
                errorSwal(data.message);
            }
        },
        error: function (xhr) {
            errorSwal(xhr.responseJSON.message);
        }

    }, 'json');
});

/*
Belanja
 */
$('#belanja-tab').on('click', function (e) {
    e.preventDefault();
    fetchKertasKerjaBelanja(tanggalIDKertasKerja);
});

function fetchKertasKerjaBelanja(tanggalId) {
    $('#belanjaContentTable').html('');
    $('#noDataBelanja').hide();
    $('#itemKertasKerjaBelanjaLoader').html('').append('<div class="d-flex justify-content-center"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    $('#statusSumberDana').text('Sisa');
    $('#statusBelanja').show();
    $('#btnTambahPendapatan').hide();
    $('#btnTambahPembiayaan').hide();
    $('#btnTambahBelanja').show().data('belanja-tanggal-id', tanggalId);

    $.ajax({
        type: 'GET',
        url: sbUrl + tanggalId + '/list/pend/json',
        dataType: 'json',
        success: function (data) {
            $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
            $('#totalBelanja').text(formatRupiah(data.totalBelanja));
            $('#itemKertasKerjaBelanjaLoader').html('');
            var html = '';
            var opd = '';
            var total = 0;
            console.log(data)
            if (data.data.length > 0) {
                opd = '';
                for (i = 0; i < data.opd.length; i++) {
                    opd += '<div class="d-flex p-2 opd">';
                    opd += '<p class="mb-0">' + data.opd[i].bidang.urusan.kode + '.' + data.opd[i].bidang.kode + '.' + data.opd[i].kode + ' ' + data.opd[i].nama_unit + '</p>';
                    opd += '<p class="mb-0 ml-auto" id="total' + data.opd[i].id + '">Rp. <span id="totalNilaiBelanja' + data.opd[i].id + '">0</span></p>';
                    opd += '</div>';
                    opd += '<div class="mb-2 pb-0" id="opdBelanja' + data.opd[i].id + '">';
                    opd += '</div>';
                }
                $('#belanjaContentTable').append(opd);

                for (i = 0; i < data.data.length; i++) {
                    total = 0;
                    html = '';
                    html += '<table class="w-100 table table-sm mb-0">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th>Uraian</th>';
                    html += '<th>Nilai</th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody id="tblOpd' + data.data[i].unit_id + '">';
                    for (j = 0; j < data.data[i].list_uraian.length; j++) {
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data[i].list_uraian[j].uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="item' + data.data[i].list_uraian[j].id + '" onclick="editItem(' + data.data[i].list_uraian[j].id + ',' + data.data[i].list_uraian[j].nilai + ', event)">' + formatRupiah(data.data[i].list_uraian[j].nilai) + '</a></td>';
                        html += '</tr>';
                        total += data.data[i].list_uraian[j].nilai;
                    }
                    html += '</tbody>';
                    html += '</table>';
                    $('#opdBelanja' + data.data[i].unit_id).html(html);
                    $('#totalNilaiBelanja' + data.data[i].unit_id).html(formatRupiah(total)).attr('data-total' + data.data[i].unit_id, total);
                }

                $('#noDataBelanja').hide();
                $('#tblBelanja').show();
            } else {
                $('#tblBelanja').hide();
                $('#bodyTblBelanja').html('');
                $('#noDataBelanja').show();
            }
        },
    });
}

$('#btnTambahBelanja').on('click', function () {
    $('#modalFormBelanja').text('Form Tambah Kertas Kerja Belanja');
    $('#tanggalId').val(tanggalIDKertasKerja);
    $('#modalItemBelanjan').modal('show');
});

$('#btnSimpanItemBelanja').on('click', function (e) {
    e.preventDefault();
    var html = '';
    formatBelanja.unformat();
    $.ajax({
        type: 'POST',
        url: pendapatanUrl + 'store-belanja',
        dataType: 'json',
        data: $('#formItemBelanja').serialize(),
        success: function (data) {
            var html = '';
            $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
            console.log($('#totalSumberDana').text());
            if (data.status) {
                if ($('#belanjaContentTable').children().length > 0) {
                    if ($('#opdBelanja' + data.data.unit_id).children().length > 0) {
                        html = '';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" onclick="editItem(' + data.data.id + ', ' + data.data.nilai + ', event)">' + formatRupiah(data.data.nilai) + '</a></td>';
                        html += '</tr>';
                        $('#tblOpdBelanja' + data.data.unit_id).append(html);
                        newTotal = parseInt($('#totalNilaiBelanja' + data.data.unit_id).data('total')) + parseInt(data.data.nilai);
                        $('#totalNilaiBelanja' + data.data.unit_id).html(formatRupiah(newTotal)).attr('data-total' + data.data.unit_id, newTotal);
                        $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
                        $('#totalBelanja').text(formatRupiah(data.totalBelanja));
                    } else {
                        html = '';
                        html += '<table class="w-100 table table-sm mb-0">';
                        html += '<thead>';
                        html += '<tr>';
                        html += '<th>Uraian</th>';
                        html += '<th>Nilai</th>';
                        html += '</tr>';
                        html += '</thead>';
                        html += '<tbody id="tblOpdBelanja' + data.data.unit_id + '">';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="item' + data.data.id + '" onclick="editItem(' + data.data.id + ', ' + data.data.nilai + ', event)">' + formatRupiah(data.data.nilai) + '</a></td>';
                        html += '</tr>';
                        html += '</tbody>';
                        html += '</table>';
                        $('#opdBelanja' + data.data.unit_id).html(html);
                        $('#totalNilaiBelanja' + data.data.unit_id).html('Rp. ' + formatRupiah(data.data.nilai)).attr('data-total' + data.data.unit_id, data.data.nilai);
                        $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
                        $('#totalBelanja').text(formatRupiah(data.totalBelanja));
                    }
                    $('#modalItemPendapatan').modal('hide');
                } else {
                    fetchKertasKerjaBelanja($('#kertasKerjaTanggalTitle').text(), $('#pendapatanTanggalId').val());
                }
                $('#modalItemPendapatan').modal('hide');
                successSwal('Berhsail')
            }
        },
    });
});


