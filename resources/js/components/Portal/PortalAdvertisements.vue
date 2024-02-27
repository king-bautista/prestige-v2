<template>
<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fas fa-photo-video"></i>&nbsp;&nbsp;Create Content</h4>
						<h4 v-show="add_record && data_form"><i class="nav-icon fas fa-user-plus"></i> Add New Content</h4>
						<h4 v-show="edit_record && data_form"><i class="nav-icon fas fa-user-edit"></i> Edit Content</h4>
					</div>
					<div class="card-body" v-show="data_list">
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
					<div class="card-body" v-show="data_form">
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="advertisement.name" placeholder="Advertisements Name" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Contract <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<multiselect v-model="advertisement.contract_id" 
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
							<label for="firstName" class="col-sm-3 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<multiselect v-model="advertisement.brand_id" 
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
							<label for="firstName" class="col-sm-3 col-form-label">Duration <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-2">
								<input type="text" class="form-control" v-model="advertisement.display_duration" placeholder="Duration"> 
								<footer class="blockquote-footer">In Seconds</footer>
							</div>
						</div>	
						<div class="form-group row" v-show="edit_record">
							<label for="active" class="col-sm-3 col-form-label">Active</label>
							<div class="col-sm-9">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="active" v-model="advertisement.active">
									<label class="custom-control-label" for="active"></label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">SSP/s</label>
							<div class="col-sm-9">
								<ul v-if="screens.length > 0" class="list-group" style="max-height: 200px; height: auto; overflow: hidden; overflow-y: auto;">
									<li class="list-group-item" v-for="(screen, index) in screens" v-bind:key="index">{{ screen.site_screen_location }}</li>
								</ul>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-12 col-form-label">Material <span class="font-italic text-danger"> *</span></label>
						</div>
						<div v-for="(material, index) in advertisement.materials" v-bind:key="index" v-if="advertisement.contract_id">
							<hr/>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">{{ material.dimension }}</label>								
								<div class="col-sm-5">
									<input type="file" accept="image/*" ref="materials" @change="fileUpload($event, index)" multiple>
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer">Compatible file types: .jpg, .png, .ogv</footer>
								</div>
								<div class="col-sm-3 text-center">
									<span v-if="material.src && material.file_type == 'image'">
										<img v-if="material.src" :src="material.src" class="img-thumbnail"/>
									</span>
									<span v-else-if="material.src && material.file_type == 'video'">
										<video muted="muted" class="img-thumbnail" @load="onImgLoad(index)" controls>
											<source :src="material.src" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>&nbsp;
								<button type="button" class="btn btn-primary pull-right btn-sm" v-show="add_record" @click="storeAdvertisements"><i class="fas fa-plus"></i>&nbsp;Add New Advertisements</button>
								<button type="button" class="btn btn-primary pull-right btn-sm" v-show="edit_record" @click="updateAdvertisements"><i class="fas fa-save"></i>&nbsp;Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
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
                advertisement: {
                    id: '',
					company_id: '',
					contract_id: '',
					brand_id: '',
                    name: '',
                    active: true,
					display_duration: 10,
					materials: []
                },
				companies: [],
                contracts: [],
                brands: [],
				screens: [],
                data_list: true,
				data_form: false,
                add_record: true,
                edit_record: false,
            	dataFields: {
					serial_number: "ID",
					material_thumbnails_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
            		name: "Name", 
					company_name: "Company Name",
					brand_name: "Brand Name",
					display_duration: "Duration (in sec)",
					active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Inactive</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/manage-ads/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Content',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'advertisement.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Content',
            			name: 'Delete',
            			apiUrl: '/portal/manage-ads/delete',
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
				axios.get('/portal/manage-account/user-details')
                .then(response => {
                    var user = response.data.data;
					console.log(user);
					this.contracts = user.company.contracts;			
					this.advertisement.company_id = user.company;
					this.company = user.company;
                });
			},

			contractSelected: function(contract) {
				this.brands = contract.brands;
				this.getCompanyScreens(contract.id);
			},

			getCompanyScreens: function (id) {
				axios.post('/portal/site/site-screen-product/get-screens', {contract_id: id})
                .then(response => {
					this.screens = response.data.data
					this.getMaterialSize();
				});				
			},

			fileUpload: function(e, index) {
				var file = e.target.files[0];
				if(e.target.files[0].type == 'image/jpeg' || e.target.files[0].type == 'image/jpg' || e.target.files[0].type == 'image/png' || e.target.files[0].type == 'video/ogg') {
					var file_type = e.target.files[0].type.split("/");
					var file_path = URL.createObjectURL(file);
					var obj = this;
					var material;

					this.advertisement.materials[index].file = file;
					this.advertisement.materials[index].name = file.name.replace(/\s+/g, '-');
					this.advertisement.materials[index].size = file.size;
					this.advertisement.materials[index].src = file_path;
					this.advertisement.materials[index].file_type = file_type[0];

					if(file_type[0] == 'image') {
						material = new Image;
						material.onload = function() {
							obj.setfilter(index, material.height, material.width);
						};
							
						material.src = file_path;
					}
					else if(file_type[0] == 'video') {
						material = document.createElement("video");
						material.src = file_path;
						material.addEventListener("loadedmetadata", function () {						
							obj.advertisement.display_duration = this.duration;
							obj.setfilter(index, this.videoHeight, this.videoWidth, file_type[0]);
						});
					}
				}
				else {
					toastr.error('Invalid file type. ');
					this.$refs.materials[index].value = null;
					return false;
				}
			},

			setfilter: function(index, height, width, file_type) {
				var up_dimension = width+'x'+height;
				if(this.advertisement.materials[index].dimension != up_dimension && file_type == 'image') {
					toastr.error('Invalid file dimension.');
					this.$refs.materials[index].value = null;
					this.advertisement.materials[index].src = '';
					return false;
				}

				this.advertisement.materials[index].height = height;
				this.advertisement.materials[index].width = width;
			},

			getMaterialSize: function() {
				this.advertisement.materials = [];				
				axios.post('/portal/site/site-screen-product/get-screen-size', this.screens)
                .then(response => {
					var screens = response.data.data;
					screens.forEach(
					key => {
							this.advertisement.materials.push({
								id: '',
								file: '',
								name: '',
								dimension: key.dimension,
								size: '',
								src: '',
								file_type: '',
								width: '',
								height: '',
							});
						}
					);
				});
			},

			AddNewAdvertisements: function() {
				this.advertisement.name = '';
				this.advertisement.contract_id = '';
				this.advertisement.brand_id = '';
				this.advertisement.display_duration = 10;
				this.advertisement.active = true;
				this.advertisement.materials = [];
				this.screens = [];
				this.data_list = false;
				this.data_form = true;
				this.add_record = true;
				this.edit_record = false;
            },

            storeAdvertisements: function() { 
				$('.fa-plus').removeClass('fa-plus').addClass( "fa-spinner fa-spin");

				let formData = new FormData();
				formData.append("name", this.advertisement.name);
				formData.append("company_id", (this.advertisement.company_id) ? JSON.stringify(this.advertisement.company_id) : '');
				formData.append("contract_id", (this.advertisement.contract_id) ? JSON.stringify(this.advertisement.contract_id) : '');
				formData.append("brand_id", (this.advertisement.brand_id) ? JSON.stringify(this.advertisement.brand_id) : '');
				formData.append("display_duration", this.advertisement.display_duration);
				formData.append("active", this.advertisement.active);

				for( let index = 0; index < this.advertisement.materials.length; index++ ) {
					formData.append('files[]', this.advertisement.materials[index].file);
				}

				formData.append("materials", (this.advertisement.materials.length > 0) ? JSON.stringify(this.advertisement.materials) : '');

                axios.post('/portal/manage-ads/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					$('.fa-spinner').removeClass('fa-spinner fa-spin').addClass("fa-plus");
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				});
            },

			editAdvertisements: function(id) {
				axios.get('/portal/manage-ads/'+id)
                .then(response => {
					this.add_record = false;
					this.edit_record = true;
					this.advertisement.materials = [];

                    var advertisement = response.data.data;
					this.contracts = advertisement.company_details.contracts;
                	this.brands = advertisement.contract_details.brands;

					this.advertisement.id = advertisement.id;
					this.advertisement.name = advertisement.name;
					this.advertisement.company_id = advertisement.company_details;
					this.advertisement.contract_id = advertisement.contract_details;
					this.advertisement.brand_id = advertisement.brand_details;
					this.advertisement.display_duration = advertisement.display_duration;
					this.advertisement.active = advertisement.active;

					var obj = this;
					advertisement.materials.forEach(function (material) {
						obj.advertisement.materials.push({
							id: material.id,
							file: '',
							name: '',
							dimension: material.dimension,
							size: material.file_size,
							src: material.material_path,
							file_type: material.file_type,
							width: material.width,
							height: material.height
						});
					});

					axios.post('/portal/site/site-screen-product/get-screens', {contract_id: advertisement.contract_id})
					.then(response => {
						this.screens = response.data.data;
						axios.post('/portal/site/site-screen-product/get-screen-size', this.screens)
						.then(response => {
							var screens = response.data.data;
							screens.forEach(
							key => {
									let obj = advertisement.materials.find(index => index.dimension === key.dimension);
									if(!obj) {
										this.advertisement.materials.push({
											id: '',
											file: '',
											name: '',
											dimension: key.dimension,
											size: '',
											src: '',
											file_type: '',
											width: '',
											height: '',
										});
									}
								}
							);
						});
					});	
                    this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
                });				
            },

            updateAdvertisements: function() {
				$('.fa-save').removeClass('fa-save').addClass( "fa-spinner fa-spin");
				let formData = new FormData();
				formData.append("id", this.advertisement.id);
				formData.append("name", this.advertisement.name);
				formData.append("company_id", (this.advertisement.company_id) ? JSON.stringify(this.advertisement.company_id) : '');
				formData.append("contract_id", (this.advertisement.contract_id) ? JSON.stringify(this.advertisement.contract_id) : '');
				formData.append("brand_id", (this.advertisement.brand_id) ? JSON.stringify(this.advertisement.brand_id) : '');
				formData.append("display_duration", this.advertisement.display_duration);
				formData.append("active", this.advertisement.active);

				for( let index = 0; index < this.advertisement.materials.length; index++ ) {
					if(this.advertisement.materials[index].file) {
						formData.append('files[]', this.advertisement.materials[index].file);
					}
					else {
						formData.append('files[]', this.advertisement.materials[index].src);
					}
				}

				formData.append("materials", (this.advertisement.materials.length > 0) ? JSON.stringify(this.advertisement.materials) : '');

				axios.post('/portal/manage-ads/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					$('.fa-spinner').removeClass('fa-spinner fa-spin').addClass("fa-save");
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})
            },

			backToList: function () {
				this.data_list = true;
				this.data_form = false;
			},

        },
		
        components: {
        	Table,
            Multiselect,
 	   }
    };
</script> 