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
                        <h3 class="card-title">{{$title}} | <strong id="lockStatus">{{$tahun->status_murni == 0 ? 'Terbuka' : 'Terkunci'}}</strong></h3>
                        <div class="card-tools">
                            <a href="{{route('sb-tahun')}}" class="btn btn-outline-secondary btn-sm"><i
                                    class="fa fa-arrow-left"></i> Kembali
                            </a>
                            @if($tanggal_kertas_kerja->count() > 0)
                                @if($tanggal_kertas_kerja->first()->jenis_pembahasan == 'struktur_murni')
                                    <div class="btn-group btn-group-xs">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="fa fa-bars"></i></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <input type="hidden" value="{{$tahun->status_murni}}" id="statusMurni">

                                            <button class="dropdown-item" type="button" id="btnKunciKertasKerja"
                                                    data-pembahasan="struktur_murni"
                                                    data-id="{{$tahun->id}}"
                                                    onclick="" style="display: {{$tahun->status_murni == 0 ? 'block' : 'none'}}">
                                                <i class="fa fa-lock"></i> Kunci Struktur Murni
                                            </button>

                                            <button class="dropdown-item" type="button" id="btnBukaKertasKerja"
                                                    data-pembahasan="struktur_murni"
                                                    data-id="{{$tahun->id}}"
                                                    onclick="" style="display: {{$tahun->status_murni == 1 ? 'block' : 'none'}}">
                                                <i class="fa fa-lock-open"></i> Buka Struktur Murni
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-1 pl-2 pr-2">
                        <div class="my-horizontal-scrollbar" id="buttonBar">
                            <button class="btn btn-outline-primary btn-xs" data-tahun-id="{{$tahun->id}}"
                                    id="btnTambahSbTanggal"><i
                                    class="fa fa-plus"></i></button>
                            @foreach($tanggal_kertas_kerja as $tanggal)
                                <div class="btn-group btn-group-xs btn-kertas-kerja-g" role="group"
                                     id="tglKertasKerja{{$tanggal->id}}" data-tanggal-id="{{$tanggal->id}}">
                                    @if($tanggal->jenis_pembahasan == 'struktur_murni')
                                        <a href="{{route('sb-tahun.fetch-kertas-kerja', [$tahun->id, 'murni', $tanggal->id])}}"
                                           class="btn btn-xs btn-outline-dark btn-kertas-kerja">{{date('d/m/Y', strtotime($tanggal->tanggal))}}</a>
                                    @else
                                        <a href="{{route('sb-tahun.fetch-kertas-kerja', [$tahun->id, 'perubahan', $tanggal->id])}}"
                                           class="btn btn-xs btn-outline-dark btn-kertas-kerja">{{date('d/m/Y', strtotime($tanggal->tanggal))}}</a>
                                    @endif
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
                        <input type="hidden" id="jenisPembahasan" name="jenis_pembahasan" value="{{$pembahasan}}">
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

@endsection

@section('script')
    <script src="{{asset('app/kertas-kerja.min.js')}}"></script>
@endsection
