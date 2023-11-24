@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Site : {{$site_details->name}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item"><a href="/admin/sites">Sites</a></li>
          <li class="breadcrumb-item active">Site : {{$site_details->name}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<admin-buildings :site-name="{{json_encode(strtolower(str_replace(' ','-',$site_details->name)))}}"></admin-buildings>
<admin-building-floors :site-name="{{json_encode(strtolower(str_replace(' ','-',$site_details->name)))}}"></admin-building-floors>

@stop

@push('scripts')    
@endpush