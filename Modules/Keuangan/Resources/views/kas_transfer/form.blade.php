
<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded">
            <div class="block block-transparent mb-0">
                <form id="form-kas" onsubmit="return false">
                    @csrf
                    <input type="hidden" id="field-id" value="" name="id"/>
                    <input type="hidden" value="add" id="method"/>
                    <div class="block-header bg-alt-secondary">
                        <h3 class="block-title" id="modal_title">Form Title</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        
                        <div class="form-group">
                            <label class="col-form-label" >Tanggal Transaksi</label>
                            <input type="text" id="field-tgl" class="form-control" name="tgl" value="">
                                <div class="invalid-feedback" id="error-tgl">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" >Nominal</label>
                            <input type="text" id="field-nominal" class="form-control input-currency" name="nominal">
                            <div class="invalid-feedback" id="error-nominal">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" >Keterangan</label>
                            <input type="text" id="field-keterangan" class="form-control" name="keterangan">
                            <div class="invalid-feedback" id="error-keterangan">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" >Dari Kas</label>
                            <select class="form-control" name="kas_id" id="field-kas_id"></select>
                            <div class="invalid-feedback" id="error-kas_id">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" >Untuk Kas</label>
                            <select class="form-control" name="tujuan" id="field-tujuan"></select>
                            <div class="invalid-feedback" id="error-tujuan">Invalid feedback</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-times-circle"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>