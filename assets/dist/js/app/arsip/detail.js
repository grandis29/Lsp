$(document).ready(function () {
    $('form#edit').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var btn = $('#submit');
        btn.attr('disabled', 'disabled').text('Wait...');

        var data = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            data: data,
            processData: false,
            contentType: false,
            method: 'POST',
            success: function (data) {
                btn.removeAttr('disabled').text('Simpan');
                console.log(data);
                if (data.status) {
                    Swal({
                        "title": "Sukses",
                        "text": "Data Berhasil disimpan",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url + 'arsip';
                        }
                    });
                } else {
                    Swal({
                        "title": "Gagal",
                        "text": "Data Gagal disimpan",
                        "type": "error"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url + 'arsip';
                        }
                    });
                }
            }
        });
    });
});