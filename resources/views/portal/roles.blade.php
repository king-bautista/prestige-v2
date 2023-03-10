@extends('layout.portal.master')
@section('Page-Title')
<h4>Roles</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#"></a></li>
  <li class="breadcrumb-item"><a href="#">Manage Account</a></li>
  <li class="breadcrumb-item active">Roles</li>
</ol>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
    <portal-roles></portal-roles>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush