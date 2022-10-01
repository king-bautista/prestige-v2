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
                        <div class="col-md-11">
                        <label class="ml-3 mouseaction">
                            Building Floor:
                        </label>
                        <label class="ml-3 mouseaction">
                          <select class="custom-select floor-data">
                            <option value="">Select Building Floor</option>
                            @foreach ($site_maps as $site_map)
                                @if($site_map->site_building_level_id == $current_map->site_building_level_id)
                                  @php
                                    $active = 'selected'
                                  @endphp
                                @else
                                  @php
                                  $active = ''
                                  @endphp
                                @endif
                            <option data-floor_map="{{ asset($site_map->map_file) }}" data-map_width="{{ $site_map->image_size_width }}" data-map_height="{{ $site_map->image_size_height }}" value="{{ $site_map->site_building_level_id }}" {{$active}}>{{ $site_map->building_name }} ({{ $site_map->descriptions }})</option>
                            @endforeach
                          </select>
                          </label>
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseDrag" class="d-none"> Drag Point
                          </label>
                          <label class="ml-3 mouseaction mouseaction-selected">
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
                            <input type="radio" name="action" id="mouseLink2" class="d-none">Single Link
                          </label>
                          <label class="ml-3 mouseaction">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink" class="d-none">Continous Link
                          </label>                          
                          
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12 map-holder">
                          <div id="selectable"></div>
                          <img id="map_path">
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="map-form-holder">
      <div class="card">
        <div class="card-body">
          <form name="frmCoordinates" id="frmCoordinates">
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Point ID:</label>
            <div class="col-sm-6">
              11111
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Position X:</label>
            <div class="col-sm-6">
              <input type="text" id="position_x" name="position_x" class="form-control form-control-sm " placeholder="0.0" required>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Position Y:</label>
            <div class="col-sm-6">
              <input type="text" id="position_y" name="position_y" class="form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div>
          <!-- <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Position Z:</label>
            <div class="col-sm-6">
              <input type="text" id="position_z" name="position_z" class="form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div> -->
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Text Rotation:</label>
            <div class="col-sm-6">
              <input type="text" id="text_y_position" name="text_y_position" class="form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Text Size:</label>
            <div class="col-sm-6">
              <input type="text" id="text_size" name="text_size" class="form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Wrap Text:</label>
            <div class="col-sm-6">
              <div class="custom-control custom-switch">
                <input type="checkbox" id="wrap_at" name="wrap_at" class="custom-control-input">
                <label class="custom-control-label" for="wrap_at"></label>
              </div>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">PWD:</label>
            <div class="col-sm-6">
              <div class="custom-control custom-switch">
                <input type="checkbox" id="is_pwd" name="is_pwd" class="custom-control-input">
                <label class="custom-control-label" for="is_pwd"></label>
              </div>
            </div>
          </div>
          <div class="form-group row mb-0">
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
          <div class="form-group row mb-0">
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
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-12 col-form-label">Label (optional):</label>
            <div class="col-sm-12">
              <input type="text" class="form-control form-control-sm" placeholder="Label" required>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
</section>
<!-- /.content -->
@stop

@push('scripts')
<script src="{{ URL::to('js/jquery-ui/jquery-ui.min.js') }}"></script>

<script>
  const slider = document.querySelector('.map-holder');
  let isDown = false;
  let startX;
  let scrollLeft;

  slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });
  slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('active');
  });
  slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('active');
  });
  slider.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 2; //scroll-fast
    slider.scrollLeft = scrollLeft - walk;
  });

  $(document).ready(function() {

    var floor_map = $(this).find(':selected').data('floor_map');
    var map_width = $(this).find(':selected').data('map_width');
    var map_height = $(this).find(':selected').data('map_height');
    $("#map_path").attr('width', map_width);
    $("#map_path").attr('src', floor_map);
		
    $('.floor-data').on('change', function() {
      var floor_id = $(this).val();
      floor_map = $(this).find(':selected').data('floor_map');
      map_width = $(this).find(':selected').data('map_width');
      map_height = $(this).find(':selected').data('map_height');

      $.get("/admin/site/tenant/get-tenants-per-floor/"+floor_id, function(data) { 

        $('#tenant_list').empty();
        $('#tenant_list').append('<option value="">Select Tenant</option>');
        $.each(data.data, function(key,val) {             
          $('#tenant_list').append('<option value="'+val.id+'">'+val.brand_name+'</option>');
        });
      });

      $("#map_path").attr('width', map_width);
      $("#map_path").attr('src', floor_map);
    });

    $(".mouseaction").on('click',function(){
			$(this).addClass('mouseaction-selected');
			$(".mouseaction").not(this).removeClass('mouseaction-selected');
		});

    $( "#map_path" ).mousemove(function( event ) {
      var msg = "Handler for .mousemove() called at ";
      msg += event.pageX + ", " + event.pageY;
    });

    $("#map_path").click(function(){
      doAction();
		});

  });

  function doAction() {
    var action = $('input[name="action"]:checked').val();
    console.log(action);
    console.log(event.pageX,event.pageY);
  }

</script>
@endpush