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
    </style>
</head>
<body>
<div>
    <p class="text-right font-10 m-3">Lampiran I : </p>
    <p class="text-right font-10 m-3">Nomor : </p>
    <p class="text-right font-10 m-3">Tanggal : 20 Januari 2020</p>
</div>

<table class="border-05">
    <tbody>
    <tr class="border-05">
        <td style="border-right: 0 !important; padding: 2px">
            <img src="{{asset('assets/logo/pemprov-ntb.jpg')}}" alt="Logo" style="width: 35px !important;">
        </td>
        <td colspan="2" class="text-center" style="border-left: 0 !important;">
            <h5 class="m-0">PEMERINTAH PROVINSI NUSA TENGGARA BARAT</h5>
            <h3 class="m-0">RINGKASAN RANCANGAN APBD</h3>
            <p class="m-0 font-10">TAHUN ANGGARAN 2020</p>
        </td>
    </tr>

    <tr style="">
        <td class="text-center border-05" style="width: 70px; "><strong>NO. URUT</strong></td>
        <td class="text-center border-05" style=""><strong>URAIAN</strong></td>
        <td class="text-center border-05" style="width: 150px; "><strong>JUMLAH</strong></td>
    </tr>

    <tr>
        <td class="text-center border-05" style=""><strong>1</strong></td>
        <td class="text-center border-05" style=""><strong>2</strong></td>
        <td class="text-center border-05" style=""><strong>3</strong></td>
    </tr>

    {{--isi table--}}

    {{--    head 1--}}
    <tr>
        <td class="border-right-05 table-row"><strong>1</strong></td>
        <td class=" table-row"><strong>PENDAPATAN</strong></td>
        <td class="border-left-05 text-right table-row"><strong>5.049.010.772.892,83</strong></td>
    </tr>

    <tr>
        <td class="border-right-05 table-row"><strong>1</strong></td>
        <td class=" table-row indent-2"><strong>PENDAPATAN</strong></td>
        <td class="border-left-05 text-right table-row"><strong>5.049.010.772.892,83</strong></td>
    </tr>

    <tr>
        <td class="border-right-05 table-row"><strong>1</strong></td>
        <td class=" table-row indent-4"><strong>PENDAPATAN</strong></td>
        <td class="border-left-05 text-right table-row"><strong>5.049.010.772.892,83</strong></td>
    </tr>


    </tbody>
</table>


<hr>

<div id="content"></div>

{{--<table>--}}
{{--    <tbody>--}}
{{--    @php--}}
{{--        $hide = 0;--}}
{{--        $totalNilaiKeseluruhan = 0;--}}
{{--    @endphp--}}

{{--    @foreach($rekenings as $akun)--}}
{{--        <tr id="pendapatan">--}}
{{--            @php--}}
{{--                $no_urut_akun = $loop->iteration;--}}
{{--            @endphp--}}

{{--            <td>{{$loop->iteration}}</td>--}}
{{--            <td>{{$akun->nama_akun}}</td>--}}
{{--            <td id="totalPendapatan">0</td>--}}
{{--        </tr>--}}

{{--        @foreach($akun->kelompok as $kelompok)--}}
{{--            @php--}}
{{--                $no_urut_kelompok = $loop->iteration;--}}
{{--            @endphp--}}
{{--            <tr id="ptk{{$loop->iteration}}">--}}
{{--                <td>{{$no_urut_akun.'.'.$loop->iteration}}</td>--}}
{{--                <td style="text-indent: 2em">{{$kelompok->nama_kelompok}}</td>--}}
{{--                <td id="totalPtk{{$loop->iteration}}">{{$totalNilaiKeseluruhan}}</td>--}}
{{--            </tr>--}}

{{--            @foreach($kelompok->jenis as $jenis)--}}
{{--                @if($jenis->kertas_kerja->count() > 0)--}}

{{--                    @php--}}
{{--                        $totalNilaiKeseluruhan = $totalNilaiKeseluruhan + $jenis->kertas_kerja->sum('nilai');--}}
{{--                    @endphp--}}

{{--                    <tr id="{{$no_urut_akun.'-'.$no_urut_kelompok.'-'.$loop->iteration}}">--}}
{{--                        <td>{{$no_urut_akun.'.'.$no_urut_kelompok.'.'.$loop->iteration}}</td>--}}
{{--                        <td style="text-indent: 4em">{{$jenis->nama_jenis}}</td>--}}
{{--                        <td style="text-indent: 4em" id="nilaiBelanja">{{$jenis->kertas_kerja->sum('nilai')}}</td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        @endforeach--}}

{{--    @endforeach--}}
{{--    </tbody>--}}
{{--</table>--}}

</body>
</html>
