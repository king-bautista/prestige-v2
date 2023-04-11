<template>
    <div>
        <div style="width:0;height:0;position:absolute; top: 0; z-index: 9999;" id="screensaverwidget" @click="reload_page">
            <div id="fullscreen-ads-carousel" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner" id="carousel-fullscreen">
                    <div v-for="(screen, index) in fullscreens.slice(0,2)" :data-index="index" :data-id="screen.id" :class="(index == 0) ? 'carousel-item active' : 'carousel-item'" :data-interval="(screen.display_duration*1000)">
                        <span v-if="screen.file_type == 'video'">
                            <video muted="muted" autoplay="true" style="margin: 0px; height: 100%; width: 100%;">
                                <source :src="screen.material_image_path" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                        </span>
                        <span v-else>
                            <img :src="screen.material_image_path" style="margin: 0px; height: 100%; width: 100%;">
                        </span>
                    </div>
                </div>
            </div>
            <img src="/assets/images/7f000001-8357-dc26.png" style="width:100%;position:absolute;bottom:0px;left:0px;height:158px;z-index:9999">
        </div>
    </div>
</template>
<script> 
    var countscreen = 2;
    var fullscreen_array = [];

	export default {
        name: "Fullscreen",
        data() {
            return {
                fullscreens: [],
                helper: new Helpers(),
            };
        },

        created() {
            this.getFullscreen();
        },

        methods: {          
            getFullscreen: function() {
                axios.get('/api/v1/advertisements/fullscreen')
                .then(response => {
                    this.fullscreens = response.data.data;
                    fullscreen_array = response.data.data;
                });
            },

            appendFullscreen: function(index = null) {
                var helper = new Helpers();
                var class_name = 'carousel-item';
                if(index != null) {
                    countscreen = index;
                    $('#carousel-fullscreen .carousel-item').removeClass('active');
                    class_name = 'carousel-item active';
                }

                if((fullscreen_array.length) >= countscreen) {

                    var carousel_item = '';
                    carousel_item += '<div data-interval="'+fullscreen_array[countscreen].display_duration*1000+'" data-index="'+countscreen+'" data-id="'+fullscreen_array[countscreen].id+'" class="'+class_name+'">';
                        if(fullscreen_array[countscreen].file_type == 'video') {
                            carousel_item += '<span>';
                            carousel_item += '<video muted="muted" autoplay="true" style="margin: 0px; height: 100%; width: 100%;">';
                            carousel_item += '<source src="'+fullscreen_array[countscreen].material_image_path+'" type="video/ogg">';
                            carousel_item += 'Your browser does not support the video tag.';
                            carousel_item += '</video>';
                            carousel_item += '</span>';
                        }
                        else {
                            carousel_item += '<span>';
                            carousel_item += '<img src="'+fullscreen_array[countscreen].material_image_path+'" style="margin: 0px; height: 100%; width: 100%;">';
                            carousel_item += '</span>';
                        }
                    carousel_item += '</div>';
                    $("#carousel-fullscreen").append(carousel_item);
                    countscreen++;
                }
            },

            reload_page: function() {
                $('.h-button').removeClass('active');
                $('.home-button').addClass('active');
                this.$router.push("/").catch(()=>{});
            },

            callHomeMethod: function(){
                this.$root.$emit('MainCategories');
            }
        },

        mounted() {
            var obj = this;
            $(document).ready(function(){
                $('#fullscreen-ads-carousel').on('slide.bs.carousel', function () {
                    $('#carousel-fullscreen .carousel-item:first').remove();
                    obj.appendFullscreen();
                    // reset rotation
                    if(fullscreen_array.length == countscreen) {
                        countscreen = 0;                        
                    }
                });

                $(document).on('click',function(){
                    $("#screensaverwidget").height('0').width('0');
                    if(screensaver_handle) {
                        clearTimeout(screensaver_handle);	
                        screensaver_handle = null;
                    }

                    screensaver_handle = setTimeout(() => {
                        screensaver_handle = setTimeout(() => {
                            $("#screensaverwidget").height('100%').width('100%');
                            $.get( "/api/v1/get-update", function( data ) {
                                if(data.data.length > 0) {
                                    location.reload();
                                }
                            });
                        }, 5000); // 5 sec delay before showing screensaver
                        obj.callHomeMethod();
                    // }, 5000); // SPEED TEST
                    // }, 5000000000); // DEV MODE
                    }, 2000 * 60 * 2); // 2 min idle time, return to screensaver mode
                });
            });
        },
    };
</script>