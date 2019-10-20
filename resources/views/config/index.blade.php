@extends('layouts.app')

@section('title', 'Konfigurasi')

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
          <h5>Konfigurasi</h5>
        </div>
        <div class="card-body">
          <div class="buttons">
            <button class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#tambah"><i class="far fa-edit"></i> Tambah</button>
          </div>
          <div class="table-responsive">
            <table class="table table-striped" id="tabel">
              <thead>                                 
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Konfigurasi</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Root Path</th>
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

<div class="modal fade" tabindex="-1" role="dialog" id="tambah">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Konfigurasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" method="POST" action="{{ route('config.store') }}" novalidate>
          @csrf
          <div class="row">
            <div class="form-group col-6">
                <label>Nama Konfigurasi</label>
                <input type="text" class="form-control" name="nama_config" placeholder="Nama Konfigurasi" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Host</label>
                <input type="text" class="form-control" name="host" placeholder="Host" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
                <label>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Password</label>
                <input type="text" class="form-control" name="password" placeholder="Password" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-12">
                <label>Root Path</label>
                <input type="text" class="form-control" name="root_path" placeholder="Root Path" required>
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
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="update">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" method="POST" action="{{ route('config.update') }}" novalidate>
          @csrf
          @method('PUT')
          <input type="hidden" name="id" id="id">
          <div class="row">
            <div class="form-group col-6">
                <label>Nama Konfigurasi</label>
                <input type="text" class="form-control" name="nama_config" id="nama_config" placeholder="Nama Konfigurasi" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
                <label>Password</label>
                <input type="text" class="form-control" name="password" id="password" placeholder="Password" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Root Path</label>
                <input type="text" class="form-control" name="root_path" id="root_path" placeholder="Root Path" required>
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
</div>
@endsection
@push('javascript')
<script src="{{ asset('datatables/datatables.min.js') }}"></script>
<script src="{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(function() {
    $('#tabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('config.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'nama_config', name: 'nama_config' },
            { data: 'username', name: 'username' },
            { data: 'password', name: 'password' },
            { data: 'root_path', name: 'root_path' },
            { data: 'aksi', name: 'aksi', className: 'text-center' }
        ]
    });
});
</script>
<script>
$('#update').on('show.bs.modal', function(event){
    var row = $(event.relatedTarget);
    var id = row.data('id');
    var nama_config = row.data('nama_config');
    var username = row.data('username');
    var password = row.data('password');
    var root_path = row.data('root_path');
    $('#id').val(id);
    $('#nama_config').val(nama_config);
    $('#username').val(username);
    $('#password').val(password);
    $('#root_path').val(root_path);
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
        $.post( "{{url('config/destroy')}}/"+id, { "_token": "{{ csrf_token() }}" })
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