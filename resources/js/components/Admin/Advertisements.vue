<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
	    			<div class="card-body">
			          	<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:AddNewAdvertisements="AddNewAdvertisements"
						v-on:editButton="editAdvertisements"
                        ref="dataTable">
			          	</Table>
		          	</div>
		        </div>
	          </div>
	        </div>
	        <!-- /.row -->
	      </div><!-- /.container-fluid -->
	    </section>
	    <!-- /.content -->

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="site_ad-form" data-backdrop="static" tabindex="-1" aria-labelledby="site_ad-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Advertisements</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Advertisements</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="advertisements.name" placeholder="Advertisements Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="advertisements.company_id"
										:options="companies"
										:multiple="false"
										:close-on-select="true"
										:searchable="true" 
										:allow-empty="false"
										placeholder="Select Company"
										label="name"
										track-by="name"
										@select="companySelected">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Contract <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="advertisements.contract_id" 
										:options="contracts" 
										:multiple="false"
										:close-on-select="true"
										:searchable="true" 
										:allow-empty="false"
										track-by="name" 
										label="name" 
										placeholder="Select Contract" 
										@select="contractSelected">
                                    </multiselect> 
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="advertisements.brand_id" 
										:options="brands" 
										:multiple="false"
										:close-on-select="true"
										:searchable="true" 
										:allow-empty="false"
										track-by="name" 
										label="name" 
										placeholder="Select Brand">
                                    </multiselect> 
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Duration <span class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" v-model="advertisements.display_duration" placeholder="Duration"> 
									<footer class="blockquote-footer">In Seconds</footer>
								</div>
							</div>							
							<div class="form-group row" ">
								<label for="firstName" class="col-sm-4 col-form-label">Status <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="advertisements.status_id" 
										:options="statuses" 
										:multiple="false"
										:close-on-select="true"
										:searchable="true" 
										:allow-empty="false"
										track-by="name" 
										label="name" 
										placeholder="Select Status">
                                    </multiselect> 
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" v-model="advertisements.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record" @click="storeAdvertisements">Add New Advertisements</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record" @click="updateAdvertisements">Save Changes</button>
						</div>
					<!-- /.card-body -->
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
		
    </div>
</template>
<script>
	import Table from '../Helpers/Table';
    // Import this component
    import Multiselect from 'vue-multiselect';

	export default {
        name: "Advertisements",
        data() {
            return {
                helper: new Helpers(),
                advertisements: {
                    id: '',
					company_id: '',
					contract_id: '',
					brand_id: '',
                    name: '',
					status_id: '',
                    active: true,
					display_duration: '',      
                },
				material_type: '',
                companies: [],
                contracts: [],
                brands: [],
                statuses: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
					material_image_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
            		name: "Name", 
					company_name: "Company Name",
					brand_name: "Brand Name",
					display_duration: "Duration (in sec)",
            		status_id: {
            			name: "Transaction Status", 
            			type:"Boolean", 
            			status: { 
            				1: '<span class="badge badge-primary">Draft</span>',
            				2: '<span class="badge badge-primary">New</span>',
            				3: '<span class="badge badge-info">Pending approval</span>',
            				4: '<span class="badge badge-danger">Disapprove</span>',
            				5: '<span class="badge badge-success">Approved</span>',
            				6: '<span class="badge badge-secondary">For review</span>',
            				7: '<span class="badge badge-info">Archive</span>',
            				8: '<span class="badge badge-success">Saved</span>',
            			}
            		},
					active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/advertisement/list/",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Content',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'advertisements.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Content',
            			name: 'Delete',
            			apiUrl: '/admin/advertisements/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'Add Content',
						v_on: 'AddNewAdvertisements',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> Add Content',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
            };
        },

        created(){
			this.getCompany();
			this.getStatuses();
        },

        methods: {
			getCompany: function() {
				axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
			},

			getStatuses: function() {
				axios.get('/admin/transaction/statuses/get-all')
                .then(response => this.statuses = response.data.data);
			},

			companySelected: function(company) {
				this.contracts = company.contracts;
			},

			contractSelected: function(contract) {
				this.brands = contract.brands;
				this.screens = contract.screens;
			},

			applicationSelected: function(application) {
				this.advertisements.screen_ids = '';
				if(application == 'All') {
					this.tmp_screens = this.screens;
				}
				else {
					this.tmp_screens = this.screens.filter(option => option.product_application == application); 
				}
			},

			portraitBannerAd: function(e) {
				var file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.banner_portrait = URL.createObjectURL(file);
				this.advertisements.banner_portrait.file = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var material;

				if(file_type[0] == 'image') {
					material = new Image;
					material.onload = function() {
						if(material.width > material.height) {
							obj.banner_portrait = null;
							obj.advertisements.banner_portrait = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.portrait_banner_ad.value = null;							
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.banner_portrait.width = material.width;
							obj.advertisements.banner_portrait.height = material.height;
							obj.advertisements.materials++;							
						}
					};
						
					material.src = this.banner_portrait;
				}
				else if(file_type[0] == 'video') {
					material = document.createElement("video");
					material.src = this.banner_portrait;
					material.addEventListener("loadedmetadata", function () {
						
						obj.advertisements.display_duration = this.duration;

						if(this.videoWidth > this.videoHeight) {
							obj.banner_portrait = null;
							obj.advertisements.banner_portrait = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.portrait_banner_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.banner_portrait.width = this.videoWidth;
							obj.advertisements.banner_portrait.height = this.videoHeight;		
							obj.advertisements.materials++;							
						}
					});
				}
			},

			portraitFullscreenAd: function(e) {
				var file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.fullscreen_portrait = URL.createObjectURL(file);
				this.advertisements.fullscreen_portrait.file = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var material;

				if(file_type[0] == 'image') {
					material = new Image;
					material.onload = function() {
						if(material.width > material.height) {
							obj.fullscreen_portrait = null;
							obj.advertisements.fullscreen_portrait = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.portrait_fullscreen_ad.value = null;							
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.fullscreen_portrait.width = material.width;
							obj.advertisements.fullscreen_portrait.height = material.height;
						}
					};
						
					material.src = this.fullscreen_portrait;
				}
				else if(file_type[0] == 'video') {
					material = document.createElement("video");
					material.src = this.fullscreen_portrait;
					material.addEventListener("loadedmetadata", function () {
						
						obj.advertisements.display_duration = this.duration;

						if(this.videoWidth > this.videoHeight) {
							obj.fullscreen_portrait = null;
							obj.advertisements.fullscreen_portrait = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.portrait_fullscreen_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.fullscreen_portrait.width = this.videoWidth;
							obj.advertisements.fullscreen_portrait.height = this.videoHeight;		
							obj.advertisements.materials++;							
						}
					});
				}
			},

			landscapeBannerAd: function(e) {
				var file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.banner_landscape = URL.createObjectURL(file);
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var material;

				if(file_type[0] == 'image') {
					material = new Image;
					material.onload = function() {
						if(material.height > material.width) {
							obj.banner_landscape = null;
							obj.advertisements.banner_landscape = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.landscape_banner_ad.value = null;							
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.banner_landscape.width = material.width;
							obj.advertisements.banner_landscape.height = material.height;
							obj.advertisements.banner_landscape.file = file;
						}
					};
						
					material.src = this.banner_landscape;
				}
				else if(file_type[0] == 'video') {
					material = document.createElement("video");
					material.src = this.banner_landscape;
					material.addEventListener("loadedmetadata", function () {
						
						obj.advertisements.display_duration = this.duration;

						if(this.videoWidth > this.videoHeight) {
							obj.banner_landscape = null;
							obj.advertisements.banner_landscape = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.landscape_banner_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.banner_landscape.width = this.videoWidth;
							obj.advertisements.banner_landscape.height = this.videoHeight;		
						}
					});
				}
			},

			landscapeFullscreenAd: function(e) {
				var file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.fullscreen_landscape = URL.createObjectURL(file);
				this.advertisements.fullscreen_landscape.file = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var material;

				if(file_type[0] == 'image') {
					material = new Image;
					material.onload = function() {
						if(material.height > material.width) {
							obj.fullscreen_landscape = null;
							obj.advertisements.fullscreen_landscape = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.landscape_fullscreen_ad.value = null;							
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.fullscreen_landscape.width = material.width;
							obj.advertisements.fullscreen_landscape.height = material.height;
						}
					};
						
					material.src = this.fullscreen_landscape;
				}
				else if(file_type[0] == 'video') {
					material = document.createElement("video");
					material.src = this.fullscreen_landscape;
					material.addEventListener("loadedmetadata", function () {
						
						obj.advertisements.display_duration = this.duration;

						if(this.videoWidth > this.videoHeight) {
							obj.fullscreen_landscape = null;
							obj.advertisements.fullscreen_landscape = {
								file: '',
								width: '',
								height: ''
							};
							obj.$refs.landscape_fullscreen_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.fullscreen_landscape.width = this.videoWidth;
							obj.advertisements.fullscreen_landscape.height = this.videoHeight;		
						}
					});
				}
			},

			AddNewAdvertisements: function() {
				this.advertisements.company_id = '';
				this.advertisements.contract_id = '';
				this.advertisements.brand_id = '';
				this.advertisements.product_application = '';
				this.advertisements.screen_ids = '';
				this.advertisements.name = '';
				this.advertisements.status_id = '';
				this.advertisements.active = true;
				this.advertisements.display_duration = '';
				this.advertisements.materials = 0;
				this.advertisements.banner_portrait = {
					file: '',
					width: '',
					height: ''
				};
				this.advertisements.banner_landscape = {
					file: '',
					width: '',
					height: ''
				};
				this.advertisements.fullscreen_portrait = {
					file: '',
					width: '',
					height: ''
				};
				this.advertisements.fullscreen_landscape = {
					file: '',
					width: '',
					height: ''
				};

				this.add_record = true;
				this.edit_record = false;
              	$('#site_ad-form').modal('show');
            },

            storeAdvertisements: function() {
				let formData = new FormData();
				formData.append("company_id", (this.advertisements.company_id) ? JSON.stringify(this.advertisements.company_id) : '');
				formData.append("contract_id", (this.advertisements.contract_id) ? JSON.stringify(this.advertisements.contract_id) : '');
				formData.append("brand_id", (this.advertisements.brand_id) ? JSON.stringify(this.advertisements.brand_id) : '');
				formData.append("product_application", this.advertisements.product_application);
				formData.append("screen_ids", JSON.stringify(this.advertisements.screen_ids));
				formData.append("name", this.advertisements.name);
				formData.append("status_id", (this.advertisements.status_id) ? JSON.stringify(this.advertisements.status_id) : '');
				formData.append("active", this.advertisements.active);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("banner_portrait", (this.advertisements.banner_portrait.file) ? this.advertisements.banner_portrait.file : '');
				formData.append("banner_portrait_dimension", (this.advertisements.banner_portrait.file) ? JSON.stringify(this.advertisements.banner_portrait) : '');
				formData.append("banner_landscape", (this.advertisements.banner_landscape.file) ? this.advertisements.banner_landscape.file : '');
				formData.append("banner_landscape_dimension", (this.advertisements.banner_landscape.file) ? JSON.stringify(this.advertisements.banner_landscape) : '');
				formData.append("fullscreen_portrait", (this.advertisements.fullscreen_portrait.file) ? this.advertisements.fullscreen_portrait.file : '');
				formData.append("fullscreen_portrait_dimension", (this.advertisements.fullscreen_portrait.file) ? JSON.stringify(this.advertisements.fullscreen_portrait) : '');
				formData.append("fullscreen_landscape", (this.advertisements.fullscreen_landscape.file) ? this.advertisements.fullscreen_landscape.file : '');
				formData.append("fullscreen_landscape_dimension", (this.advertisements.fullscreen_landscape.file) ? JSON.stringify(this.advertisements.fullscreen_landscape) : '');

                axios.post('/admin/advertisement/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#site_ad-form').modal('hide');
				});				
            },

			editAdvertisements: function(id) {
                axios.get('/admin/advertisement/'+id)
                .then(response => {
                    var advertisements = response.data.data;
					this.advertisements.id = advertisements.id;
					this.advertisements.company_id = advertisements.company_details;
					this.advertisements.name = advertisements.name;
					this.advertisements.display_duration = advertisements.display_duration;
					this.advertisements.status_id = advertisements.transaction_status;
					this.advertisements.active = advertisements.active;

					this.contracts = advertisements.company_details.contracts;
                	this.brands = advertisements.contract_details.brands;
					this.screens = advertisements.contract_details.screens;
					this.tmp_screens = [];
					if(advertisements.product_application == 'All') {
						this.tmp_screens = this.screens;
					}
					else {
						this.tmp_screens = this.screens.filter(option => option.product_application == advertisements.product_application);
					}

					this.advertisements.contract_id = advertisements.contract_details;
					this.advertisements.brand_id = advertisements.brand_details;
					this.advertisements.product_application = advertisements.product_application;
					this.advertisements.screen_ids = advertisements.screens;

					this.add_record = false;
					this.edit_record = true;

                    $('#site_ad-form').modal('show');
                });
            },

            updateAdvertisements: function() {
				let formData = new FormData();
				formData.append("id", this.advertisements.id);
				formData.append("company_id", (this.advertisements.company_id) ? JSON.stringify(this.advertisements.company_id) : '');
				formData.append("contract_id", (this.advertisements.contract_id) ? JSON.stringify(this.advertisements.contract_id) : '');
				formData.append("brand_id", (this.advertisements.brand_id) ? JSON.stringify(this.advertisements.brand_id) : '');
				formData.append("product_application", this.advertisements.product_application);
				formData.append("screen_ids", JSON.stringify(this.advertisements.screen_ids));
				formData.append("name", this.advertisements.name);
				formData.append("status_id", (this.advertisements.status_id) ? JSON.stringify(this.advertisements.status_id) : '');
				formData.append("active", this.advertisements.active);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("banner_portrait", (this.advertisements.banner_portrait.file) ? this.advertisements.banner_portrait.file : '');
				formData.append("banner_portrait_dimension", (this.advertisements.banner_portrait.file) ? JSON.stringify(this.advertisements.banner_portrait) : '');
				formData.append("banner_landscape", (this.advertisements.banner_landscape.file) ? this.advertisements.banner_landscape.file : '');
				formData.append("banner_landscape_dimension", (this.advertisements.banner_landscape.file) ? JSON.stringify(this.advertisements.banner_landscape) : '');
				formData.append("fullscreen_portrait", (this.advertisements.fullscreen_portrait.file) ? this.advertisements.fullscreen_portrait.file : '');
				formData.append("fullscreen_portrait_dimension", (this.advertisements.fullscreen_portrait.file) ? JSON.stringify(this.advertisements.fullscreen_portrait) : '');
				formData.append("fullscreen_landscape", (this.advertisements.fullscreen_landscape.file) ? this.advertisements.fullscreen_landscape.file : '');
				formData.append("fullscreen_landscape_dimension", (this.advertisements.fullscreen_landscape.file) ? JSON.stringify(this.advertisements.fullscreen_landscape) : '');

				axios.post('/admin/advertisement/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#site_ad-form').modal('hide');
				})
            },

        },

        components: {
        	Table,
            Multiselect,
 	   }
    };
</script> 