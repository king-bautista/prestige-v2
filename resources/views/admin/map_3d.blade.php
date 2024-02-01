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
                          <span><b>Mouse Action: </b></span>
                          <label class="ml-3 mouseaction mouseaction-selected" title="Press Key Alt + M">
                            <i class="fas fa-sync-alt" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseRotate" value="rotate" class="d-none"> Rotate/Pan/Zoom Map
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + M">
                            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseDrag" value="drag_point" class="d-none"> Drag Point
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + A">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseAdd" value="add_point" class="d-none"> Add Points
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + R">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseDelete" value="delete_point" class="d-none"> Delete Point                          
                          </label>                     
                          <label class="ml-3 mouseaction" title="Press Key Alt + P">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseInfo" value="point_info" class="d-none">Point Info
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + S">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink" value="single_link" class="d-none">Single Link
                          </label>
                          <label class="ml-3 mouseaction" title="Press Key Alt + C">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            <input type="radio" name="action" id="mouseLink2" value="continous_link" class="d-none">Continous Link
                          </label>    
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12 map-holder">
                          <div id="canvas" style="height:100%;"></div>
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
      <div class="card h-98">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
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
                <button data-map_id="{{ $site_map->id }}" data-floor_map="{{ asset($site_map->map_file) }}" data-map_width="{{ $site_map->image_size_width }}" data-map_height="{{ $site_map->image_size_height }}" data-floor_id="{{ $site_map->site_building_level_id }}" type="button" class="btn btn-primary btn-map {{$active}} form-control mb-1 ">{{ $site_map->building_name }} ({{ $site_map->floor_name }})</button>
              @endforeach              
            </div>
          </div>          
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
<script src="{{ URL::to('themes/three.js/examples/js/libs/jszip.min.js') }}"></script>
<script src="{{ URL::to('themes/custom-js/text-wrapper-3js.js') }}"></script>
<script type="module">
  import * as THREE from "{{ URL::to('themes/three.js/build/three.module.js') }}";
	import Stats from "{{ URL::to('themes/three.js/examples/jsm/libs/stats.module.js') }}";
	import { KMZLoader } from "{{ URL::to('themes/three.js/examples/jsm/loaders/KMZLoader.js') }}";
	import { OBJLoader } from "{{ URL::to('themes/three.js/examples/jsm/loaders/OBJLoader.js') }}";
	import { OrbitControls } from "{{ URL::to('themes/three.js/examples/jsm/controls/OrbitControls.js') }}";
	import { GeometryUtils } from "{{ URL::to('themes/three.js/examples/jsm/utils/GeometryUtils.js') }}";

	import { Line2 } from "{{ URL::to('themes/three.js/examples/jsm/lines/Line2.js') }}";
	import { LineMaterial } from "{{ URL::to('themes/three.js/examples/jsm/lines/LineMaterial.js') }}";
	import { LineGeometry } from "{{ URL::to('themes/three.js/examples/jsm/lines/LineGeometry.js') }}";

	import { GLTFLoader } from "{{ URL::to('themes/three.js/examples/jsm/loaders/GLTFLoader.js') }}";

  var container, stats, controls, theball, rotationPoint;
	var camera, cameraTarget, scene, renderer;
	var dotstoremove = [];

	// Track all objects and collisions.
	var objects = [];
		
	// Set mouse and raycaster.
	var raycaster = new THREE.Raycaster();
	var mouse = new THREE.Vector2();
		
	// Store movements.
	var movements = [];
	var currentpos = 1;
	var playerSpeed = .1;
	
	var groupmarker;

	var floors = {};	

	var theball;

	var texts = {};
	var map_points = {};

	var vec = new THREE.Vector3(); // create once and reuse
	var pos = new THREE.Vector3(); // create once and reuse

	let sphere = new THREE.SphereGeometry( 1,32,32);
	let	redmarker =	new THREE.MeshBasicMaterial( {color: "rgba(255,0,0)"} );

	//drag variables
	var ROTATE = 1, DRAG = 2, ADD = 3, DELETE = 4, INFO = 5, LINK = 6, LINKSINGLE = 7;  // Possible mouse actions
	var mouseAction = ROTATE;  // currently selected mouse action
	var dragItem;  // the cylinder that is being dragged, during a drag operation
	var targetForDragging;  // An invisible object that is used as the target for raycasting while
	// dragging a cylinder.  I use it to find the new location of the
	// cylinder.  I tried using the ground for this purpose, but to get
	// the motion right, I needed a target that is at the same height
	// above the ground as the point where the user clicked the cylinder.
	var intersects; //the objects intersected

	var floor;
	var pointMarker;
	var pointMarkerHighlight;
	var links = [];
	
	var lineMaterial = new THREE.LineBasicMaterial({
				color: "rgb(255,0,0)", linewidth: 3
			});

  var tenants = @php echo $site_tenants; @endphp;
	var lines_start = {};
	var lines_end = {};

	var font;
	var textMaterial = new THREE.MeshBasicMaterial( { color: 0x000000, overdraw: true } )

	var active_floor =  @php echo $current_map->site_building_level_id; @endphp;

	init();
	animate();

	function init() {
		container = document.getElementById('canvas');
		
		var w = container.offsetWidth;
		var h = container.offsetHeight;

		$("#info").css('height',h + 'px');

		/**setup camera*/
		var fov = 30; //35 //Camera frustum vertical field of view.
		var aspect  = w / h; 
		var near = 1;  //0.1
		var far = 1000; //2000

		camera = new THREE.PerspectiveCamera( fov, aspect, near, far );
		camera.position.set( 300, 0, 300 );

		cameraTarget = new THREE.Vector3( 0, - 0.25, 0 );
		camera.lookAt( cameraTarget );

		scene = new THREE.Scene();
		scene.background = new THREE.Color();

		pointMarker = new THREE.Mesh(
			new THREE.SphereGeometry( 1,32,32), 
			new THREE.MeshBasicMaterial( {color: "rgba(255,0,0)"} )
		);
		pointMarker.name = "point"
		pointMarker.position.y = 6

		pointMarkerHighlight = new THREE.Mesh(
			new THREE.SphereGeometry( 1,50,50), 
			new THREE.MeshBasicMaterial( {color: "rgba(0,255,255)"} )
		);
		pointMarkerHighlight.position.y = 6
		scene.add(pointMarkerHighlight);

		targetForDragging = new THREE.Mesh(
			new THREE.BoxGeometry(innerWidth,6,innerHeight),
			new THREE.MeshBasicMaterial()
		);
		targetForDragging.material.visible = false;

		targetForDragging.material.transparent = true;  // This was used for debugging
		targetForDragging.material.opacity = 1;

		var fontloader = new THREE.FontLoader();

		groupmarker = new THREE.Object3D;
		fontloader.load( "{{ URL::to('themes/three.js/examples/fonts/helvetiker_bold.typeface.json') }}", function ( response ) {
			font = response

			drawPoints();
			//drawLinkLine();
		});

		// Lights
		scene.add( new THREE.HemisphereLight( 0x443333, 0x111122 ) );

		addShadowedLight( 1, 1, 1, 0xFFFFFF, .5 );
		addShadowedLight( 0.5, 1, - 1, 0xFFFFF, .5 );

		@foreach ($site_maps as $site_map)
			@if($site_map->site_building_level_id == $current_map->site_building_level_id)
				active_floor = '@php echo $site_map->site_building_level_id; @endphp';
			@endif
			createFloor('@php echo $site_map->site_building_level_id; @endphp', '@php echo $site_map->map_file_path; @endphp');
			//drawLinkLine('@php echo $site_map->site_building_level_id; @endphp');
		@endforeach
		// renderer

		renderer = new THREE.WebGLRenderer( { antialias: true } );
		renderer.setPixelRatio( window.devicePixelRatio );
		renderer.outputEncoding = THREE.sRGBEncoding;
		var element = renderer.domElement;
		
		renderer.shadowMap.enabled = true;

		renderer.setSize(w,h);
		container.appendChild( renderer.domElement );

		// Create a rotation point.
		rotationPoint = new THREE.Object3D();
		rotationPoint.position.set( 0, 0, 0 );
		scene.add( rotationPoint );
		
		// Build the controls.
		controls = new OrbitControls( camera, element );
		var viewAngle = 60; //60;

		controls.minDistance = 40; //max zoom in
		controls.maxDistance = 500; //max zoom out
		camera.position.set( 500, 0, 500 );

		controls.addEventListener( 'change', controlChange );

		controls.update();

		window.addEventListener( 'resize', onWindowResize, false );
		document.addEventListener( 'mousedown', onDocumentMouseDown, false );
		document.addEventListener( 'touchstart', onTouchStart, false );
	}

	function addShadowedLight( x, y, z, color, intensity ) {

		var directionalLight = new THREE.DirectionalLight( color, intensity );
		directionalLight.position.set( x, y, z );
		scene.add( directionalLight );

		directionalLight.castShadow = true;

		var d = 1;
		directionalLight.shadow.camera.left = - d;
		directionalLight.shadow.camera.right = d;
		directionalLight.shadow.camera.top = d;
		directionalLight.shadow.camera.bottom = - d;

		directionalLight.shadow.camera.near = 1;
		directionalLight.shadow.camera.far = 4;

		directionalLight.shadow.bias = - 0.002;

	}

	function onWindowResize() {
		/*camera.aspect = window.innerWidth / window.innerHeight;
		camera.updateProjectionMatrix();

		renderer.setSize( window.innerWidth, window.innerHeight );*/

		var container = document.getElementById('canvas');
		var w = container.offsetWidth;
		var h = container.offsetHeight;

		camera.aspect = w / h;
		camera.updateProjectionMatrix();

		renderer.setSize( w, h );
	}

	function loadKmz(index,map_url,is_default=false)
	{
		/** load map */
		var kmzloader = new KMZLoader();
		kmzloader.load( map_url, function ( kmz ) {
			kmz.scene.position.set(10, 0.2  , 5);
			floors[index].add( kmz.scene );
			floors[index].visible = is_default;
		});

		console.log(floors);
	}

	// Updates to apply to the scene while running.
	function update() {
		camera.updateProjectionMatrix();
	}
	
	function animate() {
		requestAnimationFrame( animate );
		update();
		//stats.update();
		render();
	}

	function render() {
		renderer.render( scene, camera );

		// Don't let the camera go too low.
		//if ( camera.position.y < 10 ) {
		//	camera.position.y = 10;
		//}

		// If any movement was added, run it!
		if ( movements.length > 0 ) {    
			moveBall( theball, movements[ currentpos ] );
		}

		$.each(lines_start,function(){
			var ls = this;
			$.each(ls,function(){
				this.geometry.attributes.position.needsUpdate = true;
			})
		});
	}

	// Create the floor of the scene.
	function createFloor(index,kmz) {
		floors[index] = new THREE.Group(); 

		var w = container.offsetWidth;
		var h = container.offsetHeight;

		var geometry = new THREE.PlaneBufferGeometry( w , h  );
		var material = new THREE.MeshToonMaterial( {color: new THREE.Color()} );
		
		var floor = new THREE.Mesh( geometry, material );
		floor.rotation.x = -1 * Math.PI/2;
		floor.position.y = 0;
		floor.name = "floor";
		floors[index].add(floor);

		scene.add( floors[index] );

		loadKmz(index,kmz,(active_floor == index));
	}

	// Event that fires upon mouse down.
	function onDocumentMouseDown( event, bypass = false ) {
		
		if(event.target.tagName == 'CANVAS')
		{
			//console.log(event.target.tagName);
			event.preventDefault();
		}

		// Detect which mouse button was clicked.
		if ( event.which == 1 )
		{
			mouse.x = ( event.clientX / renderer.domElement.clientWidth ) * 2 - 1;
			mouse.y = - ( event.clientY / renderer.domElement.clientHeight ) * 2 + 1;

			// Use the raycaster to detect intersections.
			raycaster.setFromCamera( mouse, camera );

			// Grab all objects that can be intersected.
			var intersects = raycaster.intersectObject( floors[active_floor]);
			if ( intersects.length > 0 ) {
				//movements.push(intersects[ 0 ].point);
			}
		}
	}

	// remove the dot trail
	function removetrail()
	{
		dotstoremove.forEach(function (item, index) {
			scene.remove(item);
			item.geometry.dispose();
			item.material.dispose();
			delete dotstoremove[index];
		});
	}

	function moveBall( location, destination, speed = playerSpeed ) {
		var moveDistance = speed;
		
		// Translate over to the position.
		var posX = location.position.x;
		var posZ = location.position.z;
		var posY = location.position.y;
		var newPosX = destination.x;
		var newPosZ = destination.z;
		var newPosY = destination.y;

		// Set a multiplier just in case we need negative values.
		var multiplierX = 1;
		var multiplierZ = 1;
		var multiplierY = 1;
		
		// Detect the distance between the current pos and target.
		var diffX = Math.abs( posX - newPosX );
		var diffZ = Math.abs( posZ - newPosZ );
		var diffY = Math.abs( posY - newPosY );
		
		//var distance = Math.sqrt( diffX * diffX + diffZ * diffZ );
		var distance = Math.sqrt( diffX * diffX + diffZ * diffZ + diffY * diffY);
		
		// Use negative multipliers if necessary.
		if (posX > newPosX) {
			multiplierX = -1;
		}
		
		if (posZ > newPosZ) {
			multiplierZ = -1;
		}

		if (posY > newPosY) {
			multiplierY = -1;
		}
		
		// Update the main position.
		location.position.x = location.position.x + ( moveDistance * ( diffX / distance )) * multiplierX;
		location.position.z = location.position.z + ( moveDistance * ( diffZ / distance )) * multiplierZ;
		location.position.y = location.position.y + ( moveDistance * ( diffY / distance )) * multiplierY;
		
		// If the position is close we can call the movement complete.
		if (( location.position.x <= newPosX + playerSpeed && location.position.x >= newPosX - playerSpeed ) &&
			( location.position.z <= newPosZ + playerSpeed && location.position.z >= newPosZ - playerSpeed ) &&
			( location.position.y <= newPosY + playerSpeed &&  location.position.y >= newPosY - playerSpeed )) {

			if(currentpos < movements.length - 1)
			{
				currentpos++;
			}else{
				location.position.x = movements[0].x;
				location.position.y = movements[0].y;
				location.position.z = movements[0].z;
				currentpos=1;				
			}
		}
	}

	// Stop character movement.
	function stopMovement() {
		movements = [];
	}

	function move( location, destination, speed = playerSpeed ) {
		var moveDistance = speed;
		
		// Translate over to the position.
		var posX = location.position.x;
		var posZ = location.position.z;
		var newPosX = destination.x;
		var newPosZ = destination.z;
		
		// draw the line	
		var tempball = new THREE.Mesh(
			new THREE.SphereGeometry( .02, 1, 1 ), 
			new THREE.MeshBasicMaterial( {color: 0xff0000} ),
			);
		tempball.position.set ( posX, .1, posZ);
		dotstoremove.push(tempball);
		scene.add(tempball);

		// Set a multiplier just in case we need negative values.
		var multiplierX = 1;
		var multiplierZ = 1;
		
		// Detect the distance between the current pos and target.
		var diffX = Math.abs( posX - newPosX );
		var diffZ = Math.abs( posZ - newPosZ );
		var distance = Math.sqrt( diffX * diffX + diffZ * diffZ );
		
		// Use negative multipliers if necessary.
		if (posX > newPosX) {
			multiplierX = -1;
		}
		
		if (posZ > newPosZ) {
			multiplierZ = -1;
		}
		
		// Update the main position.
		location.position.x = location.position.x + ( moveDistance * ( diffX / distance )) * multiplierX;
		location.position.z = location.position.z + ( moveDistance * ( diffZ / distance )) * multiplierZ;
		
		// If the position is close we can call the movement complete.
		if (( location.position.x <= newPosX + playerSpeed && location.position.x >= newPosX - playerSpeed ) &&
			( location.position.z <= newPosZ + playerSpeed && location.position.z >= newPosZ - playerSpeed )) {		
			// Reset any movements.
			stopMovement();
		}
	}

	// Create the main character.
	function createCharacter() {
		theball = new THREE.Mesh(
			new THREE.SphereGeometry( .1, 32, 32 ), 
			new THREE.MeshBasicMaterial( {color: 0xff0000} ),
			);
		theball.position.y = .1;
		rotationPoint.add( theball );
	}
	
	function onTouchStart(event, bypass = false) {
		event.preventDefault();

		// Grab the coordinates.
		mouse.x = ( event.touches[0].clientX / renderer.domElement.clientWidth ) * 2 - 1;
		mouse.y = - ( event.touches[0].clientY / renderer.domElement.clientHeight ) * 2 + 1;

		// Use the raycaster to detect intersections.
		raycaster.setFromCamera( mouse, camera );

		// Grab all objects that can be intersected.
		var intersects = raycaster.intersectObject( floor );
		if ( intersects.length > 0 ) {
			//movements.push(intersects[ 0 ].point);
		}
	}

	function controlChange()
	{
		var c = this;
		$.each(texts,function(index){
			if(c.getAzimuthalAngle() < -0.0)
			{
				if(this.type == 'text')
				{
					this.text.rotation.z = (360 * Math.PI / 180) - this.rotz;
				}else{
					var children = this.text.children;
					for(var i = 0, j = children.length; i < j; i++)
					{
						children[i].rotation.z = (360 * Math.PI / 180) - this.rotz;
					}
				}
			}else if(c.getAzimuthalAngle() > 1.5){
				//this.text.rotation.z = (360 * Math.PI / 180) - this.rotz;
			}else{
				if(this.type == 'text')
				{
					this.text.rotation.z = this.rotz;
				}else{
					var children = this.text.children;
					for(var i = 0, j = children.length; i < j; i++)
					{
						children[i].rotation.z = this.rotz;
					}
				}
			}
		});
	}

	function onDblClick(event){
		mouse.x = ( event.clientX / innerWidth ) * 2 - 1;
		mouse.y = - ( event.clientY / innerHeight ) * 2 + 1;	
		raycaster.setFromCamera(mouse, camera);
		
		let intersects = raycaster.intersectObject(floor);
		
		if (intersects.length < 1) return;

		let o = intersects[0];
  		let pIntersect = o.point.clone();
  		floor.worldToLocal(pIntersect);
	}

	function setUpMouseHandler(element, mouseDownFunc, mouseDragFunc, mouseUpFunc) {

		/*
			element -- either the element itself or a string with the id of the element
			mouseDownFunc(x,y,evt) -- should return a boolean to indicate whether to start a drag operation
			mouseDragFunc(x,y,evt,prevX,prevY,startX,startY)
			mouseUpFunc(x,y,evt,prevX,prevY,startX,startY)
		*/
		if (!element || !mouseDownFunc || !(typeof mouseDownFunc == "function")) {
			throw "Illegal arguments in setUpMouseHander";
		}
		if (typeof element == "string") {
			element = document.getElementById(element);
		}
		if (!element || !element.addEventListener) {
			throw "first argument in setUpMouseHander is not a valid element";
		}
		var dragging = false;
		var startX, startY;
		var prevX, prevY;

		function doMouseDown(evt) {
			console.log('dito');

			if (dragging) {
				return;
			}
			var r = element.getBoundingClientRect();
			var x = evt.clientX - r.left;
			var y = evt.clientY - r.top;
			prevX = startX = x;
			prevY = startY = y;
			dragging = mouseDownFunc(x, y, evt);
			if (dragging) {
				document.addEventListener("mousemove", doMouseMove);
				document.addEventListener("mouseup", doMouseUp);
			}
		}

		function doMouseMove(evt) {
			if (dragging) {
				if (mouseDragFunc) {
					var r = element.getBoundingClientRect();
					var x = evt.clientX - r.left;
					var y = evt.clientY - r.top;
					mouseDragFunc(x, y, evt, prevX, prevY, startX, startY);
				}
				prevX = x;
				prevY = y;
			}
		}

		function doMouseUp(evt) {
			if (dragging) {
				document.removeEventListener("mousemove", doMouseMove);
				document.removeEventListener("mouseup", doMouseUp);
				if (mouseUpFunc) {
					var r = element.getBoundingClientRect();
					var x = evt.clientX - r.left;
					var y = evt.clientY - r.top;
					mouseUpFunc(x, y, evt, prevX, prevY, startX, startY);
				}
				dragging = false;
			}
		}
		element.addEventListener("mousedown", doMouseDown);
	}

	function setUpTouchHandler(element, touchStartFunc, touchMoveFunc, touchEndFunc, touchCancelFunc) {
		/*
			element -- either the element itself or a string with the id of the element
			touchStartFunc(x,y,evt) -- should return a boolean to indicate whether to start a drag operation
			touchMoveFunc(x,y,evt,prevX,prevY,startX,startY)
			touchEndFunc(evt,prevX,prevY,startX,startY)
			touchCancelFunc()   // no parameters
		*/
		if (!element || !touchStartFunc || !(typeof touchStartFunc == "function")) {
			throw "Illegal arguments in setUpTouchHander";
		}
		if (typeof element == "string") {
			element = document.getElementById(element);
		}
		if (!element || !element.addEventListener) {
			throw "first argument in setUpTouchHander is not a valid element";
		}
		var dragging = false;
		var startX, startY;
		var prevX, prevY;

		function doTouchStart(evt) {
			if (evt.touches.length != 1) {
				doTouchEnd(evt);
				return;
			}
			evt.preventDefault();
			if (dragging) {
				doTouchEnd();
			}
			var r = element.getBoundingClientRect();
			var x = evt.touches[0].clientX - r.left;
			var y = evt.touches[0].clientY - r.top;
			prevX = startX = x;
			prevY = startY = y;
			dragging = touchStartFunc(x, y, evt);
			if (dragging) {
				element.addEventListener("touchmove", doTouchMove);
				element.addEventListener("touchend", doTouchEnd);
				element.addEventListener("touchcancel", doTouchCancel);
			}
		}

		function doTouchMove(evt) {
			if (dragging) {
				if (evt.touches.length != 1) {
					doTouchEnd(evt);
					return;
				}
				evt.preventDefault();
				if (touchMoveFunc) {
					var r = element.getBoundingClientRect();
					var x = evt.touches[0].clientX - r.left;
					var y = evt.touches[0].clientY - r.top;
					touchMoveFunc(x, y, evt, prevX, prevY, startX, startY);
				}
				prevX = x;
				prevY = y;
			}
		}

		function doTouchCancel() {
			if (touchCancelFunc) {
				touchCancelFunc();
			}
		}

		function doTouchEnd(evt) {
			if (dragging) {
				dragging = false;
				element.removeEventListener("touchmove", doTouchMove);
				element.removeEventListener("touchend", doTouchEnd);
				element.removeEventListener("touchcancel", doTouchCancel);
				if (touchEndFunc) {
					touchEndFunc(evt, prevX, prevY, startX, startY);
				}
			}
		}
		element.addEventListener("touchstart", doTouchStart);
	}

	function doMouseDown(x,y) {
		if (mouseAction == ROTATE) {
			return true;
		}

		if (targetForDragging.parent == floors[active_floor]) {
			floors[active_floor].remove(targetForDragging);  // I don't want to check for hits on targetForDragging
		}

		var a = 2 * x / container.offsetWidth - 1;
		var b = 1 - 2 * y / container.offsetHeight;

		raycaster.setFromCamera( new THREE.Vector2(a,b), camera );
		let intersects = raycaster.intersectObjects(floors[active_floor].children);

		if (intersects.length < 1) return;

		let item = intersects[0];
		let objectHit = item.object;
  		let pIntersect = item.point.clone();
  		floors[active_floor].worldToLocal(pIntersect);

		switch (mouseAction) {
			case ADD:
				if (objectHit.name == 'floor') {
					var locationX = item.point.x;
					var locationZ = item.point.z;
					var coords = new THREE.Vector3(locationX, 3.5, locationZ);
					addPointMarker(coords,active_floor);
					render();
				}
				links = [];
				return false;
			case DRAG:
				links = [];
				if (objectHit == floor)
				{
					return false;
				}
				
				if(objectHit.name = 'point')
				{
					dragItem = objectHit;
					targetForDragging.position.set(objectHit.position);
					floors[active_floor].add(targetForDragging);
					targetForDragging.position.set(0,6,0);
					render();
				}
				return true;
				
			case DELETE:
				links = [];
				
				if (objectHit != floor) {
					if(objectHit.name == 'point')
					{
						//remove from db
						$.ajax({
							url: '' + objectHit.userData.id,
							type: 'POST',
							data: {},
							dataType: 'JSON',
							beforeSend: function(){
							},
							success: function(data){
								
							},
							complete: function(){
								
							},
							error: function(jqXHR, textStatus, errorThrown){
								
							}
						});
					} else if(objectHit.name == 'link')
					{
						//remove from db
						$.ajax({
							url: '' + objectHit.userData.mapid + '/' + objectHit.userData.start + '/' + objectHit.userData.end,
							type: 'POST',
							data: {},
							dataType: 'JSON',
							beforeSend: function(){
							},
							success: function(data){
								
							},
							complete: function(){
								
							},
							error: function(jqXHR, textStatus, errorThrown){
								
							}
						});
					}
					

					//scene.remove(objectHit);
					floors[active_floor].remove(objectHit);
					render();
				}
				return false;
			case LINK:
				if(objectHit.name == 'point')
				{
					if(links.length < 2)
					{
						links.push(objectHit);
					}

					if(links.length == 2)
					{
						drawLine(); //use links = array of points
					}
					showPointInfo(objectHit);
				}
				
				return false;
			case LINKSINGLE:
				
				if(objectHit.name == 'point')
				{
					if(links.length < 2)
					{
						links.push(objectHit);
					}

					if(links.length == 2)
					{
						drawLine(); //use links = array of points
					}
					showPointInfo(objectHit);
				}
				return false;
			default: //info
				if(objectHit.name == 'point')
				{
					showPointInfo(objectHit);
				}
				return false;
		}
	}

	function doMouseMove(x,y,evt,prevX,prevY) {
		if (mouseAction == DRAG) {

			var a = 2 * x /innerWidth - 1;
			var b = 1 - 2 * y/innerHeight;
			raycaster.setFromCamera( new THREE.Vector2(a,b), camera );
			intersects = raycaster.intersectObject( targetForDragging ); 

			if (intersects.length == 0) {				
				console.log('moving no in');
				return;
			}

			if(dragItem.name == 'point')
			{
				var w = container.offsetWidth;
				var h = container.offsetHeight;

				var locationX = intersects[0].point.x;
				var locationZ = intersects[0].point.z;
				var locationY = dragItem.position.y;

				var coords = new THREE.Vector3(locationX, locationY , locationZ);
				scene.worldToLocal(coords);
				a = Math.min(w,Math.max(-w,coords.x));  // clamp coords to the range -19 to 19, so object stays on ground
				b = Math.min(h,Math.max(-h,coords.z));
				
				dragItem.position.set(a,locationY,b);

				redrawLine(dragItem.userData.id,a,locationY,b);

				if(texts.hasOwnProperty(dragItem.userData.id))
				{
					texts[dragItem.userData.id].text.position.x = locationX;
					texts[dragItem.userData.id].text.position.y = locationY;
					texts[dragItem.userData.id].text.position.z = locationZ;
				}

				showPointInfo(dragItem);
				render();
			}
		}
	}

	function doMouseUp(x,y) {
		if (mouseAction == DRAG) {			
			// UPDATE TO site_points TABLE

			// $.ajax({
			// 	url: '' + dragItem.userData.id,
			// 	type: 'POST',
			// 	data: {'point_x':dragItem.position.x,'point_y':dragItem.position.y,'point_z':dragItem.position.z},
			// 	dataType: 'JSON',
			// 	beforeSend: function(){
			// 	},
			// 	success: function(data){
			// 		//obj.userData.id = data.id;
			// 	},
			// 	complete: function(){
					
			// 	},
			// 	error: function(jqXHR, textStatus, errorThrown){
					
			// 	}
			// });
		}
	}

	function redrawLine(id,x,y,z)
	{
		if(lines_start.hasOwnProperty(id))
		{
			$.each(lines_start[id],function(){
				var positions = this.geometry.attributes.position.array;
				positions[0] = x;
				positions[1] = y+2;
				positions[2] = z;
			});
		}

		if(lines_end.hasOwnProperty(id))
		{
			$.each(lines_end[id],function(){
				var positions = this.geometry.attributes.position.array;
				positions[3] = x;
				positions[4] = y+2;
				positions[5] = z;
			});
		}
	}

	function drawLine()
	{
		var points = [];
		$.each(links,function(){
			points.push(new THREE.Vector3(this.position.x,this.position.y,this.position.z));
		});
		let geometry = new THREE.BufferGeometry().setFromPoints( points );
		geometry.dynamic = true;
		let line = new THREE.Line( geometry, lineMaterial );
		line.geometry.attributes.position.needsUpdate = true;
		line.name = "link";
		line.userData = {"mapid":'',"start":links[0].userData.id,"end":links[1].userData.id};
		scene.add( line );
		
		if(!lines_start.hasOwnProperty(links[0].userData.id)) lines_start[links[0].userData.id] = [];
		lines_start[links[0].userData.id].push(line);

		if(!lines_end.hasOwnProperty(links[1].userData.id)) lines_end[links[1].userData.id] = [];
		lines_end[links[1].userData.id].push(line);

		//save to db
		$.ajax({
				url: '',
				type: 'POST',
				data: {},
				dataType: 'JSON',
				beforeSend: function(){
				},
				success: function(data){
					if(mouseAction == LINK)
					{
						//remove first element
						links.shift();
					}else{
						links = [];
					}
				},
				complete: function(){
					
				},
				error: function(jqXHR, textStatus, errorThrown){
					
				}
			});
	}

	function drawLinkLine(floor)
	{
		// let mapPointsAll = <?php //echo json_encode($map_points);?>;
		// let mapPoints = mapPointsAll[floor];
		// let linksDBAll = <?php //echo json_encode($links);?>;
		// let linksDB = linksDBAll[floor];
		
		// $.each(linksDB,function(index){
		// 	var links = this;
		// 	$.each(links,function(){
		// 		if(mapPoints.hasOwnProperty(this.point_a) && mapPoints.hasOwnProperty(this.point_b))
		// 		{
		// 			var pointa = mapPoints[this.point_a];
		// 			var pointb = mapPoints[this.point_b];
		// 			var points = [];

		// 			var coordsa = new THREE.Vector3(pointa.point_x,pointa.point_y+2,pointa.point_z);
		// 			var coordsb = new THREE.Vector3(pointb.point_x,pointb.point_y+2,pointb.point_z);

		// 			points.push(coordsa);
		// 			points.push(coordsb);
					
		// 			let geometry = new THREE.BufferGeometry().setFromPoints( points );
		// 			geometry.dynamic = true;
		// 			let line = new THREE.Line( geometry, lineMaterial );
		// 			line.geometry.attributes.position.needsUpdate = true;
		// 			line.name = "link";
		// 			line.userData = {"mapid":floor,"start":this.point_a,"end":this.point_b};
		// 			//scene.add( line );
		// 			floors[floor].add(line);
					
		// 			if(!lines_start.hasOwnProperty(pointa.id)) lines_start[pointa.id] = [];
		// 			if(!lines_end.hasOwnProperty(pointb.id)) lines_end[pointb.id] = [];

		// 			lines_start[pointa.id].push(line);
		// 			lines_end[pointb.id].push(line);
		// 		}
		// 	});
		// });
	}

	function drawPoints()
	{
		// let mapPoints = <?php //echo json_encode($map_points);?>;
		// var coords = new THREE.Vector3(0,0,0);

		// $.each(mapPoints,function(index){
		// 	$.each(mapPoints[index],function(){
		// 		coords.x = this.point_x;
		// 		coords.y = this.point_y;
		// 		coords.z = this.point_z + 1;
		// 		addPointMarker(coords,index,this);
		// 	});
		// });
		
	}

	function addPointMarker(coords,floor,info)
	{
		scene.worldToLocal(coords);
		var obj = pointMarker.clone();
		obj.position.x = coords.x;
		obj.position.y = coords.y;
		obj.position.z = coords.z;
		obj.userData = info;
		
		floors[floor].add( obj );

		if(info === undefined)
		{
			// SAVE TO site_points
			$.post("/admin/site/map/create-point", { 
				map_id: active_floor, 
				point_x: coords.x, 
				point_y: coords.y, 
				point_z: coords.z 
			}).done(function( data ) {
				obj.userData = data.data;
				map_points[data.id] = obj;
			});
		}else if(info.tenant_id > 0){
			
			let label = info.point_label ? info.point_label : tenants[info.tenant_id].store_name;

			if(info.wrap_at > 0 && label.length > info.wrap_at)
			{
				var group = new THREE.Object3D();
				var cutText = label;
				var origCoords = coords;
				info.wrap_at = label.split(' ').reduce(function (a, b) {
					return a.length > b.length ? a : b;
				}).length;

				while(cutText.length > info.wrap_at && cutText.indexOf(' ') >= 0){
					if( cutText.charAt(info.wrap_at) === ' ' ) {
						var line = cutText.substring(0, info.wrap_at);
						cutText = cutText.slice( info.wrap_at );
					}else{
						var counter = 1;
						var charpos = info.wrap_at - counter;
						while( cutText.charAt(charpos) !== ' ' && charpos > 0) {
							counter++;
							charpos = info.wrap_at - counter;
						}
						// cut line at space
						var line = cutText;
						if(charpos > 0)
						{
							line = cutText.substring( 0,charpos );
							cutText = cutText.slice( charpos );
						}
					}

					line = line.trim();
					var text3d = new THREE.TextGeometry( line, {
						font: font,
						size: info.text_size,
						height: 0.001,
						bevelEnabled: false,
						color: "rgba(255,0,0)"
					});
					text3d.center();

					var text = new THREE.Mesh( text3d, textMaterial );
					text.rotation.x = -90 * Math.PI / 180;
					text.rotation.z = info.rotation_z * Math.PI / 180;
					text.position.x = coords.x;
					text.position.y = coords.y + 1;
					text.position.z = coords.z;
			
					if(info.rotation_z > 0 )
					{
						coords.x += info.text_size * 1.5;
					}else{
						coords.z += info.text_size * 1.5;
					}
					group.add(text);
					cutText = cutText.trim();
				}
				cutText = cutText.trim();
				var text3d = new THREE.TextGeometry( cutText, {
					font: font,
					size: info.text_size,
					height: 0.001,
					bevelEnabled: false,
					color: "rgba(255,0,0,1)"
				});
				text3d.center();

				var text = new THREE.Mesh( text3d, textMaterial );
				text.rotation.x = -90 * Math.PI / 180;
				text.rotation.z = info.rotation_z * Math.PI / 180;
				text.position.x = coords.x;
				text.position.y = coords.y + 1;
				text.position.z = coords.z;
				group.add(text);
				floors[floor].add(group);

				texts[info.id] = {'text':group,'rotz':text.rotation.z,'type':'group','coords':origCoords,'info':info};
			}else{
				var text3d = new THREE.TextGeometry( label, {
					font: font,
					size: info.text_size,
					height: 0.001,
					bevelEnabled: false,
					color: "rgba(255,0,0)"
				});
				text3d.center();

				var text = new THREE.Mesh( text3d, textMaterial );
				text.rotation.x = -90 * Math.PI / 180;
				text.rotation.z = info.rotation_z * Math.PI / 180;
				text.position.x = coords.x;
				text.position.y = coords.y + 1;
				text.position.z = coords.z;
				texts[info.id] = {'text':text,'rotz':text.rotation.z,'type':'text'};
				floors[floor].add( text );
			}

			//update point details
			obj.userData = info;
			map_points[info.id] = obj;
		}else{
			map_points[info.id] = obj;
		}
	}

	function showPointInfo(objectHit)
	{
		// $("#info .id").html("Point ID: " + objectHit.userData.id);
		// $("#info .point_x").val(objectHit.position.x);
		// $("#info .point_y").val(objectHit.position.y);
		// $("#info .point_z").val(objectHit.position.z);
		
		// $("#info .tenant_id").val(objectHit.userData.tenant_id);
		// $("#info #point_id").val(objectHit.userData.id);
		// $("#info .text_size").val(objectHit.userData.text_size);
		// $("#info .rotation_z").val(objectHit.userData.rotation_z);
		// $("#info .point_type").val(objectHit.userData.point_type);
		// $("#info .point_label").val(objectHit.userData.point_label);
		// $("#info .wrap_at").val(objectHit.userData.wrap_at > 0 ? 1: 0);
		// $("#info #customSwitchwrapat").prop('checked',objectHit.userData.wrap_at > 0 ? true: false);
		// $("#info .is_pwd").val(objectHit.userData.is_pwd > 0 ? 1: 0);
		// $("#info #customSwitchpwd").prop('checked',objectHit.userData.is_pwd > 0 ? true: false);
		// console.log(objectHit.userData);
		// pointMarkerHighlight.position.x = objectHit.position.x;
		// pointMarkerHighlight.position.y = objectHit.position.y-1;
		// pointMarkerHighlight.position.z = objectHit.position.z;
	}
	
	setUpMouseHandler(container,doMouseDown,doMouseMove,doMouseUp);
	setUpTouchHandler(container,doMouseDown,doMouseMove,doMouseUp);

	$(document).ready(function() {
		$.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

		$(".mouseaction").on('click',function() {
			$(this).addClass('mouseaction-selected');
			$(".mouseaction").not(this).removeClass('mouseaction-selected');
			$(this).find('input[type="radio"]').prop("checked", true);
			var action = $('input[name="action"]:checked').val();
			switch(action) {
				case 'rotate':
					mouseAction = ROTATE;
					controls.enabled = true;
				break;
				case 'drag_point':
					mouseAction = DRAG;
					controls.enabled = false;
				break;
				case 'add_point':
					mouseAction = ADD;
					controls.enabled = false;
				break;
				case 'delete_point':
					mouseAction = DELETE;
				break;
				case 'point_info':
					mouseAction = INFO;
				break;
				case 'single_link':
					mouseAction = LINK;
				break;
				case 'continous_link':
					mouseAction = LINKSINGLE;
					links = [];
				break;
			}
		});

		$('.btn-map').on('click', function() {
			$('.btn-map').removeClass('active');
			$(this).addClass('active');

			active_floor = $(this).data('floor_id');

			$.each(floors,function(index){
				this.visible = (index == active_floor);
			});

			// GET TENANTS ASSIGN FROM FLOOR
			$.get("/admin/site/tenant/get-tenants-per-floor/"+floor_id, function(data) { 
				$('#tenant_id').empty();
				$('#tenant_id').append('<option value="">Select Tenant</option>');
				$.each(data.data, function(key,val) {             
				$('#tenant_id').append('<option value="'+val.id+'">'+val.brand_name+'</option>');
				});
			});

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

		var container_height = $('.content-wrapper').height()-200;
		// $('.map-holder').css('height', container_height);
		$('.map-form-holder').css('height', container_height);

	});
</script>
<!-- END -->

@endpush