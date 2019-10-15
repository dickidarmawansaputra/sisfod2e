@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Kirim Surat</h1>
    </div>

    <div class="section-body">
      	<div class="card">
      		<div class="card-header">
      			<h5>Input Detail Surat</h5>
      		</div>

      		<div class="card-body">
            <form action="" method="">
      				<div class="form-group">
      				  <label>Tanggal Surat</label>
      				  <input type="date" class="form-control" name="tgl_surat" placeholder="Tanggal Surat" required>
      				</div>
              <div class="form-group">
                <label>Nomor Surat</label>
                <input type="number" class="form-control" name="no_surat" placeholder="Nomor Surat" required>
              </div>
              <div class="form-group">
                <label>Perihal Surat</label>
                <input type="text" class="form-control" name="perihal_surat" placeholder="Perihal Surat" required>
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" id="" cols="40" rows="30" placeholder="Deskripsi singkat"></textarea>
              </div>
              <div class="form-group">
                <div class="control-label">Jenis surat</div>
                <div class="custom-switches-stacked mt-2">
                  <label class="custom-switch">
                    <input type="radio" name="option" value="1" class="custom-switch-input" checked>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">Surat Masuk</span>
                  </label>
                  <label class="custom-switch">
                    <input type="radio" name="option" value="2" class="custom-switch-input">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">Surat Keluar</span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label>Upload Surat</label>
                <!-- <textarea name="deskripsi" class="form-control" id="" cols="40" rows="30" placeholder="Deskripsi singkat"></textarea> -->
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary">Kirim</button>
              </div>
            </form>
      		</div>

      	</div>
    </div>
  </section>
@endsection
