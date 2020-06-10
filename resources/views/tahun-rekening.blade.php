@extends('layouts.app')

@section('title', 'Tahun Rekening')
@section('menu-tahun-rekening', 'active')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Tahun Rekening</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="">Pengaturan</a></li>
            <li class="breadcrumb-item active">Tahun Rekening</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tahun Rekening</h3>
                        <div class="card-tools">
                            <button class="btn btn-outline-primary btn-sm" id="btnTambahTahunRekening"><i
                                    class="fa fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm mt-1" id="tableTahunRekening">
                            <thead class="bg-danger w-100">
                            <tr class="table-head">
                                <th>Tahun</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th class="pull-right" style="width: 80px">Aksi</th>
                            </tr>
                            </thead>
                            <tbody id="tableTahunRekeningBody">
                            @if($tahuns->count() > 0)
                                @foreach($tahuns as $tahun)
                                    <tr id="rowTahunRekening{{$tahun->id}}">
                                        <td id="rowTahun{{$tahun->id}}">{{$tahun->tahun}}</td>
                                        @if ($tahun->deskripsi !== null)
                                            <td id="rowDeskripsi{{$tahun->id}}">{{$tahun->deskripsi}}</td>
                                        @else
                                            <td id="rowDeskripsi{{$tahun->id}}">-</td>
                                        @endif

                                        @if($tahun->status == 1)
                                            <td id="rowStatus{{$tahun->id}}"><span class="badge badge-info">Aktif</span>
                                            </td>
                                        @else
                                            <td id="rowStatus{{$tahun->id}}"><span
                                                    class="badge badge-dark">Tidak Aktif</span></td>
                                        @endif
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                <button class="btn btn-outline-primary btn-xs dropdown-toggle"
                                                        type="button"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"><i
                                                        class="fa fa-bars"></i></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    {{--                                                    <a href="{{route('sb-tahun.kertas-kerja', $tahun->id)}}" class="dropdown-item" type="button">Buka</a>--}}
                                                    @if($tahun->status == 0)
                                                        <button class="dropdown-item" type="button"
                                                                id="btnactivateTahunRekening"
                                                                data-id="{{$tahun->id}}"
                                                                onclick="activateTahunRekening('{{$tahun->id}}')">
                                                            Aktifkan
                                                        </button>
                                                    @endif

                                                    <button class="dropdown-item" type="button"
                                                            id="btnHapusTahunRekening"
                                                            data-id="{{$tahun->id}}"
                                                            onclick="deleteTahunRekening('{{$tahun->id}}')">Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center"><h3>Tidak ada data.</h3></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kertas Kerja Modal -->
    <div class="modal fade" id="modalTahunRekening" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormTahunRekening" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormTahunRekening">Form Kertas Kerja</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formTahunRekening">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="tahunId">
                        <div class="form-group">
                            <label for="tahun"><span class="text-danger">*</span>Tahun</label>
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
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanTahunRekening">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahTahunRekening"
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
    <script src="{{asset('app/th-rek.js')}}"></script>
@endsection
