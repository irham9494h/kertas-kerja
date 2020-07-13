<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
{{-- notifikasi form validasi --}}
@if ($errors->has('file'))
    <span class="invalid-feedback" role="alert">
			<strong>{{ $errors->first('file') }}</strong>
		</span>
@endif

{{-- notifikasi sukses --}}
@if ($sukses = Session::get('sukses'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $sukses }}</strong>
    </div>
@endif

<form action="{{route('import-prose')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <select name="jenis" id="">
        <option value="rek_akun">Rekening Akun</option>
        <option value="rek_kelompok">Rekening Kelompok</option>
        <option value="rek_jenis">Rekening jenis</option>
        <option value="rek_obyek">Rekening Obyek</option>
        <option value="rek_rincian_obyek">Rekening Rincian Obyek</option>
    </select>
    <input type="file" required name="file"
           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

    <button type="submit">IMPORT</button>
</form>
</body>
</html>

