@extends('layout.portal.master')
@section('Page-Title')
<h4>Tenants</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item active">Tenants</li>
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
    <portal-building-tenants></portal-building-tenants>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush