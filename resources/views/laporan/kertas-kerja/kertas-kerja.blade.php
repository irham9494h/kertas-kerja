@extends('layouts.app')

@section('title', 'Laporan Kertas Kerja')
@section('menu-laporan', 'active')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark"> Laporan Kertas Kerja</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{route('sb-tahun')}}">Kertas Kerja</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Filter Laporan</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select class="form-control form-control-sm" id="selectTahun" name="tahun_id">
                                        @foreach($tahuns as $tahun)
                                            <option value="{{$tahun->id}}">{{$tahun->tahun}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <select class="form-control form-control-sm" id="selectTanggal" name="tanggal_id">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Pergeseran</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm float-right">Tampilkan</button>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            getTanggalByID($('#selectTahun').val())

            $('#selectTahun').on('change', function () {
                getTanggalByID($(this).val())
            })

        });

        function getTanggalByID(tahunID) {
            $.ajax({
                type: 'GET',
                url: window.location.origin + '/report/kertas-kerja/tgl/' + tahunID,
                dataType: 'json',
                success: function (response) {
                    var html = '';
                    var data = response;
                    if (data.length > 0) {
                        html = '';
                        for (i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].id + '" data-tanggal="' + data[i].tanggal + '">' + formatDate(data[i].tanggal) + '</option>';
                        }
                        $('#selectTanggal').html(html)
                    } else {
                        html = '<option>Belum ada pembahasan.</option>';
                        $('#selectTanggal').html(html)
                    }
                }
            })
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [day, month, year].join('/');
        }
    </script>
@endsection
