<template>
    <div>
        <div id="banner-ads-carousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <div v-for="(banner, index) in banners[0]" :class="(index == 0) ? 'carousel-item active' : 'carousel-item'" :data-interval="(banner.display_duration*1000)">
                    <span v-if="getFileExtension(banner.file_type) == 'video'">
                        <video preload="none" muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%;">
                            <source :src="banner.material_image_path" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </span>
                    <span v-else-if="getFileExtension(banner.file_type) == 'image'">
                        <img :src="banner.material_image_path" style="border-radius: 20px; margin: 0px; height: 100%;">
                    </span>
                </div>
            </div>
        </div> 
    </div>
</template>
<script> 
    var count = 1;
    var banner_array = [];
	export default {
        name: "Banners",
        data() {
            return {
                banners: [],
            };
        },

        created() {
            this.getBanners();
        },

        methods: {
            getFileExtension(fileType) {			
                switch(fileType) {
                    case 'ogg':
                    case 'ogv':
                    case 'mp4':
                    case 'wmv':
                    case 'avi':
                    case 'mkv':
                    case 'video/ogg':
                    case 'video/ogv':
                    case 'video/mp4':
                    case 'video/wmv':
                    case 'video/avi':
                    case 'video/mkv':
                        return 'video';
                        break;
                    case 'jpeg':
                    case 'jpg':
                    case 'png':
                    case 'gif':
                    case 'image/jpeg':
                    case 'image/jpg':
                    case 'image/png':
                    case 'image/gif':
                        return 'image';
                        break;
                    default:
						return false;
                        break;
                }
            },

            getBanners: function() {
                axios.get('/api/v1/advertisements/banners')
                .then(response => {
                    this.banners = response.data.data;
                    banner_array = response.data.data;
                });
            }
        },

        mounted() {
            $(function() {
                $('#banner-ads-carousel').on('slide.bs.carousel', function () {
                    if(banner_array[count].length > 0) {
                        $.each(banner_array[count], function (index, banner) {
                            console.log(banner);
                            var carousel_item = '';
                            carousel_item = '<div class=carousel-item data-interval="('+banner.display_duration*1000+')">';
                                if(getFileExtension(banner.file_type) == 'video') {
                                    carousel_item += '<span>';
                                    carousel_item += '<video preload="none" muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%;">';
                                    carousel_item += '<source src="'+banner.material_image_path+'" type="video/ogg">';
                                    carousel_item += 'Your browser does not support the video tag.';
                                    carousel_item += '</video>';
                                    carousel_item += '</span>';
                                }

                                <span v-else-if="getFileExtension(banner.file_type) == 'image'">
                                    <img :src="banner.material_image_path" style="border-radius: 20px; margin: 0px; height: 100%;">
                                </span>
                            </div>'
                        });
                        count++;
                    }
                })
            })
        },

        components: {
 	    }
    };
</script>