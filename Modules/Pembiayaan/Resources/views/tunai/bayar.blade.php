
<div class="modal" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <form id="bayar" onsubmit="return false">
                <div class="block mb-0">
                    <div class="block-header border-3x border-bottom">
                        <h3 class="block-title">Pembayaran</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-3 border-bottom border-3x">
                        <div class="form-group justify-content-between no-gutters row">
                            <label class="col-lg-4 col-form-label" for="field-tgl">Tanggal Transaksi</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="field-tgl" name="tgl" placeholder="">
                                <span id="error-tgl" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="form-group justify-content-between no-gutters row mb-0">
                            <label class="col-lg-4 col-form-label" for="field-kas_id">Kas</label>
                            <div class="col-lg-6">
                                <select class="form-control" name="kas_id" id="field-kas_id"></select>
                                <span id="error-kas_id" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="block-content p-3 border-bottom border-3x" id="angsuran-list">
                    </div>
                    <div class="block-content p-3">
                        <div class="d-flex justify-content-between pb-3">
                            <div class="font-size-20 font-weight-bold my-auto">Total Tagihan</div>
                            <div class="font-size-20 font-weight-bold my-auto total_bayar harga">Rp. 500000</div>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">
                            Bayar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>