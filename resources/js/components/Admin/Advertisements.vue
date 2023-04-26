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
								<label for="firstName" class="col-sm-4 col-form-label">Product Application <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="advertisements.product_application" 
									placeholder="Select Type" 
									:options="product_applications" 
									:searchable="true" 
									:allow-empty="false"
									@select="applicationSelected">
                                    </multiselect> 
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Screens <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="advertisements.screen_ids" 
									track-by="site_screen_location" 
									label="site_screen_location" 
									placeholder="Select Screens" 
									:options="tmp_screens" 
									:searchable="true" 
									:allow-empty="false"
									:multiple="true">
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
							<hr v-if="advertisements.product_application == 'Directory' || advertisements.product_application == 'Digital Signage'"/>
							<div class="form-group row" v-if="advertisements.product_application == 'Directory' || advertisements.product_application == 'Digital Signage'">
								<label for="firstName" class="col-sm-4 col-form-label">Banner Ad  <span class="font-italic text-danger"> *</span>
									<footer class="blockquote-footer">Portrait</footer>
								</label>								
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="portrait_banner_ad" @change="portraitBannerAd"  multiple>
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer">image/video max size is 1080 x 1920 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<span v-if="banner_portrait && helper.getFileExtension(material_type) == 'image'">
										<img v-if="banner_portrait" :src="banner_portrait" class="img-thumbnail" />
									</span>
									<span v-else-if="banner_portrait && helper.getFileExtension(material_type) == 'video'">
										<video muted="muted" class="img-thumbnail">
											<source :src="banner_portrait" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
								</div>
							</div>
							<hr v-if="advertisements.product_application == 'Directory'"/>
							<div class="form-group row" v-if="advertisements.product_application == 'Directory'">
								<label for="firstName" class="col-sm-4 col-form-label">Fullscreen Ad <span class="font-italic text-danger"> *</span>
									<footer class="blockquote-footer">Portrait</footer>
								</label>								
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="material" @change="portraitFullscreenAd"  multiple>
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer">image/video max size is 1080 x 1920 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<span v-if="fullscreen_portrait && helper.getFileExtension(material_type) == 'image'">
										<img v-if="fullscreen_portrait" :src="fullscreen_portrait" class="img-thumbnail" />
									</span>
									<span v-else-if="fullscreen_portrait && helper.getFileExtension(material_type) == 'video'">
										<video muted="muted" class="img-thumbnail">
											<source :src="fullscreen_portrait" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
								</div>
							</div>
							<hr v-if="advertisements.product_application == 'Directory' || advertisements.product_application == 'Digital Signage'"/>
							<div class="form-group row" v-if="advertisements.product_application == 'Directory' || advertisements.product_application == 'Digital Signage'">
								<label for="firstName" class="col-sm-4 col-form-label">Banner Ad <span class="font-italic text-danger"> *</span>
									<footer class="blockquote-footer">Landscape</footer>
								</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="material" @change="landscapeBannerAd"  multiple>
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer">image/video max size is 1920 x 1080 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<span v-if="banner_landscape && helper.getFileExtension(material_type) == 'image'">
										<img v-if="banner_landscape" :src="banner_landscape" class="img-thumbnail" />
									</span>
									<span v-else-if="banner_landscape && helper.getFileExtension(material_type) == 'video'">
										<video muted="muted" class="img-thumbnail">
											<source :src="banner_landscape" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
								</div>
							</div>
							<hr v-if="advertisements.product_application == 'Directory'"/>
							<div class="form-group row" v-if="advertisements.product_application == 'Directory'">
								<label for="firstName" class="col-sm-4 col-form-label">Fullscreen Ad <span class="font-italic text-danger"> *</span>
									<footer class="blockquote-footer">Landscape</footer>
								</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="material" @change="landscapeFullscreenAd" multiple>
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer">image/video max size is 1920 x 1080 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<span v-if="fullscreen_landscape && helper.getFileExtension(material_type) == 'image'">
										<img v-if="fullscreen_landscape" :src="fullscreen_landscape" class="img-thumbnail" />
									</span>
									<span v-else-if="fullscreen_landscape && helper.getFileExtension(material_type) == 'video'">
										<video muted="muted" class="img-thumbnail">
											<source :src="fullscreen_landscape" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
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
					product_application: '',
					screen_ids: '',
                    name: '',
					status_id: '',
                    active: true,
					banner_portrait: {
						file: '',
						with: '',
						height: ''
					},
					banner_landscape: {
						file: '',
						with: '',
						height: ''
					},
					fullscreen_portrait: {
						file: '',
						with: '',
						height: ''
					},        
					fullscreen_landscape: {
						file: '',
						with: '',
						height: ''
					},        
                },
				width: '',
				height: '',
				banner_portrait: '',
				banner_landscape: '',
				fullscreen_portrait: '',
				fullscreen_landscape: '',
				material_type: '',
                companies: [],
                contracts: [],
                brands: [],
				product_applications: ['Digital Signage', 'Directory'],
                screens: [],
				tmp_screens: [],
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
					dimension: "Dimension",
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
            	dataUrl: "/admin/advertisement/list/"+this.product_application,
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
        },

        methods: {
			getCompany: function() {
				axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
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
				this.tmp_screens = this.screens.filter(option => option.product_application == application); 
			},

			portraitBannerAd: function(e) {
				const file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.banner_portrait = URL.createObjectURL(file);
				this.advertisements.banner_portrait.file = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;

				if(file_type[0] == 'image') {
					var img = new Image;
					img.onload = function() {
						
						if(img.width > img.height) {
							obj.banner_portrait = null;
							obj.advertisements.banner_portrait = null;
							obj.$refs.portrait_banner_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.banner_portrait.with = img.width;
							obj.advertisements.banner_portrait.height = img.height;
						}
					};
						
					img.src = this.banner_portrait;
				}
				else if(file_type[0] == 'video') {
					const video = document.createElement("video");
					video.src = this.banner_portrait;
					video.addEventListener("loadedmetadata", function () {
						
						obj.advertisements.display_duration = this.duration;

						if(this.videoWidth > this.videoHeight) {
							obj.banner_portrait = null;
							obj.advertisements.banner_portrait = null;
							obj.$refs.portrait_banner_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
					});
				}

				console.log(this.advertisements.banner_portrait);
			},

			portraitFullscreenAd: function(e) {
				const file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.fullscreen_portrait = URL.createObjectURL(file);
				this.advertisements.fullscreen_portrait.file = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var portrait_width;
				var portrait_height;

				if(file_type[0] == 'image') {
					var img = new Image;
					img.onload = function() {
						portrait_width = img.width;
						portrait_height = img.height;
						if(img.width > img.height) {
							obj.fullscreen_portrait = null;
							obj.advertisements.fullscreen_portrait = null;
							obj.$refs.portrait_banner_ad.value = null;
							toastr.error('Invalid material for banner ad portrait size.');
						}
						else {
							obj.advertisements.banner_portrait.with = img.width;
							obj.advertisements.banner_portrait.height = img.height;
						}
					};
						
					img.src = this.fullscreen_portrait;
				}
				else if(file_type[0] == 'video') {
					const video = document.createElement("video");
					video.src = this.fullscreen_portrait;
					video.addEventListener("loadedmetadata", function () {
						portrait_width = this.videoWidth;
						portrait_height = this.videoHeight;
						
						obj.advertisements.display_duration = this.duration;
					});
				}

				if(portrait_width > portrait_height) {
					this.fullscreen_portrait = null;
					this.advertisements.fullscreen_portrait = null;
					toastr.error('Invalid material for fullscreen portrait size.');
				}
			},

			landscapeBannerAd: function(e) {
				const file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.banner_landscape = URL.createObjectURL(file);
				this.advertisements.banner_landscape = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var portrait_width;
				var portrait_height;

				if(file_type[0] == 'image') {
					var img = new Image;
					img.onload = function() {
						portrait_width = img.width;
						portrait_height = img.height;
					};
						
					img.src = this.banner_landscape;
				}
				else if(file_type[0] == 'video') {
					const video = document.createElement("video");
					video.src = this.banner_landscape;
					video.addEventListener("loadedmetadata", function () {
						portrait_width = this.videoWidth;
						portrait_height = this.videoHeight;
						obj.advertisements.display_duration = this.duration;
					});
				}

				if(portrait_width > portrait_height) {
					this.banner_landscape = null;
					this.advertisements.banner_portrait = null;
					toastr.error('Invalid material for banner ad landscape size.');
				}
			},

			landscapeFullscreenAd: function(e) {
				const file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.fullscreen_landscape = URL.createObjectURL(file);
				this.advertisements.fullscreen_landscape = file;
				
				var file_type = this.material_type.split("/");
				var obj = this;
				var portrait_width;
				var portrait_height;

				if(file_type[0] == 'image') {
					var img = new Image;
					img.onload = function() {
						portrait_width = img.width;
						portrait_height = img.height;
					};
						
					img.src = this.fullscreen_landscape;
				}
				else if(file_type[0] == 'video') {
					const video = document.createElement("video");
					video.src = this.fullscreen_landscape;
					video.addEventListener("loadedmetadata", function () {
						portrait_width = this.videoWidth;
						portrait_height = this.videoHeight;
						obj.advertisements.display_duration = this.duration;
					});
				}

				if(portrait_width > portrait_height) {
					this.fullscreen_landscape = null;
					this.advertisements.fullscreen_landscape = null;
					toastr.error('Invalid material for fullscreen landscape size.');
				}
			},

			AddNewAdvertisements: function() {
				this.advertisements.company_id = null;
				this.advertisements.brand_id = null;
                this.advertisements.name = '';
				this.advertisements.product_application = null;
				this.advertisements.display_duration = '';
                this.advertisements.active = true;				

				this.add_record = true;
				this.edit_record = false;
              	$('#site_ad-form').modal('show');
            },

            storeAdvertisements: function() {
				let formData = new FormData();
				formData.append("company_id", JSON.stringify(this.advertisements.company_id));
				formData.append("brand_id", JSON.stringify(this.advertisements.brand_id));
				formData.append("name", this.advertisements.name);
				formData.append("product_application", this.advertisements.product_application);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("active", this.advertisements.active);
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
					this.advertisements.brand_id = advertisements.brand_details;
					this.advertisements.product_application = this.product_application;
					this.advertisements.name = advertisements.name;
					this.advertisements.file_path = advertisements.name;
					this.advertisements.display_duration = advertisements.display_duration;
					this.advertisements.active = advertisements.active;
					this.add_record = false;
					this.edit_record = true;

                    $('#site_ad-form').modal('show');
                });
            },

            updateAdvertisements: function() {
				let formData = new FormData(); 
				formData.append("id", this.advertisements.id);
				formData.append("company_id", JSON.stringify(this.advertisements.company_id));
				formData.append("brand_id", JSON.stringify(this.advertisements.brand_id));
				formData.append("product_application", this.advertisements.product_application);
				formData.append("name", this.advertisements.name);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("active", this.advertisements.active);
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