@extends('layout.portal.master')
@section('Page-Title')
<h4>Brands</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Customer Care</a></li>
  <li class="breadcrumb-item active">View Tickets</li>
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
    <portal-customer-care-view-ticket></portal-customer-care-view-ticket>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush