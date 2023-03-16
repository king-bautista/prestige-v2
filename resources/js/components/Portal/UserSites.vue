<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-tags"></i>&nbsp;&nbsp;User Sites</h4>
					</div>
					<div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <img v-for="(data, index) in sites" v-bind:key="index" class="img-thumbnail" :src="data.site_logo_path" />
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>

    </div>
</template>
<script> 
	export default {
        name: "UserSites",
        data() {
            return {
                sites: [],
            };
        },

        created(){
            axios.get('/portal/manage-account/sites/list')
                .then(response => {
                    this.sites = response.data.data;
                });
        },

        methods: {

        },

    };
</script>