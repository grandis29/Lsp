<div class="box">
    <div class="box-header with-header">
        <h3 class="box-title">Detail Arsip</h3>
        <div class="pull-right">
            <a href="<?= base_url('uploads/arsip/' . $arsip->file) ?>" class="btn btn-xs btn-flat btn-primary" id="unduh">
                <i class="fa fa-download"></i> Unduh
            </a>
            <button class="btn btn-xs btn-flat btn-warning" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-pencil"></i> Edit
            </button>
            <a href="<?= base_url() ?>arsip" class="btn btn-xs btn-flat btn-info" id="kembali">
                Kembali <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-3">
                <table class="table" style="border: 0px;">
                    <tr>
                        <th>Nomor</th>
                        <td><?= $arsip->nomor_surat ?></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td><?= $arsip->kategori ?></td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td><?= $arsip->judul ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Unggah</th>
                        <td><?= $arsip->created_at ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <iframe src="<?= base_url('uploads/arsip/' . $arsip->file) ?>" style="width: 100%;height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit File Arsip</h4>
            </div>
            <?= form_open_multipart('arsip/save', array('id' => 'edit'), array('method' => 'edit')); ?>
            <div class="modal-body">
                <input type="hidden" name="id_arsip" value="<?= $arsip->id; ?>">
                <input type="hidden" name="nomor_surat" value="<?= $arsip->nomor_surat; ?>">
                <div class="form-group">
                    <input type="file" name="file_arsip" id="file_arsip" class="form-control" accept="application/pdf" required>
                    <small class="help-block" style="color: #dc3545"><?= form_error('file') ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="submit">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script src="<?= base_url() ?>assets/dist/js/app/arsip/detail.js"></script>