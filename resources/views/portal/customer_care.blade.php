@extends('layout.portal.master')
@section('Page-Title')
<h4>Create Ad</h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
    <li class="breadcrumb-item active">Create Add</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-12">
    <portal-customer-care></portal-customer-care>
  </div>
</div>
<!-- Leaflet Map -->
<div class="row">
  <div id="map" style="width: 100%; height: 400px;"></div>
</div>

<!-- End of Leaflet Map -->

@stop

<!-- /.content -->
@push('scripts')
@endpush