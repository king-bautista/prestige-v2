@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Site : {{$site_details->name}}
        <a type="button" href="/admin/sites" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-left"></i>&nbsp;Back to Sites</a>
        </h1>
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

<!-- <div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 text-center">
              @if($site_details->site_logo)
                <img src="{{ URL::to($site_details->site_logo) }}" style="width:100%" />
              @else
                <img src="{{ URL::to('/images/no-image-available.png') }}" style="width:50%" />
              @endif
            </div>
            <div class="col-md-9">
              <img src="{{ URL::to($site_details->site_banner) }}" style="width:100%" />
            </div>
          </div>        
        </div>
      </div>
    </div>
  </div>
</div> -->

<admin-buildings></admin-buildings>
<admin-building-floors></admin-building-floors>
<admin-building-screens></admin-building-screens>

@stop

@push('scripts')    
@endpush