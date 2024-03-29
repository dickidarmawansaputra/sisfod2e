@extends('layouts.app')

@section('title', 'Upload Surat')

@push('stylesheet')
<link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('content')
  <section class="section">
    <div class="section-body">
      	<div class="card">
      		<div class="card-header">
      			<h5>Upload Surat</h5>
      		</div>
         {{--  @foreach($gambar as $val)
          <img src="{{ Storage::disk('local')->url($val) }}">
          @endforeach --}}

      		<div class="card-body">
            <form action="{{ route('move.server') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="file" name="gambar">
              <input type="submit" name="Kirim">
            </form>
            <form class="needs-validation" action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    <div class="form-group col-6">
                        <label>Nomor Surat</label>
                        <input type="text" name="no_surat" class="form-control" required placeholder="Nomor Surat">
                        <div class="invalid-feedback">
                            Data tidak boleh kosong !
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label>Perihal Surat</label>
                        <input type="text" name="no_surat" class="form-control" required placeholder="Perihal Surat">
                        <div class="invalid-feedback">
                            Data tidak boleh kosong !
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Jenis Surat</label>
                        <select name="jenis_surat" class="form-control" required>
                          <option value="" selected="" disabled="">Pilih</option>
                          <option value="a">A</option>
                          <option value="b">B</option>
                        </select>
                        <div class="invalid-feedback">
                            Data tidak boleh kosong !
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label>Tanggal Surat</label>
                        <input type="text" name="tgl_surat" class="form-control datepicker" required placeholder="Tanggal Surat">
                        <div class="invalid-feedback">
                            Data tidak boleh kosong !
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="form-group col-12">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" required placeholder="Deskripsi"></textarea>
                    <div class="invalid-feedback">
                        Data tidak boleh kosong !
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-12">
                    <label>Gambar</label>
                    <input type="file" name="gambar[]" class="form-control" required multiple>
                    <div class="invalid-feedback">
                        Data tidak boleh kosong !
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <button class="btn btn-primary btn-block">Simpan</button>
                    </div>
                </div>
            </form>
      		</div>
      	</div>
    </div>
  </section>
@endsection
@push('javascript')
<script src="{{ asset('bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
  $('.datepicker').daterangepicker({
          locale: {format: 'YYYY-MM-DD'},
          singleDatePicker: true,
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