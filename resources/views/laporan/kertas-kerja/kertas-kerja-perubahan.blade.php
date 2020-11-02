@extends('layouts.app')

@section('title', 'Laporan Kertas Kerja')
@section('menu-laporan', 'active')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/laporan-style/kertas-kerja-2020.css')}}">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px !important;
        }

        .table-row {
            padding: 0px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
    </style>
@endsection

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
                <div class="alert alert-danger">
                    <ol>
                        <li>ubah algoritma tambah tanggal di kertas kerja perubahan</li>
                        <li>terapkan rule tidak bisa menambah
                            kertas kerja perubahan jika tidak ada kertas kerja murni
                        </li>
                        <li> dan ketika membuat kertas kerja
                            perubahan pertama kali, copy semua dari sturktur murni
                        </li>
                    </ol>
                </div>

                <form action="{{route('lap-kk.view')}}" method="POST" id="filterForm">
                    @csrf
                    <input type="hidden" name="report" id="report">
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
                                                <option
                                                    value="{{$tahun->id}}" {{$request->tahun_id == $tahun->id ? 'selected' : ''}}>{{$tahun->tahun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <select class="form-control form-control-sm" id="selectTanggal"
                                                name="tanggal">
                                            @foreach($tanggals as $tgl)
                                                <option value="{{$tgl->tanggal}}"
                                                        {{$request->tanggal == $tgl->tanggal ? 'selected' : ''}}
                                                        data-tanggal="{{$tgl->tanggal}}">
                                                    {{\Carbon\Carbon::parse($tgl->tanggal)->format('d/m/Y')}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="pergeseran"
                                                   name="pergeseran" {{$pergeseran == 'on' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="pergeseran">Pergeseran</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-primary btn-sm float-right" id="btnView">
                                Tampilkan
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btnReport">
                                <i class="fa fa-file-pdf"></i> Cetak
                            </button>
                        </div>
                    </div>
                </form>
                <!-- /.card -->
            </div>

            @if(isset($perubahanPendapatan))
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Tanggal {{\Carbon\Carbon::parse($request->tanggal)->format('d/m/Y')}}</h3>
                        </div>

                        <div class="card-body">
                            <table class="border-05">
                                <tbody>
                                <tr class="border-05">
                                    <td style="border-right: 0 !important; padding: 2px">
                                        <img src="{{asset('assets/logo/pemprov-ntb.jpg')}}" alt="Logo"
                                             style="width: 35px !important;">
                                    </td>
                                    <td colspan="5" class="text-center" style="border-left: 0 !important;">
                                        <h5 class="m-0">PEMERINTAH PROVINSI NUSA TENGGARA BARAT</h5>
                                        <h3 class="m-0">RINGKASAN RANCANGAN APBD</h3>
                                        <p class="m-0 font-10">TAHUN ANGGARAN 2020</p>
                                    </td>
                                </tr>

                                <tr style="">
                                    <td class="text-center border-05" style="width: 70px; " rowspan="2"><strong>NO.
                                            URUT</strong>
                                    </td>
                                    <td class="text-center border-05" style="" rowspan="2"><strong>URAIAN</strong></td>
                                    <td class="text-center border-05" style="width: 150px; " colspan="2">
                                        <strong>JUMLAH</strong>
                                    </td>
                                    <td class="text-center border-05" style="width: 150px; " colspan="2">
                                        <strong>BERTAMBAH/(BERKURANG)</strong>
                                    </td>
                                </tr>

                                <tr style="">
                                    <td class="text-center border-05" style="width: 150px; "><strong>SEBELUM
                                            PERGESERAN</strong>
                                    </td>
                                    <td class="text-center border-05" style="width: 150px; "><strong>SETELAH
                                            PERGESERAN</strong>
                                    </td>
                                    <td class="text-center border-05" style="width: 150px; "><strong>(Rp)</strong>
                                    </td>
                                    <td class="text-center border-05" style="width: 80px; "><strong>%</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center border-05" style=""><strong>1</strong></td>
                                    <td class="text-center border-05" style=""><strong>2</strong></td>
                                    <td class="text-center border-05" style=""><strong>3</strong></td>
                                    <td class="text-center border-05" style=""><strong>4</strong></td>
                                    <td class="text-center border-05" style=""><strong>5 = 4 - 3</strong></td>
                                    <td class="text-center border-05" style=""><strong>6</strong></td>
                                </tr>

                                <!--Perubahan Pendapatan-->
                                @if (!empty($perubahanPendapatan))
                                    @include('laporan.kertas-kerja.part-perubahan.pendapatan')
                                @endif

                                <tr>
                                    <td class="border-right-05 table-row"></td>
                                    <td class=" table-row"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                </tr>

                                <!--Perubahan Belanja-->
                                @if(!empty($perubahanBelanja))
                                    @include('laporan.kertas-kerja.part-perubahan.belanja')
                                @endif

                                <tr>
                                    <td class="border-right-05 table-row"></td>
                                    <td class=" table-row"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                    <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            // getTanggalByID($('#selectTahun').val())

            // $('#selectTahun').on('change', function () {
            //     getTanggalByID($(this).val())
            // })

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

        $('#btnView').on('click', function () {
            $('#report').val(0);
            $('#filterForm').submit();
        })

        $('#btnReport').on('click', function () {
            $('#report').val(1);
            $('#filterForm').submit();
        })


    </script>
@endsection
