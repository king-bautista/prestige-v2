@extends('layout.portal.master')
@section('Page-Title')
<h4>Dashboard</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#"></a></li>
  <li class="breadcrumb-item active">Dashboard</li>
</ol>
@endsection
@section('content')
<div class="row">
  <div class="col-lg-6">
    <portal-dashboard_population></portal-dashboard_population>
  </div>
  <!-- /.col-md-6 -->
  <div class="col-lg-6">
    <portal-dashboard_monthly_usage></portal-dashboard_monthly_usage>
  </div>
  <!-- /.col-md-6 -->
</div>
<div class="row">
  <div class="col-lg-4">
    <portal-dashboard_tenant_search></portal-dashboard_tenant_search>
  </div>
  <div class="col-lg-4">
    <portal-dashboard_top_key_words></portal-dashboard_top_key_words>
  </div>
  <div class="col-lg-4">
    <portal-dashboard_highest_usage></portal-dashboard_highest_usage>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <portal-dashboard_merchant_usage></portal-dashboard_merchant_usage>
  </div>
</div>
@stop

@push('scripts')    
@endpush
