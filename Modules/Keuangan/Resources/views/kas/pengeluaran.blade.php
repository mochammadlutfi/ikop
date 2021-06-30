@extends('admin.layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded block-transparent bg-gd-lake">
        <div class="block-content bg-pattern bg-black-op-25">
            <div class="py-20 text-center">
                <h1 class="font-w700 text-white mb-10">Keuangan</h1>
                <h2 class="h4 font-w400 text-white-op">Transaksi Pengeluaran Kas</h2>
            </div>
        </div>
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Kelola Data Pengeluaran Kas</h3>
            <button id="btn_tambah" type="button" class="btn btn-secondary mr-5 mb-5 float-right btn-rounded">
                <i class="si si-plus mr-5"></i>
                Tambah Pengeluaran Kas Baru
            </button>
        </div>
        <div class="block-content">
            <table class="table table-hover table-striped font-size-sm" id="list-pengeluaran">
                <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Dari Kas</th>
                        <th>Untuk Akun</th>
                        <th>Jumlah</th>
                        <th>Pengguna</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modal_form"tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header block-header-default">
                        <h3 class="block-title" id="modal_title">Tambah Pengeluaran Kas</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form id="form-pengeluaran" onsubmit="return false;">
                        <input type="hidden" name="transaksi_id" value="">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Tanggal Transaksi</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-tgl" class="form-control" name="tgl" placeholder="Tanggal" value="">
                                <div class="invalid-feedback" id="error-tgl">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Jumlah</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-jumlah" class="form-control" name="jumlah" placeholder="Nominal">
                                <div class="invalid-feedback" id="error-jumlah">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Keterangan</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-keterangan" class="form-control" name="keterangan" placeholder="keterangan Kas">
                                <div class="invalid-feedback" id="error-keterangan">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Dari Kas</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="kas_id" id="field-kas_id">
                                    <option value="">Pilih</option>
                                    @foreach($kas as $k)
                                    <option value="{{ $k->kas_id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-kas_id">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Untuk Akun</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="akun_id" id="field-akun_id">
                                    <option value="">Pilih</option>
                                    @foreach($akun as $a)
                                    <option value="{{ $a->akun_id }}">{{ $a->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-akun_id">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-alt-primary btn-block"><i class="si si-check mr-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$(function () {
    $('#list-pengeluaran').DataTable({
        processing: true,
        serverSide : true,
        ajax: laroute.route('admin.kas.pengeluaran'),
        ordering:false,
        columns: [
            {
                data: 'kd_trans_kas',
                name: 'kd_trans_kas'
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'kas',
                name: 'kas'
            },
            {
                data: 'akun',
                name: 'akun'
            },
            {
                data: 'jumlah',
                name: 'jumlah'
            },
            {
                data: 'user',
                name: 'user'
            },
            // {
            //     data: 'action',
            //     name: 'action',
            //     orderable: false,
            //     searchable: false
            // },
        ]
    });
});

jQuery(document).ready(function () {
    $('#field-tgl').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $(document).on('click', '#btn_tambah', function () {
        save_method = 'tambah';
        $('#form-pengeluaran')[0].reset();
        $('#modal_form').modal({
            backdrop: 'static',
            keyboard: false
        })
    });

    $("#form-pengeluaran").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-pengeluaran')[0]);

        var link;
        var pesan;
        if (save_method == 'tambah') {
            link = laroute.route('admin.kas.pengeluaran_simpan');
            pesan = "Pengeluaran Kas Baru Berhasil Ditambahkan";
        } else {
            link = laroute.route('admin.kas.pengeluaran_simpan');
            pesan = "Pengeluaran Kas Berhasil Diperbaharui";
        }

        $.ajax({
            url: link,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail == false) {
                    $('#modal_embed').modal('hide');
                    swal({
                        title: "Berhasil",
                        text: pesan,
                        timer: 3000,
                        buttons: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSimpan').text('Simpan');
                $('#btnSimpan').attr('disabled', false);

            }
        });
    });

});

function edit(id){
    save_method = 'update';
    $('#form-cabang')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : laroute.route('admin.kas.edit', {id : id}),
        type: "GET",
        dataType: "JSON",
        success: function(response)
        {
            $('[name="supplier_id"]').val(response.supplier_id);
            $('[name="kode_supplier"]').val(response.kode);
            $('[name="nama"]').val(response.nama);
            $('[name="telp"]').val(response.telp);
            $('[name="kontak"]').val(response.kontak);
            $('[name="email"]').val(response.email);
            $('[name="provinsi"]').val(response.provinsi_id);
            $('[name="alamat"]').val(response.alamat);
            $('[name="fax"]').val(response.fax);
            $('#modal_title').text('Perbaharui Pengeluaran Kas');
            $('#modal_form').modal({
                backdrop: 'static',
                keyboard: false
            })
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
        }
    });
}

function hapus(id) {
    swal({
        title: "Anda Yakin?",
        text: "Data Yang Dihapus Tidak Akan Bisa Dikembalikan",
        icon: "warning",
        buttons: ["Batal", "Hapus"],
        dangerMode: true,
        closeOnClickOutside: false
    })
    .then((willDelete) => {
        if (willDelete) {
        $.ajax({
            url: laroute.route('cabang.hapus', { id: id }),
            type: "get",
            dataType: "JSON",
            success: function(data) {
                swal({
                    title: "Berhasil",
                    text: "Data Supplier Berhasil Dihapus",
                    timer: 3000,
                    buttons: false,
                    icon: 'success',
                    allowOutsideClick: false
                });
                window.setTimeout(function(){
                    location.reload();
                } ,1500);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });
        } else {
            window.setTimeout(function(){
                location.reload();
            } ,1500);
        }
    });
}
</script>
@endpush
