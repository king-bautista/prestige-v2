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
        <h4 class="card-title"><i class="fas fa-city"></i>&nbsp;<strong>{{ $site_details->name}}</strong>
        <a type="button" class="btn btn-secondary btn-sm float-end" href="/portal/property-details"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</a>
      </h4>					
        
      </div>
      <div class="form-group row mb-0">
        <img src="{{ $site_details->site_banner_path}}" />
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