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
								<label for="firstName" class="col-sm-3 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="advertisement.name" placeholder="Advertisements Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-3 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<multiselect v-model="advertisement.company_id"
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
								<label for="firstName" class="col-sm-3 col-form-label">Status <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
                                    <multiselect v-model="advertisement.status_id" 
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
							<div v-for="(material, index) in advertisement.materials" v-bind:key="index" v-if="advertisement.contract_id">
								<hr/>
								<div class="position-absolute" style="right: 2.5rem; z-index: 9;">
									<button type="button" class="btn btn-outline-danger" @click="deleteRow(index, material.id)"><i class="fas fa-trash-alt"></i></button>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-3 col-form-label">Material <span class="font-italic text-danger"> *</span></label>								
									<div class="col-sm-5">
										<input type="file" accept="image/*" ref="materials" @change="fileUpload($event, index)" multiple>
										<footer class="blockquote-footer">Max file size is 15MB</footer>

									</div>
									<div class="col-sm-3 text-center">
										<span v-if="material.src && material.file_type == 'image'">
											<img v-if="material.src" :src="material.src" class="img-thumbnail"/>
										</span>
										<span v-else-if="material.src && material.file_type == 'video'">
											<video muted="muted" class="img-thumbnail" @load="onImgLoad(index)">
												<source :src="material.src" type="video/ogg">
												Your browser does not support the video tag.
											</video>
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-3 col-form-label">SSP <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-9">
										<button type="button" v-show="material.button_show" class="btn btn-primary" @click="getScreens(index)">Evaluate</button>
										<multiselect v-model="material.screen_ids" 
										track-by="site_screen_location" 
										label="site_screen_location" 
										placeholder="Select Screens" 
										:options="material.screens" 
										:searchable="true" 
										:multiple="true"
										v-show="material.list_show">
										</multiselect>
									</div>
								</div>
							</div>
							<hr v-if="advertisement.contract_id"/>
							<div class="form-group row" v-if="advertisement.contract_id">
								<div class="col-sm-12">
									<button type="button" class="btn btn-primary" @click="addMaterial">Add Material</button>
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
                advertisement: {
                    id: '',
					company_id: '',
					contract_id: '',
					brand_id: '',
                    name: '',
					status_id: '',
                    active: true,
					display_duration: '',
					materials: []
                },
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
            				1: '<span class="badge badge-secondary">Draft</span>',
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
            	dataUrl: "/admin/manage-ads/list",
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
			},

			addMaterial: function() {
				this.advertisement.materials.push({
					id: '',
					file: '',
					name: '',
					size: '',
					src: '',
					file_type: '',
					width: '',
					height: '',
					screens: [],
					screen_ids: [],
					button_show: true,
					list_show: false,
					contract_id: '',
				});
			},

			deleteRow: function(index, id) {
				axios.get('/admin/manage-ads/material/delete/'+id)
                .then(response => {
					if(response.status == false)
						return false;
					
					this.advertisement.materials.splice(index, 1);
				});
			},

			fileUpload: function(e, index) {
				var file = e.target.files[0];
				var file_type = e.target.files[0].type.split("/");
				var file_path = URL.createObjectURL(file);
				var obj = this;
				var material;

				this.advertisement.materials[index].file = file;
				this.advertisement.materials[index].name = file.name.replace(/\s+/g, '-');
				this.advertisement.materials[index].size = file.size;
				this.advertisement.materials[index].src = file_path;
				this.advertisement.materials[index].file_type = file_type[0];
				this.advertisement.materials[index].button_show = true;
				this.advertisement.materials[index].list_show = false;

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
						obj.setfilter(index, this.videoHeight, this.videoWidth);
					});
				}
			},

			setfilter: function(index, height, width) {
				this.advertisement.materials[index].height = height;
				this.advertisement.materials[index].width = width;
				this.advertisement.materials[index].contract_id = this.advertisement.contract_id.id;
			},

			getScreens: function(index) {
				console.log(this.advertisement.materials);
				axios.post('/admin/site/pi-product/get-screens', this.advertisement.materials[index])
                .then(response => {
					this.advertisement.materials[index].screens = response.data.data;
					this.advertisement.materials[index].button_show = false;
					this.advertisement.materials[index].list_show = true;
				});
			},

			AddNewAdvertisements: function() {
				this.advertisement.name = '';
				this.advertisement.company_id = '';
				this.advertisement.contract_id = '';
				this.advertisement.brand_id = '';
				this.advertisement.status_id = '';
				this.advertisement.display_duration = '';
				this.advertisement.active = true;
				this.advertisement.materials = [];
				this.addMaterial();

				this.add_record = true;
				this.edit_record = false;
              	$('#site_ad-form').modal('show');
            },

            storeAdvertisements: function() {
				let formData = new FormData();
				formData.append("company_id", (this.advertisement.company_id) ? JSON.stringify(this.advertisement.company_id) : '');
				formData.append("contract_id", (this.advertisement.contract_id) ? JSON.stringify(this.advertisement.contract_id) : '');
				formData.append("brand_id", (this.advertisement.brand_id) ? JSON.stringify(this.advertisement.brand_id) : '');
				formData.append("name", this.advertisement.name);
				formData.append("status_id", (this.advertisement.status_id) ? JSON.stringify(this.advertisement.status_id) : '');
				formData.append("active", this.advertisement.active);
				formData.append("display_duration", this.advertisement.display_duration);

				for( let index = 0; index < this.advertisement.materials.length; index++ ) {
					formData.append('files[]', this.advertisement.materials[index].file);
				}

				formData.append("materials", (this.advertisement.materials.length > 0) ? JSON.stringify(this.advertisement.materials) : '');

                axios.post('/admin/manage-ads/store', formData, {
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
                axios.get('/admin/manage-ads/'+id)
                .then(response => {
                    var advertisement = response.data.data;

					this.contracts = advertisement.company_details.contracts;
                	this.brands = advertisement.contract_details.brands;

					this.advertisement.materials = [];
					this.advertisement.id = advertisement.id;
					this.advertisement.name = advertisement.name;
					this.advertisement.company_id = advertisement.company_details;
					this.advertisement.contract_id = advertisement.contract_details;
					this.advertisement.brand_id = advertisement.brand_details;
					this.advertisement.status_id = advertisement.transaction_status;
					this.advertisement.display_duration = advertisement.display_duration;
					this.advertisement.active = advertisement.active;

					var obj = this;

					advertisement.materials.forEach(function (material) {
						obj.advertisement.materials.push({
							id: material.id,
							file: '',
							name: '',
							size: material.file_size,
							src: material.material_path,
							file_type: material.file_type,
							width: material.width,
							height: material.height,
							screens: [],
							screen_ids: material.pi_screens,
							button_show: true,
							list_show: false,
							contract_id: advertisement.contract_details.id,
						});
					});

					this.add_record = false;
					this.edit_record = true;

                    $('#site_ad-form').modal('show');
                });
            },

            updateAdvertisements: function() {
				let formData = new FormData();
				formData.append("id", this.advertisement.id);
				formData.append("company_id", (this.advertisement.company_id) ? JSON.stringify(this.advertisement.company_id) : '');
				formData.append("contract_id", (this.advertisement.contract_id) ? JSON.stringify(this.advertisement.contract_id) : '');
				formData.append("brand_id", (this.advertisement.brand_id) ? JSON.stringify(this.advertisement.brand_id) : '');
				formData.append("name", this.advertisement.name);
				formData.append("status_id", (this.advertisement.status_id) ? JSON.stringify(this.advertisement.status_id) : '');
				formData.append("active", this.advertisement.active);
				formData.append("display_duration", this.advertisement.display_duration);

				console.log(this.advertisement.materials.length);
				for( let index = 0; index < this.advertisement.materials.length; index++ ) {
					if(this.advertisement.materials[index].file) {
						formData.append('files[]', this.advertisement.materials[index].file);
					}
					else {
						formData.append('files[]', this.advertisement.materials[index].src);
					}
				}

				formData.append("materials", (this.advertisement.materials.length > 0) ? JSON.stringify(this.advertisement.materials) : '');

				axios.post('/admin/manage-ads/update', formData, {
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