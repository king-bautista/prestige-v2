<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-tags"></i>&nbsp;&nbsp;User Brands</h4>
					</div>
					<div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <img v-for="(data, index) in brands" v-bind:key="index" class="img-thumbnail-brands" :src="data.logo_image_path" />
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
        name: "UserBrands",
        data() {
            return {
                brands: [],
            };
        },

        created(){
            axios.get('/portal/manage-account/brand/list')
                .then(response => {
                    this.brands = response.data.data;
                });
        },

        methods: {

        },

    };
</script>