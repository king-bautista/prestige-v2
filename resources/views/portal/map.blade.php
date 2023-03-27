@extends('layout.portal.master')
@section('Page-Title')
<h4>Edit Maps</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item"><a href="#">Screens Maps</a></li>
  <li class="breadcrumb-item active">Edit Maps : {{ $site_details->name }}</li>
</ol>
@endsection

@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title"><i class="nav-icon fas fa-map"></i>&nbsp;&nbsp;Edit Maps : {{ $site_details->name }}
          <a type="button" href="/portal/maps" class="btn btn-outline-primary btn-sm float-end"><i class="fas fa-arrow-left"></i>&nbsp;Back to Maps</a>        
          <label class="ml-3"> - Building Floor: </label>
          <label class="ml-3">
              <select class="form-select form-select-sm floor-data">
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
                <option data-map_id="{{ $site_map->id }}" data-floor_map="{{ asset($site_map->map_file) }}" data-map_width="{{ $site_map->image_size_width }}" data-map_height="{{ $site_map->image_size_height }}" value="{{ $site_map->site_building_level_id }}" {{$active}}>{{ $site_map->building_name }} ({{ $site_map->floor_name }})</option>
                @endforeach
              </select>
            </label>
        </h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 map-holder">
            <div id="selectable" class="ui-selectable"></div>
            <canvas id="my-point" width="3000" height="3000" style="position: absolute;"></canvas>
            <img id="map_path">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="map-form-holder">
  <div class="toggle-right-button">
    <i class="fa fa-chevron-right fa-2x" aria-hidden="true" style="line-height:1.5;"></i>
  </div>
  <div class="card">
    <div class="card-body ">          
      <form name="frmCoordinates" id="frmCoordinates">
      {{ csrf_field() }}
      <div class="form-group row mb-0">
        <label for="firstName" class="col-sm-6 col-form-label">Point ID:</label>
        <label class="col-sm-6 col-form-label" id="point_id"></label>
        <input type="hidden" id="pid" name="pid" readonly="readonly">
      </div>
      <div class="form-group row mb-0">
        <label for="firstName" class="col-sm-12 col-form-label">Tenant:</label>
        <div class="col-sm-12">
          <select class="frm_info form-select" id="tenant_id" name="tenant_id">
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
          <select class="frm_info form-select" id="point_type" name="point_type">
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
          <input type="text" id="point_label" name="point_label" class="frm_info form-control form-control-sm" placeholder="Label">
        </div>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="map-form-arrow-left">
    <i class="fa fa-chevron-left fa-2x" aria-hidden="true" style="line-height:1.5;"></i>
</div>
@stop

@push('scripts')
<script src="{{ URL::to('js/jquery-ui/jquery-ui.min.js') }}"></script>

<script>
	var mypoint = document.getElementById('my-point');
  var contextp = mypoint.getContext('2d');
  //const slider = document.querySelector('.map-holder');
  var action;
  let isDown = false;
  let startX;
  let scrollLeft;
  var map_id;

  var current_point = -1;
	var previous_point = -2;

  $(document).ready(function() {

    // LOAD SELECTED MAP
    var floor_map = $(this).find(':selected').data('floor_map');
    var map_width = $(this).find(':selected').data('map_width');
    var map_height = $(this).find(':selected').data('map_height');
    map_id = $(this).find(':selected').data('map_id');
    
    // SET WIDTH, HEIGHT, AND IMAGE PATH
    $("#map_path").attr('width', map_width);
    $("#map_path").attr('src', floor_map);
    $("#selectable").attr('style', 'width: '+map_width+'px; height: '+map_height+'px;');

    // GET MAP POINTS
    get_map_points();

    $('.floor-data').on('change', function() {

      // SET SELECT FROM MAP DROPDOWN 
      var floor_id = $(this).val();
      floor_map = $(this).find(':selected').data('floor_map');
      map_width = $(this).find(':selected').data('map_width');
      map_height = $(this).find(':selected').data('map_height');
      map_id = $(this).find(':selected').data('map_id');

      // GET MAP POINTS
      get_map_points();

      // SET WIDTH, HEIGHT, AND IMAGE PATH
      $("#map_path").attr('width', map_width);
      $("#map_path").attr('src', floor_map);
      $("#selectable").attr('style', 'width: '+map_width+'px; height: '+map_height+'px;');

      // GET TENANTS ASSIGN FROM FLOOR
      $.get("/portal/tenant/get-tenants-per-floor/"+floor_id, function(data) { 

        $('#tenant_list').empty();
        $('#tenant_list').append('<option value="">Select Tenant</option>');
        $.each(data.data, function(key,val) {             
          $('#tenant_list').append('<option value="'+val.id+'">'+val.brand_name+'</option>');
        });
      });

    });

    $("#map_path").mousemove(function( event ) {
      var msg = "Handler for .mousemove() called at ";
      msg += event.pageX + ", " + event.pageY;
    });

    $('#frmCoordinates').on('submit', function(e) {
      e.preventDefault();
      $.post("/portal/map/update-details", $( "#frmCoordinates" ).serialize(), function(response) {
        if(response.status_code == 200) {
          get_map_points();
          toastr.success(response.message);
        }
      });

    });

    $('.frm_info').on('change', function() {
      if($("#pid").val() > 0) {
        $('#frmCoordinates').submit();
      }
    });

    $('.toggle-right-button').on('click', function() {
      $('.map-form-holder').toggle( "slide", { direction: "right" }, function() {
        $('.map-form-arrow-left').show();
      });
    });

    $('.map-form-arrow-left').on('click', function() {
      $('.map-form-holder').show( "slide", { direction: "right" } );
      $('.map-form-arrow-left').hide();
    });

  }); // END $(document).ready()

  function get_map_points() {

    $.get('/portal/map/get-points/'+map_id, function( data ) {
      if(data.status_code == 200) {
        $(".point").remove();
        $.each(data.data,function(i,item) {
          add_point(item);
        });
        get_map_links();
      }
    },
    "json");

    return true;

  }

  function get_map_links() {
    $.get('/portal/map/get-links/'+map_id, function( data ) {
      if(data.status_code == 200) {
        // CLEAR CANVAS
        contextp.clearRect(0, 0, mypoint.width, mypoint.height);
        // DRAW LINE IN CANVAS
        $.each(data.data,function(i,item) {
          draw_line_canvas(item.point_a_x,item.point_a_y,item.point_b_x,item.point_b_y);
        });					
      }
    }, "json");
  }

  function add_point(data) {
    $("#selectable").append('<div id="'+data.id+'" class="point ui-draggable" style="left: ' + (data.point_x) +'px; top: ' + data.point_y + 'px;" lat="' + data.point_x +'" lng="' + data.point_y + '"></div>');

    var stitle_w = '';
    var point_label = '';
    if(data.brand_name) {
      point_label = data.brand_name;
    }
    else if(data.amenity_name) {
      point_label = data.amenity_name;
    }
    else if(data.point_label) {
      point_label = data.point_label;
    }
 
    var mtop='16';
    var fontSize = (data.text_size == 0) ? 1 : data.text_size;
    var text_width = (data.text_width == 0) ? '' : 'width:'+data.text_width+'px;';

    $("#" +  data.id).html('<p class="point-text">'+point_label+'</p>');

    var p_width = $("#" +  data.id+" p:first" ).width();
    var center=((p_width/2)-5)*-1;

    $("#" +  data.id+" p:first" ).css({"font-size": fontSize+"rem", "margin": mtop+"px 0 0 "+center+"px", "transform": "rotate(" + data.rotation_z + "deg)"});

    $("#" +  data.id).click(function() {
      $(this).css('background-color', 'red');
      $('#pid').val(data.id);

      $(".point").each(function(){
        $(this).css('background-color', 'red');
      });
      $(this).css('background-color', '#0f0');
      point_info(data.id);

    })

  }

  function update_point(id, x, y) {
    $.post('/portal/map/update-point', { _token:"{{ csrf_token() }}", id: id, point_x: x, point_y: y }, function( data ) {
      get_map_points();
      toastr.success(data.message);
    }, "json");

  }

  function point_info(id) {
    $.get('/portal/map/point-info/'+id, function( data ) {
      if(data.status_code == 200) {
        var info = data.data;
        $('#point_id').html(info.id);
        $('#position_x').val(info.point_x);
        $('#position_y').val(info.point_y);
        $('#text_y_position').val(info.rotation_z);
        $('#text_size').val(info.text_size);
        $('#text_width').val(info.text_width);
        if(info.is_pwd == 1) {
          $('#is_pwd').prop( "checked", true);
        }
        else {
          $('#is_pwd').prop( "checked", false);
        }
        if(info.wrap_at == 1) {
          $('#wrap_at').prop( "checked", true);
        }
        else {
          $('#wrap_at').prop( "checked", false);
        }

        $('#tenant_id').val(info.tenant_id);
        $('#point_type').val(info.point_type);
        $('#point_label').val(info.point_label);               
      }
    }, "json");

  }

  function lineDistance(x, y, x0, y0){
    return Math.sqrt((x -= x0) * x + (y -= y0) * y);

  };

  function draw_line_canvas(x1,y1,x2,y2) {
    contextp.beginPath();
    contextp.lineWidth = 1.5;
    contextp.lineJoin = contextp.lineCap = 'round';
    contextp.strokeStyle = '#ff0000';

    if(x1 > x2 && y1 > y2) {
      contextp.moveTo(parseInt(x1)+1.5,parseInt(y1)+6.5);
      contextp.lineTo(parseInt(x2)+1.5,parseInt(y2)+6.5);
    }
    else if(x2 > x1 && y1 > y2) {
      contextp.moveTo(parseInt(x1)+2.5,parseInt(y1)+6.5);
      contextp.lineTo(parseInt(x2)+2.5,parseInt(y2)+6.5);
    }
    else if(x1 > x2 && y2 > y1) {
      contextp.moveTo(parseInt(x1)+1.5,parseInt(y1)+6.5);
      contextp.lineTo(parseInt(x2)+1.5,parseInt(y2)+6.5);
    }
    else if(x2 > x1 && y2 > y1) {
      contextp.moveTo(parseInt(x1)+1.5,parseInt(y1)+6.5);
      contextp.lineTo(parseInt(x2)+1.5,parseInt(y2)+6.5);
    }
    else {
      contextp.moveTo(parseInt(x1)-6.5,parseInt(y1)+6.5);
      contextp.lineTo(parseInt(x2)-6.5,parseInt(y2)+6.5);
    }
    contextp.stroke();

  }

</script>
@endpush