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
            <a href="{{ route('config.edit') }}" class="btn btn-icon icon-left btn-primary" ><i class="fa fa-edit"></i> Ubah</a>
            <a href="{{ route('crypto.generate') }}" class="btn btn-icon icon-left btn-primary" ><i class="fa fa-key"></i> Buat Kunci</a>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-sm" id="tabel">
                <tr>
                  <th>Status</th>
                  <td>{{ $config['server_config_status']->value }}</td>
                </tr>
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
                <tr>
                  <th>Versi Kunci</th>
                  <td>{{ $config['versi_kunci'] ? $config['versi_kunci']->value.$config['versi_kunci']->updated_at : 'Belum Dibuat' }}</td>
                </tr>
            </table>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>



@endsection

