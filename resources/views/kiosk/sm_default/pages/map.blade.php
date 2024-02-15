<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Map</div>

<div class="map-canvas">
    <div id="canvas" class="canvas-canvas"></div>
</div>

<div class="MapBtn">
    <div class="container">
        <div class="row">
            <!-- Add Hidden Value -->
            <input type="hidden" class="direction-from" />
			<button type="button" id="btnresetmap" style="z-index:1000;display:none">Reset</button>
            <div class="d-flex justify-content-start" style="border-radius: 18px; box-shadow: 0 3px 6px rgb(0 0 0 / 0.16);">
                <div style="width: 423px; height: 62px !important;">
                    
                    <select id="tenant-select" class="form-control" style="width: 423px;">
                        <option value="0">Input Destination</option>
                        @foreach ($all_tenants as $tenant)
                            <option value="{{ $tenant['id'] }}">{{ $tenant['brand_name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button class="btn-pwd" id="btnpwdchange">
                        <svg class="svg-inline--fa fa-wheelchair fa-w-16 btn-pwd-icon" focusable="false" data-prefix="fa" data-icon="wheelchair" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M496.101 385.669l14.227 28.663c3.929 7.915.697 17.516-7.218 21.445l-65.465 32.886c-16.049 7.967-35.556 1.194-43.189-15.055L331.679 320H192c-15.925 0-29.426-11.71-31.679-27.475C126.433 55.308 128.38 70.044 128 64c0-36.358 30.318-65.635 67.052-63.929 33.271 1.545 60.048 28.905 60.925 62.201.868 32.933-23.152 60.423-54.608 65.039l4.67 32.69H336c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H215.182l4.572 32H352a32 32 0 0 1 28.962 18.392L438.477 396.8l36.178-18.349c7.915-3.929 17.517-.697 21.446 7.218zM311.358 352h-24.506c-7.788 54.204-54.528 96-110.852 96-61.757 0-112-50.243-112-112 0-41.505 22.694-77.809 56.324-97.156-3.712-25.965-6.844-47.86-9.488-66.333C45.956 198.464 0 261.963 0 336c0 97.047 78.953 176 176 176 71.87 0 133.806-43.308 161.11-105.192L311.358 352z"
                            ></path>
                        </svg>
                    </button>
                </div>

            </div>

            <div class="d-flex justify-content-start" style="margin-left: 20px; border-radius: 18px; box-shadow: 0 3px 6px rgb(0 0 0 / 0.16);">

                <div style="width: 161px;">
                    <select id="floor-select" class="form-control" style="width: 160px;">
                    @foreach ($site_floors as $floor)
                        @if($building_count > 1)         
                            <option value="{{ $floor->id }}">{{ $floor->building_floor_name }}</option>
                        @else
                            <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>

                <div>
                    <a class="mapminus btn btn-prestige-rounded2 my-auto" style="background-color: #ffffff; border-radius: 0px !important; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; height: 65px; border-left: 0px; color: #0030ff; width: 67px; height: 64px;" >
                        <svg class="svg-inline--fa fa-minus fa-w-14" style="font-size: 26px;" focusable="false" data-prefix="fa" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                        </svg>
                    </a>
                </div>

                <div>
                    <a class="mapplus btn btn-prestige-rounded2 my-auto" style="background-color: #ffffff; border-radius: 0px !important; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; height: 65px; border-left: 0px; color: #0030ff; width: 67px; height: 64px;" >
                        <svg class="svg-inline--fa fa-plus fa-w-14" style="font-size: 26px;" focusable="false" data-prefix="fa" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" ></path>
                        </svg>
                    </a>
                </div>

                <div>
                    <a class="mapexpand btn btn-prestige-rounded3 my-auto" style="background-color: #ffffff; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-top-right-radius: 18px; height: 65px; border-bottom-right-radius: 18px; border-bottom: 1px solid #aaa; border-left: 0px; color: #0030ff; width: 67px; height: 64px;" >
                        <svg class="svg-inline--fa fa-expand fa-w-14" style="font-size: 26px;" focusable="false" data-prefix="fa" data-icon="expand" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M0 180V56c0-13.3 10.7-24 24-24h124c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H64v84c0 6.6-5.4 12-12 12H12c-6.6 0-12-5.4-12-12zM288 44v40c0 6.6 5.4 12 12 12h84v84c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12V56c0-13.3-10.7-24-24-24H300c-6.6 0-12 5.4-12 12zm148 276h-40c-6.6 0-12 5.4-12 12v84h-84c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h124c13.3 0 24-10.7 24-24V332c0-6.6-5.4-12-12-12zM160 468v-40c0-6.6-5.4-12-12-12H64v-84c0-6.6-5.4-12-12-12H12c-6.6 0-12 5.4-12 12v124c0 13.3 10.7 24 24 24h124c6.6 0 12-5.4 12-12z" ></path>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ URL::to('themes/three.js/examples/js/libs/jszip.min.js') }}"></script>
<script src="{{ URL::to('themes/custom-js/text-wrapper-3js.js') }}"></script>
<script type="module">

    var default_floor = 0;
	var default_building = 0;
	var show_location = 1;

    import * as THREE from "{{ URL::to('themes/three.js/build/three.module.js') }}";
	import Stats from "{{ URL::to('themes/three.js/examples/jsm/libs/stats.module.js') }}";
	import { KMZLoader } from "{{ URL::to('themes/three.js/examples/jsm/loaders/KMZLoader.js') }}";
	import { OBJLoader } from "{{ URL::to('themes/three.js/examples/jsm/loaders/OBJLoader.js') }}";
	import { OrbitControls } from "{{ URL::to('themes/three.js/examples/jsm/controls/OrbitControls2.js?v=10') }}";
	import { GeometryUtils } from "{{ URL::to('themes/three.js/examples/jsm/utils/GeometryUtils.js') }}";

	import { Line2 } from "{{ URL::to('themes/three.js/examples/jsm/lines/Line2.js') }}";
	import { LineMaterial } from "{{ URL::to('themes/three.js/examples/jsm/lines/LineMaterial.js') }}";
	import { LineGeometry } from "{{ URL::to('themes/three.js/examples/jsm/lines/LineGeometry.js') }}";

    var camera, cameraTarget, scene, renderer, controls;
	var rotationPoint;
	var texts = {}, font;
	var buildings = {};
    var floors = {};
	var floors_label = {};
	var bldg_label = {};
	var boundaries = {};
	var spritePinFrom, spritePinTo, spriteUp;
	var viewAngle = @php echo $site_config->view_angle; @endphp; //60 CHANGE TILT
    var site_config = @php echo $site_config; @endphp;

    	//custom
	var total_floors;
    var pointMarker;
    var linePathGroup = {};

    var escalator_down, escalator_up, runner, runner2, runner3, door_sprite, marker_origin, marker_destination;

    var lineMaterial = new THREE.LineBasicMaterial({
        color: "rgb(255,0,0)", linewidth: 10
    });

    var clock = new THREE.Clock();

    //ball markert
	var theball;
	// ball movements
	var movements = [];
	var movementstmp = [];
	var currentpos = 1;
	var level_end_points = [];
    var playerSpeed = @php echo $site_config->player_speed; @endphp; // 0.6 DEFAULT VALUE

    var startMarker, endMarker, centerMarker, spotlightMarker, topLeftMarker, topRightMarker, bottomLeftMarker, bottomRightMarker;

	var scaleVector = new THREE.Vector3();

	var transitioning = 0;

    var site_floors = @php echo $site_floors; @endphp;
    var site_maps = @php echo $site_maps; @endphp;
    var map_points = @php echo $map_points; @endphp;
    var map_tenants = @php echo $map_tenants; @endphp;
    var amenities = @php echo $amenities; @endphp;

    var amenities_icon = [];
    var sm_icon = [];

    var inputDirection;

    var building_change_user = 1;
	var kmz_loaded = 0;
	var kmz_count = @php echo count($site_maps); @endphp;

	var default_zoom = {};

    var KIOSK_VIEW_ANGLE = @php echo $site_config->view_angle; @endphp; //60 CHANGE TILT
    var KIOSK_FLOOR_ID = @php echo $site_config->map_details['site_building_level_id']; @endphp;
    var FLR_ANI_HEIGHT = @php echo $site_config->floor_animation_height; @endphp;
    var FLR_LBL_HEIGHT = @php echo $site_config->floor_label_height; @endphp;
    var FLR_LBL_SPACE = @php echo $site_config->floor_label_space; @endphp;
    var BLDG_ANI_HEIGHT = @php echo $site_config->building_animation_height; @endphp;
    var BLDG_LBL_HEIGHT = @php echo $site_config->building_label_height; @endphp;
    var BLDG_LBL_SPACE = @php echo $site_config->building_label_space; @endphp;

    var KIOSK_ID = @php echo $site_config->origin_point; @endphp;
    var kiosk_zoom = @php echo $kiosk_zoom; @endphp;

    var floor_width = @php echo $floor_width; @endphp;
    var floor_height = @php echo $floor_height; @endphp;

    function init() {
        var container = document.getElementById('canvas');

        var w = container.offsetWidth;
		var h = container.offsetHeight;

		w = w > 0 ? w : 1450; 
		h = h > 0 ? h : 800;

		var w = 1450;
		var h = 800;

        /**setup camera*/
		var fov = 30; //35 //Camera frustum vertical field of view.
		var aspect  = w / h; 
		var near = 0.1;  //0.1, 1
		var far = 1000; //2000

        camera = new THREE.PerspectiveCamera( fov, aspect, near, far );
		camera.position.set( 300, 0, 300 );

        cameraTarget = new THREE.Vector3( 0, -0.25, 0 );
		camera.lookAt( cameraTarget );

        scene = new THREE.Scene();
		scene.background = new THREE.Color("rgb(255,255,255)");

        scene.add( new THREE.HemisphereLight( 0xffffff, 0x6a6a6a, 1.1 ) );

        spritePinFrom = new THREE.Sprite(
			new THREE.SpriteMaterial({
				map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/darker-v3-you-are-here-01.png') }}")
			})
		);

        spritePinFrom.position.set(0, @php echo $spritepinto_height; @endphp,0);
		spritePinFrom.scale.set(@php echo $pin_scale_x; @endphp, @php echo $pin_scale_y; @endphp, @php echo $pin_scale_z; @endphp); //ADD SCALE ON REMOVE ANIMATION
        scene.add(spritePinFrom);
		spritePinFrom.visible = false;

        spritePinTo = new THREE.Sprite(
			new THREE.SpriteMaterial({
				map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/darker-v3-you-are-here-02.png') }}")
			})
		);

        spritePinTo.position.set(0,@php echo $spritepinto_height; @endphp,0);
		spritePinTo.scale.set(@php echo $pin_scale_x; @endphp, @php echo $pin_scale_y; @endphp, @php echo $pin_scale_z; @endphp);
        scene.add(spritePinTo);
		spritePinTo.visible = false;

        $.each(amenities, function(aid, aicon) {
            amenities_icon[aid] = new THREE.Sprite(
                new THREE.SpriteMaterial({
                    map: new THREE.TextureLoader().load(aicon)
                })
            );
            amenities_icon[aid].visible = false;
			amenities_icon[aid].position.set(0, 0, 0);
			amenities_icon[aid].scale.set(@php echo $icon_scale_x; @endphp, @php echo $icon_scale_y; @endphp, @php echo $icon_scale_z; @endphp);
			scene.add(amenities_icon[aid]);
        }); 

        $.each(site_floors, function(index, floor) {
            floors[floor.id] = new THREE.Group(); 
            boundaries[floor.id] = [];

            scene.add(floors[floor.id]);

            linePathGroup[floor.id] = new THREE.Group();
            scene.add(linePathGroup[floor.id]);

            default_zoom[floor.id]  = site_config.default_zoom;
        }); 

        theball = new THREE.Mesh(
            new THREE.SphereGeometry( .5, 32, 32 ), 
            new THREE.MeshBasicMaterial( {color: "rgba(248,165,27)"} )
        );			
		theball.position.set(0,10,0);
		theball.visible = false;
		scene.add(theball);

        pointMarker = new THREE.Mesh(
			new THREE.SphereGeometry( 1,32,32), 
			new THREE.MeshBasicMaterial( {color: "rgba(255,0,0)"} )
		);
		pointMarker.name = "point";
		pointMarker.position.y = 10;

		startMarker = pointMarker.clone();
		endMarker = pointMarker.clone();
		centerMarker = new THREE.Mesh(
			new THREE.SphereGeometry( 1,32,32), 
			new THREE.MeshBasicMaterial( {color: "rgba(255,255,0)"} )
		);

		spotlightMarker = new THREE.Mesh(
			new THREE.SphereGeometry( 1,32,32), 
			new THREE.MeshBasicMaterial( {color: "rgba(0,255,0)"} )
		);
		spotlightMarker.position.x = -50;
		spotlightMarker.position.y = 50;
		spotlightMarker.position.z = 50;

		let cornerMarker = new THREE.Mesh(
			new THREE.SphereGeometry( 1,32,32), 
			new THREE.MeshBasicMaterial( {color: "rgba(0,0,255)"} )
		);
		cornerMarker.position.y = 7;
		centerMarker.position.y = 7;

		topLeftMarker = cornerMarker.clone();
		bottomLeftMarker = cornerMarker.clone();
		bottomRightMarker = cornerMarker.clone();

        $.each(site_maps, function(index, map) {
            loadKmz(map.site_building_level_id, map.map_file_path, ((map.site_building_level_id) == KIOSK_FLOOR_ID));
        });

        // ADJUST SPRITE ESCALATOR DOWN ROTATION
		var runnerTexture = new THREE.ImageUtils.loadTexture("{{ URL::to('themes/sm_default/images/escalator-down-sprite.png') }}");	
        escalator_down = new TextureAnimator( runnerTexture, 5, 1, 5, 100 ); // texture, #horiz, #vert, #total, duration.
		var runnerMaterial = new THREE.MeshBasicMaterial( { map: runnerTexture, transparent: true } );
        var runnerGeometry = new THREE.PlaneGeometry(floor_width, floor_height, 1, 1);//width, height, widthSegments, heightSegments
        runner = new THREE.Mesh(runnerGeometry, runnerMaterial);
		runner.position.set(0, FLR_ANI_HEIGHT, 0);
        runner.rotation.x = -90 * Math.PI / 180;

        if(KIOSK_VIEW_ANGLE == 180 || KIOSK_VIEW_ANGLE == 0) {
			runner.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 45) {
			runner.rotation.z = (45 * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 135) {
			runner.rotation.z = (135 * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 315) {
			runner.rotation.z = (315 * Math.PI / 180);
        }
		else {
			runner.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
        }

        runner.visible = false;
		scene.add(runner);

        // ADJUST SPRITE ESCALATOR UP ROTATION
		var runnerTexture2 = new THREE.ImageUtils.loadTexture("{{ URL::to('themes/sm_default/images/escalator-up-sprite.png') }}");
		escalator_up = new TextureAnimator( runnerTexture2, 5, 1, 5, 100 ); // texture, #horiz, #vert, #total, duration.
		var runnerMaterial2 = new THREE.MeshBasicMaterial( { map: runnerTexture2, transparent: true} );
        var runnerGeometry2 = new THREE.PlaneGeometry(floor_width, floor_height, 1, 1);
		runner2 = new THREE.Mesh(runnerGeometry2, runnerMaterial2);
		runner2.position.set(0, FLR_ANI_HEIGHT, 0);
        runner2.rotation.x = -90 * Math.PI / 180;

        if(KIOSK_VIEW_ANGLE == 180 || KIOSK_VIEW_ANGLE == 0) {
			runner2.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 45) {
			runner2.rotation.z = (45 * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 135) {
			runner2.rotation.z = (135 * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 315) {
			runner2.rotation.z = (315 * Math.PI / 180);
        }
		else {
			runner2.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
        }
		
		runner2.visible = false;
		scene.add(runner2);

        // ADJUST SPRITE INTEBUILDING
		var runnerTexture3 = new THREE.ImageUtils.loadTexture("{{ URL::to('themes/sm_default/images/building-sprite.png') }}");	
		door_sprite = new TextureAnimator( runnerTexture3, 5, 1, 5, 100 ); // texture, #horiz, #vert, #total, duration.
		runner3 = new THREE.Mesh(new THREE.PlaneGeometry(8, 2, 1, 1), new THREE.MeshBasicMaterial( { map: runnerTexture3,transparent: true} ));
		runner3.position.set(0, BLDG_ANI_HEIGHT, 0);
        runner3.rotation.x = -90 * Math.PI / 180;

        if(KIOSK_VIEW_ANGLE == 180 || KIOSK_VIEW_ANGLE == 0) {
			runner3.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 45) {
			runner3.rotation.z = (45 * Math.PI / 180);
        }
		else if(KIOSK_VIEW_ANGLE == 135) {
            runner3.rotation.z = (135 * Math.PI / 180);
        }			
		else if(KIOSK_VIEW_ANGLE == 315) {
			runner3.rotation.z = (315 * Math.PI / 180);
        }
		else {
			runner3.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
        }

        runner3.visible =false;
		runner3.scale.set(3,4, 0 );
		scene.add(runner3);

        /** renderer */
        renderer = new THREE.WebGLRenderer( { antialias: true } );
        renderer.setPixelRatio( window.devicePixelRatio );
        renderer.outputEncoding = THREE.sRGBEncoding;
        renderer.setSize(w,h);
		container.appendChild(renderer.domElement);

        // Build the controls.
		controls = new OrbitControls( camera, renderer.domElement );
		controls.maxPolarAngle = viewAngle * Math.PI / 180;
		controls.minPolarAngle = viewAngle * Math.PI / 180;
		controls.minDistance = 40; //max zoom in
		controls.maxDistance = 500; //max zoom out

		controls.touches.ONE = THREE.TOUCH.PAN;
		controls.touches.TWO = THREE.TOUCH.DOLLY_ROTATE;

		controls.enableRotate = false;

        var kiosk = map_points[KIOSK_ID];
		var coords = new THREE.Vector3(kiosk.point_x, 6,kiosk.point_z);

        coords.x = kiosk.point_x;
		coords.y = kiosk.point_y;
		coords.z = kiosk.point_z;
		scene.worldToLocal(coords);
		controls.target = coords;
		//controls.update();

		controls.minAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180 ;
		controls.maxAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180;

		spritePinFrom.position.x = kiosk.point_x;
		spritePinFrom.position.z = kiosk.point_z;
		
		spritePinFrom.visible = true;
		spritePinFrom.userData = {'floor':kiosk.building_level_id}

		camera.position.y = 100;
		camera.position.x = coords.x;
		camera.position.z = coords.z;

		controls.addEventListener( 'change', controlChange );
		controls.update();

		controls.minAzimuthAngle = -Infinity;
		controls.maxAzimuthAngle = Infinity;

		window.addEventListener( 'resize', onWindowResize, false );
    }

    function loadKmz(index,map_url,is_default)
	{
		/** load map */
		var kmzloader = new KMZLoader();
		kmzloader.load( map_url, function ( kmz ) {
			
			kmz.scene.position.set(10, 0.2  ,5);
			floors[index].add( kmz.scene );
			floors[index].visible = (is_default == true) ? true : false;

			var container = document.getElementById('canvas');
			var w = container.offsetWidth;
			var h = container.offsetHeight;
			
			kmz_loaded++;
			if(kmz_loaded == Object.keys(floors).length)
			{
				$(document).trigger('kmz_loaded');
			}
		});

        if(is_default == true)
        {
            default_floor = index;
        }
	}

    function TextureAnimator(texture, tilesHoriz, tilesVert, numTiles, tileDispDuration) 
	{	
		// note: texture passed by reference, will be updated by the update function.
			
		this.tilesHorizontal = tilesHoriz;
		this.tilesVertical = tilesVert;
		// how many images does this spritesheet contain?
		//  usually equals tilesHoriz * tilesVert, but not necessarily,
		//  if there at blank tiles at the bottom of the spritesheet. 
		this.numberOfTiles = numTiles;
		texture.wrapS = texture.wrapT = THREE.RepeatWrapping; 
		texture.repeat.set( 1 / this.tilesHorizontal, 1 / this.tilesVertical );

		// how long should each image be displayed?
		this.tileDisplayDuration = tileDispDuration;

		// how long has the current image been displayed?
		this.currentDisplayTime = 0;

		// which image is currently being displayed?
		this.currentTile = 0;
			
		this.update = function( milliSec )
		{
			this.currentDisplayTime += milliSec;
			while (this.currentDisplayTime > this.tileDisplayDuration)
			{
				this.currentDisplayTime -= this.tileDisplayDuration;
				this.currentTile++;
				if (this.currentTile == this.numberOfTiles)
					this.currentTile = 0;
				var currentColumn = this.currentTile % this.tilesHorizontal;
				texture.offset.x = currentColumn / this.tilesHorizontal;
				var currentRow = Math.floor( this.currentTile / this.tilesHorizontal );
				texture.offset.y = currentRow / this.tilesVertical;
			}
		};
	}

    function controlChange()
	{
		var c = this;
		
		$.each(texts,function(){
			if(this.rotz == 0 && (c.getAzimuthalAngle() > 1.6 || c.getAzimuthalAngle() < -1.6))	
			{
				this.text.rotation.z = (180 * Math.PI / 180) //- this.rotz;
			}
			else if(c.getAzimuthalAngle() < -0.0)
			{
				this.text.rotation.z = (360 * Math.PI / 180) - this.rotz;
			}
			else if(c.getAzimuthalAngle() > 4)
			{
				this.text.rotation.z = (360 * Math.PI / 180) - this.rotz;
			}else{				
				this.text.rotation.z = this.rotz;
			}
		});
	}

    function onWindowResize() 
    {
		var container = document.getElementById('canvas');
		var w = container.offsetWidth;
		var h = container.offsetHeight;

		camera.aspect = w / h;
		camera.updateProjectionMatrix();

		renderer.setSize( w, h );
	}

    function loadFont()
    {
        var fontloader = new THREE.FontLoader();
        fontloader.load("{{ URL::to('themes/three.js/examples/fonts/helvetiker_bold.typeface.json') }}", function ( response ) {
			font = response
			showTenantNames()
		});
    }

    function showTenantNames()
	{
        var textMaterial = new THREE.MeshBasicMaterial( { color: 0x000000, overdraw: true } );
		var coords = new THREE.Vector3(0,0,0);

        @foreach ($site_floors as $floor)
            var text3d@php echo $floor->id; @endphp = new THREE.TextGeometry('@php echo "Proceed to ".$floor->name; @endphp', {
                font: font,
                size: 0.9,
                height: 0.001,
                bevelEnabled: false,
                color: "rgba(255,0,0)"
            });

            var textfl@php echo $floor->id; @endphp = new THREE.Mesh( text3d@php echo $floor->id; @endphp, textMaterial );
			textfl@php echo $floor->id; @endphp.position.set(0,20,0);
			// set scaling
			textfl@php echo $floor->id; @endphp.scale.set(@php echo $floor_font_scale_x; @endphp, @php echo $floor_font_scale_y; @endphp, @php echo $floor_font_scale_z; @endphp);			
            textfl@php echo $floor->id; @endphp.rotation.x = -90 * Math.PI / 180;

            if(KIOSK_VIEW_ANGLE == 180 || KIOSK_VIEW_ANGLE == 0) {
				textfl@php echo $floor->id; @endphp.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
            }
			else if(KIOSK_VIEW_ANGLE == 45) {
				textfl@php echo $floor->id; @endphp.rotation.z = (45 * Math.PI / 180);
            }
			else if(KIOSK_VIEW_ANGLE == 135) {
				textfl@php echo $floor->id; @endphp.rotation.z = (135 * Math.PI / 180);
            }
			else if(KIOSK_VIEW_ANGLE == 315) {
				textfl@php echo $floor->id; @endphp.rotation.z = (315 * Math.PI / 180);
            }
			else {
				textfl@php echo $floor->id; @endphp.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
            }

            textfl@php echo $floor->id; @endphp.visible = false;
			floors_label[@php echo $floor->id; @endphp] = textfl@php echo $floor->id; @endphp;
			scene.add(textfl@php echo $floor->id; @endphp);
			textfl@php echo $floor->id; @endphp.position.z = 200;

            var newBldText = ['Proceed to','@php echo $floor->building_name; @endphp'];
			
			var text3db@php echo $floor->id; @endphp = new THREE.TextGeometry(newBldText.join("\n"), {
                font: font,
                size: 0.9,
                height: 0.001,
                bevelEnabled: false,
                color: "rgba(255,0,0)"
            });

            var textbl@php echo $floor->id; @endphp = new THREE.Mesh( text3db@php echo $floor->id; @endphp, textMaterial );
			textbl@php echo $floor->id; @endphp.position.set(0,2,0);
			// set scaling
			textbl@php echo $floor->id; @endphp.scale.set(@php echo $floor_font_scale_x; @endphp, @php echo $floor_font_scale_y; @endphp, @php echo $floor_font_scale_z; @endphp);
			//rotate based on settings
            textbl@php echo $floor->id; @endphp.rotation.x = -90 * Math.PI / 180;

            if(KIOSK_VIEW_ANGLE == 180 || KIOSK_VIEW_ANGLE == 0) {
				textbl@php echo $floor->id; @endphp.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
            }
			else if(KIOSK_VIEW_ANGLE == 45) {
				textbl@php echo $floor->id; @endphp.rotation.z = (45 * Math.PI / 180);
            }
			else if(KIOSK_VIEW_ANGLE == 135) {
				textbl@php echo $floor->id; @endphp.rotation.z = (135 * Math.PI / 180);			
            }
			else if(KIOSK_VIEW_ANGLE == 315) {
				textbl@php echo $floor->id; @endphp.rotation.z = (315 * Math.PI / 180);			                
            }
			else {
				textbl@php echo $floor->id; @endphp.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);                
            }

            textbl@php echo $floor->id; @endphp.visible = false;
			
			bldg_label[@php echo $floor->id; @endphp] = textbl@php echo $floor->id; @endphp;
			scene.add(textbl@php echo $floor->id; @endphp);

        @endforeach

        $.each(map_points,function(){
            var info = this;
			coords.x = info.point_x;
			coords.y = info.point_y;
			coords.z = info.point_z
			scene.worldToLocal(coords);

            if(info.tenant_id > 0)
			{
                if(map_tenants.hasOwnProperty(info.tenant_id)) //tenant
				{
                    if(map_tenants[info.tenant_id].brand_name == "SM SUPERMARKET"){ 
                        sm_icon[0] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Supermarket500.png') }}")
                            })
                        );
                        sm_icon[0].visible = true;
                        sm_icon[0].position.set(coords.x, coords.y+12, coords.z);
                        sm_icon[0].scale.set(30,30,1);

                        // MALL ID CONDITION HERE
                        // OR MAKE ICON SIZE IN DATABASE

                        floors[info.building_level_id].add(sm_icon[0]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "THE SM STORE") {
                        sm_icon[1] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Store500.png') }}")
                            })
                        );
                        sm_icon[1].visible = true;
                        sm_icon[1].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[1].scale.set(40,40,1);

                        // MALL ID CONDITION HERE
                        // OR MAKE ICON SIZE IN DATABASE

                        floors[info.building_level_id].add(sm_icon[1]); 
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM HYPERMARKET"){
                        sm_icon[2] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Hypermarket500.png') }}")
                            })
                        );
                        sm_icon[2].visible = true;
                        sm_icon[2].position.set(coords.x, coords.y+25, coords.z);
                        sm_icon[2].scale.set(80,80,1);

                        // MALL ID CONDITION HERE
                        // OR MAKE ICON SIZE IN DATABASE

                        floors[info.building_level_id].add(sm_icon[2]);
                        
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM BOWLING" || map_tenants[info.tenant_id].brand_name == "SM BOWLING CENTER"){ 
                        sm_icon[3] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Bowling500.png') }}")
                            })
                        );
                        sm_icon[3].visible = true;
                        sm_icon[3].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[3].scale.set(18,18,1);

                        // MALL ID CONDITION HERE
                        // OR MAKE ICON SIZE IN DATABASE

                        floors[info.building_level_id].add(sm_icon[3]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM CINEMA LOBBY" || map_tenants[info.tenant_id].brand_name == "SM CINEMA LOBBY 1" || map_tenants[info.tenant_id].brand_name == "SM CINEMA LOBBY 2"){
                        sm_icon[4] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Cinema500.png') }}")
                            })
                        );
                        sm_icon[4].visible = true;
                        sm_icon[4].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[4].scale.set(18,18,1);

                        // MALL ID CONDITION HERE
                        // OR MAKE ICON SIZE IN DATABASE

                        floors[info.building_level_id].add(sm_icon[4]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM DIRECTOR'S CLUB LOBBY"){ 
                        sm_icon[5] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/Directors Club500.png') }}")
                            })
                        );
                        sm_icon[5].visible = true;
                        sm_icon[5].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[5].scale.set(40,40,1);
                        floors[info.building_level_id].add(sm_icon[5]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM FOOD COURT"){
                        sm_icon[6] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Foodcourt500.png') }}")
                            })
                        );
                        sm_icon[6].visible = true;
                        sm_icon[6].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[6].scale.set(25,25,1);

                        // MALL ID CONDITION HERE
                        // OR MAKE ICON SIZE IN DATABASE PER MALL

                        floors[info.building_level_id].add(sm_icon[6]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "IMAX"){ 
                        sm_icon[7] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/Map_IMAX500.png') }}")
                            })
                        );
                        sm_icon[7].visible = true;
                        sm_icon[7].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[7].scale.set(19,19,1);
                        floors[info.building_level_id].add(sm_icon[7]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM FOOD HALL"){ 
                        sm_icon[8] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/Megamall food hall500.png') }}")
                            })
                        );
                        sm_icon[8].visible = true;
                        sm_icon[8].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[8].scale.set(20,20,1);
                        floors[info.building_level_id].add(sm_icon[8]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM ICE SKATING"){ 
                        sm_icon[9] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Skating Map500.png') }}")
                            })
                        );
                        sm_icon[9].visible = true;
                        sm_icon[9].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[9].scale.set(25,25,1);
                        floors[info.building_level_id].add(sm_icon[9]);
                    }
                    else if(map_tenants[info.tenant_id].brand_name == "SM STORYLAND"){ 
                        sm_icon[10] = new THREE.Sprite(
                            new THREE.SpriteMaterial({
                                map: new THREE.TextureLoader().load("{{ URL::to('themes/sm_default/images/sm/SM Storyland.png') }}")
                            })
                        );
                        sm_icon[10].visible = true;
                        sm_icon[10].position.set(coords.x, coords.y+6, coords.z);
                        sm_icon[10].scale.set(40,40,1);
                        
                        floors[info.building_level_id].add(sm_icon[10]); 
                    }
                    else{ 
                        let label = map_tenants[info.tenant_id].brand_name ? map_tenants[info.tenant_id].brand_name :  (info.point_label ? info.point_label : map_tenants[info.tenant_id].brand_name);
                        if(info.wrap_at > 0 && label.length > info.wrap_at)
						{
							var cutText = label;
							var origCoords = coords;
							info.wrap_at = label.split(' ').reduce(
									function (a, b) {
										return a.length > b.length ? a : b;
									}
								).length;
							var newText = [];
							
							while(cutText.length > info.wrap_at && cutText.indexOf(' ') >= 0){
								if( cutText.charAt(info.wrap_at) === ' ' ) {
									var line = cutText.substring(0, info.wrap_at);
								cutText = cutText.slice( info.wrap_at );
								}else{
									var counter = 1;
									while( cutText.charAt(info.wrap_at - counter) !== ' ' ) {
										counter++;
									}
									// cut line at space
									var line = cutText.substring( 0,info.wrap_at - counter );
									cutText = cutText.slice( info.wrap_at - counter );
								}
	
								line = line.trim();
								newText.push(line);
								cutText = cutText.trim();
							}
							cutText = cutText.trim();
							newText.push(cutText);
							
							var text3d = new THREE.TextGeometry( newText.join("\n"), {
								font: font,
								size: info.text_size,
								height: 0.001,
								bevelEnabled: false,
								color: "rgba(255,0,0)"
							});
							text3d.center(); 

							var text = new THREE.Mesh( text3d, textMaterial );
							text.rotation.x = -90 * Math.PI / 180;

                            if(KIOSK_VIEW_ANGLE >= 180) {
                                text.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
                            }
                            else if(KIOSK_VIEW_ANGLE > 0 ) {
                                text.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
                            }							
	
							text.position.x = coords.x;
							text.position.y = coords.y + 1;
							text.position.z = coords.z;
						
							texts[info.id] = {'text':text,'rotz':text.rotation.z,'info':info};
							
							// for 3D Text [Adds Tenant name on map]
							floors[info.building_level_id].add(text);							
                        }
                        else{
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
								
							if(KIOSK_VIEW_ANGLE >= 180) {
                                text.rotation.z = ((360 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
                            }									
                            else if(KIOSK_VIEW_ANGLE > 0 ) {
                                text.rotation.z = ((180 - KIOSK_VIEW_ANGLE) * Math.PI / 180);
                            }
		
							text.position.x = coords.x;
							text.position.y = coords.y + 1;
							text.position.z = coords.z;
						
							// for 3D Text [Adds Tenant name on map]
							texts[info.id] = {'text':text,'rotz':text.rotation.z,'info':info};
							floors[info.building_level_id].add(text);
                        }
                    }
                }
            }
            else if(info.point_type == 5){ //boundary
				var obj = pointMarker.clone();
				obj.position.x = coords.x;
				obj.position.y = coords.y;
				obj.position.z = coords.z;
				obj.userData = info;
				obj.visible = false;
				scene.add( obj );
				boundaries[info.building_level_id].push(obj);

			}else if(amenities.hasOwnProperty(info.point_type)){ //amenity
				coords.x = info.point_x;
				coords.y = info.point_y;
				coords.z = info.point_z
				scene.worldToLocal(coords);

				var obj = amenities_icon[info.point_type].clone();
				obj.visible = true;
				obj.position.x = coords.x;
				obj.position.z = coords.z;
				obj.position.y = coords.y;
				floors[info.building_level_id].add(obj);           
                   
			}
        });
    }

    function update() {
		camera.updateProjectionMatrix();

		var delta = clock.getDelta(); 
		escalator_down.update(1000 * delta);
		escalator_up.update(1000 * delta);
		door_sprite.update(1000 * delta);
	}

    function animate() {
		requestAnimationFrame( animate ); //ERROR

		var scaleFactor = 35;
		var y = 10;
		
		var scale = scaleVector.subVectors(floors[$("#floor-select").val()].position, camera.position).length() / scaleFactor;		
		var scaleFactor2 = 70;

		var scale2 = scaleVector.subVectors(floors[$("#floor-select").val()].position, camera.position).length() / scaleFactor2;		
		theball.y = y + (scale2 / 6); //REMOVE FOR SCALE SPRITE SCALING
		theball.scale.set(scale2,scale2,scale2); //REMOVE FOR SCALE SPRITE SCALING

		update();
		render(); //ERROR		
	}

	function distanceVector(x0,y0,z0,x1,y1,z1){

		var deltaX = x1 - x0;
		var deltaY = y1 - y0;
		var deltaZ = z1 - z0;

		var distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY + deltaZ * deltaZ);

		return distance;
	}

    function render() {
        renderer.render( scene, camera );
		var nav_zoom_level = 1;
		var move_top = 0;

        if ( movements.length > 0 ) {
            if(currentpos == 1 && level_end_points.hasOwnProperty(movements[currentpos].l) && level_end_points[movements[currentpos].l].length == 2)
			{
                var start_point = level_end_points[movements[currentpos].l][0];
				var end_point = level_end_points[movements[currentpos].l][1];
				var pointa = new THREE.Vector3(start_point.point_x,start_point.point_y,start_point.point_z);
				var pointb = new THREE.Vector3(end_point.point_x,end_point.point_y,end_point.point_z);

				startMarker.position.x = pointa.x;
				startMarker.position.y = pointa.y;
				startMarker.position.z = pointa.z;

				endMarker.position.x = pointb.x;
				endMarker.position.y = pointb.y;
				endMarker.position.z = pointb.z

				var markerDistance = distanceVector(pointa.x,pointa.y,pointa.z,pointb.x,pointb.y,pointb.z);

                $.each(kiosk_zoom, function(index, zoom) {
                    if(movements[currentpos].l == index){
						nav_zoom_level = zoom.zoomratio;
						move_top = zoom.movetop;
					}
                });

                fitCameraToScreen(nav_zoom_level, move_top);                
            }

			console.log(movements[currentpos].l);
			console.log($("#floor-select").val());
            if(movements[currentpos].l != $("#floor-select").val())
			{
                if(transitioning  == 0)
				{
					console.log('here 2');

                    transitioning = 1;

                    if(movements[currentpos-1] && movements[currentpos].b != movements[currentpos-1].b && @php echo $building_count; @endphp > 1)
					{
                        theball.visible = false;
						runner3.visible = true;

                        if(KIOSK_VIEW_ANGLE == 180) {
                            runner3.position.set(theball.position.x-18, BLDG_ANI_HEIGHT, theball.position.z);
                        }
                        else if(KIOSK_VIEW_ANGLE == 0) {
                            runner3.position.set(theball.position.x-10, BLDG_ANI_HEIGHT, theball.position.z);
                        }
                        else if(KIOSK_VIEW_ANGLE == 270) {
                            runner3.position.set(theball.position.x-15, BLDG_ANI_HEIGHT, theball.position.z);
                        }
                        else{
                            runner3.position.set(theball.position.x, BLDG_ANI_HEIGHT, theball.position.z);
                        }

                        bldg_label[movements[currentpos].l].visible = true;
                        //ADJUST BUILDING LABEL INTERBUILDING POSITION
						//default bldg label
						if(KIOSK_VIEW_ANGLE == 270) {
							// to accept negative value for bldg_lbl_space
                            bldg_label[movements[currentpos].l].position.set(theball.position.x, BLDG_LBL_HEIGHT,theball.position.z-BLDG_LBL_SPACE);
						}
                        else if(KIOSK_VIEW_ANGLE == 90) {
							bldg_label[movements[currentpos].l].position.set(theball.position.x, BLDG_LBL_HEIGHT,theball.position.z+BLDG_LBL_SPACE);
						}
                        else if(KIOSK_VIEW_ANGLE == 180) {
							// to accept negative value for bldg_lbl_space
                            bldg_label[movements[currentpos].l].position.set(theball.position.x+BLDG_LBL_SPACE, BLDG_LBL_HEIGHT,theball.position.z);
						}
                        else if(KIOSK_VIEW_ANGLE == 45) {
							bldg_label[movements[currentpos].l].position.set(theball.position.x-7, BLDG_LBL_HEIGHT,theball.position.z+6);
						}
                        else if(KIOSK_VIEW_ANGLE == 135) {
							bldg_label[movements[currentpos].l].position.set(theball.position.x+7, BLDG_LBL_HEIGHT,theball.position.z+8);
						}
                        else if(KIOSK_VIEW_ANGLE == 315) {
							bldg_label[movements[currentpos].l].position.set(theball.position.x-7, BLDG_LBL_HEIGHT,theball.position.z-8);
						}
                        else{
							bldg_label[movements[currentpos].l].position.set(theball.position.x- BLDG_LBL_SPACE, BLDG_LBL_HEIGHT,theball.position.z);
						}
                    }
                    else {
						console.log('here 3');

                        theball.visible = false;
                        if(movements[currentpos].l > $("#floor-select").val())
						{
							runner2.visible = true;                            
                            var temp_total_floors = total_floors;

                            if(level_end_points[movements[currentpos].l].length == 1) {
                                
                                if ( total_floors > 1 ) {
                                    //skipping floor label
									if(KIOSK_VIEW_ANGLE == 270)
									{
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x,FLR_LBL_HEIGHT,theball.position.z-FLR_LBL_SPACE);
									}
                                    else if(KIOSK_VIEW_ANGLE == 90) {
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x,FLR_LBL_HEIGHT,theball.position.z+FLR_LBL_SPACE);
									}
                                    else if(KIOSK_VIEW_ANGLE == 180) {
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l+(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l+(total_floors-temp_total_floors)].position.set(theball.position.x+FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z);
									}
                                    else if(KIOSK_VIEW_ANGLE == 45) {
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l].visible = true;
										floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z+5);
									}
                                    else if(KIOSK_VIEW_ANGLE == 135) {
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l].visible = true;
										floors_label[movements[currentpos].l].position.set(theball.position.x+5,FLR_LBL_HEIGHT,theball.position.z+5);
									}
                                    else if(KIOSK_VIEW_ANGLE == 315) {
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l].visible = true;
										floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z-4);
									}else{
										runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l+(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l+(total_floors-temp_total_floors)].position.set(theball.position.x-FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z + 1);
									}
                                }

                            }
                            else {
                                if(KIOSK_VIEW_ANGLE == 270) {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x,FLR_LBL_HEIGHT,theball.position.z-FLR_LBL_SPACE);
                                }
                                else if(KIOSK_VIEW_ANGLE == 90) {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x,FLR_LBL_HEIGHT,theball.position.z+FLR_LBL_SPACE);
                                }
                                else if(KIOSK_VIEW_ANGLE == 180) {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x+FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z);
                                }
                                else if(KIOSK_VIEW_ANGLE == 45) {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z+5);
                                }
                                else if(KIOSK_VIEW_ANGLE == 135) {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x+5,FLR_LBL_HEIGHT,theball.position.z+5);
                                }
                                else if(KIOSK_VIEW_ANGLE == 315) {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z-4);
                                }
                                else {
                                    runner2.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
                                    floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x-FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z + 1);
                                }
                            }

                            console.log('Level end point: '+level_end_points[movements[currentpos].l].length);

                        }

                        if(movements[currentpos].l < $("#floor-select").val())
						{
							runner.visible = true;
							
							var temp_total_floors = total_floors;
							
							// for skipping floor label (going down)
							if(level_end_points[movements[currentpos].l].length == 1){
							
								if ( total_floors > 1 ) {
									//default floor label
									if(KIOSK_VIEW_ANGLE == 270) {
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x,FLR_LBL_HEIGHT,theball.position.z-FLR_LBL_SPACE);
									}
                                    else if(KIOSK_VIEW_ANGLE == 90) {
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x,FLR_LBL_HEIGHT,theball.position.z+FLR_LBL_SPACE);
									}
                                    else if(KIOSK_VIEW_ANGLE == 180) {
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x+FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z+0);
									}
                                    else if(KIOSK_VIEW_ANGLE == 45) {
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l].visible = true;
										floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z+5);
									}
                                    else if(KIOSK_VIEW_ANGLE == 135) {
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l].visible = true;
										floors_label[movements[currentpos].l].position.set(theball.position.x+5,FLR_LBL_HEIGHT,theball.position.z+5);
									}
                                    else if(KIOSK_VIEW_ANGLE == 315) {
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l].visible = true;
										floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z-4);
									}
                                    else{
										runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].visible = true;
										floors_label[movements[currentpos].l-(total_floors-temp_total_floors)].position.set(theball.position.x-FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z -1);
									}
								}
								
							}else {							
                                //ADJUST FLOOR LABEL
                                if(KIOSK_VIEW_ANGLE == 270) {
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z-FLR_LBL_SPACE);
                                }
                                else if(KIOSK_VIEW_ANGLE == 90) {
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z+FLR_LBL_SPACE);
                                }
                                else if(KIOSK_VIEW_ANGLE == 180) {
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x+FLR_LBL_SPACE ,FLR_LBL_HEIGHT,theball.position.z+0);
                                }
                                else if(KIOSK_VIEW_ANGLE == 45) {
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z+5);
                                }
                                else if(KIOSK_VIEW_ANGLE == 135) {
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x+5,FLR_LBL_HEIGHT,theball.position.z+5);
                                }
                                else if(KIOSK_VIEW_ANGLE == 315) {
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x-5,FLR_LBL_HEIGHT,theball.position.z-4);
                                }
                                else{
                                    runner.position.set(theball.position.x,FLR_ANI_HEIGHT,theball.position.z);
                                    floors_label[movements[currentpos].l].visible = true;
                                    floors_label[movements[currentpos].l].position.set(theball.position.x-FLR_LBL_SPACE,FLR_LBL_HEIGHT,theball.position.z - 0.5);
                                }
								
							}
							console.log('Level end point: '+level_end_points[movements[currentpos].l].length);
						}
                    }

                    setTimeout(function(){
						if(movements.length > 0)
						{
							theball.position.set(movements[currentpos].x,movements[currentpos].y,movements[currentpos].z);
							currentpos++;
							
							moveBall( theball, movements[currentpos]);	
						}

						for (let cf = 2; cf <= total_floors; cf++) {
							
							if(movements.length > 0 && level_end_points.hasOwnProperty(movements[currentpos].l) && level_end_points[movements[currentpos].l].length == cf)
							{
								var start_point = level_end_points[movements[currentpos].l][cf-2];
								var end_point = level_end_points[movements[currentpos].l][cf-1];
	
								var pointa = new THREE.Vector3(start_point.point_x,start_point.point_y,start_point.point_z);
								var pointb = new THREE.Vector3(end_point.point_x,end_point.point_y,end_point.point_z);
	
								startMarker.position.x = pointa.x;
								startMarker.position.y = pointa.y;
								startMarker.position.z = pointa.z;// -	 (startMarker.position.z < endMarker.position.z ? 3 : 0);
	
								endMarker.position.x = pointb.x;
								endMarker.position.y = pointb.y;
								endMarker.position.z = pointb.z;// - (startMarker.position.z < endMarker.position.z ? 3 : 0);
	
								var markerDistance = distanceVector(pointa.x,pointa.y,pointa.z,pointb.x,pointb.y,pointb.z);
                                
                                $.each(kiosk_zoom, function(index, zoom) {
                                    if(movements[currentpos].l == index){
                                        nav_zoom_level = zoom.zoomratio;
                                        move_top = zoom.movetop;
                                    }
                                });
	
								fitCameraToScreen(nav_zoom_level, move_top);
								console.log(level_end_points[movements[currentpos].l].length + ' fit to end to end-- --'+cf+' condition' + ' current pos: '+movements[currentpos].l+' current pos: '+movements[currentpos].l);
							}
							
						}
						
						transitioning = 0;
					},2000);
                }
            }
            else {
				theball.visible = true;
				moveBall( theball, movements[currentpos] );
				runner.visible = false;
				runner2.visible = false;
				runner3.visible = false;
				$.each(floors_label,function(){
					this.visible = false;
				});
				$.each(bldg_label,function(){
					this.visible = false;
				});
			}
        }
    }

    function moveBall( ball, destination, speed = playerSpeed ) {
		console.log(linePathGroup);
		var moveDistance = speed;

		if(typeof destination == 'undefined')
		{
			return;
		}

		// Translate over to the position.
		var posX = ball.position.x;
		var posZ = ball.position.z;
		var posY = ball.position.y;
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
		ball.position.x = ball.position.x + ( moveDistance * ( diffX / distance )) * multiplierX;
		ball.position.z = ball.position.z + ( moveDistance * ( diffZ / distance )) * multiplierZ;
		ball.position.y = ball.position.y + ( moveDistance * ( diffY / distance )) * multiplierY;
		ball.visible = true;
		
		// If the position is close we can call the movement complete.
		if (( ball.position.x <= newPosX + playerSpeed && 
			ball.position.x >= newPosX - playerSpeed ) &&
			( ball.position.z <= newPosZ + playerSpeed && 
				ball.position.z >= newPosZ - playerSpeed ) &&
			( ball.position.y <= newPosY + playerSpeed && 
				ball.position.y >= newPosY - playerSpeed )
			) {
			
			if(currentpos < movements.length - 1)
			{
				currentpos++;
			}else{
				movementstmp = movements;
				movements = [];
				currentpos = 1;
				
				//enable dropdown
				$('#tenant-select').prop('disabled', false);
				$('#floor-select').prop('disabled', false);
				// $('#btnpwdchange').prop('disabled', false);

				// if($("#ispwd").prop('checked') == false) {
				// 	$(".btn-pwd").addClass('btn-prestige-white');
				// }else{
				// 	$(".btn-pwd").addClass('btn-prestige-color');
				// };
				
				//show map pointer
				spritePinTo.visible = true;
				
				// $(".maprepeat").show();
				//change class of mapexpand
				// $('.mapexpand').addClass('btn-prestige-rounded2').removeClass('btn-prestige-rounded3');
				
			}
		}

		if(destination.l != $("#floor-select").val())
		{
            building_change_user = 0;
			$("#floor-select").val(destination.l).change();
            building_change_user = 1;
		}
	}

    var targetYup = 16;
    var targetYdown = 14;
    var countup_from = true;
	var countup_dest = true;

    function loop() {
        // render the scene
        renderer.render(scene, camera);

        if (countup_from) {
            spritePinFrom.position.y += 0.05;
            if (spritePinFrom.position.y >=targetYup)
            countup_from = false;
        }
        else {
            spritePinFrom.position.y -= 0.05;
            if (spritePinFrom.position.y <=targetYdown)
            countup_from = true;
        }

        if (countup_dest) {
            spritePinTo.position.y += 0.05;
            if (spritePinTo.position.y >=targetYup)
            countup_dest = false;
        }
        else {
            spritePinTo.position.y -= 0.05;
            if (spritePinTo.position.y <=targetYdown)
            countup_dest = true;
        }

        // call the loop function again
        requestAnimationFrame(loop);
    }

    function switchFloor(value)
	{
        $.each(floors,function(index){
			floors[index].visible = (index == value);
			linePathGroup[index].visible = (index == value);
		});

        // var building_id = $($(".floor-select")[0].options[$(".floor-select")[0].selectedIndex]).closest('optgroup').data('building');
		// building_change_user = 0;
		// $(".building-select").val(building_id).change();
		// building_change_user = 1;

        // if(spritePinTo.userData.floor == value && $(".#tenant-select").val() > 0){
		// 	// spritePinTo.visible = true;
		// }else{
		// 	spritePinTo.visible = false;
		// }

        spritePinFrom.visible = (spritePinFrom.userData.hasOwnProperty('floor') && spritePinFrom.userData.floor == value);

        //fit to default zoom
        //zoom to default zoom
        var zoom_level =  0.4;
        if(default_zoom.hasOwnProperty($("#floor-select").val()))
        {
            zoom_level = default_zoom[$("#floor-select").val()];
        }

        fitCameraToScreen(zoom_level);
    }

    //SET ZOOM when switching floors
	function fitCameraToScreen(fitOffset = 1, moveTop = 0) {
        if(boundaries.hasOwnProperty($("#floor-select").val()))
		{  
            const box = new THREE.Box3();
			boundaries[$("#floor-select").val()].forEach(object => {box.expandByObject(object);});
			
			const size = box.getSize( new THREE.Vector3() );
			const center = box.getCenter( new THREE.Vector3() );
			const maxSize = Math.max( size.x, size.y, size.z );
			const fitHeightDistance = maxSize / ( 2 * Math.atan( Math.PI * camera.fov / 360 ) );
			const fitWidthDistance = fitHeightDistance / camera.aspect;

            var floor = $("#floor-select").val();

            var distance = fitOffset * Math.min( fitHeightDistance, fitWidthDistance );

            if(KIOSK_VIEW_ANGLE != 270 && KIOSK_VIEW_ANGLE != 90)
			{
				if(size.z > size.x)
				{
					console.log(fitHeightDistance,fitWidthDistance);
					distance = fitOffset * Math.max( fitHeightDistance, fitWidthDistance );
				}
			}else{
				console.log("portrait",fitHeightDistance,fitWidthDistance);
				distance = fitOffset * Math.max( fitHeightDistance, fitWidthDistance );
			}

			if(moveTop != 0){
				if(KIOSK_VIEW_ANGLE == 0){
					center.z = diffTwoRealNum(center.z, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 180){
					center.z = diffTwoRealNum(center.z, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 90){
					center.x = diffTwoRealNum(center.x, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 270){
					center.x = diffTwoRealNum(center.x, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 45){
					center.x = diffTwoRealNum(center.x, moveTop);
				}
			}

            //MOVE MAP UP AND DOWN ON SELECTOR
            // CREATE THIS ON DATABASE
            var kiosk_center = @php echo $kiosk_center; @endphp;
            center.y = center.y+parseFloat(kiosk_center[floor].center_y); //UP DOWN
            center.x = center.x+parseFloat(kiosk_center[floor].center_x); //LEFT RIGHT
            center.z = center.z+parseFloat(kiosk_center[floor].center_z); //LEFT RIGHT
            
            const direction = controls.target.clone()
            .sub( camera.position )
            .normalize()
            .multiplyScalar( distance );

			controls.maxDistance = distance * 10;
			controls.target.copy( center );
			camera.near = distance / 100;
			camera.far = distance * 100;
			camera.updateProjectionMatrix();
			camera.position.copy( controls.target ).sub(direction);
			//controls.update();

			//backup centermarker
			centerMarker.position.x = center.x;
			centerMarker.position.z = center.z;
			centerMarker.position.y = endMarker.position.y;

        }
        controls.maxPolarAngle = viewAngle * Math.PI / 180;
		controls.minPolarAngle = viewAngle * Math.PI / 180;

		controls.minAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180 ;
		controls.maxAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180;

		controls.update();	
    }

	function fitCameraToScreen3(fitOffset = 1, moveTop = 0)
	{
		if(boundaries.hasOwnProperty($("#floor-select").val()))
		{
			const box = new THREE.Box3();
			boundaries[$(".floor-select").val()].forEach(object => {box.expandByObject(object);});
			
			const size = box.getSize( new THREE.Vector3() );
			const center = box.getCenter( new THREE.Vector3() );
			const maxSize = Math.max( size.x, size.y, size.z );
			const fitHeightDistance = maxSize / ( 2 * Math.atan( Math.PI * camera.fov / 360 ) );
			const fitWidthDistance = fitHeightDistance / camera.aspect;

			var floor = $("#floor-select").val();

			var distance = fitOffset * Math.min( fitHeightDistance, fitWidthDistance );

            if(KIOSK_VIEW_ANGLE != 270 && KIOSK_VIEW_ANGLE != 90)
			{
				if(size.z > size.x)
				{
					console.log(fitHeightDistance,fitWidthDistance);
					distance = fitOffset * Math.max( fitHeightDistance, fitWidthDistance );
				}
			}else{
				console.log("portrait",fitHeightDistance,fitWidthDistance);
				distance = fitOffset * Math.max( fitHeightDistance, fitWidthDistance );
			}

			if(moveTop != 0){
				if(KIOSK_VIEW_ANGLE == 0){
					center.z = diffTwoRealNum(center.z, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 180){
					center.z = diffTwoRealNum(center.z, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 90){
					center.x = diffTwoRealNum(center.x, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 270){
					center.x = diffTwoRealNum(center.x, moveTop);
				}else if(KIOSK_VIEW_ANGLE == 45){
					center.x = diffTwoRealNum(center.x, moveTop);
				}
			}

			var kiosk_center = @php echo $kiosk_center; @endphp;
            center.y = center.y+parseFloat(kiosk_center[floor].center_y); //UP DOWN
            center.x = center.x+parseFloat(kiosk_center[floor].center_x); //LEFT RIGHT
            center.z = center.z+parseFloat(kiosk_center[floor].center_z); //LEFT RIGHT

			const direction = controls.target.clone()
				.sub( camera.position )
				.normalize()
				.multiplyScalar( distance );

			controls.maxDistance = distance * 10;
			controls.target.copy( center );
			camera.near = distance / 100;
			camera.far = distance * 100;
			camera.updateProjectionMatrix();
			camera.position.copy( controls.target ).sub(direction);
			//controls.update();

			//backup centermarker
			centerMarker.position.x = center.x;
			centerMarker.position.z = center.z;
			centerMarker.position.y = endMarker.position.y;

		}
		controls.maxPolarAngle = viewAngle * Math.PI / 180;
		controls.minPolarAngle = viewAngle * Math.PI / 180;

		controls.minAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180;
		controls.maxAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180;

		controls.update();	
	}

	function diffTwoRealNum(num1, num2){
		if(num1 > num2){
			if(num1 > 0 && num2 > 0){
				return num1 - num2;
			}else if(num1 < 0 && num2 < 0){
				return (num2*(-1)) - (num1*(-1));
			}else if(num1 > 0 && num2 < 0){
				return num1 + (num2*(-1));
			}

		} else {
			if(num1 > 0 && num2 > 0){
				return (num2 - num1) * (-1);
			}else if(num1 < 0 && num2 < 0){
				return ((num2*(-1)) - (num1*(-1))) * (-1);
			}else if(num1 < 0 && num2 > 0){
				return ((num1*(-1)) + (num2*(-1))) * (-1);
			}
		}
	}

    $(document).ready(function() {
        init();
        loadFont();
        animate();
        loop();

        $('#tenant-select').select2();
        $('#floor-select').select2();

        $("#floor-select").on('change',function(){
            switchFloor($(this).val());
        });

		$('#btnresetmap').on('click', function() {

			// RESET TO DEFAULT FLOOR
			$("#floor-select").val(default_floor).change();
			// RESET Input Destination
			$('#'+$('.select2-selection__rendered').attr('id')+'.select2-selection__rendered').text('Input Destination');
			onWindowResize();

			movements = [];
			currentpos = 1;

			// CLEAR TEXT GUIDE
			// HIDE TEXT GUIDE
			// BUTTON DISABLED FALSE
			// CLEAR TENANT DROPDOWN

			$.each(floors,function(index){
				linePathGroup[index].visible = (index == default_floor);
			});

			console.log(linePathGroup);
			spritePinTo.visible = false;
			theball.visible = false;

			//zoom to default zoom
			var zoom_level =  0.8;

			fitCameraToScreen(zoom_level);					
			var kiosk = map_points[KIOSK_ID];
			var coords = new THREE.Vector3(kiosk.point_x, 6,kiosk.point_z);
			
			//backup code
			coords.x = kiosk.point_x;
			coords.z = kiosk.point_z;
			coords.y = kiosk.point_y;

			//overwrite coords
			coords.z = (Math.abs(site_config.default_z) > 0) ? parseFloat(coords.z)+parseFloat(site_config.default_z) : coords.z;
			coords.x = (Math.abs(site_config.default_x) > 0) ? parseFloat(coords.x)+parseFloat(site_config.default_x) : coords.x;
			
			scene.worldToLocal(coords);
			controls.target = coords;

			//camera.position.y = 100;
			camera.position.y = site_config.default_y;
			camera.position.x = coords.x;
			camera.position.z = coords.z;
			controls.update();

		});

		$(".mapminus").on('click',function(){
			//$("#mapkeyboarclose").click();
			controls.zoomOut(4);
		});

		$(".mapplus").on('click',function(){
			//$("#mapkeyboarclose").click();
			controls.zoomIn(4);
		});

		$(".mapexpand").on('click',function() { 
			//$("#mapkeyboarclose").click();
			if(transitioning == 0)
			{
				controls.minAzimuthAngle = 0 * Math.PI / 180 ;
				controls.maxAzimuthAngle = KIOSK_VIEW_ANGLE * Math.PI / 180;
				
				controls.update();

				controls.minAzimuthAngle = -Infinity;
				controls.maxAzimuthAngle = Infinity;

				var zoom_level =  1;

				$.each(kiosk_zoom, function(index, zoom) {
					if($("#floor-select").val() == index){
						zoom_level = zoom.fitscreen;
					}
				});

				fitCameraToScreen(zoom_level);				
			}
		});

		$('#tenant-select').on('change', function() {
			// KEYBOARD INPUT CLEAR

			// HIDE DIRECTION DETAILS
			$("#toggle-down").addClass('hideArrow');
			$("#toggle-up").removeClass('hideArrow');
			$("#toggle-updown-text").html('Show Text Guide');
			$("#hiddenPanel2").hide();

			runner.visible = false;
			runner2.visible = false;
			runner3.visible = false;
			$.each(floors_label,function(){
				this.visible = false;
			});
			$.each(bldg_label,function(){
				this.visible = false;
			});

			spritePinTo.visible = false;
			theball.visible = false;

			var selected_text = $(this).find("option:selected").text();
			$('#'+$('.select2-selection__rendered').attr('id')+'.select2-selection__rendered').text('Directions to '+selected_text);

			$.each(floors,function(index){
				linePathGroup[index].remove(...linePathGroup[index].children);
			});

			// TERMINATE ACTION IF VALUE IS 0
			if(KIOSK_ID == 0 || $(this).val() == 0)
			{
				return false;
			}

			$.post( "/api/v1/get-routes", { from: KIOSK_ID, to: $(this).val(), pwd: ($("#ispwd").is(':checked') ? 1: 0), type: 'kiosk', site_id:@php echo $site->id; @endphp} )
			.done(function(data) {
				$('#tenant-select').prop('disabled', true);
				$('#floor-select').prop('disabled', true);
				$('#btnpwdchange').prop('disabled', true);

				// ADD DISABLED COLOR FOR PWD HERE

				// var steps = (data['distance'] * 1.3).toFixed(0);
				// var mins = (steps / 100).toFixed(0);
				// $(".map-distance").html(data['distance'].toFixed(0)  + 'm distance');
				// $(".map-steps").html( steps + ' steps');
				// $(".map-minutes").html( mins + ' minute' + (mins > 1 ? 's' : ''));


				// $("#mapguide li").remove();
				// $.each(data['guide'],function(){
				// 	if (this == "Turn Left" || this == "Turn Right") {

				// 	}else if (this == "Turn Left on Escalator" || this == "Turn Right on Escalator") {

				// 	}else if (this == "Turn Left on Elevator" || this == "Turn Right on Elevator") {

				// 	}else {
				// 		$("#mapguide").append('<li>' + this + '</li>');
				// 	}
				// });

				level_end_points = data['level_end_points'];
				var destination_wayfind = data['destination'];
				var tenant_guide = data['tenant_guide'];
				total_floors = data['total_levels'];
				var start_point = level_end_points[Object.keys(level_end_points)[0]];
				var initial_level = start_point[0]['building_level_id'];
	
				// $("#mapguide-destination").html(tenant_guide);

				movements = [];
				var line_points = {};
				var current_level = 0;
				var current_building = 0;
				
				$.each(data['coords'],function(index) {
					if(index == 0) {
						theball.position.set(parseFloat(this.point_x),(parseFloat(this.point_y) - 1),parseFloat(this.point_z));

						spritePinFrom.position.x = parseFloat(this.point_x);
						spritePinFrom.position.z = parseFloat(this.point_z);
						spritePinFrom.visible = true;
						spritePinFrom.userData = {'floor':this.building_level_id};
					}
					
					if( index == data['coords'].length-1) {						
						spritePinTo.position.x = parseFloat(destination_wayfind[0].point_x);
						spritePinTo.position.z = parseFloat(destination_wayfind[0].point_z);						
						spritePinTo.userData = {'floor':destination_wayfind[0].building_level_id};
					}
					
					if(!line_points.hasOwnProperty(this.building_level_id)) { 
						line_points[this.building_level_id] = [];
						line_points[this.building_level_id].push([]);
					}

					if(current_level > 0 && this.building_level_id != current_level) {
						spritePinTo.visible = false;
						line_points[this.building_level_id].push([]);
					}
					
					let current_line_index = line_points[this.building_level_id].length - 1;

					if(current_building == 0 || current_building == this.building_id) {
					 	line_points[this.building_level_id][current_line_index].push(new THREE.Vector3(parseFloat(this.point_x),(parseFloat(this.point_y) + 1),parseFloat(this.point_z)));
					 	movements.push({x:parseFloat(this.point_x),y:(parseFloat(this.point_y) + 1),z:parseFloat(this.point_z),l:this.building_level_id,b:this.building_id});
					}

					current_level = this.building_level_id;
					current_building = this.building_id;
				});

				$.each(line_points,function(index){
					var line_points_group = this;
					$.each(line_points_group,function(){
						var geometry = new THREE.BufferGeometry().setFromPoints(this);
						var line = new THREE.Line( geometry, lineMaterial );
						linePathGroup[index].add( line );
					})
				});

			})
			


		})

    });
    
</script>
@endpush