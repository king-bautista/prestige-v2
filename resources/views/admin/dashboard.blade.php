@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
  <!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <admin-sceen_uptime></admin-sceen_uptime>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <admin-dashboard_is_helpful></admin-dashboard_is_helpful>
      </div>
      <div class="col-lg-4">
        <admin-dashboard_population></admin-dashboard_population>
      </div>
      <!-- /.col-md-6 -->
      <div class="col-lg-5">
        <admin-dashboard_monthly_usage></admin-dashboard_monthly_usage>
      </div>
      <!-- /.col-md-6 -->
    </div>
    <div class="row">
      <div class="col-lg-4">
        <admin-dashboard_tenant_search></admin-dashboard_tenant_search>
      </div>
      <div class="col-lg-4">
        <admin-dashboard_top_key_words></admin-dashboard_top_key_words>
      </div>
      <div class="col-lg-4">
        <admin-dashboard_highest_usage></admin-dashboard_highest_usage>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <admin-dashboard_merchant_usage></admin-dashboard_merchant_usage>
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@stop

@push('scripts')
@endpush