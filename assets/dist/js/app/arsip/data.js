var save_label;
var table;

$(document).ready(function () {
    ajaxcsrf();

    table = $("#arsip").DataTable({
        initComplete: function () {
            var api = this.api();
            $("#arsip_filter input")
                .off(".DT")
                .on("keyup.DT", function (e) {
                    api.search(this.value).draw();
                });
        },
        dom:
            "<'row'<'col-sm-3'l><'col-sm-6 text-center'><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "arsip/data",
            type: "POST"
            //data: csrf
        },
        columns: [
            { data: "id" },
            { data: "nomor_surat" },
            { data: "kategori" },
            { data: "judul" },
            { data: "created_at" },
            { data: "file" }
        ],
        columnDefs: [
            { visible: false, targets: [5] },
            {
                targets: 6,
                searchable: false,
                orderable: false,
                data: "id",
                render: function (data, type, row, meta) {
                    return `<div class='text-center'>
    							<a href="${base_url}uploads/arsip/${row.file}" class="btn btn-warning btn-xs">
    								Unduh
    							</a>
    							<a href="${base_url}arsip/lihat/${data}" class="btn btn-primary btn-xs">
    								Lihat >>
    							</a>
    						</div>`;
                }
            },
            {
                targets: 7,
                searchable: false,
                orderable: false,
                data: "nomor_surat",
                render: function (data, type, row, meta) {
                    return `<div class="text-center">
    							<input name="checked[]" class="check" value="${data}" type="checkbox">
    						</div>`;
                }
            }
        ],
        rowId: function (a) {
            return a;
        },
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $("td:eq(0)", row).html(index);
        }
    });

    table
        .buttons()
        .container()
        .appendTo("#arsip_wrapper .col-md-7:eq(0)");

    $(".select_all").on("click", function () {
        if (this.checked) {
            $(".check").each(function () {
                this.checked = true;
                $(".select_all").prop("checked", true);
            });
        } else {
            $(".check").each(function () {
                this.checked = false;
                $(".select_all").prop("checked", false);
            });
        }
    });

    $("#arsip tbody").on("click", "tr .check", function () {
        var check = $("#arsip tbody tr .check").length;
        var checked = $("#arsip tbody tr .check:checked").length;
        if (check === checked) {
            $(".select_all").prop("checked", true);
        } else {
            $(".select_all").prop("checked", false);
        }
    });

    $("#bulk").on("submit", function (e) {
        if ($(this).attr("action") == base_url + "arsip/delete") {
            e.preventDefault();
            e.stopImmediatePropagation();

            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                type: "POST",
                success: function (respon) {
                    if (respon.status) {
                        Swal({
                            title: "Berhasil",
                            text: respon.total + " data berhasil dihapus",
                            type: "success"
                        });
                    } else {
                        Swal({
                            title: "Gagal",
                            text: "Tidak ada data yang dipilih",
                            type: "error"
                        });
                    }
                    reload_ajax();
                },
                error: function () {
                    Swal({
                        title: "Gagal",
                        text: "Ada data yang sedang digunakan",
                        type: "error"
                    });
                }
            });
        }
    });
});

function bulk_delete() {
    if ($("#arsip tbody tr .check:checked").length == 0) {
        Swal({
            title: "Gagal",
            text: "Tidak ada data yang dipilih",
            type: "error"
        });
    } else {
        $("#bulk").attr("action", base_url + "arsip/delete");
        Swal({
            title: "Anda yakin?",
            text: "Data akan dihapus!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus!"
        }).then(result => {
            if (result.value) {
                $("#bulk").submit();
            }
        });
    }
}