<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?= $judul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>arsip" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <?= form_open_multipart('arsip/save', array('id' => 'arsip'), array('method' => 'add')) ?>
                <div class="form-group">
                    <label>Nomor Surat</label>
                    <input type="text" class="form-control" name="nomor_surat" style="width: 100%!important" required>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected></option>
                        <?php foreach ($kategori as $d) : ?>
                            <option value="<?= $d->id ?>"><?= $d->kategori ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" class="form-control" name="judul" style="width: 100%!important" required>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <input type="file" name="file_arsip" id="file_arsip" class="form-control" accept="application/pdf" required>
                    <small class="help-block" style="color: #dc3545"><?= form_error('file') ?></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/arsip/add.js"></script>