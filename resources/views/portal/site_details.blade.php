@extends('layout.portal.master')
@section('Page-Title')
<h4>Brands</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item">Property Details</li>
  <li class="breadcrumb-item active">Manage Site</li>
</ol>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-12">
    @include('layout.portal.company-profile')
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $site_details->name}}</h4>					
      </div>
      <div class="card-body">
        <div class="form-group row">
          <img src="{{ $site_details->site_banner_path}}" />
        </div>
      </div>
    </div>
    <portal-buildings></portal-buildings>
    <portal-building-floors></portal-building-floors>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush