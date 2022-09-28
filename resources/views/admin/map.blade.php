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
                        <div class="col-md-2">
                          <h3 class="card-title">Manage Map</h3>
                        </div>
                        <div class="col-md-10">
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseAdd" class="d-none"> Drag Point
                          </label>
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseAdd" class="d-none"> Add Points
                          </label>
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseDelete" class="d-none"> Delete Point
                          </label>
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseInfo" class="d-none">Point Info
                          </label>
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink" class="d-none">Continous Link
                          </label>                          
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink2" class="d-none">Single Link
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-10 map-holder">
                          <canvas id="my-point" width="7500" height="6000"></canvas>
                          <!-- <canvas id="my-map"></canvas> -->
                        </div>
                        <div class="col-md-2">
                          <div class="map-list-holder">
                            <div class="btn-group-vertical btn-group-toggle w-100" data-toggle="buttons">
                              @foreach ($site_maps as $site_map)
                                @if($site_map->site_building_level_id == $current_map->site_building_level_id)
                                  @php
                                    $active = 'active'
                                  @endphp
                                @else
                                  @php
                                  $active = ''
                                  @endphp
                                @endif
                                <label class="btn btn-primary w-100 mb-1 btn-floor d-block {{$active}}" data-floor="13">
                                  <input type="radio" class="floor-data" data-floor_id="{{ $site_map->site_building_level_id }}" autocomplete="off">{{ $site_map->building_name }} ({{ $site_map->descriptions }})
                                </label>
                              @endforeach                        
                            </div>
                          </div>
                          <div class="map-form-holder">
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Point ID:</label>
                              <div class="col-sm-6">
                                11111
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Position X:</label>
                              <div class="col-sm-6">
                                <input type="text" id="position_x" name="position_x" class="form-control" placeholder="0.0" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Position Y:</label>
                              <div class="col-sm-6">
                                <input type="text" id="position_y" name="position_y" class="form-control" placeholder="0.0" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Position Z:</label>
                              <div class="col-sm-6">
                                <input type="text" id="position_z" name="position_z" class="form-control" placeholder="0.0" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Text Rotation:</label>
                              <div class="col-sm-6">
                                <input type="text" id="text_y_position" name="text_y_position" class="form-control" placeholder="0.0" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Text Size:</label>
                              <div class="col-sm-6">
                                <input type="text" id="text_size" name="text_size" class="form-control" placeholder="0.0" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">Wrap Text:</label>
                              <div class="col-sm-6">
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" id="wrap_at" name="wrap_at" class="custom-control-input">
                                  <label class="custom-control-label" for="wrap_at"></label>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-6 col-form-label">PWD:</label>
                              <div class="col-sm-6">
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" id="is_pwd" name="is_pwd" class="custom-control-input">
                                  <label class="custom-control-label" for="is_pwd"></label>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-12 col-form-label">Tenant:</label>
                              <div class="col-sm-12">
                                <select class="custom-select" id="tenant_list" name="tenant_list">
                                  <option value="">Select Tenant</option>
                                  @foreach ($site_tenants as $tenant)
                                  <option value="{{$tenant->id}}">{{$tenant->brand_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-12 col-form-label">Amenity:</label>
                              <div class="col-sm-12">
                                <select class="custom-select" id="tenant_list" name="tenant_list">
                                  <option value="">Select Amenity</option>
                                  @foreach ($amenities as $amenity)
                                  <option value="{{$amenity->id}}">{{$amenity->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="firstName" class="col-sm-12 col-form-label">Label (optional):</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" placeholder="Label" required>
                              </div>
                            </div>
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
<script>
  $(document).ready(function(){
    $('.floor-data').click(function() {
      var floor_id = $(this).data('floor_id');
      $.get("/admin/site/tenant/get-tenants-per-floor/"+floor_id, function(data){

        $('#tenant_list').empty();
        $('#tenant_list').append('<option value="">Select Tenant</option>');
        $.each(data.data, function(key,val) {             
          $('#tenant_list').append('<option value="'+val.id+'">'+val.brand_name+'</option>');
        });
      });

      $('.btn-floor').removeClass('active');
      $(this).parent().addClass('active');
    });

  });
</script>
@endpush