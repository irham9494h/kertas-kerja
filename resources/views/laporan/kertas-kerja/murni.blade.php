<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/laporan-style/kertas-kerja-2020.css')}}">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px !important;
        }

        .table-row {
            padding: 0px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .flyleaf {
            page-break-after: auto;
        }

        .header, .footer {
            position: fixed;
        }

        .header {
            top: 0;

        }

        .footer {
            bottom: 0;
        }

    </style>
</head>
<body>
<div>
    <p class="text-right font-10 m-3">Lampiran I : </p>
    <p class="text-right font-10 m-3">Nomor : </p>
    <p class="text-right font-10 m-3">Tanggal : {{$tanggal}}</p>
</div>

<div class="flyleaf">
    <table class="border-05" width="100%">
        <tbody>
        <tr class="border-05">
            <td style="border-right: 0 !important; padding: 2px">
                <img src="{{asset('assets/logo/pemprov-ntb.jpg')}}" alt="Logo"
                     style="width: 35px !important;">
            </td>
            <td colspan="2" class="text-center" style="border-left: 0 !important;">
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
            <td class="text-center border-05" style="width: 150px; "><strong>JUMLAH</strong>
            </td>
        </tr>

        <tr style="">
            <td class="text-center border-05" style="width: 150px; "><strong>SEBELUM
                    PERGESERAN</strong>
            </td>
        </tr>
        <tr>
            <td class="text-center border-05" style=""><strong>1</strong></td>
            <td class="text-center border-05" style=""><strong>2</strong></td>
            <td class="text-center border-05" style=""><strong>3</strong></td>
        </tr>

        {{--                Kertas kerja pendapatan--}}
        <tr id="">
            <td class="border-right-05 table-row">
                {{$pendapatan->kode_akun}}
            </td>
            <td class=" table-row"><strong>{{$pendapatan->nama_akun}}</strong></td>
            <td class=" table-row" style="border: 1px solid black; text-align: right">
                <strong>
                    {{number_format($pendapatan->nilai, 2, ',', '.')}}
                </strong>
            </td>
        </tr>
        @foreach($pendapatan->kelompok as $kelompok)
            <tr>
                <td class="border-right-05 table-row">
                    {{$pendapatan->kode_akun}}.{{$kelompok['kode_kelompok']}}
                </td>
                <td class=" table-row" style="text-indent: 2em">
                    <strong> {{$kelompok['nama_kelompok']}}</strong>
                </td>
                <td class=" table-row" style="border: 1px solid black; text-align: right">
                    <strong> {{number_format($kelompok['nilai'], 2, ',', '.')}}</strong>
                </td>
            </tr>
            @foreach($kelompok['jenis'] as $jenis)
                <tr>
                    <td class="border-right-05 table-row">
                        {{$pendapatan->kode_akun}}.{{$kelompok['kode_kelompok']}}
                        .{{$jenis['kode_jenis']}}
                    </td>
                    <td class=" table-row"
                        style="text-indent: 4em">{{$jenis['jenis']}}
                    </td>
                    <td class=" table-row"
                        style="border-left: 1px solid black; text-align: right">
                        {{number_format($jenis['nilai'], 2, ',', '.')}}
                    </td>
                </tr>
            @endforeach
        @endforeach

        <tr>
            <td class="border-right-05 table-row"></td>
            <td class=" table-row"></td>
            <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
        </tr>

        {{--                kertas kerja belanja--}}
        <tr id="">
            <td class="border-right-05 table-row">
                {{$belanja->kode_akun}}
            </td>
            <td class=" table-row"><strong>{{$belanja->nama_akun}}</strong></td>
            <td class=" table-row"
                style="border: 1px solid black; text-align: right">
                <strong>
                    {{number_format($belanja->nilai, 2, ',', '.')}}
                </strong>
            </td>
        </tr>
        @foreach($belanja->kelompok as $kelompok)
            <tr>
                <td class="border-right-05 table-row">
                    {{$belanja->kode_akun}}.{{$kelompok['kode_kelompok']}}
                </td>
                <td class=" table-row" style="text-indent: 2em">
                    <strong> {{$kelompok['nama_kelompok']}}</strong>
                </td>
                <td class=" table-row" style="border: 1px solid black; text-align: right">
                    <strong> {{number_format($kelompok['nilai'], 2, ',', '.')}}</strong>
                </td>
            </tr>
            @foreach($kelompok['jenis'] as $jenis)
                <tr>
                    <td class="border-right-05 table-row">
                        {{$belanja->kode_akun}}.{{$kelompok['kode_kelompok']}}
                        .{{$jenis['kode_jenis']}}
                    </td>
                    <td class=" table-row"
                        style="text-indent: 4em">{{$jenis['jenis']}}
                    </td>
                    <td class=" table-row"
                        style="border-left: 1px solid black; text-align: right">
                        {{number_format($jenis['nilai'], 2, ',', '.')}}
                    </td>
                </tr>
            @endforeach
        @endforeach

        <tr>
            <td class="border-right-05 table-row"></td>
            <td class=" table-row"></td>
            <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
        </tr>

        {{--                Surplus/defisit--}}
        <tr id="">
            <td class="border-right-05 table-row">

            </td>
            <td class="table-row text-right"><strong>SURPLUS/(DEFISIT)</strong></td>
            <td class=" table-row"
                style="border: 1px solid black; text-align: right">

                @php
                    $defisit = $pendapatan->nilai - $belanja->nilai;
                @endphp

                <strong>
                    {{$defisit < 0 ? '('.number_format(abs($defisit), 2, ',', '.').')' : $defisit}}
                </strong>
            </td>
        </tr>

        <tr>
            <td class="border-right-05 table-row"></td>
            <td class=" table-row"></td>
            <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
        </tr>

        {{--                kertas kerja pembiayaan--}}
        <tr id="">
            <td class="border-right-05 table-row">
                {{$pembiayaan->kode_akun}}
            </td>
            <td class=" table-row"><strong>{{$pembiayaan->nama_akun}}</strong></td>
            <td class=" table-row"
                style="border: 1px solid black; text-align: right">
                <strong>
                    {{number_format($pembiayaan->nilai, 2, ',', '.')}}
                </strong>
            </td>
        </tr>
        @foreach($pembiayaan->kelompok as $kelompok)

            @if ($kelompok['kode_kelompok'] == 2)
                @php
                    $nilaiPengeluaran = $kelompok['nilai'];
                @endphp
            @endif

            <tr>
                <td class="border-right-05 table-row">
                    {{$pembiayaan->kode_akun}}.{{$kelompok['kode_kelompok']}}
                </td>
                <td class=" table-row" style="text-indent: 2em">
                    <strong> {{$kelompok['nama_kelompok']}}</strong>
                </td>
                <td class=" table-row" style="border: 1px solid black; text-align: right">
                    <strong> {{number_format($kelompok['nilai'], 2, ',', '.')}}</strong>
                </td>
            </tr>
            @foreach($kelompok['jenis'] as $jenis)
                <tr>
                    <td class="border-right-05 table-row">
                        {{$pembiayaan->kode_akun}}.{{$kelompok['kode_kelompok']}}
                        .{{$jenis['kode_jenis']}}
                    </td>
                    <td class=" table-row"
                        style="text-indent: 4em">{{$jenis['jenis']}}
                    </td>
                    <td class=" table-row"
                        style="border-left: 1px solid black; text-align: right">
                        {{number_format($jenis['nilai'], 2, ',', '.')}}
                    </td>
                </tr>
            @endforeach
        @endforeach

        <tr>
            <td class="border-right-05 table-row"></td>
            <td class=" table-row"></td>
            <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
        </tr>

        Pembiayaan netto
        <tr id="">
            <td class="border-right-05 table-row">

            </td>
            <td class="table-row text-right"><strong>PEMBIAYAAN/NETTO</strong></td>
            <td class=" table-row"
                style="border: 1px solid black; text-align: right">

                @php
                    $pembiayaanNetto = $pembiayaan->nilai - ($nilaiPengeluaran * 2);
                @endphp

                <strong>
                    {{number_format($pembiayaanNetto, 2, ',', '.')}}
                </strong>
            </td>
        </tr>

        <tr>
            <td class="border-right-05 table-row"></td>
            <td class=" table-row"></td>
            <td class=" table-row" style="border: 1px solid black; height: 7px"></td>
        </tr>

        {{--                Sisa lebih pembiayaan anggaran tahun berkenaan--}}
        <tr id="">
            <td class="border-right-05 table-row">

            </td>
            <td class="table-row text-right"><strong>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN
                    BERKENAAN</strong></td>
            <td class=" table-row"
                style="border: 1px solid black; text-align: right">

                @php
                    $sisaPembiayaan = $pembiayaanNetto - abs($defisit);
                @endphp

                <strong>
                    {{number_format($sisaPembiayaan, 2, ',', '.')}}
                </strong>
            </td>
        </tr>

        </tbody>
    </table>
</div>

</body>
</html>

