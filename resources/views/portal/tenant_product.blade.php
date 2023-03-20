@extends('layout.portal.master')
@section('Page-Title')
<h4>Products</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item"><a href="#">Tenants</a></li>
  <li class="breadcrumb-item active">Products</li>
</ol>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
    <portal-tenant-products 
        :brand_id="{{$tenant_details->brand_id}}"
        :tenant_id="{{$tenant_details->id}}"
        :brand_name="'{{$tenant_details->brand_site_name}}'">
    </portal-tenant-products>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush