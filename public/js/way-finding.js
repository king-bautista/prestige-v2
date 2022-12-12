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

    scale: function(scale){
        if(scale) this.settings.scale = scale;
        this.redraw();
    },

    // redraw: function() {
    //     var obj = this;
    //     var canvas = document.getElementById(this.settings.currentmap);
    //     var canvas2 = document.getElementById('copy_' + this.settings.currentmap);
    //     var context = canvas.getContext('2d');

    //     context.clearRect(0, 0,canvas.width,canvas.height);
    //     context.save();

    //     var point_x = canvas.width / 2;
    //     var point_y = canvas.height / 2;

    //     context.translate(point_x,point_y);
    //     context.scale(this.settings.scale,this.settings.scale);
    //     context.translate(-point_x,-point_y);
    //     context.drawImage(canvas2,0,0);
    //     context.restore();

    //     //marker layer
    //     var canvas_marker = document.getElementById('here-layer');
    //     canvas_marker.width = canvas.width;
    //     canvas_marker.height = canvas.height;
        
    //     var context_marker = canvas_marker.getContext('2d');
    //     context_marker.clearRect(0, 0,canvas.width,canvas.height);
    //     context_marker.save();
    //     context_marker.translate(point_x,point_y);
    //     context_marker.scale(this.settings.scale,this.settings.scale);
    //     context_marker.translate(-point_x,-point_y);
    //     context_marker.restore();

    //     //line layer
    //     var canvas_line = document.getElementById('line-layer');
    //     canvas_line.width = canvas.width;
    //     canvas_line.height = canvas.height;
        
    //     var context_line = canvas_line.getContext('2d');
    //     context_line.clearRect(0, 0,canvas.width,canvas.height);
    //     context_line.save();
    //     context_line.translate(point_x,point_y);
    //     context_line.scale(this.settings.scale,this.settings.scale);
    //     context_line.translate(-point_x,-point_y);
    //     context_line.restore();

    //     //tenant layer
    //     var canvas_tenant = document.getElementById('tenants-layer');
    //     canvas_tenant.width = canvas.width;
    //     canvas_tenant.height = canvas.height;
        
    //     var context_tenant = canvas_tenant.getContext('2d');
    //     context_tenant.clearRect(0, 0,canvas.width,canvas.height);
    //     context_tenant.save();
    //     context_tenant.translate(point_x,point_y);
    //     context_tenant.scale(this.settings.scale,this.settings.scale);
    //     context_tenant.translate(-point_x,-point_y);
    //     context_tenant.restore();

    //     //escalator layer
    //     var canvas_escalator = document.getElementById('escalator-layer');
    //     canvas_escalator.width = canvas.width;
    //     canvas_escalator.height = canvas.height;
        
    //     var context_escalator = canvas_escalator.getContext('2d');
    //     context_escalator.clearRect(0, 0,canvas.width,canvas.height);
    //     context_escalator.save();
    //     context_escalator.translate(point_x,point_y);
    //     context_escalator.scale(this.settings.scale,this.settings.scale);
    //     context_escalator.translate(-point_x,-point_y);
    //     context_escalator.restore();

    //     //text layer
    //     var canvas_text= document.getElementById('text-layer');
    //     canvas_text.width = canvas.width;
    //     canvas_text.height = canvas.height;
        
    //     var context_text = canvas_text.getContext('2d');
    //     context_text.clearRect(0, 0,canvas.width,canvas.height);
    //     context_text.save();
    //     context_text.translate(point_x,point_y);
    //     context_text.scale(this.settings.scale,this.settings.scale);
    //     context_text.translate(-point_x,-point_y);
    //     //context_text.restore();

    //     // var currentlevel =  this.settings.currentmap.split("-").shift();
    //     // var currentbuilding = this.settings.currentmap.split("-").pop();

    //     // HIDE FOR MAPS WITH NO PLOTTED NAMES
    //     //obj.load_points(this.settings.id,currentlevel,currentbuilding);

    // },

    load_points: function(id,level,bldg) {
        var obj = this;
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
