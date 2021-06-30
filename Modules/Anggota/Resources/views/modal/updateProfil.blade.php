
<div class="modal fade" id="modalProfil" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content rounded">
            <div class="block block-transparent mb-0">
                <form id="form-profil" onsubmit="return false">
                    @csrf
                    <input type="hidden" value="" id="field-anggota_id" name="anggota_id"/>
                    <div class="block-header bg-alt-secondary">
                        <h3 class="block-title">Ubah Profile</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content"> 
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nomor KTP</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-no_ktp" class="form-control" name="no_ktp" value="">
                                <div class="invalid-feedback" id="error-no_ktp">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Lengkap</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-nama" class="form-control" name="nama" value="">
                                <div class="invalid-feedback" id="error-nama">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Jenis Kelamin</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="jk" id="jk1" value="L">
                                    <label class="custom-control-label" for="jk1">Laki-Laki</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="jk" id="jk2" value="P">
                                    <label class="custom-control-label" for="jk2">Perempuan</label>
                                </div>
                                <div class="text-danger" id="error-jk"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Tempat Tanggal Lahir</label>
                            <div class="col-lg-4">
                                <input type="text" id="field-tmp_lahir" class="form-control" name="tmp_lahir">
                                <div class="invalid-feedback" id="error-tmp_lahir">Invalid feedback</div>
                            </div>
                            <div class="col-lg-4">
                                <input type="text" id="field-tgl_lahir" class="form-control" name="tgl_lahir">
                                <div class="invalid-feedback" id="error-tgl_lahir">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Status Perkawinan</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status_pernikahan" id="status_pernikahan1" value="Lajang">
                                    <label class="custom-control-label" for="status_pernikahan1">Lajang</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status_pernikahan" id="status_pernikahan2" value="Menikah">
                                    <label class="custom-control-label" for="status_pernikahan2">Menikah</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status_pernikahan" id="status_pernikahan3" value="Duda/Janda">
                                    <label class="custom-control-label" for="status_pernikahan3">Duda/Janda</label>
                                </div>
                                <div class="text-danger" id="error-status_pernikahan"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >No. Handphone</label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            +62
                                        </span>
                                    </div>
                                    <input type="text" id="field-no_hp" class="form-control phone" name="no_hp">
                                    <div class="invalid-feedback" id="error-no_hp">Invalid feedback</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >No. Telepon <sub class="text-danger">*Optional</sub></label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            +62
                                        </span>
                                    </div>
                                    <input type="text" id="field-telp" class="form-control phone" name="no_telp">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Alamat Email<sub class="text-danger">*Optional</sub></label>
                            <div class="col-lg-8">
                                <input type="email" id="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pendidikan Terakhir</label>
                            <div class="col-lg-8">
                                <select class="form-control" id="field-pendidikan" name="pendidikan" style="width: 100%;" data-placeholder="Pilih..">
                                    <option value="">Pilih</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/Sederajat">SMA/Sederajat</option>
                                    <option value="Akademi/Diploma">Akademi/Diploma</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                <div class="invalid-feedback" id="error-pendidikan">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pekerjaan</label>
                            <div class="col-lg-8">
                                <select class="form-control" id="field-pekerjaan" name="pekerjaan" style="width: 100%;" data-placeholder="Pilih..">
                                    <option value="">Pilih</option>
                                    <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                    <option value="Pegawai Swasta">Pegawai Swasta</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                    <option value="Pegawai Negeri">Pegawai Negeri</option>
                                    <option value="Guru">Guru</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="TNI/Polisi">TNI/Polisi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div class="invalid-feedback" id="error-pekerjaan">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Ibu Kandung</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-nama_ibu" class="form-control" name="nama_ibu">
                                <div class="invalid-feedback" id="error-nama_ibu">Invalid feedback</div>
                            </div>
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