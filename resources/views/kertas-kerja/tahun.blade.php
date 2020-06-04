@extends('layouts.app')

@section('title', 'Sumber Dana')
@section('kertas-kerja', 'active')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Kertas Kerja</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Kertas Kerja</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kertas Kerja</h3>
                        <div class="card-tools">
                            <button class="btn btn-outline-primary btn-sm" id="btnTambahKertasKerja"><i
                                    class="fa fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm mt-1" id="tableKertasKerja">
                            <thead class="bg-danger w-100">
                            <tr class="table-head">
                                <th>Tahun</th>
                                <th>Deskripsi</th>
                                <th class="pull-right" style="width: 80px">Aksi</th>
                            </tr>
                            </thead>
                            <tbody id="tableKertasKerjaBody">
                            @if($tahuns->count() > 0)
                                @foreach($tahuns as $tahun)
                                    <tr id="rowKertasKerja{{$tahun->id}}">
                                        <td id="rowTahun{{$tahun->id}}">{{$tahun->tahun}}</td>
                                        @if ($tahun->deskripsi !== null)
                                            <td id="rowDeskripsi{{$tahun->id}}">{{$tahun->deskripsi}}</td>
                                        @else
                                            <td id="rowDeskripsi{{$tahun->id}}">-</td>
                                        @endif
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <button class="btn btn-outline-primary btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                        class="fa fa-bars"></i></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{route('sb-tahun.kertas-kerja', $tahun->id)}}" class="dropdown-item" type="button">Buka</a>
                                                    <button class="dropdown-item" type="button" id="btnHapusKertasKerja"
                                                            data-id="{{$tahun->id}}"
                                                            onclick="deleteKertasKerja('{{$tahun->id}}')">Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="3" class="text-center">tidak ada data</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kertas Kerja Modal -->
    <div class="modal fade" id="modalKertasKerja" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormKertasKerja" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormKertasKerja">Form Kertas Kerja</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formKertasKerja">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="tahun"><span class="text-danger">*</span>Tahun Anggaran</label>
                            <input type="text" class="form-control" name="tahun" id="tahun"
                                   placeholder="Masukan tahun">
                            <div class="invalid-feedback" id="tahun_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi"
                                      placeholder="Masukan deskripsi"></textarea>
                            <div class="invalid-feedback" id="deskripsi_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanKertasKerja">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahKertasKerja"
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
    <script src="{{asset('app/sb-tahun.js')}}"></script>
@endsection
