var defaults = {
    width: 3000,
    height: 3000,
    currentmap_id: 0,
    defaultmap_id: 0,
    currentmap: 0,
    currentlevel: 0,
    currentbuilding: 0,
    scale: 1,
    scalestep: 0.05,
    defaultmap: 0,
    locationx: 0,
    locationy: 0,
    mapcontainer: null,
    defaultaction: null,
    here_progress:0,
    frame_here: 0,
    marker_here_id: 0,
    escalator_progress: 0,
    frame_escalator: 0,
    escalator_id: 0,
    door_progress: 0,
    frame_door: 0,
    store_progress: 0,
    frame_store: 0,
    destination: 0,
    showescalator: 0,
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
            
            // add points to map
            $(".zoomable-container").css({'width':image.width,'height':image.height,'position':'relative'});
        };

        image.src = map_details.map_file_path + '?' + Math.random();
			
        if(map_details.is_default == 0) {
            $("#" + canvas.id).hide();
        }
        else {
            $("#" + canvas.id).show();
            this.settings.defaultmap = map_details;
            this.settings.currentmap = map_details.site_building_level_id + '-' + map_details.site_building_id;
            this.settings.currentlevel = map_details.site_building_level_id;
            this.settings.currentbuilding = map_details.site_building_id;
            this.settings.currentmap_id = map_details.id;
        }

        obj.load_points();
    },

    load_points: function() {
        var obj = this;
        $.get( "/api/v1/site/maps/get-points/"+this.settings.currentmap_id, function(response) {
            if(response.data.length) {
                $.each(response.data,function(index,tenant) {
                    // create points and label
                    obj.create_point(tenant);
                    if(tenant.point_type == 6) {
                        obj.settings.locationx = tenant.point_x;
                        obj.settings.locationy = tenant.point_y;                    
                    }
                });
            }
        });

        // setInterval(function(){obj.animate_escalator(991.25, 1611.48);},250);
        // setInterval(function(){obj.animate_door(1901.00, 1603.50);},250);
        // setInterval(function(){obj.animate_marker_store(2551.99, 1609.49);},250);
        
        if(this.settings.currentmap_id && !this.settings.marker_here_id)
        {
            this.settings.marker_here_id = setInterval(function(){obj.animate_marker_here()},50);
        }
    },

    create_point: function(tenant) {
        var canvas = document.getElementById('text-layer');
        var ctx = canvas.getContext("2d");
        var text = (tenant.point_label) ? tenant.point_label : tenant.brand_name; 
        var font_size = (tenant.text_size > 0) ? (tenant.text_size*16) : 16;
        var coord_x = tenant.point_x;
        var coord_y = tenant.point_y;
        var font_face = 'Henry Sans Medium';
        var dot_radius = 1;
        var font_weight = 'bold';
        var labels=[];
        var nextId=tenant.id; //no. of id shown

        if(text) {
            var label = addLabel(text,coord_x,coord_y,font_size,font_face,dot_radius);
            drawLabel(label);
        }

        function addLabel(text,coord_x,coord_y,font_size,font_face,dot_radius) {
            var font = font_weight + ' ' + font_size+'px '+ font_face;
            var title_width = longest(text);
            ctx.font = font;
            var text_width = ctx.measureText(title_width.toUpperCase()).width;

            var text = text.replace(/<br\s*\/?>/gi,' ');
            var label_height = font_size*1.286;
            var label = {
                id:nextId,
                text:text,
                x:coord_x-text_width/2,
                y:coord_y-dot_radius-label_height,
                w:text_width,
                h:label_height,
                offsetY:0,
                font:font,
                isColliding:false,
                dotRadius:dot_radius,
                dotX:coord_x,
                dotY:coord_y,
                wrap:1,
                size:font_size,
            }; 
            labels.push(label);

            // try to position this new label in a non-colliding position
            var positions = [
                { x:coord_x-text_width/2, y:coord_y-dot_radius-label_height },  // N
                { x:coord_x+dot_radius, y:coord_y-label_height/2 },    // E
                { x:coord_x-text_width/2, y:coord_y+dot_radius },    // S
                { x:coord_x-dot_radius-text_width, y:coord_y-label_height/2 },  // W
            ];

            for(var i=0;i<positions.length;i++) {
                var p=positions[i];
                label.x=p.x;
                label.y=p.y;
                label.isColliding = thisLabelCollides(label);
                if(!label.isColliding) { break; } //if false, break
            } //end of for
            return(label);
        }

        function drawLabel(label) {
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';

            if(label.wrap == 0) {
                ctx.textAlign='left';
                ctx.font = label.font;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 2;
                ctx.strokeText(label.text.toUpperCase(),label.x,label.y);
                ctx.fillStyle = "rgb(32,32,32)";
                ctx.fillText(label.text.toUpperCase(),label.x,label.y);
            }
            else {
                ctx.font = label.font;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 2;
                ctx.shadowBlur = 2;
                ctx.fillStyle = "Black";
                ctx.shadowColor = "rgba(255, 255, 255, 0.5)";
                var text = label.text;
                var stitle_w = longest(label.text);
                var w = ctx.measureText(stitle_w.toUpperCase()).width;
                var words = text.split(' '),
                    line = '',
                    lineCount = 0,
                    i,
                    test,
                    metrics;
                
                for (i = 0; i < words.length; i++) {
                    test = words[i];
                    metrics = ctx.measureText(test);
                    while (metrics.width > w) {
                        // Determine how much of the word will fit
                        test = test.substring(0, test.length - 1);
                        metrics = ctx.measureText(test);
                    }
                    if (words[i] != test) {
                        words.splice(i + 1, 0,  words[i].substr(test.length))
                        words[i] = test;
                    }  

                    test = line + words[i] + ' ';  
                    metrics = ctx.measureText(test);
                    
                    if (metrics.width > w && i > 0) {
                        ctx.strokeText(line.toUpperCase(), label.x + (w/2) + 4, label.y);
                        ctx.fillText(line.toUpperCase(), label.x + (w/2) + 4, label.y);
                        line = words[i] + ' ';
                        label.y += label.size - 1;
                        label.dotY = parseInt(label.dotY) + label.size - 1;
                        lineCount++;
                    }
                    else {
                        line = test;
                    }
                }

                ctx.strokeText(line.toUpperCase(), label.x + (w/2) + 4, label.y);
                ctx.fillText(line.toUpperCase(), label.x + (w/2) + 4, label.y);				
            }

            //create dots
            ctx.beginPath();
            ctx.arc(label.dotX,label.dotY,label.dotRadius,0,Math.PI*2);
            ctx.fill();
 
        }

        function longest(string) {
            var words = string.split(' ');
            var longest = ''; 
    
            for (var i = 0; i < words.length; i++) {
                if (words[i].length > longest.length) { 
                    longest = words[i]; 
                }
            }
            return longest;
        }

        function thisLabelCollides(r1){					
            for(var i=0;i<labels.length;i++) { 
                var r2=labels[i];
                if(r1.id==r2.id || r2.isColliding){continue;}
                //if(r2.isColliding){continue;}
                var collides=(!(
                    r1.x      > r2.x+r2.w ||
                    r1.x+r1.w < r2.x      ||
                    r1.y      > r2.y+r2.h ||
                    r1.y+r1.h < r2.y
                ));
                if(collides){return(true);}
            }
            return(false);
        }

    },    

    clearTextlayer: function() {
        var canvas = document.getElementById('text-layer');
        var context = canvas.getContext('2d');
        context.clearRect(0, 0,canvas.width,canvas.height);
        context.save();
    },

    showmap: function(map_details) {
        if(map_details.id) {
            id = map_details.id;
        }
        else {
            id = this.settings.currentmap_id;
        }

        this.settings.currentmap_id = id;
        this.settings.currentmap = map_details.site_building_level_id + '-' + map_details.site_building_id;

        $(".my-map").hide();
        $("#" + this.settings.currentmap).show();

        this.load_points();

        if(this.settings.defaultmap.id == id) {
            $("#here-layer").show();
        }
        else {
            $("#here-layer").hide();
        }

    },

    animate_marker_here: function () {
        if(this.settings.here_progress ) return;
        this.settings.here_progress = 1;
        if(this.settings.frame_here > 23) this.settings.frame_here = 0;

        var x = this.settings.locationx;
        var y = this.settings.locationy;                    
       
        var canvas = document.getElementById('here-layer');
        var context = canvas.getContext('2d');
        
        context.clearRect((x-70),(y-160),canvas.width , canvas.height);
        context.drawImage(document.getElementById('marker-you-are-here'),(x-70),(this.settings.frame_here + (y-160)), 130, 130);
        context.restore();
        
        this.settings.frame_here +=1 ;
        this.settings.here_progress = 0;
    },

    animate_marker_here_stop: function() {
        clearInterval(this.settings.marker_here_id);
        this.settings.marker_here_id = 0;
        this.settings.here_progress = 0;
    },

    animate_escalator: function(x, y, direction = '', text = 'text') {
        if(this.settings.escalator_progress) return;
        this.settings.escalator_progress = 1;
        if(this.settings.frame_escalator > 4) this.settings.frame_escalator = 0;

        var canvas = document.getElementById('escalator-layer');
        var context = canvas.getContext('2d');

        context.clearRect(0,0,canvas.width,canvas.height);

        if(direction) {
            context.drawImage(document.getElementById('marker-escalator-up'),(this.settings.frame_escalator*142),0*67,142,67,x,(y-80),142,67);
            context.font = "bold 30px Raleway";
            context.fillStyle = "rgb(71, 131, 162)";
            context.fillText(text.toUpperCase(),(x+65),(y-37));
        }
        else {
            context.drawImage(document.getElementById('marker-escalator-down'),(this.settings.frame_escalator*142),(0*67),142,67,x,(y-80),142,67);
            context.font = "bold 30px Raleway";
            context.fillStyle = "rgb(71, 131, 162)";
            context.fillText(text.toUpperCase(),(x+65),(y-37));
        }
        context.restore();

        this.settings.frame_escalator++;
        this.settings.escalator_progress = 0;
    },

    animate_escalator_stop: function(){
        clearInterval(this.settings.escalator_id);
        this.settings.escalator_id = 0;
        this.settings.escalator_progress = 0;
    },

    animate_door: function(x, y) {
        if(this.settings.door_progress) return;
        this.settings.door_progress = 1;
        if(this.settings.frame_door > 3) this.settings.frame_door = 0;

        var canvas = document.getElementById('escalator-layer');
        var context = canvas.getContext('2d');

        context.clearRect((x-75),(y-50),155,100);
        context.drawImage(document.getElementById('marker-door'),(this.settings.frame_door*400),0,400,255,(x-75),(y-50),155,100);
        context.restore();

        this.settings.frame_door++;
        this.settings.door_progress = 0;
    },

    animate_door_stop: function(){
        clearInterval(this.settings.door_id);
        this.settings.door_id = 0;
        this.settings.door_progress = 0;
    },

    animate_marker_store: function(x, y) {
        if(this.settings.store_progress) return;
        // if(this.points.linePoint.length == 0 ) return;
        this.settings.store_progress = 1;
        if(this.settings.frame_store > 23) this.settings.frame_store = 0;

        var canvas = document.getElementById('tenants-layer');
        var context = canvas.getContext('2d');

        context.clearRect((x-60),(y-170),130.4,150);
        context.drawImage(document.getElementById('marker-store-here'),(this.settings.frame_store*130.4),0,130.4,150,(x-60),(y-170),130.4,150);
        context.restore();
        
        this.settings.frame_store++;
        this.settings.store_progress = 0;
    },

    animate_marker_store_stop: function(){
        clearInterval(this.settings.store_id);
        this.settings.store_id = 0;
        this.settings.store_progress = 0;
    },

    show_tenant_details: function(id) {

    },

    drawline: function(id) {
        this.showmap(this.settings.defaultmap);
        this.drawpoints_stop();
        this.show_tenant_details(id);

        this.destination = id;
        this.showescalator = 1;
        var obj = this;


    },

    drawpoints_stop: function() {
        clearInterval(this.inter);
        this.inter = 0;
    },

};
