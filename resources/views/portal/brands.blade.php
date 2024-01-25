@extends('layout.portal.master')
@section('Page-Title')
<h4>Brands</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item active">Brands</li>
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
  <portal-brands></portal-brands>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush