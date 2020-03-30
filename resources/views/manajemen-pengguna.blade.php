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
                        <h3 class="card-title">DataTable with default features</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="users-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
{{--        <link rel="stylesheet" href="{{asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">--}}
@endsection

@section('js')
{{--    <script src="{{asset('assets/lte/plugins/datatables/jquery.dataTables.js')}}"></script>--}}
{{--    <script src="../../"></script>--}}
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
@endsection

@section('script')
    <script>
        $(function () {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('user-json') !!}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'username', name: 'username' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        })
    </script>
@endsection
