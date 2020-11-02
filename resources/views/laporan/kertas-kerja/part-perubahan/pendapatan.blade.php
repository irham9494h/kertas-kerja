<tr id="">
    <td class="border-right-05 table-row">
        {{$perubahanPendapatan['kode_akun']}}
    </td>
    <td class=" table-row"><strong>{{$perubahanPendapatan['nama_akun']}}</strong></td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
            {{number_format($perubahanPendapatan['nilai_murni'], 2, ',', '.')}}
        </strong>
    </td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
            {{number_format($perubahanPendapatan['nilai_perubahan'], 2, ',', '.')}}
        </strong>
    </td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
            @php
                $nilaiPendapatanAkun5 = $perubahanPendapatan['nilai_perubahan'] - $perubahanPendapatan['nilai_murni'];
            @endphp
            {{ $nilaiPendapatanAkun5 < 0 ? '( '.number_format(abs($nilaiPendapatanAkun5), 2, ',', '.').' )' : number_format($nilaiPendapatanAkun5, 2, ',', '.') }}
        </strong>
    </td>
    <td class=" table-row" style="border: 1px solid black; text-align: right">
        <strong>
            @php
                if ($perubahanPendapatan['nilai_murni'] == 0 || $perubahanPendapatan['nilai_perubahan'] == 0 || $nilaiPendapatanAkun5 == 0){
                    $nilaiPendapatanAkun6 = 0;
                }else{
                    $nilaiPendapatanAkun6 = ($nilaiPendapatanAkun5/$perubahanPendapatan['nilai_perubahan']) * 100;
                }
            @endphp
            {{ $nilaiPendapatanAkun6 < 0 ? '( '.number_format(abs($nilaiPendapatanAkun6), 2, ',', '.').' )' : number_format($nilaiPendapatanAkun6, 2, ',', '.') }}
        </strong>
    </td>
</tr>
@foreach($perubahanPendapatan['kelompok'] as $kelompok)
    <tr>
        <td class="border-right-05 table-row">
            {{$perubahanPendapatan['kode_akun']}}.{{$kelompok['kelompok_kode']}}
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
            @php
                $nilaiPendapatanKelompok5 = $kelompok['nilai_perubahan'] - $kelompok['nilai_murni'];
            @endphp
            <strong> {{ $nilaiPendapatanKelompok5 < 0 ? '( '.number_format(abs($nilaiPendapatanKelompok5), 2, ',', '.') .' )': number_format($nilaiPendapatanKelompok5, 2, ',', '.')}}</strong>
        </td>
        <td class=" table-row" style="border: 1px solid black; text-align: right">
            @php
                if($kelompok['nilai_murni'] == 0 || $kelompok['nilai_perubahan'] == 0 || $nilaiPendapatanKelompok5 == 0){
                    $nilaiPendapatanKelompok6 = 0;
                }else{
                    $nilaiPendapatanKelompok6 = ($nilaiPendapatanKelompok5 / $kelompok['nilai_murni']) * 100;
                }
            @endphp
            <strong> {{ $nilaiPendapatanKelompok6 < 0 ? '( '.number_format(abs($nilaiPendapatanKelompok6), 2, ',', '.') .' )': number_format($nilaiPendapatanKelompok6, 2, ',', '.')}}</strong>
        </td>
    </tr>
    @foreach($kelompok['jenis'] as $key => $jenis)
        <tr>
            <td class="border-right-05 table-row">
                {{$perubahanPendapatan['kode_akun']}}.{{$kelompok['kelompok_kode']}}
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
                @php
                    $nilaiPendapatanJenis5 = $jenis['nilai_perubahan'] - $jenis['nilai_murni'];
                @endphp
                {{$nilaiPendapatanJenis5 < 0 ? '( '.number_format(abs($nilaiPendapatanJenis5), 2, ',', '.').' )' : number_format($nilaiPendapatanJenis5, 2, ',', '.')}}
            </td>
            <td class=" table-row"
                style="border-left: 1px solid black; text-align: right">
                @php
                    if($jenis['nilai_murni'] == 0 || $jenis['nilai_perubahan'] == 0 || $nilaiPendapatanJenis5 == 0){
                        $nilaiPendapatanJenis6 = 0;
                    }else{
                        $nilaiPendapatanJenis6 = ($nilaiPendapatanJenis5 / $jenis['nilai_murni']) * 100;
                    }
                @endphp
                {{$nilaiPendapatanJenis6 < 0 ? '( '.number_format(abs($nilaiPendapatanJenis6), 2, ',', '.').' )' : number_format($nilaiPendapatanJenis6, 2, ',', '.')}}
            </td>
        </tr>
    @endforeach
@endforeach
