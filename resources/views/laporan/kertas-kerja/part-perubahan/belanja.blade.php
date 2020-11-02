<tr id="">
    <td class="border-right-05 table-row">
        {{$perubahanBelanja['kode_akun']}}
    </td>
    <td class=" table-row"><strong>{{$perubahanBelanja['nama_akun']}}</strong></td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
            {{number_format($perubahanBelanja['nilai_murni'], 2, ',', '.')}}
        </strong>
    </td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
            {{number_format($perubahanBelanja['nilai_perubahan'], 2, ',', '.')}}
        </strong>
    </td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
{{--            @php--}}
{{--                $nilai5 = $perubahanBelanja['nilai_pergeseran'] - $perubahanBelanja['nilai_murni'];--}}
{{--            @endphp--}}
{{--            {{ $nilai5 < 0 ? '( '.number_format(abs($nilai5), 2, ',', '.').' )' : number_format($nilai5, 2, ',', '.') }}--}}
        </strong>
    </td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
{{--            @php--}}
{{--                if ($perubahanBelanja['nilai_murni'] == 0 || $perubahanBelanja['nilai_pergeseran'] == 0 || $nilai5 == 0){--}}
{{--                $nilai6 = 0;--}}
{{--                }else{--}}
{{--                $nilai6 = ($nilai5/$perubahanBelanja['nilai_pergeseran']) * 100;--}}
{{--                }--}}
{{--            @endphp--}}
{{--            {{ $nilai6 < 0 ? '( '.number_format(abs($nilai6), 2, ',', '.').' )' : number_format($nilai6, 2, ',', '.') }}--}}
        </strong>
    </td>
</tr>

@foreach($perubahanBelanja['kelompok'] as $kelompok)
    <tr>
        <td class="border-right-05 table-row">
            {{$perubahanBelanja['kode_akun']}}.{{$kelompok['kelompok_kode']}}
        </td>
        <td class=" table-row" style="text-indent: 2em">
            <strong> {{$kelompok['nama_kelompok']}}</strong>
        </td>
        <td class=" table-row" style="border: 1px solid black; text-align: right">
            <strong> {{number_format($kelompok['nilai_murni'], 2, ',', '.')}}</strong>
        </td>
        <td class=" table-row" style="border: 1px solid black; text-align: right">
            <strong> {{number_format($kelompok['nilai_perubahan'], 2, ',', '.')}}</strong>
        </td>
        <td class=" table-row" style="border: 1px solid black; text-align: right">
{{--            @php--}}
{{--                $nilai5_2 = $kelompok['nilai_pergeseran'] - $kelompok['nilai_murni'];--}}
{{--            @endphp--}}
{{--            <strong> {{ $nilai5_2 < 0 ? '( '.number_format(abs($nilai5_2), 2, ',', '.') .' )': number_format($nilai5_2, 2, ',', '.')}}</strong>--}}
        </td>
        <td class=" table-row" style="border: 1px solid black; text-align: right">
{{--            @php--}}
{{--                if($kelompok['nilai_murni'] == 0 || $kelompok['nilai_pergeseran'] == 0 || $nilai5_2 == 0){--}}
{{--                    $nilai6_2 = 0;--}}
{{--                }else{--}}
{{--                    $nilai6_2 = ($nilai5_2 / $kelompok['nilai_murni']) * 100;--}}
{{--                }--}}
{{--            @endphp--}}
{{--            <strong> {{ $nilai6_2 < 0 ? '( '.number_format(abs($nilai6_2), 2, ',', '.') .' )': number_format($nilai6_2, 2, ',', '.')}}</strong>--}}
        </td>
    </tr>
    @foreach($kelompok['jenis'] as $key => $jenis)
        <tr>
            <td class="border-right-05 table-row">
                {{$perubahanBelanja['kode_akun']}}.{{$kelompok['kelompok_kode']}}
                .{{$jenis['jenis_kode']}}
            </td>
            <td class=" table-row"
                style="text-indent: 4em">{{$jenis['nama_jenis']}}
            </td>
            <td class=" table-row"
                style="border-left: 1px solid black; text-align: right">
                {{number_format($jenis['nilai_murni'], 2, ',', '.')}}
            </td>
            <td class=" table-row"
                style="border-left: 1px solid black; text-align: right">
                {{number_format($jenis['nilai_perubahan'], 2, ',', '.')}}
            </td>
            <td class=" table-row"
                style="border-left: 1px solid black; text-align: right">
{{--                @php--}}
{{--                    $nilai5_3 = $kelompok['jenis_pergeseran'][$key]['nilai'] - $jenis_murni['nilai'];--}}
{{--                @endphp--}}
{{--                {{$nilai5_3 < 0 ? '( '.number_format(abs($nilai5_3), 2, ',', '.').' )' : number_format($nilai5_3, 2, ',', '.')}}--}}
            </td>
            <td class=" table-row"
                style="border-left: 1px solid black; text-align: right">
{{--                @php--}}
{{--                    if($jenis_murni['nilai'] == 0 || $kelompok['jenis_pergeseran'][$key]['nilai'] == 0 || $nilai5_3 == 0){--}}
{{--                        $nilai6_3 = 0;--}}
{{--                    }else{--}}
{{--                        $nilai6_3 = ($nilai5_2 / $jenis_murni['nilai']) * 100;--}}
{{--                    }--}}
{{--                @endphp--}}
{{--                {{$nilai6_3 < 0 ? '( '.number_format(abs($nilai6_3), 2, ',', '.').' )' : number_format($nilai6_3, 2, ',', '.')}}--}}
            </td>
        </tr>
    @endforeach
@endforeach
