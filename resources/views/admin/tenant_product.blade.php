@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tenant Products & Promos : {{ $tenant_details->brand_site_name }}
        <a type="button" href="/admin/site/tenants" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-left"></i>&nbsp;Back to Tenants</a>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Tenant Products & Promos : {{ $tenant_details->brand_site_name }}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<admin-tenant-products 
  :brand_id="{{$tenant_details->brand_id}}"
  :tenant_id="{{$tenant_details->id}}">
</admin-tenant-products>
<!-- /.content -->
@stop

@push('scripts')    
@endpush