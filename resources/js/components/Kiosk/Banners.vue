<template>
    <div>
        <div id="banner-ads-carousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner" id="carousel-banner">
                <div v-for="(banner, index) in banners.slice(0,2)" :data-index="index" :data-id="banner.id" :class="(index == 0) ? 'carousel-item active' : 'carousel-item'" :data-interval="(banner.display_duration*1000)">
                    <span v-if="helper.getFileExtension(banner.file_type) == 'video'" @click="helper.saveLogs(banner.id, 'Banner Ad')">
                        <video muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">
                            <source :src="banner.material_image_path" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </span>
                    <span v-else-if="helper.getFileExtension(banner.file_type) == 'image'" @click="helper.saveLogs(banner.id, 'Banner Ad')">
                        <img :src="banner.material_image_path" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">
                    </span>
                </div>
            </div>
        </div> 
    </div>
</template>
<script> 
    var count = 2;
    var banner_array = [];

	export default {
        name: "Banners",
        data() {
            return {
                banners: [],
                helper: new Helpers(),
            };
        },

        created() {
            this.getBanners();
        },

        methods: {
            getBanners: function() {
                axios.get('/api/v1/advertisements/banners')
                .then(response => {
                    this.banners = response.data.data;
                    banner_array = response.data.data;
                });
            },

            appendBanners: function(index = null) {
                var helper = new Helpers();
                var class_name = 'carousel-item';
                if(index != null) {
                    count = index;
                    $('#carousel-banner .carousel-item').removeClass('active');
                    class_name = 'carousel-item active';
                }

                if((banner_array.length) >= count) {

                    var type = 'image';
                    type = helper.getFileExtension(banner_array[count].file_type);

                    var carousel_item = '';
                    carousel_item += '<div data-interval="'+banner_array[count].display_duration*1000+'" data-index="'+count+'" data-id="'+banner_array[count].id+'" class="'+class_name+'">';
                        if(type == 'video') {
                            carousel_item += '<span>';
                            carousel_item += '<video muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;" id="logs_'+banner_array[count].id+'" data-id="'+banner_array[count].id+'">';
                            carousel_item += '<source src="'+banner_array[count].material_image_path+'" type="video/ogg">';
                            carousel_item += 'Your browser does not support the video tag.';
                            carousel_item += '</video>';
                            carousel_item += '</span>';
                        }
                        else {
                            carousel_item += '<span>';
                            carousel_item += '<img src="'+banner_array[count].material_image_path+'" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;"  id="logs_'+banner_array[count].id+'" data-id="'+banner_array[count].id+'">';
                            carousel_item += '</span>';
                        }
                    carousel_item += '</div>';
                    $("#carousel-banner").append(carousel_item);
                    // $('#logs_'+banner_array[count].id).on('click', function() {
                    //     var id = $(this).data('id');
                    //     helper.saveLogs(id, 'Banner Ad');
                    // });
                    count++;
                }
            }
        },

        mounted() {
            var obj = this;
            $(document).ready(function(){
                $('#banner-ads-carousel').on('slide.bs.carousel', function () {
                    $('#carousel-banner .carousel-item:first').remove();
                    obj.appendBanners();
                    // reset rotation
                    if(banner_array.length == count) {
                        count = 0;                        
                    }
                });
            });
        },
    };
</script>