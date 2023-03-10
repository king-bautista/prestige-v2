@extends('layout.portal.master')
@section('Page-Title')
<h4>Roles</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
  <li class="breadcrumb-item active">Roles</li>
</ol>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-4">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-8">
    <portal-roles></portal-roles>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush