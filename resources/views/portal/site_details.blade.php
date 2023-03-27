@extends('layout.portal.master')
@section('Page-Title')
<h4>Property Details</h4>
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
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
    <portal-buildings></portal-buildings>
    <portal-building-floors></portal-building-floors>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush