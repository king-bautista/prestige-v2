@extends('layout.portal.master')
@section('Page-Title')
<h4>Screens / Maps</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item active">Screens / Maps</li>
</ol>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
  <portal-building-screens></portal-building-screens>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush