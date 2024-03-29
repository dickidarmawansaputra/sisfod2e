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
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          
          <form action="{{ route('config.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="form-group col-6">
              <label>Nama OPD</label>
              <input type="text" name="nama_opd" class="form-control" required placeholder="Nama OPD">
              <div class="invalid-feedback">
                Data tidak boleh kosong !
              </div>
            </div>


           
            <div class="form-group col-6">
              <label>Alamat Pos</label>
              <input type="text" name="alamat_pos" class="form-control" required placeholder="Alamat POS">
              <div class="invalid-feedback">
                Data tidak boleh kosong !
              </div>
            </div>
            <div class="form-group col-6">
              <label>Email</label>
              <input type="text" name="email" class="form-control" required placeholder="Alamat Email">
              <div class="invalid-feedback">
                Data tidak boleh kosong !
              </div>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Tambahkan">
            </div>
          </form>
        </div>
      </div>


    </div>
  </div>
</section>



@endsection

