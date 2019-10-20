@extends('layouts.app')

@section('title', 'Kelola Pengguna')

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
          <h5>Kelola Pengguna</h5>
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
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Hak Akses</th>
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
        <h5 class="modal-title">Tambah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" method="POST" action="{{ route('user.store') }}" novalidate>
          @csrf
          <div class="row">
            <div class="form-group col-6">
                <label>Nama</label>
                <input type="text" class="form-control" name="name" placeholder="Nama" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
            <div class="form-group col-6">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
                <div class="invalid-feedback">
                  Data tidak boleh kosong!
                </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
                <label>Hak Akses</label>
                <select name="role" class="form-control" required>
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
                <input type="password" class="form-control" name="password" placeholder="Password" required>
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
                <input type="password" class="form-control" name="password" placeholder="Password" required>
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
        ajax: '{!! route('user.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'peran', name: 'peran', className: 'text-center' },
            { data: 'aksi', name: 'aksi', className: 'text-center' }
        ]
    });
});
</script>
<script>
$('#update').on('show.bs.modal', function(event){
    var row = $(event.relatedTarget);
    var id = row.data('id');
    var name = row.data('name');
    var email = row.data('email');
    var role = row.data('role');
    $('#id').val(id);
    $('#name').val(name);
    $('#email').val(email);
    $('#role').val(role).change();
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
