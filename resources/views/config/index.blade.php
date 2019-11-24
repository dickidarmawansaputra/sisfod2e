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
            <a href="{{ route('config.edit') }}" class="btn btn-icon icon-left btn-primary" ><i class="far fa-edit"></i> Ubah</a>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-sm" id="tabel">
                <tr>
                  <th>Nama OPD</th>
                  <td>{{ $config['nama_opd']->value }}</td>
                </tr>
                <tr>
                  <th>Alamat Jaringan</th>
                  <td>{{ $config['alamat_jaringan']->value }}</td>
                </tr>
                <tr>
                  <th>Alamat Pos</th>
                  <td>{{ $config['alamat_pos']->value }}</td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td>{{ $config['email']->value }}</td>
                </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5>Manajemen Kunci</h5>
        </div>
        <div class="card-body">
          <div class="buttons">
            <a href="{{ route('crypto.generate') }}" class="btn btn-icon icon-left btn-primary" ><i class="far fa-edit"></i> Buat</a>
          </div>
          <div class="table-responsive">
            <table class="table table-striped" id="tabel">
              <thead>                                 
                <tr>
                  <th class="text-center">No.</th>
                  <th>Kunci</th>
                  <th>Nama file</th>
                  <th>Created At</th>
                  <th>Updated At</th>
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



@endsection

