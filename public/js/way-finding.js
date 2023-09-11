var defaults = {
    width: 5000,
    height: 5000,
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
    tenant_details: '',
    showescalator: 0,
    points: {linePoint : []},
    inter: 0,
    current_point: 0,
    mapchange: 0,
    storefound: 0,
    store_id: 0,
    tenant_store_address: '',
    panzoom: '',
    with_disability: 0,
    vue_obj: '',
}

var Point = function Point(x,y,z,z2,map_id) {
	this.x = x;
	this.y = y;
	this.z = z;
	this.z2 = z2;
	this.map_id = z2;
}

var self_class = '';

var WayFinding = function(params, obj) {
    this.settings = Object.assign(defaults, params);
    this.vue_obj = obj;
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

        //amenities layer
        var canvas = document.createElement('canvas');
        canvas.id = 'amenities-layer';
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

        //tenant layer
        var canvas = document.createElement('canvas');
        canvas.id = 'tenants-layer';
        canvas.width  = this.settings.width;
        canvas.height = this.settings.height;
        canvas.style.position = 'absolute';
        document.getElementById(this.settings.mapcontainer).appendChild(canvas);
    },

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
    },

    load_points: function() {
        var obj = this;

        console.log(this.vue_obj.site_points[this.settings.currentmap_id]);
        

        // $.get( "/api/v1/site/maps/get-points/"+this.settings.currentmap_id, function(response) {
        //     if(response.data.length) {
        //         $.each(response.data,function(index,tenant) {
        //             // create points and label
        //             obj.create_point(tenant);
        //             if(tenant.point_type == 6) {
        //                 obj.settings.locationx = tenant.point_x;
        //                 obj.settings.locationy = tenant.point_y;                    
        //             }
        //         });
        //     }
        // });

        $.each(this.vue_obj.site_points[this.settings.currentmap_id], function(index,tenant) {
            // create points and label
            obj.create_point(tenant);
            if(tenant.point_type == 6) {
                obj.settings.locationx = tenant.point_x;
                obj.settings.locationy = tenant.point_y;                    
            }
        });

        if(this.settings.currentmap_id && !this.settings.marker_here_id)
        {
            this.settings.marker_here_id = setInterval(function(){obj.animate_marker_here()},50);
        }
    },

    create_point: function(tenant) {
        var canvas = document.getElementById('text-layer');
        var amenities_canvas = document.getElementById('amenities-layer');
        var ctx = canvas.getContext("2d");
        var amenities_ctx = amenities_canvas.getContext("2d");
        var text = (tenant.point_label) ? tenant.point_label : tenant.brand_name; 
        var font_size = (tenant.text_size > 0) ? (tenant.text_size*16) : 16;
        var coord_x = tenant.point_x;
        var coord_y = tenant.point_y;
        var deg = tenant.rotation_z;
        var font_face = 'Overpass';
        var dot_radius = 1;
        var font_weight = 'bold';
        var labels=[];
        var nextId=tenant.id; //no. of id shown
        var wrap=tenant.wrap_at

        //Draw Ameneties Icon
        if (tenant.point_type > 0) {
            var imageObj = new Image();

            imageObj.onload = function() {
                amenities_ctx.drawImage(imageObj, coord_x-30, coord_y-30, 40, 40);
            };
            
            //replace with dynamic value from tenant.point_type_icon
            imageObj.src = tenant.icon_path;
        }

        if(text) {
            var label = addLabel(text,coord_x,coord_y,font_size,font_face,dot_radius,wrap,deg);
            if (tenant.point_type == 0) {
                drawLabel(label);
            }
        }

        function addLabel(text,coord_x,coord_y,font_size,font_face,dot_radius,wrap,degree) {
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
                //y:coord_y-dot_radius-label_height,
                y:coord_y,
                w:text_width,
                h:label_height,
                offsetY:0,
                font:font,
                isColliding:false,
                dotRadius:dot_radius,
                dotX:coord_x,
                dotY:coord_y,
                wrap:wrap,
                size:font_size,
                deg:degree,
            }; 
            labels.push(label);

            // try to position this new label in a non-colliding position
            // var positions = [
            //     { x:coord_x-text_width/2, y:coord_y-dot_radius-label_height },  // N
            //     { x:coord_x+dot_radius, y:coord_y-label_height/2 },    // E
            //     { x:coord_x-text_width/2, y:coord_y+dot_radius },    // S
            //     { x:coord_x-dot_radius-text_width, y:coord_y-label_height/2 },  // W
            // ];

            // for(var i=0;i<positions.length;i++) {
            //     var p=positions[i];
            //     label.x=p.x;
            //     label.y=p.y;
            //     label.isColliding = thisLabelCollides(label);
            //     if(!label.isColliding) { break; } //if false, break
            // } //end of for
            return(label);
        }

        function drawLabel(label) {
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';
            //label_height = 14;

            if(label.wrap == 0) {
                ctx.textAlign='left';

                if (parseInt(label.deg) == 0) {

                    ctx.font = label.font;
                    ctx.strokeStyle = 'white';
                    ctx.lineWidth = 1;
                    ctx.shadowBlur = 1;
                    ctx.fillStyle = "Black";
                    ctx.shadowColor = "rgba(255, 255, 255, 0.5)";
                    var text = label.text;
                    var stitle_w = longest(label.text);
                    var w = ctx.measureText(stitle_w.toUpperCase()).width + 10;
                    vtop = (parseInt(label.y-(label.h/2)));

                    ctx.strokeText(label.text.toUpperCase(), label.x + (w/2) - 5, vtop-2);
                    ctx.fillText(label.text.toUpperCase(), label.x + (w/2) - 5, vtop-2);
                    //ctx.fillText(label.text.toUpperCase(),label.x+3,vtop-2);
                }
                else {

                    ctx.font = label.font;
                    ctx.strokeStyle = 'white';
                    ctx.lineWidth = 1;
                    ctx.shadowBlur = 1;
                    ctx.fillStyle = "Black";
                    ctx.shadowColor = "rgba(255, 255, 255, 0.5)";
                    var text = label.text;
                    var stitle_w = longest(label.text);
                    var w = ctx.measureText(stitle_w.toUpperCase()).width + 10;
                    vtop = (parseInt(label.y-(label.h/2)));

                    if (label.deg<0) {
                        x = label.x+(label.w/2)-10;
                    } else {
                        x = label.x+(label.w/2)+5;
                    }	
                    y = label.y;
                
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(parseInt(label.deg) * Math.PI / 180);
                    ctx.textAlign = 'center';

                    ctx.strokeText(label.text.toUpperCase(), 0, 0);
                    ctx.fillText(label.text.toUpperCase(), 0, 0);
                    //ctx.fillText(label.text.toUpperCase(), 0, 0);
                    ctx.clearRect(x, y, label.w, label.h);
                    ctx.restore();
                }

                // var text_width = ctx.measureText(label.text.toUpperCase()).width/2;
                // ctx.font = label.font;
                // ctx.textAlign='left';
                // ctx.strokeStyle = 'white';
                // ctx.lineWidth = 2;   
            

                // if (label.deg<0) {
                //     x = label.x+(text_width/2)-10;
                // } else {
                //     x = label.x+(text_width/2)+5;
                // }	
                // y = label.y;
                // ctx.save();
            	// ctx.translate(x, y);
                // ctx.rotate(parseInt(label.deg) * Math.PI / 180);

                // ctx.strokeText(label.text.toUpperCase(),label.dotX-text_width,label.y+label_height);
                // ctx.fillStyle = "rgb(32,32,32)";
                // ctx.fillText(label.text.toUpperCase(),label.dotX-text_width,label.y+label_height);
            }
            else {
                function wrapText(context, text, x, y, maxWidth, lineHeight, h) {
                    var words = text.split(' ');
                    var line = '';
                    y = (y - h);

                    for(var n = 0; n < words.length; n++) {
                        var testLine = line + words[n] + ' ';
                        var metrics = context.measureText(testLine);
                        var testWidth = metrics.width;

                        if (testWidth > maxWidth && n > 0) {
                            context.strokeText(line.toUpperCase(), x + ((w/2)+3), y+2);
                            context.fillText(line.toUpperCase(), x + ((w/2)+4), y+3);
                            line = words[n] + ' ';
                            y += (lineHeight/1.286)+1;
                        }else {
                            line = testLine;
                        }
                    }
                    context.strokeText(line.toUpperCase(), x + ((w/2)+3), y+2);
                    context.fillText(line.toUpperCase(), x + ((w/2)+4), y+3);
                }

                function wrapText2(context, text, x, y, maxWidth, lineHeight, h,deg) {
                    var words = text.split(' ');
                    var line = '';
                    y = (y - h);

                    for(var n = 0; n < words.length; n++) {
                        var testLine = line + words[n] + ' ';
                        var metrics = context.measureText(testLine);
                        var testWidth = metrics.width;

                        if (testWidth > maxWidth && n > 0) {
                            ctx.save();
                            if (deg<0) {
                                ctx.translate(x+(maxWidth/2)-10, y+ ((h/2)+4));
                            } else {
                                ctx.translate(x+(maxWidth/2)+10, y+ ((h/2)+4));
                            }
                            ctx.rotate(parseInt(deg) * Math.PI / 180);
                            context.strokeText(line.toUpperCase(), 0, 0);
                            context.fillText(line.toUpperCase(), 0, 0);
                            ctx.restore();
                            line = words[n] + ' ';
                            if (deg<0) {
                                x += (lineHeight/1.286)+2;
                            } else {
                                x -= (lineHeight/1.286);
                            }					        
                        }else {
                            line = testLine;
                        }
                    }
                    ctx.save();
                    if (deg<0) {
                        ctx.translate(x+(maxWidth/2)-10, y+ ((h/2)+4));
                    } else {
                        ctx.translate(x+(maxWidth/2)+10, y+ ((h/2)+4));
                    }
                    ctx.rotate(parseInt(deg) * Math.PI / 180);
                    context.strokeText(line.toUpperCase(), 0, 0);
                    context.fillText(line.toUpperCase(), 0, 0);
                    ctx.restore();
                }

                ctx.font = label.font;
                ctx.strokeStyle = 'white';
                ctx.lineWidth = 1;
                ctx.shadowBlur = 1;
                ctx.fillStyle = "Black";
                ctx.shadowColor = "rgba(255, 255, 255, 0.8)";
                var text = label.text;
                var stitle_w = longest(label.text);
                var w = ctx.measureText(stitle_w.toUpperCase()).width + 10;
                vtop = (parseInt(label.y-(label.h/2)));

                var stitle_w = longest(label.text,label.size);
                var w=ctx.measureText(stitle_w.toUpperCase()).width;

                if (parseInt(label.deg) == 0) {
                    wrapText(ctx, label.text, label.x, label.y, w+2, parseInt(label.size), parseInt(label.h));
                } else {
                    wrapText2(ctx, label.text, label.x, label.y, w+2, parseInt(label.size), parseInt(label.h),label.deg);
                }	


                // ctx.font = label.font;
                // ctx.strokeStyle = 'white';
                // ctx.lineWidth = 2;
                // ctx.shadowBlur = 2;
                // ctx.fillStyle = "Black";
                // ctx.shadowColor = "rgba(255, 255, 255, 0.5)";
                // var text = label.text;
                // var stitle_w = longest(label.text);
                // var w = ctx.measureText(stitle_w.toUpperCase()).width + 10;
                // var words = text.split(' '),
                //     line = '',
                //     lineCount = 0,
                //     i,
                //     test,
                //     metrics;
                
                // for (i = 0; i < words.length; i++) {
                //     test = words[i];
                //     metrics = ctx.measureText(test);
                //     while (metrics.width > w) {
                //         // Determine how much of the word will fit
                //         test = test.substring(0, test.length - 1);
                //         metrics = ctx.measureText(test);
                //     }
                //     if (words[i] != test) {
                //         words.splice(i + 1, 0,  words[i].substr(test.length))
                //         words[i] = test;
                //     }  

                //     test = line + words[i] + ' ';  
                //     metrics = ctx.measureText(test);
                    
                //     if (metrics.width > w && i > 0) {
                //         ctx.strokeText(line.toUpperCase(), label.x + (w/2) -5, label.y +label_height);
                //         // ctx.rotate(Math.PI/rotation_z);
                //         ctx.fillText(line.toUpperCase(), label.x + (w/2) -5, label.y +label_height);
                //         line = words[i] + ' ';
                //         label.y += label.size - 1;
                //         label.dotY = parseInt(label.dotY) + label.size - 1;
                //         lineCount++;
                //     }
                //     else {
                //         line = test;
                //     }
                // }

                // ctx.strokeText(line.toUpperCase(), label.x + (w/2) - 5, label.y +label_height);
                // ctx.fillText(line.toUpperCase(), label.x + (w/2) - 5, label.y +label_height);		
            }

            //create dots
            // ctx.beginPath();
            // ctx.arc(label.dotX,label.dotY,label.dotRadius,0,Math.PI*2);
            // ctx.fill();
 
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
    
    clearAmenitiesLayer: function() {
        var canvas = document.getElementById('amenities-layer');
        var context = canvas.getContext('2d');
        context.clearRect(0, 0,canvas.width,canvas.height);
        context.save();
    },

    clearTextlayer: function() {
        var canvas = document.getElementById('text-layer');
        var context = canvas.getContext('2d');
        context.clearRect(0, 0,canvas.width,canvas.height);
        context.save();
    },

    showmap: function(map_details,reference) {
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

        const panzoom = this.settings.panzoom;
        if(panzoom) {
            panzoom.zoom(map_details.default_zoom, {
                relative: true,
                animate: true
            })
            setTimeout(() => panzoom.pan(map_details.default_x, map_details.default_y))    
        }

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

    drawescalator: function(from,to,bldg) {
        var text = "";
        var bldg_name = "";
        var store_address = "";

        var node = document.createElement("li");
        if(this.settings.with_disability) {
            node.innerHTML = 'Proceed to <img src="images/services/elevator-black.png" align="middle">';
        } 
        else {
            if((to>from)) {
                node.innerHTML = 'Proceed to <img src="images/services/escalator-black.png" align="middle">';
            }
            else {
                node.innerHTML = 'Proceed to <img src="images/services/stairs-black.png" align="middle">';
            }
        }

        $('.assist').append(node);
        if(this.settings.tenant_store_address) {
            store_address = this.settings.tenant_store_address;

            var node = document.createElement("li");
            node.innerHTML = 'Go to ' + store_address + '';
            $('.assist').append(node);            
        }
        else {
            $.get( "/api/v1/site/maps/get-floor-name/"+to, function(response) {
                text = response.name;
                bldg_name = response.building_name;
                store_address = text + ', ' + bldg_name;

                var node = document.createElement("li");
                node.innerHTML = 'Go to ' + store_address + '';
                $('.assist').append(node);            
            });
        }

        if(this.settings.tenant_details){
            text = this.settings.tenant_details.floor_name;
        }

        var obj = this;

        if(!this.settings.escalator_id) this.settings.escalator_id = setInterval(function(){obj.animate_escalator((to>from),text);},200);
        setTimeout(function(){obj.drawpoints_resume(to);},4000);
    },

    drawesstair: function(from,to,bldg) {
        var text = "";
        var bldg_name = "";
        var store_address = "";

        var node = document.createElement("li");
        node.innerHTML = 'Proceed to <img src="images/services/smcg_stairs.png" align="middle">';
        $('.assist').append(node);

        if(this.settings.tenant_store_address) {
            store_address = this.settings.tenant_store_address;

            var node = document.createElement("li");
            node.innerHTML = 'Go to ' + store_address + '';
            $('.assist').append(node);            
        }
        else {
            $.get( "/api/v1/site/maps/get-floor-name/"+to, function(response) {
                text = response.name;
                bldg_name = response.building_name;
                store_address = text + ', ' + bldg_name;

                var node = document.createElement("li");
                node.innerHTML = 'Go to ' + store_address + '';
                $('.assist').append(node);            
            });
        }

        if(this.settings.tenant_details){
            text = this.settings.tenant_details.floor_name;
        }

        var obj = this;

        if(!this.settings.escalator_id) this.settings.escalator_id = setInterval(function(){obj.animate_escalator((to>from),text);},200);
        setTimeout(function(){obj.drawpoints_resume(to);},4000);
    },

    drawdoor: function(bldg){
        var obj = this;

        var bldg_name = "";

        $.get( "/api/v1/site/maps/get-building-name/"+bldg, function(response) {
            bldg_name = response.name;

            var node = document.createElement("li");
            node.innerHTML = 'Transfer to ' + bldg_name + '';   
            $('.assist').append(node);            
        });

        if(!this.settings.door_id) this.settings.door_id = setInterval(function(){obj.animate_door();},100);
        setTimeout(function(){obj.drawpoints_resume();},3000);
        //obj.assist_bldg(bldg);
    },

    animate_escalator: function(direction,text) {
        if(this.settings.escalator_progress) return;
        this.settings.escalator_progress = 1;
        if(this.settings.frame_escalator > 4) this.settings.frame_escalator = 0;

        var canvas = document.getElementById('escalator-layer');
        var context = canvas.getContext('2d');

        context.clearRect(0,0,canvas.width,canvas.height);
        if(direction) {
            context.drawImage(document.getElementById('marker-escalator-up'),(this.settings.frame_escalator*142),0,142,67,(this.settings.points.linePoint[this.settings.current_point].x),(this.settings.points.linePoint[this.settings.current_point].y-80),142,67);
            context.font = "bold 30px Overpass";
            context.fillStyle = "rgb(71, 131, 162)";
            context.fillText(text.toUpperCase(),(this.settings.points.linePoint[this.settings.current_point].x+65),(this.settings.points.linePoint[this.settings.current_point].y-37));
        }
        else {
            context.drawImage(document.getElementById('marker-escalator-down'),(this.settings.frame_escalator*142),0,142,67,(this.settings.points.linePoint[this.settings.current_point].x),(this.settings.points.linePoint[this.settings.current_point].y-80),142,67);
            context.font = "bold 30px Overpass";
            context.fillStyle = "rgb(71, 131, 162)";
            context.fillText(text.toUpperCase(),(this.settings.points.linePoint[this.settings.current_point].x+65),(this.settings.points.linePoint[this.settings.current_point].y-37));
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

    animate_door: function() {
        if(this.settings.door_progress) return;
        this.settings.door_progress = 1;
        if(this.settings.frame_door > 3) this.settings.frame_door = 0;

        if(this.settings.points.linePoint[this.settings.current_point])
        {
            var canvas = document.getElementById('escalator-layer');
            var context = canvas.getContext('2d');
            var point_x = canvas.width / 2;
            var point_y = canvas.height / 2;
            
            context.save();
            context.translate(point_x,point_y);
            context.scale(this.settings.scale,this.settings.scale);
            context.translate(-point_x,-point_y);
            context.clearRect((this.settings.points.linePoint[this.settings.current_point].x-75),(this.settings.points.linePoint[this.settings.current_point].y-50),155,100);
            context.drawImage(document.getElementById('marker-door'),(this.settings.frame_door*400),0,400,255,(this.settings.points.linePoint[this.settings.current_point].x-75),(this.settings.points.linePoint[this.settings.current_point].y-50),155,100);
            context.restore();
        }

        this.settings.frame_door++;
        this.settings.door_progress = 0;
    },

    animate_door_stop: function(){
        clearInterval(this.settings.door_id);
        this.settings.door_id = 0;
        this.settings.door_progress = 0;
    },

    animate_marker_store: function() {
        if(this.settings.store_progress) return;
        if(this.settings.points.linePoint.length == 0 ) return;

        if(this.settings.points.linePoint[this.settings.points.linePoint.length - 1] && (this.settings.points.linePoint[this.settings.points.linePoint.length - 1].z + '-' + this.settings.points.linePoint[this.settings.points.linePoint.length - 1].z2)!= this.settings.currentmap) return;

        this.settings.store_progress = 1;
		
        if(this.settings.frame_store > 23) this.settings.frame_store = 0;

        var x = this.settings.points.linePoint[this.settings.points.linePoint.length - 1].x;
        var y = this.settings.points.linePoint[this.settings.points.linePoint.length - 1].y;
    
        var canvas = document.getElementById('tenants-layer');
        var context = canvas.getContext('2d');
        
        var point_x = canvas.width / 2;
        var point_y = canvas.height / 2;
        
        context.save();
        context.translate(point_x,point_y);
        context.scale(this.settings.scale,this.settings.scale);
        context.translate(-point_x,-point_y);
        context.clearRect((x-60),(y-170),130.4,150);
        context.drawImage(document.getElementById('marker-store-here'),(this.settings.frame_store*130.4),0,130.4,150,(x-60),(y-170),130.4,150);
        context.restore();
        
        this.settings.frame_store +=1 ;
        this.settings.store_progress = 0;

        // var scale = 0.60;
        // $('.zoomable-container').css({'transform':'scale(' + scale + ')'});

    },

    animate_marker_store_stop: function(){
        clearInterval(this.settings.store_id);
        this.settings.store_id = 0;
        this.settings.store_progress = 0;
    },

    show_tenant_details: function(id) {
    },

    drawline: function(id, tenant, with_disability = 0, panzoom = null) {
        this.settings.panzoom = panzoom;
        this.settings.with_disability = with_disability;
        this.showmap(this.settings.defaultmap);
        $('#repeatButton').hide();
        $('#zoomResetButton').addClass('last-border-radius');
        var tenant_name = tenant.brand_name;
        var tenant_location = tenant.floor_name + ', '+tenant.building_name;
        var tenant_category = tenant.category_name;

        if(tenant.tenant_details) {
            tenant_location = tenant.tenant_details.address;
            this.settings.tenant_store_address = tenant_location;
        }

        $('.tenant-name').html(tenant_name + ', ' + tenant_location);
        $('.tenant-floor').html(tenant_location);
        $('.tenant-category').html(tenant_category);
        $('.assist').html('');

        this.clearMarker();
        this.drawpoints_stop();
        this.show_tenant_details(id);

        this.settings.destination = id;
        this.settings.tenant_details = tenant;
        this.settings.showescalator = 1;
        var obj = this;
        var distance = '';

        setTimeout(() => {
            $.get( "/api/v1/site/maps/get-routes/"+id+"/"+with_disability, function(response) {
                if(response.data.length) {
                    obj.settings.points = { linePoint : []};

                    $.each(response.data,function(index, route) {
                        var x = parseFloat(response.data[index][0]); //point_x
                        var y = parseFloat(response.data[index][1]); //point_y
                        var z = parseFloat(response.data[index][2]); //floor level
                        var z2 = parseFloat(response.data[index][3]); //building
                        var map_id = parseFloat(response.data[index][4]); //building
                        distance = parseFloat(response.data[index][5]); //distance

                        if(index == 0) {
                            obj.settings.points.linePoint.push(new Point(x,y,z,z2,map_id));
                        }else{
                            var tmp_x = parseFloat(obj.settings.points.linePoint[obj.settings.points.linePoint.length-1].x);
                            var tmp_y = parseFloat(obj.settings.points.linePoint[obj.settings.points.linePoint.length-1].y);
                            var tmp_z = parseFloat(obj.settings.points.linePoint[obj.settings.points.linePoint.length-1].z);
                            var tmp_z2 = parseFloat(obj.settings.points.linePoint[obj.settings.points.linePoint.length-1].z2);
                        
                            if(tmp_z != z || tmp_z2 != z2) {
                                obj.settings.points.linePoint.push(new Point(x,y,z,z2,map_id));
                            }
                            else{
                                var StepSize = 10;
                                var delta_x =  x - tmp_x;
                                var delta_y = y - tmp_y;
                                var slope = delta_x == 0 ? 1 : delta_y / delta_x;
                                var b = tmp_y - slope * tmp_x;
                                var loop_exit = true;

                                if(Math.abs(delta_x) < Math.abs(delta_y)) {
                                    
                                    iy_increment = (delta_y < 0) ? -1 * StepSize : StepSize;

                                    for (iy = tmp_y; loop_exit; iy += iy_increment) {

                                        loop_exit = delta_y < 0 ? (iy >= y) : (iy <= y);
                                        ix = slope == 1 ? tmp_x : (iy - b) / slope;
                                        
                                        if (delta_y < 0 ? (iy >= y) : (iy <= y)) {
                                            obj.settings.points.linePoint.push(new Point(Math.floor(ix),Math.floor(iy),tmp_z,tmp_z2,map_id));
                                        }                                    
                                    }
                                }
                                else {
                                    ix_increment = delta_x < 0 ? -1 * StepSize : StepSize;
                                    for (ix = tmp_x; loop_exit; ix += ix_increment)
                                    {
                                        if (loop_exit)
                                        {
                                            loop_exit = delta_x < 0 ? (ix >= x) : (ix <= x);
                                            iy = slope * ix + b;
                                            if (delta_x < 0 ? (ix >= x) : (ix <= x))
                                            {
                                                obj.settings.points.linePoint.push(new Point(Math.floor(ix),Math.floor(iy),tmp_z,tmp_z2,map_id));
                                            }
                                        }
                                    }
                                }                    
                            }
                        }
                    });

                    var steps = (distance / 10).toFixed(0);
                    var mins = (steps / 90).toFixed(0);
                    $(".map-distance").html((steps * 0.74).toFixed(0)  + 'm');
                    $(".map-steps").html( steps + ' steps');
                    $(".map-minutes").html( mins + ' min' + (mins > 1 ? 's' : ''));

                    clearInterval(obj.settings.inter);
                    obj.settings.inter = 0;
                    obj.settings.current_point = 0;
                    
                    if(obj.settings.points.linePoint.length > 1 && !obj.settings.inter) {
                        obj.settings.inter = setInterval(function(){obj.drawpoints()},20);
                    }
                }
            });
        }, 500);
    },

    replay: function(with_disability = 0, panzoom = null){
        this.settings.panzoom = panzoom;
        this.stopall();
        this.clearLine();
        this.clearAmenitiesLayer();
        this.clearTextlayer();
        this.clearEscalator();
        this.drawline(this.settings.destination,this.settings.tenant_details, with_disability, this.settings.panzoom);
    },

    drawpoints_stop: function() {
        clearInterval(this.settings.inter);
        this.settings.inter = 0;
    },

    drawpoints: function() {
        
        if(this.settings.points.linePoint[this.settings.current_point] 
        && this.settings.currentmap != (this.settings.points.linePoint[this.settings.current_point].z + '-' + this.settings.points.linePoint[this.settings.current_point].z2) 
        && this.settings.showescalator) {

            var flr_build = this.settings.currentmap.split('-');
            if(flr_build[0] != this.settings.points.linePoint[this.settings.current_point].z && flr_build[1] == this.settings.points.linePoint[this.settings.current_point].z2) {
                var to = this.settings.points.linePoint[this.settings.current_point].z;
                var bldg = this.settings.points.linePoint[this.settings.current_point].z2;
                this.settings.current_point--;
                this.drawescalator(flr_build[0],to,bldg);
            }
            else {
                var bldg = this.settings.points.linePoint[this.settings.current_point].z2;
                this.settings.current_point--;
                this.drawdoor(bldg);
            }
            clearInterval(this.settings.inter);
            this.settings.inter = 0;
            return;
        }

        if(this.settings.points.linePoint[this.settings.current_point] && (this.settings.points.linePoint[this.settings.current_point].z + '-' + this.settings.points.linePoint[this.settings.current_point].z2) == this.settings.currentmap) {

            var canvas = document.getElementById('line-layer');
            var context = canvas.getContext('2d');
            
            var point_x = canvas.width / 2;
            var point_y = canvas.height / 2;
        
            context.save();
            context.translate(point_x,point_y);0
            context.scale(this.settings.scale,this.settings.scale);
            context.translate(-point_x,-point_y);
            context.strokeStyle = 'red';
            context.fillStyle = 'red';
            context.shadowColor = 'red';
            context.shadowBlur = 2;
            context.lineCap = 'round';
            context.fillRect(this.settings.points.linePoint[this.settings.current_point].x, this.settings.points.linePoint[this.settings.current_point].y,5,5);
            context.restore();
        
        }

        this.settings.current_point++;

        if(parseInt(this.settings.current_point) >= parseInt(this.settings.points.linePoint.length,10))
        {
            this.drawpoints_stop();
            this.settings.storefound = 1;

            var node = document.createElement("li");
            node.innerHTML = 'Follow the <font color="red">red path</font> to your destination';  
            $('.assist').append(node);   

            $('#repeatButton').show();
            $('#zoomResetButton').removeClass('last-border-radius');

            if(!this.settings.store_id)
            {
                var obj = this;
                const panzoom = this.settings.panzoom;
                this.settings.store_id = setInterval(function(){obj.animate_marker_store();},50);

                var x = obj.settings.points.linePoint[obj.settings.points.linePoint.length - 1].x
                var y = obj.settings.points.linePoint[obj.settings.points.linePoint.length - 1].y
                var scale = 0.45;

                if(Math.abs(y) > 900) {
                    y = (y-900);
                }
                else {
                    y = (y-300);
                }

                this.vue_obj.tenant_dropdown = false;
                
                setTimeout(() => {
                    panzoom.pan('-'+(x-500), '-'+(y));
                    panzoom.zoom(scale, {
                        relative: true,
                        animate: true
                    });
                }, 500);
            }
        }

    },

    drawpoints_resume: function(to) {
        var obj = this;
			
        clearInterval(this.settings.escalator_id);
        clearInterval(this.settings.door_id);
        this.settings.escalator_id = 0;
        this.settings.door_id = 0;
        
        this.settings.current_point+=2;
        if(this.settings.points.linePoint[this.settings.current_point])
        {
            this.settings.currentmap = this.settings.points.linePoint[this.settings.current_point].z + '-' + this.settings.points.linePoint[this.settings.current_point].z2;
        }
      
        this.changemap(this.settings.currentmap);
        setTimeout(() => {
            if(!obj.settings.inter) obj.settings.inter = setInterval(function(){obj.drawpoints()},20);
        }, 500);
    },

    changemap: function(id){
        this.stopall();
        this.clearLine();
        this.clearAmenitiesLayer();
        this.clearTextlayer();
        this.clearEscalator();

        var obj = this;
        var flr_build = id.split('-');

        $.get( "/api/v1/site/maps/get-map-id/"+flr_build[0]+"/"+flr_build[1], function(response) {
            obj.showmap(response);
            $('.map-floor-option .multiselect__tags .multiselect__single').html(response.building_floor_name);
        });

        this.settings.mapchange = 1;
        if(this.settings.defaultmap == id)
        {
            this.settings.marker_here_id = setInterval(function(){obj.animate_marker_here(obj.settings.locationx,obj.settings.locationy)},50);
        }
    },

    stopall:function(){
        this.drawpoints_stop();
        this.animate_marker_here_stop();
        this.animate_escalator_stop();
        this.animate_door_stop();
        this.animate_marker_store_stop();
    },

    clearLine: function(){
        var canvas = document.getElementById('line-layer');
        var context = canvas.getContext('2d');
        
        context.clearRect(0, 0,canvas.width,canvas.height);
        this.points = { linePoint : []};
    }, 

    clearMarker: function() {
        var canvas = document.getElementById('tenants-layer');
        var context = canvas.getContext('2d');
        
        context.clearRect(0, 0,canvas.width,canvas.height);
    },

    clearEscalator: function() {
        var canvas = document.getElementById('escalator-layer');
        var context = canvas.getContext('2d');
        
        context.clearRect(0, 0,canvas.width,canvas.height);        
    }

};
