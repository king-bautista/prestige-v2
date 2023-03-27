@extends('layout.portal.master')
@section('Page-Title')
<h4>User Brands</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item active">User Brands</li>
</ol>
@endsection
@section('content')
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
  <portal-brands></portal-brands>
  </div>
</div>
@stop

@push('scripts')    
@endpush
