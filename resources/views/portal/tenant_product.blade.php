
@extends('layout.portal.master')
@section('Page-Title')
<h4>Tenant Products & Promos</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#"></a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item active">Tenant Products & Promos : {{ $tenant_details->brand_site_name }}</li>
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
      :tenant_id="{{$tenant_details->id}}">
    </portal-tenant-products>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush