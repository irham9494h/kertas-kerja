@extends('layouts.app')

@section('title', 'Rekening')
@section('menu-rekening', 'active')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Rekening</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item">Data Master</li>
            <li class="breadcrumb-item active">Rekening</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
{{--                        <h3>Rekening</h3>--}}
{{--                        <div class="card-tools">--}}
{{--                            <span>tahun</span>--}}
{{--                        </div>--}}
                        <div class="d-flex justify-content-between">
                            <h3>Rekening</h3>
                            <h4><span class="badge badge-info">Rekening tahun {{$tahun->tahun}}</span></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="content-akun-tab" data-toggle="pill"
                                   href="#akun" role="tab"
                                   aria-controls="content-akun" aria-selected="false">Akun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-kelompok-tab" data-toggle="pill"
                                   href="#kelompok" role="tab"
                                   aria-controls="content-kelompok" aria-selected="false">Kelompok</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-jenis-tab" data-toggle="pill"
                                   href="#jenis" role="tab"
                                   aria-controls="content-jenis" aria-selected="true">Jenis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-objek-tab" data-toggle="pill"
                                   href="#objek" role="tab"
                                   aria-controls="content-objek" aria-selected="false">Objek</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="custom-content-above-tabContent">
                            <div class="tab-pane fade active show" id="akun" role="tabpanel"
                                 aria-labelledby="content-akun-tab">

                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahAkun"
                                                data-mode="create"><i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <table class="table table-striped table-sm mt-1" id="tableAkun">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode</th>
                                        <th>Akun</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($akuns as $akun)
                                        <tr id="rowAkun{{$akun->id}}">
                                            <td id="rowKodeAkun{{$akun->id}}">{{$akun->kode}}</td>
                                            <td><a href="#" id="rowNamaAkun{{$akun->id}}"
                                                   onclick="goToKelompokTab('{{$akun->id}}')">{{$akun->nama_akun}}</a>
                                            </td>
                                            @can('isSuperAdmin')
                                                <td>
                                                    <button class="btn btn-xs btn-outline-warning"
                                                            onclick="editAkun('{{$akun->id}}')"
                                                            id="btnEditAkun{{$akun->id}}"
                                                            data-update-url="{{route('rek.akun.update', $akun->id)}}"
                                                            data-urusan-id="{{$akun->id}}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-outline-danger"
                                                            id="btnDeleteAkun{{$akun->id}}"
                                                            data-delete-url="{{route('rek.akun.delete', $akun->id)}}"
                                                            data-akun-id="{{$akun->id}}"
                                                            onclick="deleteAkun({{$akun->id}})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="kelompok" role="tabpanel"
                                 aria-labelledby="content-kelompok-tab">
                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahKelompok"
                                                data-mode="create" data-id-akun="" data-kode-akun="" data-akun=""
                                                style="display: none">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <div class="d-flex justify-content-center" id="kelompokLoader"></div>
                                <h3 class="text-center mt-3" id="noDataKelompok">Tidak ada data.</h3>

                                <table class="table table-striped table-sm mt-1" id="tableKelompok"
                                       style="display: none">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode Akun</th>
                                        <th style="width: 5rem">Kode Kelompok</th>
                                        <th>Kelompok</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody id="tableKelompokBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="jenis" role="tabpanel"
                                 aria-labelledby="content-jenis-tab">
                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahJenis"
                                                data-mode="create" data-id-kelompok="" data-kode-kelompok=""
                                                data-kelompok=""
                                                style="display: none">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <div class="d-flex justify-content-center" id="jenisLoader"></div>
                                <h3 class="text-center mt-3" id="noDataJenis">Tidak ada data.</h3>

                                <table class="table table-striped table-sm mt-1" id="tableJenis" style="display: none">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode Akun</th>
                                        <th style="width: 5rem">Kode Kelompok</th>
                                        <th style="width: 5rem">Kode Jenis</th>
                                        <th>Jenis</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody id="tableJenisBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="objek" role="tabpanel"
                                 aria-labelledby="content-objek-tab">
                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahObjek"
                                                data-mode="create" data-id-jenis="" data-kode-jenis="" data-jenis=""
                                                style="display: none">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <div class="d-flex justify-content-center" id="objekLoader"></div>
                                <h3 class="text-center mt-3" id="noDataObjek">Tidak ada data.</h3>

                                <table class="table table-striped table-sm mt-1" id="tableObjek" style="display: none">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode Akun</th>
                                        <th style="width: 5rem">Kode Kelompok</th>
                                        <th style="width: 5rem">Kode Jenis</th>
                                        <th style="width: 5rem">Kode Objek</th>
                                        <th>Objek</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody id="tableObjekBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekening Akun Modal -->
    <div class="modal fade" id="modalAkun" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormAkun"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormAkun">Form Akun</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formAkun">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="kodeAkun">Kode Akun</label>
                            <input type="text" class="form-control" name="kode_akun" id="kodeAkun"
                                   placeholder="Masukan kode akun">
                            <div class="invalid-feedback" id="kode_akun_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaAkun">Nama Akun</label>
                            <input type="text" class="form-control" name="nama_akun" id="namaAkun"
                                   placeholder="Masukan nama akun">
                            <div class="invalid-feedback" id="nama_akun_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="aliasAkun">Nama Alias</label>
                            <select name="alias_akun" id="aliasAkun" class="form-control">
                                <option value="pendapatan" selected>Rek. Pendapatan</option>
                                <option value="belanja">Rek. Belanja</option>
                                <option value="pembiayaan">Rek. Pembiayaan</option>
                            </select>
                            <div class="invalid-feedback" id="alias_akun_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="akunModalFooter">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanAkun">Simpan</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahAkun"
                                onclick="updateAkun(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rekening Kelompok Modal -->
    <div class="modal fade" id="modalKelompok" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormKelompok"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormKelompok">Form Kelompok</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formKelompok">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="akun_id" id="akunId">
                        <div class="form-group">
                            <label for="akunKelompok">Akun</label>
                            <input type="text" class="form-control" id="akunKelompok" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kodeKelompok">Kode Kelompok</label>
                            <input type="text" class="form-control" name="kode_kelompok" id="kodeKelompok"
                                   placeholder="Masukan kode kelompok">
                            <div class="invalid-feedback" id="kode_kelompok_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaKelompok">Nama Kelompok</label>
                            <input type="text" class="form-control" name="nama_kelompok" id="namaKelompok"
                                   placeholder="Masukan nama kelompok">
                            <div class="invalid-feedback" id="nama_kelompok_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="kelompokModalFooter">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanKelompok">Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahKelompok"
                                onclick="updateKelompok(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rekening Jenis Modal -->
    <div class="modal fade" id="modalJenis" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormJenis"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormJenis">Form Jenis</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formJenis">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="kelompok_id" id="kelompokId">
                        <div class="form-group">
                            <label for="kelompokJenis">Kelompok</label>
                            <input type="text" class="form-control" id="kelompokJenis" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kodeJenis">Kode Jenis</label>
                            <input type="text" class="form-control" name="kode_jenis" id="kodeJenis"
                                   placeholder="Masukan kode jenis">
                            <div class="invalid-feedback" id="kode_jenis_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaJenis">Nama Jenis</label>
                            <input type="text" class="form-control" name="nama_jenis" id="namaJenis"
                                   placeholder="Masukan nama jenis">
                            <div class="invalid-feedback" id="nama_jenis_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="jenisModalFooter">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanJenis">Simpan</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahJenis"
                                onclick="updateJenis(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rekening Objek Modal -->
    <div class="modal fade" id="modalObjek" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormObjek"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormObjek">Form Objek</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formObjek">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="jenis_id" id="jenisId">
                        <div class="form-group">
                            <label for="jenisObjek">Jenis</label>
                            <input type="text" class="form-control" id="jenisObjek" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kodeObjek">Kode Objek</label>
                            <input type="text" class="form-control" name="kode_objek" id="kodeObjek"
                                   placeholder="Masukan kode objek">
                            <div class="invalid-feedback" id="kode_objek">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaObjek">Nama Objek</label>
                            <input type="text" class="form-control" name="nama_objek" id="namaObjek"
                                   placeholder="Masukan nama objek">
                            <div class="invalid-feedback" id="nama_objek">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="jenisModalFooter">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanObjek">Simpan</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahObjek"
                                onclick="updateObjek(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('assets/lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
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
    <script src="{{asset('assets/lte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endsection

@section('script')
    <script src="{{asset('app/rekening.min.js')}}"></script>
@endsection
