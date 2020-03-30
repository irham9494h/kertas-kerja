@if ($message = Session::get('success'))
    <script>
        swal({
            title: 'Berhasil!',
            text: '{{$message}}',
            icon: "success",
            buttons : {
                confirm : {
                    className: 'btn btn-success'
                }
            }
        });
    </script>
@endif

@if ($message = Session::get('info'))
    <script>
        swal({
            title: 'Informasi!',
            text: '{{$message}}',
            icon: "info",
            buttons : {
                confirm : {
                    className: 'btn btn-success'
                }
            }
        });
    </script>
@endif

@if ($message = Session::get('warning'))
    <script>
        swal({
            title: 'Opps!',
            text: '{{$message}}',
            icon: "warning",
            buttons : {
                confirm : {
                    className: 'btn btn-success'
                }
            }
        });
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        swal({
            title: 'Terjadi Kesalahan!',
            text: '{{$message}}',
            icon: "error",
            buttons : {
                confirm : {
                    className: 'btn btn-success'
                }
            }
        });
    </script>
@endif
