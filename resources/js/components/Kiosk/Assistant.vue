<template>
    <div class="" style="width: 100%;">
        <div id="popover-content" class="hide d-none" style="z-index:1">
            Need help? Touch here.
        </div>
        <div tabindex="0" data-toggle="popover" data-container="body" data-placement="left" data-trigger="focus" type="button" data-html="true"  class="assistance_tooltip" data-content="Need help? Touch here.">
            <img src="assets/images/English/Help.png" id="helpbutton" @click="generateAssist()">
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
            };
        },

        created() {
            this.getAssistantMessage();
        },

        methods: {
            getAssistantMessage: function() {
				axios.get('/api/v1/assistant-message')
                .then(response => {
                    this.assitant_message = response.data.data
                    this.location_content = this.assitant_message.filter(option => option.location == this.location && option.content_language == this.content_language);
                });   
			},
            filterAssist: function(value) {
                this.location = value;
                // this.content_language = content_language;
                this.location_content = this.assitant_message.filter(option => option.location == this.location && option.content_language == this.content_language);
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

                obj.$root.$on('callAssistantFrom', (value) => {
                    obj.filterAssist(value);
                });
            });
        }
    };
</script>