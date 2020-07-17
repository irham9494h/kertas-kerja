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
let activeTab = 'pendapatan';
let totalPendapatan = 0;
let totalBelanja = 0;
let totalPembiayaan = 0;
let jenisPembahasan = $('#jenisPembahasan').val();

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

    $('#rekeningPendapatan').select2({
        minimumInputLength: 1,
        allowClear: false,
        placeholder: 'Nama rekening',
        ajax: {
            dataType: 'json',
            url: window.location.origin + '/sb/t/kertas-kerja/rekening-pendapatan',
            delay: 1000,
            data: function (params) {
                return {
                    nama_rekening: params.term
                }
            },
            processResults: function (data, page) {
                var arr = [];
                $.each(data, function (i, value) {
                    arr.push({
                        id: value.id,
                        text: value.obyek.jenis.kelompok.akun.kode + '.' + value.obyek.jenis.kelompok.kode + '.' + value.obyek.jenis.kode + '.' + value.obyek.kode + '.' + value.kode + '. ' + value.nama_rincian_obyek
                    });
                });
                return {
                    results: arr
                };
            }
        },
        language: {
            noResults: function (params) {
                return "Rekening tidak ditemukan.";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    }).on('select2:select', function (evt) {
        var data = $("#rekeningPendapatan option:selected").val();
    });

    $('#opdBelanja').select2();
    $('#rekeningBelanja').select2({
        minimumInputLength: 1,
        allowClear: false,
        placeholder: 'Nama rekening',
        ajax: {
            dataType: 'json',
            url: window.location.origin + '/sb/t/kertas-kerja/rekening-belanja',
            delay: 1000,
            data: function (params) {
                return {
                    nama_rekening: params.term
                }
            },
            processResults: function (data, page) {
                var arr = [];
                $.each(data, function (i, value) {
                    arr.push({
                        id: value.id,
                        text: value.obyek.jenis.kelompok.akun.kode + '.' + value.obyek.jenis.kelompok.kode + '.' + value.obyek.jenis.kode + '.' + value.obyek.kode + '.' + value.kode + '. ' + value.nama_rincian_obyek
                    });
                });
                return {
                    results: arr
                };
            }
        },
        language: {
            noResults: function (params) {
                return "Rekening tidak ditemukan.";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    }).on('select2:select', function (evt) {
        var data = $("#rekeningPendapatan option:selected").val();
    });
    $('#rekeningSumberDana').select2({
        minimumInputLength: 1,
        allowClear: false,
        placeholder: 'Nama rekening',
        ajax: {
            dataType: 'json',
            url: window.location.origin + '/sb/t/kertas-kerja/rekening-pendapatan',
            delay: 1000,
            data: function (params) {
                return {
                    nama_rekening: params.term
                }
            },
            processResults: function (data, page) {
                var arr = [];
                $.each(data, function (i, value) {
                    arr.push({
                        id: value.id,
                        text: value.obyek.jenis.kelompok.akun.kode + '.' + value.obyek.jenis.kelompok.kode + '.' + value.obyek.jenis.kode + '.' + value.obyek.kode + '.' + value.kode + '. ' + value.nama_rincian_obyek
                    });
                });
                return {
                    results: arr
                };
            }
        },
        language: {
            noResults: function (params) {
                return "Rekening tidak ditemukan.";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    }).on('select2:select', function (evt) {
        var data = $("#rekeningPendapatan option:selected").val();
    });
    $('#opdPembiayaan').select2();
    $('#rekeningPembiayaan').select2({
        minimumInputLength: 1,
        allowClear: false,
        placeholder: 'Nama rekening',
        ajax: {
            dataType: 'json',
            url: window.location.origin + '/sb/t/kertas-kerja/rekening-pembiayaan',
            delay: 1000,
            data: function (params) {
                return {
                    nama_rekening: params.term
                }
            },
            processResults: function (data, page) {
                var arr = [];
                $.each(data, function (i, value) {
                    arr.push({
                        id: value.id,
                        text: value.obyek.jenis.kelompok.akun.kode + '.' + value.obyek.jenis.kelompok.kode + '.' + value.obyek.jenis.kode + '.' + value.obyek.kode + '.' + value.kode + '. ' + value.nama_rincian_obyek
                    });
                });
                return {
                    results: arr
                };
            }
        },
        language: {
            noResults: function (params) {
                return "Rekening tidak ditemukan.";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    }).on('select2:select', function (evt) {
        var data = $("#rekeningPendapatan option:selected").val();
    });
    $('#rekeningBelanjaPembiayaan').select2({
        minimumInputLength: 1,
        allowClear: false,
        placeholder: 'Nama rekening',
        ajax: {
            dataType: 'json',
            url: window.location.origin + '/sb/t/kertas-kerja/rekening-pembiayaan',
            delay: 1000,
            data: function (params) {
                return {
                    nama_rekening: params.term
                }
            },
            processResults: function (data, page) {
                var arr = [];
                $.each(data, function (i, value) {
                    arr.push({
                        id: value.id,
                        text: value.obyek.jenis.kelompok.akun.kode + '.' + value.obyek.jenis.kelompok.kode + '.' + value.obyek.jenis.kode + '.' + value.obyek.kode + '.' + value.kode + '. ' + value.nama_rincian_obyek
                    });
                });
                return {
                    results: arr
                };
            }
        },
        language: {
            noResults: function (params) {
                return "Rekening tidak ditemukan.";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    }).on('select2:select', function (evt) {
        var data = $("#rekeningPendapatan option:selected").val();
    });
    $('#updateModalRekeningBelanjaPembiayaan').select2({
        minimumInputLength: 1,
        allowClear: false,
        placeholder: 'Nama rekening',
        ajax: {
            dataType: 'json',
            url: window.location.origin + '/sb/t/kertas-kerja/rekening-pembiayaan',
            delay: 1000,
            data: function (params) {
                return {
                    nama_rekening: params.term
                }
            },
            processResults: function (data, page) {
                var arr = [];
                $.each(data, function (i, value) {
                    arr.push({
                        id: value.id,
                        text: value.obyek.jenis.kelompok.akun.kode + '.' + value.obyek.jenis.kelompok.kode + '.' + value.obyek.jenis.kode + '.' + value.obyek.kode + '.' + value.kode + '. ' + value.nama_rincian_obyek
                    });
                });
                return {
                    results: arr
                };
            }
        },
        language: {
            noResults: function (params) {
                return "Rekening tidak ditemukan.";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    }).on('select2:select', function (evt) {
        var data = $("#rekeningPendapatan option:selected").val();
    });

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

    formatPembiayaan = new AutoNumeric('#nilaiPembiayaan', {
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
    $('#formItemPendapatan').trigger('reset');
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

    $('#pendapatanSatatusText').show();
    $('#statusSumberDana').text('Total').show();
    $('#belanjaStatusText').hide();
    $('#pembiayaanStatusText').hide();

    $('#cardKertasKerja').hide().show();
    $('#pendapatanContentTable').html('');
    $('#noDataPendapatan').hide();
    $('#itemKertasKerjaLoader').html('').append('<div class="d-flex justify-content-center"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    fetchKertasKerja(tanggalIDKertasKerja);
});

function fetchKertasKerja(tanggalId) {
    $('#btnTambahBelanja').hide().data('belanja-tanggal-id', tanggalId);
    $('#btnTambahPembiayaan').hide();
    $('#btnTambahPendapatan').show().data('pendapatan-tanggal-id', tanggalId);

    tanggalIDKertasKerja = tanggalId;
    $('.btn-kertas-kerja').removeClass('btn-dark').addClass('btn-outline-dark');
    $('#btnFetchKertasKerja' + tanggalId).removeClass('btn-outline-dark').addClass('btn-dark');

    $.ajax({
        type: 'GET',
        url: pendapatanUrl + tanggalId + '/list/json',
        dataType: 'json',
        success: function (data) {
            $('#itemKertasKerjaLoader').html('');
            var html = '';
            var opd = '';
            var total = 0;

            if (data.data.length > 0) {
                $('#totalSumberDana').text(formatRupiah(data.totalSumberDana)).show();
                totalPendapatan = data.totalSumberDana;
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
    formatPendapatan.unformat();
    $.ajax({
        type: 'POST',
        url: pendapatanUrl + 'store-pendapatan',
        dataType: 'json',
        data: $('#formItemPendapatan').serialize(),
        success: function (data) {
            var html = '';
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
                        totalPendapatan = data.totalSumberDana;
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
                        totalPendapatan = data.totalSumberDana;
                    }
                } else {
                    fetchKertasKerja(tanggalIDKertasKerja);
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
    activeTab = 'pendapatan';
    $('#kertasKerjaId').val(id);
    $('#updateNominalTanggalId').val(tanggalIDKertasKerja);
    $('#updateNominalTotalPendapatan').val('').hide();
    $('#updateNominalTotalPembiayaan').val('').hide();
    $('#updateModalGunakanPembiayaan').hide();
    $('#updateModalBelanjaPembiayaan').hide();
    $('#updateNominalNilaiPembiayaan').val('').hide();
    $('#updateNominalIdPembiayaan').val('').hide();

    $('#btnSimpanUbahNominal').show();

    newNominal.set(nominal);
    oldNominal = nominal;
    $('#modalNominal').modal('show')
}

function editItemBelanja(id, nominal, pembiayaan_id, nilai_pembiayaan, e) {
    e.preventDefault();

    activeTab = 'belanja';
    $('#kertasKerjaId').val(id);
    $('#updateNominalTanggalId').val(tanggalIDKertasKerja);
    $('#updateNominalTotalPendapatan').show().val(totalPendapatan);
    $('#updateNominalTotalPembiayaan').show().val(totalPembiayaan);
    $('#updateModalGunakanPembiayaan').show();
    $('#updateModalBelanjaPembiayaan').show();
    $('#updateNominalNilaiPembiayaan').show().val(nilai_pembiayaan);
    $('#updateNominalIdPembiayaan').show().val(pembiayaan_id);

    $('#btnSimpanUbahNominal').show();
    $('#updateModalNilaiWarning').hide();
    $('#updateModalPembiayaanWarning').hide();

    $.ajax({
        type: 'GET',
        url: window.location.origin + '/sb/t/kertas-kerja/rekening-pembiayaan/' + pembiayaan_id,
        dataType: 'json',
        success: function (data) {
            var html = '';
            html = '<option value="' + data.id + '">' + data.obyek.jenis.kelompok.akun.kode + '.' + data.obyek.jenis.kelompok.kode + '.' + data.obyek.jenis.kode + '.' + data.obyek.kode + '.' + data.kode + '. ' + data.nama_rincian_obyek + '</option>';

            $('#updateModalRekeningBelanjaPembiayaan').html(html);
        }
    });

    $('#updateModalRekeningBelanjaPembiayaan').val(pembiayaan_id).trigger('change');

    if (pembiayaan_id !== null) {
        // console.log(pembiayaan_id + '------centang')
        $('#UpdateModalpembiayaanCheckbox').removeAttr('disabled').attr('checked', true);
        $('#updateModalRekeningBelanjaPembiayaan').removeAttr('disabled');
    } else {
        // console.log(pembiayaan_id + '------tidak di centang')
        $('#UpdateModalpembiayaanCheckbox').removeAttr('checked').attr('disabled', true);
        $('#updateModalRekeningBelanjaPembiayaan').prop('disabled', true);
    }

    newNominal.set(nominal);
    oldNominal = nominal;
    $('#modalNominal').modal('show')
}

function editItemPembiayaan(id, nominal, e) {
    e.preventDefault();
    activeTab = 'pembiayaan';
    $('#kertasKerjaId').val(id);
    $('#updateNominalTanggalId').val(tanggalIDKertasKerja);
    $('#updateNominalTotalPendapatan').val('').hide();
    $('#updateNominalTotalPembiayaan').val('').hide();
    $('#updateModalGunakanPembiayaan').hide();
    $('#updateModalBelanjaPembiayaan').hide();
    $('#updateNominalNilaiPembiayaan').val('').hide();
    $('#updateNominalIdPembiayaan').val('').hide();

    $('#btnSimpanUbahNominal').show();

    newNominal.set(nominal);
    oldNominal = nominal;
    $('#modalNominal').modal('show')
}

$('#newNominal').keyup(function () {
    var nilaiBaru = $('#newNominal').val();
    var nilaiLama = oldNominal;

    nilaiBaru = nilaiBaru.replace(/\./g, "");
    nilaiBaru = nilaiBaru.replace(/\,/g, ".");

    if (activeTab === 'belanja') {
        var pembiayaan_id = $('#updateNominalIdPembiayaan').val();
        var nilai_pembiayaan = $('#updateNominalNilaiPembiayaan').val();
        var nilaiBelanjaTanpaPembiayaan = 0;
        var selisihPendapatan = 0;
        var selisihPembiayaan = 0;
        var selisihNilai = 0;
        var selisihNilaiBelanja = 0;

        if (nilai_pembiayaan > nilaiLama) {
            nilaiBelanjaTanpaPembiayaan = nilai_pembiayaan - nilaiLama;
        } else {
            nilaiBelanjaTanpaPembiayaan = nilaiLama - nilai_pembiayaan;
        }

        if (nilaiBaru > nilaiLama) {
            selisihNilaiBelanja = nilaiBaru - nilaiLama;
            if ($('#UpdateModalpembiayaanCheckbox').is(":checked")) {
                if (selisihNilaiBelanja > totalPendapatan) {
                    if (selisihNilaiBelanja > totalPembiayaan) {
                        $('#updateModalPembiayaanWarning').show();
                        $('#btnSimpanUbahNominal').hide();
                    } else {
                        $('#updateModalPembiayaanWarning').hide();
                        $('#btnSimpanUbahNominal').show();
                    }
                } else {
                    $('#UpdateModalpembiayaanCheckbox').removeAttr('checked').attr('disabled', true);
                    $('#updateModalRekeningBelanjaPembiayaan').attr('disabled', true);
                    $('#updateModalRekeningBelanjaPembiayaan').val($('#updateModalRekeningBelanjaPembiayaan option:first-child').val()).trigger('change');

                    $('#updateModalPembiayaanWarning').hide();
                    $('#updateModalNilaiWarning').hide();
                    $('#btnSimpanUbahNominal').show();
                }
            } else {
                // console.log('pembiayaan tidak tercentang');
                $('#updateModalRekeningBelanjaPembiayaan').attr("disabled", true);
                // console.log('nilai lebih besar' + selisihNilaiBelanja)
                if (selisihNilaiBelanja > totalPendapatan) {
                    $('#updateModalNilaiWarning').show();

                    if (selisihNilaiBelanja > totalPembiayaan) {
                        $('#updateModalPembiayaanWarning').show();
                        $('#btnSimpanUbahNominal').hide();
                    } else {
                        $('#updateModalPembiayaanWarning').hide();
                        $('#btnSimpanUbahNominal').show();

                        $('#UpdateModalpembiayaanCheckbox').removeAttr('disabled').attr('checked', true);
                        $('#updateModalRekeningBelanjaPembiayaan').removeAttr('disabled');
                        $('#updateModalRekeningBelanjaPembiayaan').val($('#updateModalRekeningBelanjaPembiayaan option:first-child').val()).trigger('change');

                    }
                } else {
                    $('#updateModalNilaiWarning').hide();
                    $('#btnSimpanUbahNominal').show();
                }
            }
        } else {
            selisihNilaiBelanja = nilaiLama - nilaiBaru;

            $('#updateModalNilaiWarning').hide();
            $('#updateModalPembiayaanWarning').hide();
            $('#btnSimpanUbahNominal').show();

            if (pembiayaan_id == "") {
                $('#UpdateModalpembiayaanCheckbox').removeAttr('checked').attr('disabled', true);
                $('#updateModalRekeningBelanjaPembiayaan').attr('disabled', true);
            }

        }
    }

});

$('#UpdateModalpembiayaanCheckbox').on('click', function () {
    var nilaiBaru = $('#newNominal').val();
    var nilai_pembiayaan = $('#updateNominalNilaiPembiayaan').val();
    var nilaiLama = oldNominal;
    nilaiBaru = nilaiBaru.replace(/\./g, "");
    nilaiBaru = nilaiBaru.replace(/\,/g, ".");

    var selisih = nilaiBaru - nilaiLama;

    if ($(this).is(":checked")) {
        $('#updateModalNilaiWarning').hide();
        if (selisih > totalPembiayaan) {
            $('#btnSimpanUbahNominal').hide();
            $('#updateModalPembiayaanWarning').show();
        } else {
            $('#btnSimpanUbahNominal').show();
            $('#updateModalPembiayaanWarning').hide();
        }
        $('#updateModalRekeningBelanjaPembiayaan').removeAttr("disabled");
    } else {
        if (nilaiBaru > totalPendapatan) {
            $('#btnSimpanUbahNominal').hide();
            $('#updateModalRekeningBelanjaPembiayaan').attr("disabled", true);
            $('#updateModalNilaiWarning').show();
        } else {
            $('#updateModalNilaiWarning').hide();
            $('#btnSimpanUbahNominal').show();
        }
    }
});

$('#btnSimpanUbahNominal').on('click', function (e) {
    e.preventDefault();
    newNominal.unformat()
    var nominal = $('#newNominal').val()
    var updateUrl = '';
    var data = '';
    if (activeTab === 'pendapatan') {
        updateUrl = 'update-nominal';
        data = {
            'new_nominal': nominal,
            'uraian_id': $('#kertasKerjaId').val(),
            'sd_tanggal_id': $('#updateNominalTanggalId').val()
        }
    } else if (activeTab === 'belanja') {
        updateUrl = 'update-nominal-belanja';
        var checkbox = null;
        var pembiayaanId = null;
        if (!$('#UpdateModalpembiayaanCheckbox').is(":disabled")) {
            checkbox = $('#UpdateModalpembiayaanCheckbox').val();
            pembiayaanId = $('#updateModalRekeningBelanjaPembiayaan').val();
        }

        data = {
            'new_nominal': nominal,
            'uraian_id': $('#kertasKerjaId').val(),
            'sd_tanggal_id': $('#updateNominalTanggalId').val(),
            'total_pendapatan': $('#updateNominalTotalPendapatan').val(),
            'total_pembiayaan': $('#updateNominalTotalPembiayaan').val(),
            'pembiayaan_checkbox': checkbox,
            'pembiayaan_id': pembiayaanId,
        }
    } else if (activeTab === 'pembiayaan') {
        updateUrl = 'update-nominal-pembiayaan';
        data = {
            'new_nominal': nominal,
            'uraian_id': $('#kertasKerjaId').val(),
            'sd_tanggal_id': $('#updateNominalTanggalId').val()
        }
    }
    $.ajax({
        type: 'POST',
        url: sbUrl + updateUrl,
        dataType: 'json',
        data: data,
        success: function (data) {
            var oldTotal = 0;
            var selisih = 0;
            if (data.status) {
                successSwal(data.message);
                $('#modalNominal').modal('hide');
                if (activeTab === 'pendapatan') {
                    tot = new AutoNumeric('#totalNilai' + data.data.unit_id, {
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        decimalCharacterAlternative: '.',
                    });
                    oldTotal = tot.getNumber();
                    if (oldNominal > data.data.nilai) {
                        selisih = oldNominal - nominal;
                        // console.log('> ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal - selisih));
                        $('#totalNilai' + data.data.unit_id).html(formatRupiah(oldTotal - selisih)).attr('data-total', oldTotal - selisih);
                    } else if (oldNominal < nominal) {
                        selisih = data.data.nilai - oldNominal;
                        // console.log('< ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal + selisih));
                        $('#totalNilai' + data.data.unit_id).html(formatRupiah(oldTotal + selisih)).attr('data-total', oldTotal + selisih);
                    }

                    $('#item' + data.data.id).attr('onclick', 'editItem(' + data.data.id + ', ' + nominal + ', event)')
                        .text(formatRupiah(nominal));
                    $('#totalSumberDana').text(formatRupiah(data.totalSumberDana));
                    totalPendapatan = data.totalSumberDana;
                } else if (activeTab === 'belanja') {
                    tBelanja = new AutoNumeric('#totalNilaiBelanja' + data.data.unit_id, {
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        decimalCharacterAlternative: '.',
                    });
                    oldTotal = tBelanja.getNumber();

                    if (oldNominal > data.data.nilai) {
                        selisih = oldNominal - nominal;
                        // console.log('> ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal - selisih));
                        $('#totalNilaiBelanja' + data.data.unit_id).html(formatRupiah(oldTotal - selisih)).attr('data-total', oldTotal - selisih);
                    } else if (oldNominal < nominal) {
                        selisih = data.data.nilai - oldNominal;
                        // console.log('< ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal + selisih));
                        $('#totalNilaiBelanja' + data.data.unit_id).html(formatRupiah(oldTotal + selisih)).attr('data-total', oldTotal + selisih);
                    }

                    $('#itemBelanja' + data.data.id).attr('onclick', 'editItemBelanja(' + data.data.id + ', ' + nominal + ', ' + data.data.pembiayaan_id + ', ' + data.data.nilai_pembiayaan + ', event)')
                        .text(formatRupiah(nominal));

                    $('#totalSumberDana').text(formatRupiah(data.totalSumberDana)).show();
                    totalPendapatan = data.totalSumberDana;
                    $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan)).show();
                    totalPembiayaan = data.totalPembiayaan;

                    $('#totalBelanja').text(formatRupiah(data.totalBelanja));
                    totalBelanja = data.totalBelanja;
                } else if (activeTab === 'pembiayaan') {
                    tPembiayaan = new AutoNumeric('#totalNilaiPembiayaan' + data.data.unit_id, {
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        decimalCharacterAlternative: '.',
                    });
                    oldTotal = tPembiayaan.getNumber();
                    if (oldNominal > data.data.nilai) {
                        selisih = oldNominal - nominal;
                        // console.log('> ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal - selisih));
                        $('#totalNilaiPembiayaan' + data.data.unit_id).html(formatRupiah(oldTotal - selisih)).attr('data-total', oldTotal - selisih);
                    } else if (oldNominal < nominal) {
                        selisih = data.data.nilai - oldNominal;
                        // console.log('< ' + oldNominal + ', ' + nominal + ', ' + selisih + ', ' + oldTotal + ', ' + (oldTotal + selisih));
                        $('#totalNilaiPembiayaan' + data.data.unit_id).html(formatRupiah(oldTotal + selisih)).attr('data-total', oldTotal + selisih);
                    }

                    $('#itemPembiayaan' + data.data.id).attr('onclick', 'editItemPembiayaan(' + data.data.id + ', ' + nominal + ', event)')
                        .text(formatRupiah(nominal));
                    $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan));
                    totalPembiayaan = data.totalPembiayaan;
                }
            } else {
                errorSwal('Gagal mengubah nominal.');
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

    $('#pendapatanSatatusText').show();
    $('#statusSumberDana').text('Sisa').show();
    $('#belanjaStatusText').show();
    $('#pembiayaanStatusText').show();

    $('#belanjaContentTable').html('');
    $('#noDataBelanja').hide();
    $('#itemKertasKerjaBelanjaLoader').html('').append('<div class="d-flex justify-content-center"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    $('#btnTambahPendapatan').hide();
    $('#btnTambahPembiayaan').hide();

    fetchKertasKerjaBelanja(tanggalIDKertasKerja);
});

function fetchKertasKerjaBelanja(tanggalId) {

    $('#btnTambahBelanja').show().data('belanja-tanggal-id', tanggalId);

    $.ajax({
        type: 'GET',
        url: sbUrl + tanggalId + '/list/pend/json',
        dataType: 'json',
        success: function (data) {
            $('#totalSumberDana').text(formatRupiah(data.totalSumberDana)).show();
            totalPendapatan = data.totalSumberDana;
            $('#totalBelanja').text(formatRupiah(data.totalBelanja)).show();
            totalBelanja = data.totalBelanja;
            $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan)).show();
            totalPembiayaan = data.totalPembiayaan;
            $('#itemKertasKerjaBelanjaLoader').html('');
            var html = '';
            var opd = '';
            var total = 0;
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
                    html += '<tbody id="tblOpdBelanja' + data.data[i].unit_id + '">';
                    for (j = 0; j < data.data[i].list_uraian.length; j++) {
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data[i].list_uraian[j].uraian + '</td>';
                        if (data.data[i].list_uraian[j].pembiayaan_id == null) {
                            html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="itemBelanja' + data.data[i].list_uraian[j].id + '" onclick="editItemBelanja(' + data.data[i].list_uraian[j].id + ',' + data.data[i].list_uraian[j].nilai + ',' + data.data[i].list_uraian[j].pembiayaan_id + ',' + data.data[i].list_uraian[j].nilai_pembiayaan + ', event)">' + formatRupiah(data.data[i].list_uraian[j].nilai) + '</a></td>';
                        } else {
                            html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="itemBelanja' + data.data[i].list_uraian[j].id + '" onclick="editItemBelanja(' + data.data[i].list_uraian[j].id + ',' + data.data[i].list_uraian[j].nilai + ',' + data.data[i].list_uraian[j].pembiayaan_id + ',' + data.data[i].list_uraian[j].nilai_pembiayaan + ', event)"><span class="text-warning">' + formatRupiah(data.data[i].list_uraian[j].nilai) + '</span></a></td>';
                        }
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
    $('#formItemBelanja').trigger('reset')
    $('#nilaiWarning').hide();
    $('#pembiayaanWarning').hide();

    $('#tanggalId').val(tanggalIDKertasKerja);
    $('#belanjaTotalPendapatan').val(totalPendapatan);
    $('#modalItemBelanjan').modal('show');

});

$('#nilaiBelanja').keyup(function () {
    var nilai = $('#nilaiBelanja').val();
    nilai = nilai.replace(/\./g, "");
    nilai = nilai.replace(/\,/g, ".");
    var selisih = nilai - totalPendapatan;

    if (nilai > totalPendapatan) {
        $('#nilaiWarning').show();
        $('#btnSimpanItemBelanja').hide();
        $('#pembiayaanCheckbox').removeAttr("disabled");

        if ($('#pembiayaanCheckbox').is(":checked")) {
            if (selisih > totalPembiayaan) {
                $('#pembiayaanWarning').show();
            } else {
                $('#pembiayaanWarning').hide();
                $('#btnSimpanItemBelanja').show();
            }
            $('#rekeningBelanjaPembiayaan').removeAttr("disabled");
        } else {
            $('#btnSimpanItemBelanja').hide();
            $('#rekeningBelanjaPembiayaan').attr("disabled", true);
        }
    } else {
        $('#nilaiWarning').hide();
        $('#btnSimpanItemBelanja').show();
        $('#pembiayaanCheckbox').attr("disabled", true);
        $('#pembiayaanWarning').hide();
    }
});

$('#pembiayaanCheckbox').on('click', function () {
    var nilai = $('#nilaiBelanja').val();
    nilai = nilai.replace(/\./g, "");
    nilai = nilai.replace(/\,/g, ".");

    var selisih = nilai - totalPendapatan;

    if ($(this).is(":checked")) {
        if (selisih > totalPembiayaan) {
            $('#btnSimpanItemBelanja').hide();
            $('#pembiayaanWarning').show();
        } else {
            $('#btnSimpanItemBelanja').show();
            $('#pembiayaanWarning').hide();
        }
        $('#rekeningBelanjaPembiayaan').removeAttr("disabled");
    } else {
        $('#btnSimpanItemBelanja').hide();
        $('#rekeningBelanjaPembiayaan').attr("disabled", true);
    }
});

$('#btnSimpanItemBelanja').on('click', function (e) {
    e.preventDefault();
    var html = '';
    formatBelanja.unformat();
    var nilai = $('#nilaiBelanja').val();
    $.ajax({
        type: 'POST',
        url: pendapatanUrl + 'store-belanja',
        dataType: 'json',
        data: $('#formItemBelanja').serialize(),
        success: function (data) {
            var html = '';
            $('#itemKertasKerjaBelanjaLoader').html('');
            if (data.status) {
                $('#totalSumberDana').text(formatRupiah(data.totalSumberDana)).show();
                totalPendapatan = data.totalSumberDana;
                $('#totalBelanja').text(formatRupiah(data.totalBelanja)).show();
                totalBelanja = data.totalBelanja;
                $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan)).show();
                totalPembiayaan = data.totalPembiayaan;

                if ($('#belanjaContentTable').children().length > 0) {
                    if ($('#opdBelanja' + data.data.unit_id).children().length > 0) {
                        html = '';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" onclick="editItemBelanja(' + data.data.id + ', ' + data.data.nilai + ',' + data.data.pembiayaan_id + ',' + data.data.nilai_pembiayaan + ',  event)">' + formatRupiah(data.data.nilai) + '</a></td>';
                        html += '</tr>';
                        $('#tblOpdBelanja' + data.data.unit_id).append(html);
                        newTotal = parseInt($('#totalNilaiBelanja' + data.data.unit_id).data('total' + data.data.unit_id)) + parseInt(data.data.nilai);
                        $('#totalNilaiBelanja' + data.data.unit_id).html(formatRupiah(newTotal)).attr('data-total' + data.data.unit_id, newTotal);
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
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="itemBelanja' + data.data.id + '" onclick="editItemBelanja(' + data.data.id + ', ' + data.data.nilai + ',' + data.data.pembiayaan_id + ', ' + data.data.nilai_pembiayaan + ',event)">' + formatRupiah(data.data.nilai) + '</a></td>';
                        html += '</tr>';
                        html += '</tbody>';
                        html += '</table>';
                        $('#opdBelanja' + data.data.unit_id).html(html);
                        $('#totalNilaiBelanja' + data.data.unit_id).html('Rp. ' + formatRupiah(data.data.nilai)).attr('data-total' + data.data.unit_id, data.data.nilai);
                    }
                    $('#modalItemBelanjan').modal('hide');
                } else {
                    fetchKertasKerjaBelanja(tanggalIDKertasKerja);
                }
                $('#modalItemBelanjan').modal('hide');
                successSwal('Berhsail')
            } else {
                errorSwal('Terdapat inputan yang masih kosong.')
                // showError(data.error, 'formItemBelanja');
            }
        }
    });

});

/*
Pembiayaan
 */
$('#pembiayaan-tab').on('click', function (e) {
    e.preventDefault();

    $('#pendapatanSatatusText').hide();
    $('#belanjaStatusText').hide();
    $('#pembiayaanStatusText').show();

    $('#pembiayaanContentTable').html('');
    $('#noDataPembiayaan').hide();
    $('#itemKertasKerjaPembiayaanLoader').html('').append('<div class="d-flex justify-content-center"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    fetchKertasKerjaPembiayaan(tanggalIDKertasKerja);
});

function fetchKertasKerjaPembiayaan(tanggalId) {

    $('#btnTambahPendapatan').hide();
    $('#btnTambahPembiayaan').show().data('pembiayaan-tanggal-id', tanggalId);
    $('#btnTambahBelanja').hide();
    //
    $.ajax({
        type: 'GET',
        url: sbUrl + tanggalId + '/list/pembiayaan/json',
        dataType: 'json',
        success: function (data) {
            $('#totalSumberDana').hide();
            $('#statusSumberDana').hide();
            $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan));
            totalPembiayaan = data.totalPembiayaan;
            $('#itemKertasKerjaPembiayaanLoader').html('');
            var html = '';
            var opd = '';
            var total = 0;
            if (data.data.length > 0) {
                opd = '';
                for (i = 0; i < data.opd.length; i++) {
                    opd += '<div class="d-flex p-2 opd">';
                    opd += '<p class="mb-0">' + data.opd[i].bidang.urusan.kode + '.' + data.opd[i].bidang.kode + '.' + data.opd[i].kode + ' ' + data.opd[i].nama_unit + '</p>';
                    opd += '<p class="mb-0 ml-auto" id="total' + data.opd[i].id + '">Rp. <span id="totalNilaiPembiayaan' + data.opd[i].id + '">0</span></p>';
                    opd += '</div>';
                    opd += '<div class="mb-2 pb-0" id="opdPembiayaan' + data.opd[i].id + '">';
                    opd += '</div>';
                }
                $('#pembiayaanContentTable').append(opd);

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
                    html += '<tbody id="tblOpdPembiayaan' + data.data[i].unit_id + '">';
                    for (j = 0; j < data.data[i].list_uraian.length; j++) {
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data[i].list_uraian[j].uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="itemPembiayaan' + data.data[i].list_uraian[j].id + '" onclick="editItemPembiayaan(' + data.data[i].list_uraian[j].id + ',' + data.data[i].list_uraian[j].nilai + ', event)">' + formatRupiah(data.data[i].list_uraian[j].nilai) + '</a></td>';
                        html += '</tr>';
                        total += data.data[i].list_uraian[j].nilai;
                    }
                    html += '</tbody>';
                    html += '</table>';
                    $('#opdPembiayaan' + data.data[i].unit_id).html(html);
                    $('#totalNilaiPembiayaan' + data.data[i].unit_id).html(formatRupiah(total)).attr('data-total' + data.data[i].unit_id, total);
                }

                $('#noDataPembiayaan').hide();
                $('#tblpembiayaan').show();
            } else {
                $('#tblPembiayaan').hide();
                $('#bodyTblPembiayaan').html('');
                $('#noDataPembiayaan').show();
                $('#itemKertasKerjaPembiayaanLoader').html('')
            }
        },
    });
}

$('#btnTambahPembiayaan').on('click', function () {
    $('#modalFormItemPembiayaan').text('Form Tambah Kertas Kerja Pembiayaan');
    $('#pembiayaanTanggalId').val(tanggalIDKertasKerja);
    $('#formItemPembiayaan').trigger('reset');
    $('#modalItemPembiayaan').modal('show');
});

$('#btnSimpanItemPembiayaan').on('click', function (e) {
    e.preventDefault();
    var html = '';
    formatPembiayaan.unformat();
    $.ajax({
        type: 'POST',
        url: pendapatanUrl + 'store-pembiayaan',
        dataType: 'json',
        data: $('#formItemPembiayaan').serialize(),
        success: function (data) {
            var html = '';
            if (data.status) {
                if ($('#pembiayaanContentTable').children().length > 0) {
                    if ($('#opdPembiayaan' + data.data.unit_id).children().length > 0) {
                        html = '';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" onclick="editItemPembiayaan(' + data.data.id + ', ' + data.data.nilai + ', event)">' + formatRupiah(data.data.nilai) + '</a></td>';
                        html += '</tr>';
                        $('#tblOpdPembiayaan' + data.data.unit_id).append(html);
                        newTotal = parseInt($('#totalNilaiPembiayaan' + data.data.unit_id).data('total' + data.data.unit_id)) + parseInt(data.data.nilai);
                        $('#totalNilaiPembiayaan' + data.data.unit_id).html(formatRupiah(newTotal)).attr('data-total' + data.data.unit_id, newTotal);
                        $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan));
                        totalPembiayaan = data.totalPembiayaan;
                    } else {
                        html = '';
                        html += '<table class="w-100 table table-sm mb-0">';
                        html += '<thead>';
                        html += '<tr>';
                        html += '<th>Uraian</th>';
                        html += '<th>Nilai</th>';
                        html += '</tr>';
                        html += '</thead>';
                        html += '<tbody id="tblOpdPembiayaan' + data.data.unit_id + '">';
                        html += '<tr>';
                        html += '<td style="width: 80%">' + data.data.uraian + '</td>';
                        html += '<td style="text-align: end; width: 20%; padding-right: 0.5rem !important;"><a href="#" class="text-dark nominal" id="itemPembiayaan' + data.data.id + '" onclick="editItemPembiayaan(' + data.data.id + ', ' + data.data.nilai + ', event)">' + formatRupiah(data.data.nilai) + '</a></td>';
                        html += '</tr>';
                        html += '</tbody>';
                        html += '</table>';
                        $('#opdPembiayaan' + data.data.unit_id).html(html);
                        $('#totalNilaiPembiayaan' + data.data.unit_id).html('Rp. ' + formatRupiah(data.data.nilai)).attr('data-total' + data.data.unit_id, data.data.nilai);
                        $('#totalPembiayaan').text(formatRupiah(data.totalPembiayaan));
                        totalPembiayaan = data.totalPembiayaan;
                    }
                    $('#modalItemPembiayaan').modal('hide');
                } else {
                    fetchKertasKerjaPembiayaan(tanggalIDKertasKerja);
                }
                $('#modalItemPembiayaan').modal('hide');
                successSwal('Berhsail')
            }
        },
    });
});
