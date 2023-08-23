<template>
    <div class="" style="width: 100%;">
        <div id="popover-content" class="popover hide d-none mr-5" style="z-index:1;">Need help? Touch here.</div>
        <div @click="generateAssist()">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'assistance_tooltip assistance_tooltip-portrait': 'assistance_tooltip'" tabindex="0" data-toggle="popover" data-container="body" data-placement="top" data-trigger="focus" type="button" data-html="true" data-content="Need help? Touch here.">
            </div>
            <div v-bind:class="(site_orientation == 'Portrait') ? 'help-overlay help-overlay-portrait translateme': 'help-overlay translateme'" data-en="Help">Help</div>
        </div>
    </div>
</template>
<script> 
	export default {
        name: "Assistant",
        data() {
            return {
                assitant_message: '',
                location: 'tenantcategory',
                location_content: '',
                content_language: 'english',
                site_orientation: '',
            };
        },

        created() {
            this.getSite();
            this.getAssistantMessage();
        },

        methods: {
            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => {
                    this.site_orientation = response.data.data.site_orientation
                });
			},

            getAssistantMessage: function() {
				axios.get('/api/v1/assistant-message')
                .then(response => {
                    this.assitant_message = response.data.data
                    this.location_content = this.assitant_message.filter(option => option.location == this.location && option.content_language == this.content_language);
                });   
			},
            filterAssist: function(location,content_language) {                
                if (location == null) {      
                    this.content_language = content_language;           
                    this.location_content = this.assitant_message.filter(option => option.location == this.location && option.content_language == content_language);
                }else if (content_language == null) {      
                    this.location = location;           
                    this.location_content = this.assitant_message.filter(option => option.location == location && option.content_language == this.content_language);
                }else {
                    this.location = location;
                    this.content_language = content_language;
                    this.location_content = this.assitant_message.filter(option => option.location == this.location && option.content_language == this.content_language);
                }
                
			},
            generateAssist: function() {
                $("#popover-content").html(this.location_content[0]['content']);
                this.location_content.push(this.location_content.shift());
                $('.assistance_tooltip').popover('show');
			},
        },

        mounted() {
            var obj = this;
            $(function() {
                $('.assistance_tooltip').popover({
                    trigger: 'focus',
                    delay: { "show": 500, "hide": 100 },
                    html: true,
                    content: function() {
                        return $('#popover-content').html();
                    }
                });

                obj.$root.$on('callAssistant', (location,content_language) => {
                    obj.filterAssist(location,content_language);
                });
                obj.$root.$on('callMutateLocation', (location) => {
                    obj.filterAssist(location,null);
                });
            });
        }
    };
</script>