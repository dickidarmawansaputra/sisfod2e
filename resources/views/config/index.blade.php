@extends('layouts.app')

@section('title', 'Konfigurasi')

@push('stylesheet')
<link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('content')
  <section class="section">
    <div class="section-body">
      	<div class="card">
      		<div class="card-header">
      			<h5>Konfigurasi</h5>
      		</div>
          

      		<div class="card-body">
            <form action="{{ route('config.store') }}" method="POST">
              @csrf
              <input type="text" name="host">
              <input type="text" name="username">
              <input type="text" name="password">
              <input type="text" name="path">
              <input type="submit" name="submit">
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