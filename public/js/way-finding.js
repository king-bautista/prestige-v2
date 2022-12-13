var defaults = {
    width: 3000,
    height: 3000,
    currentmap: 0,
    currentlevel: 0,
    currentbuilding: 0,
    scale: 1,
    scalestep: 0.05,
    defaultmap: 0,
    locationx: 0,
    locationy: 0,
    mapcontainer: null,
    defaultaction: null
}

var self_class = '';

var WayFinding = function(params) {
    this.settings = Object.assign(defaults, params);
    this.init(1);
}

WayFinding.prototype = {
    init: function(reset) {
        if(reset) {
            this.addLayers();
        }
    },

    addLayers: function() {
        //line
        var canvas = document.createElement('canvas');
        canvas.id = 'line-layer';
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;
        canvas.style.position = 'absolute';
        document.getElementById(this.settings.mapcontainer).appendChild(canvas);

        //text layer
        var canvas = document.createElement('canvas');
        canvas.id = 'text-layer';
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;
        canvas.style.position = 'absolute';
        document.getElementById(this.settings.mapcontainer).appendChild(canvas);

        //tenant layer
        var canvas = document.createElement('canvas');
        canvas.id = 'tenants-layer';
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;
        canvas.style.position = 'absolute';
        document.getElementById(this.settings.mapcontainer).appendChild(canvas);

        //current location
        var canvas = document.createElement('canvas');
        canvas.id = 'here-layer';
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;
        canvas.style.position = 'absolute';
        document.getElementById(this.settings.mapcontainer).appendChild(canvas);

        //escalator
        var canvas = document.createElement('canvas');
        canvas.id = 'escalator-layer';
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;
        canvas.style.position = 'absolute';
        document.getElementById(this.settings.mapcontainer).appendChild(canvas);

    },

    // getMaps: function() {
    //     self_class = this;
    //     $.get( "/api/v1/site/maps").done(this.manageMaps);
    // },

    // manageMaps: function(response) {
    //     for (var i = 0; i < response.data.length; i++){
    //         self_class.addMaps(response.data[i]);
    //     }
    // },

    addMaps: function(map_details) {
        var obj = this;

        //append canvas to document
        var canvas = document.createElement('canvas');
        canvas.id = map_details.site_building_level_id + '-' + map_details.site_building_id;
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;

        var container = document.getElementById(this.settings.mapcontainer);
        container.insertBefore(canvas,container.firstChild);

        //create a copy of the canvas
        var canvas2 = document.createElement('canvas');
        canvas2.id = 'copy_' + map_details.site_building_level_id + '-' + map_details.site_building_id;
        canvas2.width  = this.settings.width;
        canvas2.height = this.settings.height;
        document.body.appendChild(canvas2);

        var context = canvas.getContext('2d');
        var image = new Image();

        image.onload = function(){
            canvas.width = image.width;
            canvas.height = image.height;
            canvas.style.position = 'absolute';
            
            canvas2.width = image.width;
            canvas2.height = image.height;
            canvas2.style.display = 'none';
            canvas2.style.position = 'absolute';
            
            context.drawImage(image,0,0,image.width,image.height,0,0,image.width,image.height);
            var imageData = context.getImageData(0, 0, image.width,image.height);
            canvas2.getContext("2d").putImageData(imageData, 0, 0);

            //add class to map
            $("#" + canvas.id).addClass('my-map').attr('level', map_details.site_building_level_id);
            
            $(".zoomable-container").css({'width':image.width,'height':image.height,'position':'relative'});
        };

        image.src = map_details.map_file_path + '?' + Math.random();
			
        if(map_details.is_default == 0)
        {
            $("#" + canvas.id).hide();
        }else{
            $("#" + canvas.id).show();
            this.settings.currentmap = map_details.site_building_level_id + '-' + map_details.site_building_id;
            this.settings.defaultmap = map_details.site_building_level_id + '-' + map_details.site_building_id;
            this.settings.currentlevel = map_details.site_building_level_id;
            this.settings.currentbuilding = map_details.site_building_id;
        }
        
    },

    load_points: function(map_id) {
        var obj = this;
        $.get( "/api/v1/site/maps/get-points/"+map_id).done(this.manageMaps);
        // $.ajax({
        //     url: 'get-shops-points.php?iid=' + id + '&level=' + level + '&bldg=' + bldg,
        //     type: 'get',
        //     dataType: 'json',
        //     success: function(data){
        //         $.each(data,function(i,item){
        //             if(item.lat > 0)
        //             {
        //                 var services = ["Atm Center", "Clinic", "Concierge", "Directory", "Elevator", "Escalator", "Rest Room","Event Hall","Food Hall","Garden","Origami Glass","PUV Drop-Off","SM Cinema"];

        //                     if ($.inArray(item.shop_title, services) != -1){

        //                         obj.create_point(item.lat,item.lng,item.inline,item.wrap,item.size,item.rotate);
        //                         // obj.create_point(item.lat,item.lng);

        //                     }else{
        //                         obj.create_point(item.lat,item.lng,item.inline,item.wrap,item.size,item.rotate,item.shop_title);
        //                         // obj.create_point(item.lat,item.lng,item.shop_title);
        //                     }
        //             }
        //             });					
        //         }
        // });
    },

};
