@extends('layouts.app')

@section('title', 'Kertas Kerja')
@section('kertas-kerja', 'active')

@section('style')
    <style>
        /*custome scrollbar*/
        div.my-horizontal-scrollbar {
            overflow: auto;
            white-space: nowrap;
            padding: 5px 0px 5px 0px;
        }

        .my-horizontal-scrollbar::-webkit-scrollbar {
            height: 5px;
            width: 5px;
        }

        .my-horizontal-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .my-horizontal-scrollbar::-webkit-scrollbar-thumb {
            background: #A8A8A8;
            border-radius: 12px;
        }

        .my-horizontal-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #C1C1C1;
        }

        .active-tab {
            color: #DC3545 !important;
        }

        .opd {
            color: #000000 !important;
            background-color: #F2F2F2 !important;
        }
    </style>
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Kertas Kerja </h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{route('sb-tahun')}}">Kertas Kerja</a></li>
            <li class="breadcrumb-item active">Tahun {{$tahun->tahun}}</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rencana Anggaran Tahun {{$tahun->tahun}}</h3>
                        <div class="card-tools">
                            <a href="{{route('sb-tahun')}}" class="btn btn-outline-secondary btn-sm"><i
                                    class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-1 pl-2 pr-2">
                        <div class="my-horizontal-scrollbar" id="buttonBar">
                            <button class="btn btn-outline-primary btn-xs" data-tahun-id="{{$tahun->id}}"
                                    id="btnTambahSbTanggal"><i
                                    class="fa fa-plus"></i></button>
                            @foreach($tahun->tanggal as $tanggal)
                                <div class="btn-group btn-group-xs btn-kertas-kerja-g" role="group"
                                     id="tglKertasKerja{{$tanggal->id}}" data-tanggal-id="{{$tanggal->id}}">
                                    {{--                                    <button type="button"--}}
                                    {{--                                            class="btn btn-xs btn-outline-dark btn-kertas-kerja"--}}
                                    {{--                                            onclick="fetchKertasKerja('{{$tanggal->tanggal}}','{{$tanggal->id}}')"--}}
                                    {{--                                            id="btnFetchKertasKerja{{$tanggal->id}}">{{date('d/m/Y', strtotime($tanggal->tanggal))}}</button>--}}
                                    <a href="{{route('sb-tahun.fetch-pendapatan', [$tahun->id, $tanggal->id])}}"
                                       class="btn btn-xs btn-kertas-kerja {{$tanggal->id == $tanggal_id ? 'btn-dark' :'btn-outline-dark'}}"
                                       id="btnFetchKertasKerja{{$tanggal->id}}">{{date('d/m/Y', strtotime($tanggal->tanggal))}}</a>
                                    @if($loop->last)
                                        <button onclick="deleteTanggalKertasKerja('{{$tanggal->id}}')"
                                                class="btn btn-xs btn-outline-danger" id="btnDeleteTanggal"><i
                                                class="fa fa-times"></i></button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card card-danger card-outline card-outline-tabs" id="cardKertasKerja"
                     style="display: block">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link text-muted active" id="pendapatan-tab" data-toggle="pill"
                                   href="#pendapatanContent" role="tab" aria-controls="custom-tabs-two-home"
                                   aria-selected="false">Pendapatan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-muted" id="belanja-tab" data-toggle="pill"
                                   href="#belanjaContent" role="tab" aria-controls="custom-tabs-two-profile"
                                   aria-selected="false">Belanja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-muted" id="pembiayaan-tab" data-toggle="pill"
                                   href="#pembiayaanContent" role="tab" aria-controls="custom-tabs-two-messages"
                                   aria-selected="false">Pembiayaan</a>
                            </li>
                        </ul>
                        <div class="tab-custom-content pl-3 mb-0 d-flex" id="kertasKerjaCustomeTitle">
                            <p class="text-muted mb-0">Kertas Kerja tanggal <span
                                    id="kertasKerjaTanggalTitle">{{date('d/m/Y', strtotime($tahun->tanggal->where('id', '=', $tanggal_id)->first()->tanggal))}}</span>
                                <span id="pendapatanSatatusText"> |
                                    <span id="statusSumberDana">Total</span> Pendapatan : Rp.
                                    <span id="totalSumberDana" class="text-danger">0</span>
                                </span>
                                <span id="belanjaStatusText" style="display: none"> |
                                    <span id="statusBelanja">Total Belanja : Rp. </span>
                                    <span id="totalBelanja" class="text-warning">0</span>
                                </span>
                                <span id="pembiayaanStatusText" style="display: none"> |
                                    <span id="statusPembiayaan">Total Pembiayaan : Rp. </span>
                                    <span id="totalPembiayaan" class="text-danger">0</span>
                                </span>
                            </p>
                            <button class="btn btn-outline-secondary btn-xs ml-auto mr-2" id="btnTambahPendapatan"
                                    style="display: block"
                                    data-pendapatan-tanggal-id="{{$tanggal_id}}"><i class="fa fa-plus"></i> Tambah
                            </button>
                            <button class="btn btn-outline-secondary btn-xs ml-auto mr-2" style="display: none"
                                    id="btnTambahBelanja" data="belanja-tanggal-id"><i class="fa fa-plus"></i> Tambah
                            </button>
                            <button class="btn btn-outline-secondary btn-xs ml-auto mr-2" style="display: none"
                                    id="btnTambahPembiayaan" data="pembiayaan-tanggal-id"><i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="custom-tabs-two-tabContent">
                            <div class="tab-pane fade active show" id="pendapatanContent" role="tabpanel"
                                 aria-labelledby="custom-tabs-two-home-tab">
                                <h3 class="text-center mt-3 pb-3" style="display: none" id="noDataPendapatan">Tidak ada
                                    data.</h3>
                                <div class="d-flex justify-content-center" id="itemKertasKerjaLoader"></div>
                                <div id="pendapatanContentTable"></div>
                            </div>
                            <div class="tab-pane fade" id="belanjaContent" role="tabpanel"
                                 aria-labelledby="custom-tabs-two-profile-tab">
                                <h3 class="text-center mt-3 pb-3" style="display: none" id="noDataBelanja">Tidak ada
                                    data.</h3>
                                <div class="d-flex justify-content-center" id="itemKertasKerjaBelanjaLoader"></div>
                                <div id="belanjaContentTable"></div>
                            </div>
                            <div class="tab-pane fade" id="pembiayaanContent" role="tabpanel"
                                 aria-labelledby="custom-tabs-two-messages-tab">
                                <h3 class="text-center mt-3 pb-3" style="display: none" id="noDataPembiayaan">Tidak ada
                                    data.</h3>
                                <div class="d-flex justify-content-center" id="itemKertasKerjaPembiayaanLoader"></div>
                                <div id="pembiayaanContentTable"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>

    <!-- Tanggal Modal -->
    <div class="modal fade" id="modalTanggal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalTanggal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormTanggal">Form Tanggal Kertas Kerja</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formTanggal">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="tahunId" name="sb_tahun_id">
                        <div class="form-group">
                            <label for="tanggal"><span class="text-danger">*</span>Tanggal Kertas Kerja</label>
                            <input type="text" class="form-control" name="tanggal" id="tanggal"
                                   placeholder="Masukan tanggal">
                            <div class="invalid-feedback" id="tanggal_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanTanggal">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahTanggal"
                                onclick="" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pendapatan -->
    <div class="modal fade" id="modalItemPendapatan" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalItemPendapatan" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormItemKertasKerja">Form pendapatan</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formItemPendapatan">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="pendapatanTanggalId" name="sd_tanggal_id">
                        <div class="form-group">
                            <label for="opd"><span class="text-danger">*</span>OPD</label>
                            <select name="unit_id" id="opdPendapatan" class="form-control" style="width: 100%;">
                                @foreach($opds as $opd)
                                    <option value="{{$opd->id}}">
                                        {{$opd->bidang->urusan->kode.'.'.$opd->bidang->kode.'.'.$opd->kode.' '.$opd->nama_unit}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="unit_id_feedback">
                                Please provide a valid city.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rekeningPendapatan"><span class="text-danger">*</span>Rekening</label>
                            <select name="jenis_id" id="rekeningPendapatan" class="form-control" style="width: 100%;">
                                @foreach($rekPendapatans as $rek)
                                    <option value="{{$rek->id}}">
                                        {{$rek->kode_akun.'.'.$rek->kode_kelompok.'.'.$rek->kode.' '.$rek->nama_jenis}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="jenis_id_feedback">
                                Please provide a valid city.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="uraianPendapatan"><span class="text-danger">*</span>Uraian</label>
                            <textarea name="uraian" id="uraianPendapatan" rows="2" class="form-control"></textarea>
                            <div class="invalid-feedback" id="uraian_feedback">
                                Please provide a valid city.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nilaiPendapatan"><span class="text-danger">*</span>Nilai</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RP.</span>
                                </div>
                                <input type="text" class="form-control format-nilai" autofocus name="nilai"
                                       id="nilaiPendapatan" data-a-dec="," data-a-sep=".">
                            </div>
                            <div class="invalid-feedback" id="nilai_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanItemPendapatan">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Belanja -->
    <div class="modal fade" id="modalItemBelanjan" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalItemBelanjan" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormItemBelanja">Form Tanggal Kertas Kerja</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formItemBelanja">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col">
                                @csrf
                                <input type="hidden" id="tanggalId" name="sd_tanggal_id">
                                <input type="hidden" id="belanjaTotalPendapatan" name="total_pendapatan">
                                <div class="form-group">
                                    <label for="tanggal"><span class="text-danger">*</span>OPD</label>
                                    <select name="unit_id" id="opdBelanja" class="form-control" style="width: 100%;">
                                        @foreach($opds as $opd)
                                            <option value="{{$opd->id}}">
                                                {{$opd->bidang->urusan->kode.'.'.$opd->bidang->kode.'.'.$opd->kode.' '.$opd->nama_unit}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="opd_feedback">
                                        Please provide a valid city.
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="rekening"><span class="text-danger">*</span>Rekening</label>
                                    <select name="jenis_id" id="rekeningBelanja" class="form-control"
                                            style="width: 100%;">
                                        @foreach($rekBelanjas as $belanja)
                                            <option value="{{$belanja->id}}">
                                                {{$belanja->kode_akun.'.'.$belanja->kode_kelompok.'.'.$belanja->kode.' '.$belanja->nama_jenis}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="rekening_feedback">
                                        Please provide a valid city.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="uraian"><span class="text-danger">*</span>Uraian</label>
                            <textarea name="uraian" id="uraian" rows="2" class="form-control"></textarea>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="nilaiBelanja"><span class="text-danger">*</span>Nilai</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">RP.</span>
                                        </div>
                                        <input type="text" class="form-control format-nilai" autofocus name="nilai"
                                               id="nilaiBelanja" data-a-dec="," data-a-sep=".">
                                    </div>
                                    {{--                            <div class="invalid-feedback" id="nilai_feedback">--}}
                                    {{--                                Please provide a valid city.--}}
                                    {{--                            </div>--}}
                                    <span class="text-danger mt-1" id="nilaiWarning" style="display: none"><small>Nilai yang dimasukan melebihi pendapatan.</small></span>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="rekening"><span class="text-danger">*</span>Sumber Dana</label>
                                    <select name="pendapatan_id" id="rekeningSumberDana" class="form-control"
                                            style="width: 100%;">
                                        @foreach($rekPendapatans as $rek)
                                            <option value="{{$rek->id}}">
                                                {{$rek->kode_akun.'.'.$rek->kode_kelompok.'.'.$rek->kode.' '.$rek->nama_jenis}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="rekening_feedback">
                                        Please provide a valid city.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="pembiayaanCheckbox"
                                   name="pembiayaan_checkbox" value="1" disabled>
                            <label class="form-check-label" for="pembiayaanCheckbox"><strong>Gunakan Pembiayaan</strong></label>
                        </div>

                        <div class="form-group" id="belanjaPembiayaan" style="display: none">
                            <select name="pembiayaan_id" id="rekeningBelanjaPembiayaan" class="form-control"
                                    style="width: 100%;">

                                @foreach($rekPembiayaans as $pembiayaan)
                                    <option value="{{$pembiayaan->id}}">
                                        {{$pembiayaan->kode_akun.'.'.$pembiayaan->kode_kelompok.'.'.$pembiayaan->kode.' '.$pembiayaan->nama_jenis}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="rekening_feedback">
                                Please provide a valid city.
                            </div>
                            <span class="text-danger mt-1" id="pembiayaanWarning" style="display: none"><small>Nilai pembiayaan tidak mencukupi.</small></span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanItemBelanja">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pembiayaan -->
    <div class="modal fade" id="modalItemPembiayaan" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalItemPembiayaan" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormItemPembiayaan">Form Tanggal Kertas Kerja</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formItemPembiayaan">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="pembiayaanTanggalId" name="sd_tanggal_id">
                        <div class="form-group">
                            <label for="tanggal"><span class="text-danger">*</span>OPD</label>
                            <select name="unit_id" id="opdPembiayaan" class="form-control" style="width: 100%;">
                                @foreach($opds as $opd)
                                    <option value="{{$opd->id}}">
                                        {{$opd->bidang->urusan->kode.'.'.$opd->bidang->kode.'.'.$opd->kode.' '.$opd->nama_unit}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="opd_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rekening"><span class="text-danger">*</span>Rekening</label>
                            <select name="jenis_id" id="rekeningPembiayaan" class="form-control" style="width: 100%;">

                                @foreach($rekPembiayaans as $pembiayaan)
                                    <option value="{{$pembiayaan->id}}">
                                        {{$pembiayaan->kode_akun.'.'.$pembiayaan->kode_kelompok.'.'.$pembiayaan->kode.' '.$pembiayaan->nama_jenis}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="rekening_feedback">
                                Please provide a valid city.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="uraian"><span class="text-danger">*</span>Uraian</label>
                            <textarea name="uraian" id="uraianPembiayaan" rows="2" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="nilaiPembiayaan"><span class="text-danger">*</span>Nilai</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RP.</span>
                                </div>
                                <input type="text" class="form-control format-nilai" autofocus name="nilai"
                                       id="nilaiPembiayaan" data-a-dec="," data-a-sep=".">
                            </div>
                            {{--                            <div class="invalid-feedback" id="nilai_feedback">--}}
                            {{--                                Please provide a valid city.--}}
                            {{--                            </div>--}}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanItemPembiayaan">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal upadte nominal--}}
    <div class="modal fade" id="modalNominal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalNominal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormItemBelanja">Form Ubah Nominal</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formNominal">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="kertasKerjaId" name="kertas_kerja_id">
                        <input type="hidden" id="updateNominalTanggalId" name="sd_tanggal_id">
                        <div class="form-group">
                            <label for="newNominal"><span class="text-danger">*</span>Nominal</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RP.</span>
                                </div>
                                <input type="text" class="form-control" autofocus name="new_nominal"
                                       id="newNominal">
                            </div>
                            <div class="invalid-feedback" id="new_nominal_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahNominal"
                                onclick="">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('assets/lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/lte/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/lte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@section('style')
    <style>
        .table-head {
            height: 3rem !important;
        }

        .table thead th {
            vertical-align: middle;
        }
    </style>
@endsection

@section('js')
    <script src="{{asset('assets/lte/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/lte/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/lte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('assets/lte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('app/autonumeric@4.5.4.js')}}"></script>

@endsection

@section('script')
    <script src="{{asset('app/sb.js')}}"></script>
    <script>
        $(function () {
            fetchKertasKerja('{{$tanggal_id}}')
        })
    </script>
@endsection
