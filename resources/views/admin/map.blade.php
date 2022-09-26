@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Map : {{ $site_details->name }}
            <a type="button" onclick="history.back()" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-left"></i>&nbsp;Back to Site Details</a>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Map : {{ $site_details->name }}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-1">
                          <h3 class="card-title">Manage Map</h3>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-10 map-holder">
                          <canvas id="my-point" width="7500" height="6000"></canvas>
                          <!-- <canvas id="my-map"></canvas> -->
                        </div>
                        <div class="col-md-2 map-list-holder">
                          <div class="btn-group-vertical btn-group-toggle w-100" data-toggle="buttons">
                            @foreach ($site_maps as $site_map)
                            <label class="btn btn-primary w-100 mb-1 btn-floor d-block" data-floor="13">
                              <input type="radio" name="optionsfloor" data-floor_id="14" id="option13" autocomplete="off">{{ $site_map->building_name }} ({{ $site_map->descriptions }})
                            </label>
                            @endforeach                        
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop

@push('scripts')
<script src="{{ URL::to('js/jcanvas.min.js') }}"></script>
@endpush