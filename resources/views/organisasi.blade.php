@extends('layouts.app')

@section('title', 'Organisasi')
@section('menu-master', 'active')
@section('menu-organisasi', 'active')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Organisasi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item">Data Master</li>
            <li class="breadcrumb-item active">Organisasi</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Organisasi</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="content-urusan-tab" data-toggle="pill"
                                   href="#urusan" role="tab"
                                   aria-controls="content-urusan" aria-selected="false">Urusan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-bidang-tab" data-toggle="pill"
                                   href="#bidang" role="tab"
                                   aria-controls="content-bidang" aria-selected="false">Bidang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-unit-tab" data-toggle="pill"
                                   href="#unit" role="tab"
                                   aria-controls="content-unit" aria-selected="true">Unit</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-subunit-tab" data-toggle="pill"
                                   href="#subunit" role="tab"
                                   aria-controls="content-subunit" aria-selected="false">Sub Unit</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="custom-content-above-tabContent">
                            <div class="tab-pane fade active show" id="urusan" role="tabpanel"
                                 aria-labelledby="content-urusan-tab">

                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahUrusan"
                                                data-mode="create"><i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <table class="table table-striped table-sm mt-1" id="tableUrusan">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode</th>
                                        <th>Urusan</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($urusans as $urusan)
                                        <tr id="rowUrusan{{$urusan->id}}">
                                            <td id="rowKode{{$urusan->id}}">{{$urusan->kode}}</td>
                                            <td><a href="#" id="rowNama{{$urusan->id}}"
                                                   onclick="goToBidangTab('{{$urusan->id}}')">{{$urusan->nama_urusan}}</a>
                                            </td>
                                            @can('isSuperAdmin')
                                                <td>
                                                    <button class="btn btn-xs btn-outline-warning"
                                                            onclick="editUrusan('{{$urusan->id}}')"
                                                            id="btnEditUrusan{{$urusan->id}}"
                                                            data-update-url="{{route('org.urusan.update', $urusan->id)}}"
                                                            data-urusan-id="{{$urusan->id}}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-outline-danger"
                                                            id="btnDeleteUrusan{{$urusan->id}}"
                                                            data-delete-url="{{route('org.urusan.delete', $urusan->id)}}"
                                                            data-urusan-id="{{$urusan->id}}"
                                                            onclick="deleteUrusan({{$urusan->id}})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="bidang" role="tabpanel"
                                 aria-labelledby="content-bidang-tab">
                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahBidang"
                                                data-mode="create" data-id-urusan="" data-kode-urusan="" data-urusan=""
                                                style="display: none">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <div class="d-flex justify-content-center" id="bidangLoader"></div>
                                <h3 class="text-center mt-3" id="noDataBidang">Tidak ada data.</h3>

                                <table class="table table-striped table-sm mt-1" id="tableBidang" style="display: none">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode Urusan</th>
                                        <th style="width: 5rem">Kode Bidang</th>
                                        <th>Bidang</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody id="tableBidangBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="unit" role="tabpanel"
                                 aria-labelledby="content-unit-tab">
                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahUnit"
                                                data-mode="create" data-id-bidang="" data-kode-bidang="" data-bidang=""
                                                style="display: none">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <div class="d-flex justify-content-center" id="unitLoader"></div>
                                <h3 class="text-center mt-3" id="noDataUnit">Tidak ada data.</h3>

                                <table class="table table-striped table-sm mt-1" id="tableUnit" style="display: none">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode Urusan</th>
                                        <th style="width: 5rem">Kode Bidang</th>
                                        <th style="width: 5rem">Kode Unit</th>
                                        <th>Unit</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody id="tableUnitBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="subunit" role="tabpanel"
                                 aria-labelledby="content-subunit-tab">
                                @can('isSuperAdmin')
                                    <div class="row pt-1 pb-1 pl-2 pr-2 justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" id="btnTambahSubUnit"
                                                data-mode="create" data-id-unit="" data-kode-unit="" data-unit=""
                                                style="display: none">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                @endcan

                                <div class="d-flex justify-content-center" id="subUnitLoader"></div>
                                <h3 class="text-center mt-3" id="noDataSubUnit">Tidak ada data.</h3>

                                <table class="table table-striped table-sm mt-1" id="tableSubUnit" style="display: none">
                                    <thead class="bg-danger">
                                    <tr class="table-head">
                                        <th style="width: 5rem">Kode Urusan</th>
                                        <th style="width: 5rem">Kode Bidang</th>
                                        <th style="width: 5rem">Kode Unit</th>
                                        <th style="width: 5rem">Kode Sub Unit</th>
                                        <th>Sub Unit</th>
                                        @can('isSuperAdmin')
                                            <th class="pull-right" style="width: 80px">Aksi</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody id="tableSubUnitBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Urusan Modal -->
    <div class="modal fade" id="modalUrusan" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormUrusan"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormUrusan">Form Urusan</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formUrusan">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="kodeUrusan">Kode Urusan</label>
                            <input type="text" class="form-control" name="kode_urusan" id="kodeUrusan"
                                   placeholder="Masukan kode urusan">
                            <div class="invalid-feedback" id="kode_urusan_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaUrusan">Nama Urusan</label>
                            <input type="text" class="form-control" name="nama_urusan" id="namaUrusan"
                                   placeholder="Masukan nama urusan">
                            <div class="invalid-feedback" id="nama_urusan_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUrusan">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahUrusan"
                                onclick="updateUrusan(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bidang Modal -->
    <div class="modal fade" id="modalBidang" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormBidang"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormBidang">Form Bidang</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formBidang">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="urusan_id" id="urusanId">
                        <div class="form-group">
                            <label for="urusanBidang">Urusan</label>
                            <input type="text" class="form-control" id="urusanBidang" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kodeBidang">Kode Bidang</label>
                            <input type="text" class="form-control" name="kode_bidang" id="kodeBidang"
                                   placeholder="Masukan kode bidang">
                            <div class="invalid-feedback" id="kode_bidang_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaBidang">Nama Bidang</label>
                            <input type="text" class="form-control" name="nama_bidang" id="namaBidang"
                                   placeholder="Masukan nama bidang">
                            <div class="invalid-feedback" id="nama_bidang_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanBidang">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahBidang"
                                onclick="updateBidang(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Unit Modal -->
    <div class="modal fade" id="modalUnit" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormUnit"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormUnit">Form Unit</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formUnit">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="bidang_id" id="bidangId">
                        <div class="form-group">
                            <label for="bidangUnit">Bidang</label>
                            <input type="text" class="form-control" id="bidangUnit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kodeUnit">Kode Unit</label>
                            <input type="text" class="form-control" name="kode_unit" id="kodeUnit"
                                   placeholder="Masukan kode unit">
                            <div class="invalid-feedback" id="kode_unit_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaUnit">Nama Unit</label>
                            <input type="text" class="form-control" name="nama_unit" id="namaUnit"
                                   placeholder="Masukan nama unit">
                            <div class="invalid-feedback" id="nama_unit_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUnit">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahUnit"
                                onclick="updateUnit(event)" style="display: none">Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sub Unit Modal -->
    <div class="modal fade" id="modalSubUnit" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormSubUnit"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormSubUnit">Form Sub Unit</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formSubUnit">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="unit_id" id="unitId">
                        <div class="form-group">
                            <label for="UnitSubUnit">Unit</label>
                            <input type="text" class="form-control" id="unitSubUnit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kodeSubUnit">Kode Sub Unit</label>
                            <input type="text" class="form-control" name="kode_sub_unit" id="kodeSubUnit"
                                   placeholder="Masukan kode sub unit">
                            <div class="invalid-feedback" id="kode_sub_unit_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaSubUnit">Nama Sub Unit</label>
                            <input type="text" class="form-control" name="nama_sub_unit" id="namaSubUnit"
                                   placeholder="Masukan nama sub unit">
                            <div class="invalid-feedback" id="nama_sub_unit_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanSubUnit">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahSubUnit"
                                onclick="updateSubUnit(event)" style="display: none">Simpan Perubahan
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
    <script src="{{asset('app/urusan.min.js')}}"></script>
@endsection
