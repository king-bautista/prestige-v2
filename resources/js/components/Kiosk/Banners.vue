<template>
    <div>
        <div id="banner-ads-carousel" class="carousel slide carousel-fade" data-ride="carousel" data-pause="false">
            <div class="carousel-inner" id="carousel-banner">
                <div v-for="(banner, index) in banners.slice(0,2)" :data-index="index" :data-id="banner.id" :class="(index == 0) ? 'carousel-item active' : 'carousel-item'" :data-interval="(banner.display_duration*1000)">
                    <span v-if="banner.file_type == 'video'" @click="helper.saveLogs(banner, 'Banner Ad'); showTenant(banner.tenant_details);">
                        <video muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">
                            <source :src="banner.material_path" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </span>
                    <span v-else @click="helper.saveLogs(banner, 'Banner Ad'); showTenant(banner.tenant_details);">
                        <img :src="banner.material_path" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">
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
                    banner_array = this.banners;
                });
            },

            appendBanners: function(index = null) {
                var obj = this;
                var helper = new Helpers();
                var class_name = 'carousel-item';
                if(index != null) {
                    count = index;
                    $('#carousel-banner .carousel-item').removeClass('active');
                    class_name = 'carousel-item active';
                }

                if((banner_array.length) > count) {

                    var carousel_item = '';
                    carousel_item += '<div data-interval="'+banner_array[count].display_duration*1000+'" data-index="'+count+'" class="'+class_name+'">';
                        if(banner_array[count].file_type == 'video') {
                            carousel_item += '<span>';
                            carousel_item += '<video muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;" class="banner-add-'+count+'" data-id="'+count+'">';
                            carousel_item += '<source src="'+banner_array[count].material_path+'" type="video/ogg">';
                            carousel_item += 'Your browser does not support the video tag.';
                            carousel_item += '</video>';
                            carousel_item += '</span>';
                        }
                        else {
                            carousel_item += '<span>';
                            carousel_item += '<img src="'+banner_array[count].material_path+'" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;" class="banner-add-'+count+'" data-id="'+count+'">';
                            carousel_item += '</span>';
                        }
                    carousel_item += '</div>';
                    $("#carousel-banner").append(carousel_item);
                    $('.banner-add-'+count).on('click', function() {
                        var id = $(this).data('id');
                        helper.saveLogs(banner_array[id], 'Banner Ad');
                        obj.showTenant(banner_array[id].tenant_details);
                    });
                    count++;
                }
            },

            showTenant: function(tenant) {
                if(tenant) {
                    if(tenant.is_subscriber) {
                        this.$root.$emit('showTenantSubscriber', tenant, 'bannerAd');
                    }
                    else {
                        this.$root.$emit('showTenantMap', tenant, 'bannerAd');
                    }
                }
            },

        },

        mounted() {
            var obj = this;
            $(document).ready(function(){
                $('#banner-ads-carousel').on('slide.bs.carousel', function () {                    
                    obj.appendBanners();
                    if($('#carousel-banner .carousel-item').length > 3) {
                        $('#carousel-banner .carousel-item:first').remove();
                    }

                    // reset rotation
                    if(banner_array.length == count) {
                        count = 0;                        
                    }
                });

                const carousel_item = document.querySelectorAll('.carousel-item');
                carousel_item.forEach(function(item) {
                    item.addEventListener('touchend', (e) => {
                            if(e.touches.length > 1) {
                                console.log('1');
                                return false;
                            }
                            else {
                                console.log('2');
                                return true;
                            }
                        }
                    );
                });

            });
        },
    };
</script>