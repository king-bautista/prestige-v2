<template>
    <div>
        <div style="width:0;height:0;position:absolute; top: 0; z-index: 9999;" id="screensaverwidget">
            <div id="fullscreen-ads-carousel" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner" id="carousel-fullscreen">
                    <div v-for="(screen, index) in fullscreen.slice(0,2)" :data-index="index" :data-id="screen.id" :class="(index == 0) ? 'carousel-item active' : 'carousel-item'" :data-interval="(screen.display_duration*1000)" @click="reload_page">
                        <span v-if="getFileExtension(screen.file_type) == 'video'">
                            <video muted="muted" autoplay="true" style="margin: 0px; height: 100%; width: 100%;">
                                <source :src="screen.material_image_path" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                        </span>
                        <span v-else-if="getFileExtension(screen.file_type) == 'image'">
                            <img :src="screen.material_image_path" style="margin: 0px; height: 100%; width: 100%;">
                        </span>
                    </div>
                </div>
            </div>
            <img src="/assets/images/touchheretostart.png" style="width:100%;position:absolute;bottom:0px;left:0px;height:158px;z-index:9999">
        </div>
    </div>
</template>
<script> 
	export default {
        name: "Fullscreen",
        data() {
            return {
                fullscreen: [],
            };
        },

        created() {
            this.getFullscreen();
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

            getFullscreen: function() {
                axios.get('/api/v1/advertisements/fullscreen')
                .then(response => {
                    this.fullscreen = response.data.data;
                    fullscreen_array = response.data.data;
                });
            },

            reload_page: function() {
                this.$router.push('/');
            },
        },
    };
</script>