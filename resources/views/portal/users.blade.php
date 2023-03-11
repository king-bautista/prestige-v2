@extends('layout.portal.master')
@section('Page-Title')
<h4>404</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#"></a></li>
  <li class="breadcrumb-item active">Manage Account</li>
</ol>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
    <portal-users></portal-users>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush