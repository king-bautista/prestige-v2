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
                    <div class="card-header sticky-nav">
                      <div class="row">
                        <div class="col-md-12">
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
                            <option data-map_id="{{ $site_map->id }}" data-floor_map="{{ asset($site_map->map_file) }}" data-map_width="{{ $site_map->image_size_width }}" data-map_height="{{ $site_map->image_size_height }}" value="{{ $site_map->site_building_level_id }}" {{$active}}>{{ $site_map->building_name }} ({{ $site_map->floor_name }})</option>
                            @endforeach
                          </select>
                          </label>
                          <label class="ml-3 mouseaction mouseaction-selected" title="Press Key Alt + 1">
                            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseDrag" value="drag_point" class="d-none"> Drag Point
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + 2">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseAdd" value="add_point" class="d-none"> Add Points
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + 3">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseDelete" value="delete_point" class="d-none"> Delete Point/Link
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + 4">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseInfo" value="point_info" class="d-none">Point Info
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + 5">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink" value="single_link" class="d-none">Single Link
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + 6">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink2" value="continous_link" class="d-none">Continous Link
                          </label>    
                          <!-- <label class="ml-3 mouseaction" title="Press Key Alt + 7">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="deleteLink" value="delete_link" class="d-none">Delete Link
                          </label>                        -->
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12 map-holder">
                          <div id="selectable" class="ui-selectable"></div>
                          <canvas id="my-point" style="position: absolute;"></canvas>
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
            <label for="firstName" class="col-sm-6 col-form-label">Position X:</label>
            <div class="col-sm-6">
              <input type="text" id="position_x" name="position_x" class="frm_info form-control form-control-sm " placeholder="0.0">
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Position Y:</label>
            <div class="col-sm-6">
              <input type="text" id="position_y" name="position_y" class="frm_info form-control form-control-sm" placeholder="0.0">
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Text Rotation:</label>
            <div class="col-sm-6">
              <input type="text" id="text_y_position" name="text_y_position" class="frm_info form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Text Size:</label>
            <div class="col-sm-6">
              <input type="text" id="text_size" name="text_size" class="frm_info form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">Text Width:</label>
            <div class="col-sm-6">
              <input type="text" id="text_width" name="text_width" class="frm_info form-control form-control-sm" placeholder="0.0" required>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">PWD:</label>
            <div class="col-sm-6">
              <div class="custom-control custom-switch">
                <input type="checkbox" id="is_pwd" name="is_pwd" class="frm_info custom-control-input" value="1">
                <label class="custom-control-label" for="is_pwd"></label>
              </div>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-6 col-form-label">WRAP:</label>
            <div class="col-sm-6">
              <div class="custom-control custom-switch">
                <input type="checkbox" id="wrap_at" name="wrap_at" class="frm_info custom-control-input" value="1">
                <label class="custom-control-label" for="wrap_at"></label>
              </div>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="firstName" class="col-sm-12 col-form-label">Tenant:</label>
            <div class="col-sm-12">
              <select class="frm_info custom-select" id="tenant_id" name="tenant_id">
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
              <select class="frm_info custom-select" id="point_type" name="point_type">
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

</section>
<!-- /.content -->
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
    $("#my-point").attr('width', map_width);
    $("#my-point").attr('height', map_width);
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
      $.get("/admin/site/tenant/get-tenants-per-floor/"+floor_id, function(data) { 
        $('#tenant_id').empty();
        $('#tenant_id').append('<option value="">Select Tenant</option>');
        $.each(data.data, function(key,val) {             
          $('#tenant_id').append('<option value="'+val.id+'">'+val.brand_name+'</option>');
        });
      });

    });

    $(".mouseaction").on('click',function() {
			$(this).addClass('mouseaction-selected');
			$(".mouseaction").not(this).removeClass('mouseaction-selected');
      $(this).find('input[type="radio"]').prop("checked", true);
      action = $('input[name="action"]:checked').val();

		});

    $("#map_path").mousemove(function( event ) {
      var msg = "Handler for .mousemove() called at ";
      msg += event.pageX + ", " + event.pageY;
     
    });

    $("#selectable").click(function() {
      var offset = $(this).offset();
      if(action === 'add_point' ) {
        create_point(offset);
      }

		});

    $('#frmCoordinates').on('submit', function(e) {
      e.preventDefault();
      $.post("/admin/site/map/update-details", $( "#frmCoordinates" ).serialize(), function(response) {
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

    $.get('/admin/site/map/get-points/'+map_id, function( data ) {
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
    $.get('/admin/site/map/get-links/'+map_id, function( data ) {
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

  function create_point(offset) {    
    var x = (event.pageX-offset.left);
    var y = (event.pageY-offset.top);
    $.post('/admin/site/map/create-point', { _token:"{{ csrf_token() }}", map_id: map_id, point_x: x, point_y: y }, function( data ) {
      add_point(data.data);
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

    var p_width = $("#" +  data.id+" p:first").width();
    var p_height = $("#" +  data.id+" p:first").height();
    var center=((p_width/2)-5)*-1;
    var vcenter=((p_height/2)-5)*-1;

    $("#" +  data.id+" p:first" ).css({"width": (data.wrap_at == 0) ? 'max-content' : '',  "font-size": fontSize+"rem", "position" : "absolute", "top":+ vcenter +"px", "left":+ center +"px", "transform": "rotate(" + data.rotation_z + "deg)"});
    // $("#" +  data.id+" p:first" ).css({"font-size": fontSize+"rem", "margin": mtop+"px 0 0 "+center+"px", "transform": "rotate(" + data.rotation_z + "deg)"});
    
    //Updated values
    if (data.wrap_at == 0) {
      var p_width = $("#" +  data.id+" p:first").width();
      var p_height = $("#" +  data.id+" p:first").height();
      var center=((p_width/2)-5)*-1;
      var vcenter=((p_height/2)-5)*-1;
      $("#" +  data.id+" p:first" ).css({"top":+ vcenter +"px", "left":+ center +"px"});
    }

    $("#" +  data.id).click(function() {
      $(this).css('background-color', 'red');
      $('#pid').val(data.id);
      switch(action) {

        case 'delete_point':
            delete_point(data.id)
          break;

        case 'point_info':
            $(".point").each(function(){
              $(this).css('background-color', 'red');
            });
            $(this).css('background-color', '#0f0');
            point_info(data.id);
          break;

        case 'single_link':
            current_point = data.id;
            if(previous_point == -2)
            {
              previous_point = current_point;
            }else {
              if(previous_point == current_point){
                alert("Warning: Do not connect a point to itself");
              }else{
                $.post('/admin/site/map/connect-point', { _token:"{{ csrf_token() }}", map_id: map_id, point_a: previous_point, point_b: current_point }, function( data ) {
                  draw_line_canvas(data.data.point_a_x,data.data.point_a_y,data.data.point_b_x,data.data.point_b_y);
                }, "json");
                previous_point = -2;
                current_point = -1;
              }
            }
          break;

        case 'continous_link':
            current_point = data.id;
            if(previous_point == -2) {
              previous_point = current_point;
            }
            else {
              if(previous_point == current_point){
                alert("Warning: Do not connect a point to itself");
              }else{
                $.post('/admin/site/map/connect-point', { _token:"{{ csrf_token() }}", map_id: map_id, point_a: previous_point, point_b: current_point }, function( data ) {
                  draw_line_canvas(data.data.point_a_x,data.data.point_a_y,data.data.point_b_x,data.data.point_b_y);
                }, "json");
                previous_point = current_point;
              }
            }
          break;
      }

    })
    .draggable( {
      stop: function(event,ui) 
      {
        update_point(data.id, $(this).position().left, $(this).position().top);
      }
    });

  }

  function update_point(id, x, y) {
    $.post('/admin/site/map/update-point', { _token:"{{ csrf_token() }}", id: id, point_x: x, point_y: y }, function( data ) {
      get_map_points();
      toastr.success(data.message);
    }, "json");

  }

  function delete_point(id) {
    $.get('/admin/site/map/delete-point/'+id, function( data ) {
      if(data.status_code == 200) {
        $('#'+id).remove();
      }
    }, "json");
    get_map_links();

  }

  function point_info(id) {
    $.get('/admin/site/map/point-info/'+id, function( data ) {
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

    // if(x1 > x2 && y1 > y2) {
    //   contextp.moveTo(parseInt(x1)+1.5,parseInt(y1)+6.5);
    //   contextp.lineTo(parseInt(x2)+1.5,parseInt(y2)+6.5);
    // }
    // else if(x2 > x1 && y1 > y2) {
    //   contextp.moveTo(parseInt(x1)+2.5,parseInt(y1)+6.5);
    //   contextp.lineTo(parseInt(x2)+2.5,parseInt(y2)+6.5);
    // }
    // else if(x1 > x2 && y2 > y1) {
    //   contextp.moveTo(parseInt(x1)+1.5,parseInt(y1)+6.5);
    //   contextp.lineTo(parseInt(x2)+1.5,parseInt(y2)+6.5);
    // }
    // else if(x2 > x1 && y2 > y1) {
    //   contextp.moveTo(parseInt(x1)+1.5,parseInt(y1)+6.5);
    //   contextp.lineTo(parseInt(x2)+1.5,parseInt(y2)+6.5);
    // }
    // else {
      contextp.moveTo(parseInt(x1),parseInt(y1)+7.5);
      contextp.lineTo(parseInt(x2),parseInt(y2)+7.5);
    // }
    contextp.stroke();

  }

</script>
@endpush