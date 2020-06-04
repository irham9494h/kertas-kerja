@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('menu-menejemen-pengguna', 'active')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Manajemen Pengguna</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Manajemen Pengguna</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Pengguna</h3>
                        <div class="card-tools">
                            <button class="btn btn-outline-primary btn-sm" id="btnTambahPengguna"><i
                                    class="fa fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelPengguna" class="table table-striped table-sm">
                            <thead class="bg-danger">
                            <tr class="table-head">
                                <th>No.</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody id="tablePenggunaBody">

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center" id="tablePenggunaLoader"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- Pengguna Modal -->
    <div class="modal fade" id="modalPengguna" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="modalFormPengguna"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="modalFormPengguna">Form Pengguna</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="formPengguna">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" name="nip" id="nip"
                                   placeholder="Masukan NIP">
                            <div class="invalid-feedback" id="nip_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Pengguna</label>
                            <input type="text" class="form-control" name="nama" id="nama"
                                   placeholder="Masukan nama pengguna">
                            <div class="invalid-feedback" id="nama_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username"
                                   placeholder="Masukan username pengguna">
                            <div class="invalid-feedback" id="username_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="password"
                                   placeholder="Masukan password pengguna">
                            <div class="invalid-feedback" id="password_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="admin">Admin</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                            <div class="invalid-feedback" id="role_feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanPengguna">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSimpanUbahPengguna"
                                onclick="updatePengguna(event)" style="display: none">Simpan Perubahan
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
    <script src="{{asset('app/pengguna.js')}}"></script>
@endsection
