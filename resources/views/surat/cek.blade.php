@extends('layouts.app')

@section('title', 'Kirim Surat')

@push('stylesheet')
<link rel="stylesheet" type="text/css" href="{{ asset('datatables/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
  <div class="section-body">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5>Cek Surat Masuk</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped" id="tabel">
              <thead>                                 
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nomor Surat</th>
                  <th>Perihal Surat</th>
                  <th>Jenis Surat</th>
                  <!-- <th>Deskripsi Surat</th> -->
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>                                 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<div class="modal fade" tabindex="-1" role="dialog" id="detail">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Keterangan Surat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id" id="id">
          <div class="row">
            <div class="form-group col-6">
                <label>Nomor Surat</label>
                <input disabled disabled type="text" name="no_surat" class="form-control" id="no_surat" placeholder="Nomor Surat">
                
            </div>
            <div class="form-group col-6">
                <label>Perihal Surat</label>
                <input disabled type="text" name="perihal_surat" class="form-control" id="perihal_surat" placeholder="Perihal Surat">
                
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
                <label>Jenis Surat</label>
                <select disabled name="jenis_surat"  id="jenis_surat" class="form-control">
                  <option value="" selected="" disabled="">Pilih</option>
                  <option value="surat undangan">Surat Undangan</option>
                  <option value="surat perintah">Surat Perintah</option>
                </select>
                
            </div>
            <div class="form-group col-6">
                <label>Tanggal Surat</label>
                <input disabled type="text" name="tgl_surat" class="form-control" id="tgl_surat" placeholder="Tanggal Surat">
               
            </div>
          </div>

           <div class="row">
            <div class="form-group col-6">
                <label>Pengirim Surat</label>
                <input disabled type="text" name="pengirim" class="form-control" id="pengirim">
                
            </div>
            <div class="form-group col-6">
                <label>Tujuan Surat</label>
                <input disabled type="text" name="tujuan" class="form-control datepicker" id="tujuan">
                
            </div>
          </div>
          <div class="row">
            <div class="form-group col-12">
              <label>Deskripsi</label>
              <textarea disabled name="deskripsi" class="form-control" id="deskripsi" placeholder="Deskripsi"></textarea>
              <div class="invalid-feedback">
                  Data tidak boleh kosong !
              </div>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- <div class="modal fade" tabindex="-1" role="dialog" id="detail">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" method="POST" action="" novalidate>
          @csrf
          <input type="hidden" name="id" id="id">
          <div class="row">
            <div class="form-group col-6">
                <label>Nama</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
                <label>Hak Akses</label>
                <select name="role" id="role" class="form-control" required>
                  <option value="" selected="" disabled="">Pilih</option>
                  <option value="admin">Admin</option>
                  <option value="operator">Operator</option>
                  <option value="opd">OPD</option>
                </select>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> -->
@endsection
@push('javascript')
<script src="{{ asset('datatables/datatables.min.js') }}"></script>
<script src="{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(function() {
    $('#tabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('surat-masuk.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'no_surat', name: 'no_surat' },
            { data: 'perihal_surat', name: 'perihal_surat' },
            { data: 'jenis_surat', name: 'jenis_surat' },
            // { data: 'deskripsi', name: 'deskripsi' },
            { data: 'aksi', name: 'aksi', className: 'text-center' }
        ]
    });
});
</script>
<script>
$('#detail').on('show.bs.modal', function(event){
    var row = $(event.relatedTarget);
    var id = row.data('id');
    var no_surat = row.data('no_surat');
    var perihal_surat = row.data('perihal_surat');
    var tgl_surat = row.data('tgl_surat');
    var jenis_surat = row.data('jenis_surat');
    var deskripsi = row.data('deskripsi');
    var pengirim = row.data('pengirim');
    var tujuan = row.data('tujuan');
    $('#id').val(id);
    $('#no_surat').val(no_surat);
    $('#perihal_surat').val(perihal_surat);
    $('#tgl_surat').val(tgl_surat);
    $('#jenis_surat').val(jenis_surat);
    $('#deskripsi').val(deskripsi);
    $('#pengirim').val(pengirim);
    $('#tujuan').val(tujuan);
});
</script>
<script>
$(document).on("click", ".delete", function (e) {
    var id = $(this).data("id");
    e.preventDefault();
    Swal.fire({
      title: 'Anda yakin?',
      text: "Data ini akan dihapus!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {
      if (result.value) {
        $.post( "{{url('user/destroy')}}/"+id, { "_token": "{{ csrf_token() }}" })
        Swal.fire(
          'Terhapus!',
          'Data berhasil dihapus.',
          'success'
        )
        location.reload()
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire(
          'Batal hapus',
          'Data batal dihapus :)',
          'error'
        )
      }
    })
});
</script>
<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
@endpush
