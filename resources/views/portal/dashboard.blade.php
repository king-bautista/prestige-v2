@extends('layout.portal.master')
@section('Page-Title')
<h4>Dashboard</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
</ol>
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    <portal-dashboard_sceen_uptime></portal-dashboard_sceen_uptime>
  </div>
  <div class="col-3">
    <portal-dashboard_is_helpful></portal-dashboard_is_helpful>
  </div>
  <div class="col-4">
    <portal-dashboard_population></portal-dashboard_population>
  </div>
  <div class="col-5">
    <portal-dashboard_monthly_usage></portal-dashboard_monthly_usage>
  </div>
  <div class="col-7">
    <portal-dashboard_tenant_search></portal-dashboard_tenant_search>
  </div>
  <div class="col-5">
    <portal-dashboard_top_key_words></portal-dashboard_top_key_words>
  </div>
  <div class="col-3">
    <portal-dashboard_highest_usage></portal-dashboard_highest_usage>
  </div>
  <div class="col-9">
    <portal-dashboard_merchant_usage></portal-dashboard_merchant_usage>
  </div>
  
</div>
<div class="row">
  
  <!-- /.col-md-6 -->
  
  <!-- /.col-md-6 -->
</div>
<div class="row">
  

</div>
<div class="row">

</div>
@stop

@push('scripts')    
@endpush
