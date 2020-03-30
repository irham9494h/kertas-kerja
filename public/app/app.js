$("input").keyup(function () {
    $(this).removeClass('is-invalid');
});

function deleteData(url){
    swal({
        title: 'Apakah Anda yakin?',
        text: "Data yang terhapus tidak dapat dikembalikan lagi!",
        icon: 'warning',
        buttons:{
            cancel: {
                text: 'Batalkan',
                visible: true,
                className: 'btn btn-danger'
            },
            confirm: {
                text : 'Ya, Hapus sekarang!',
                className : 'btn btn-success'
            }
        }
    }).then((Delete) => {
        if (Delete) {
            window.location.href = url;
        } else {
            swal.close();
        }
    });
}
