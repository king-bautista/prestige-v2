<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-tag"></i>&nbsp;&nbsp;Tenants</h4>
					</div>
					<div class="card-body">
						<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:AddNewTenant="AddNewTenant"
						v-on:editButton="editTenant"
						v-on:DeleteTenant="DeleteTenant"
						v-on:modalBatchUpload="modalBatchUpload"
                        ref="tenantsDataTable">
			          	</Table>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="tenant-form" tabindex="-1" aria-labelledby="tenant-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Tenant</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Tenant</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
                            <div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
								<!-- <div class="col-sm-9"><div>
									<multiselect 
										:multiple="true" 
										:selected="selected" 
										:options="options" 
										group-values="instruments" 
										group-label="name" 
										track-by="name" 
										label="name"></multiselect></div>', -->
                                    <multiselect v-model="tenant.brand_id" 
										track-by="name" 
										label="name" 
										placeholder="Select Brand"
										:selected="selected" 
										:options="brands" 
										:searchable="true" 
										:allow-empty="required">
                                    </multiselect> 
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Site <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
                                    <select class="custom-select" v-model="tenant.site_id" @change="getBuildings($event.target.value)">
									    <option value="">Select Site</option>
									    <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
                                    <select class="custom-select" v-model="tenant.site_building_id" @change="getFloorLevel($event.target.value)">
									    <option value="">Select Building</option>
									    <option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Floor <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
                                    <select class="custom-select" v-model="tenant.site_building_level_id">
									    <option value="">Select Floor</option>
									    <option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-3 col-form-label">Company</label>
								<div class="col-sm-9">
									<treeselect v-model="tenant.company_id" :options="companies" placeholder="Select Company"/>
								</div>
							</div>					
							<div class="form-group row">
								<label for="is_subscriber" class="col-sm-3 col-form-label">Operational Hours</label>
								<div class="col-sm-9">
									<div class="row mb-3 mx-0" v-for="(operational, index)  in tenant.operational_hours ">
										<div class="col-9 d-flex">
											<div class="btn-group-toggle" data-toggle="buttons">
												<label v-bind:class="conditionActive(operational.schedules, 'Sun', index)" @click="getChecked('Sun', index)">SU</label>
												<label v-bind:class="conditionActive(operational.schedules, 'Mon', index)" @click="getChecked('Mon', index)">M</label>
												<label v-bind:class="conditionActive(operational.schedules, 'Tue', index)" @click="getChecked('Tue', index)">T</label>
												<label v-bind:class="conditionActive(operational.schedules, 'Wed', index)" @click="getChecked('Wed', index)">W</label>
												<label v-bind:class="conditionActive(operational.schedules, 'Thu', index)" @click="getChecked('Thu', index)">TH</label>
												<label v-bind:class="conditionActive(operational.schedules, 'Fri', index)" @click="getChecked('Fri', index)">F</label>
												<label v-bind:class="conditionActive(operational.schedules, 'Sat', index)" @click="getChecked('Sat', index)">S</label>
											</div>
											<input type="time" v-model="operational.start_time" class="form-control ml-1 time mr-2" style="width: 120px">
											<p class="m-0 pt-2">to</p>
											<input type="time" v-model="operational.end_time" class="form-control time ml-2" style="width: 120px">
										</div>
										<div class="col-3">
											<i>{{ operational.schedules }} <span v-if="operational.start_time">|</span> {{ operational.start_time }} <span v-if="operational.end_time">to</span> {{ operational.end_time }}</i>
										</div>
									</div>
									<div class="form-group">
                                        <div class="col-12">
                                            <button class="btn btn-link" style="padding-left:0px" @click="addOperationalHours">Add Hours +</button>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store Address</label>
								<div class="col-sm-9">
									<textarea class="form-control" v-model="tenant.address" placeholder="Store Address"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store E-mail</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="tenant.email" placeholder="Store E-mail">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store Contact Number</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="tenant.contact_number" placeholder="Store Contact No.">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store Facebook</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="tenant.facebook" placeholder="Store Facebook">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store Twitter</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="tenant.twitter" placeholder="Store Twitter">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store Instagram</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="tenant.instagram" placeholder="Store Instagram">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Store Website</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="tenant.website" placeholder="Store Website">
								</div>
							</div>
							<div class="form-group row" >
								<label for="tennat_active" class="col-sm-3 col-form-label">Active</label>
								<div class="col-sm-3">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="tennat_active" v-model="tenant.active">
										<label class="custom-control-label" for="tennat_active"></label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="is_subscriber" class="col-sm-3 col-form-label">Is Subscriber</label>
								<div class="col-sm-3">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_subscriber" v-model="tenant.is_subscriber">
										<label class="custom-control-label" for="is_subscriber"></label>
									</div>
								</div>
							</div>
							<div class="form-group row" v-if="tenant.is_subscriber == 1">
								<label for="firstName" class="col-sm-3 col-form-label">Subscriber Logo <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-3">
                                    <input type="file" accept="image/*" ref="subscriber_logo" @change="subscriberLogoChange">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer">image max size is 550 x 550 pixels</footer>
								</div>
								<div class="col-sm-3 offset-sm-1 text-center">
                                    <img v-if="subscriber_logo" :src="subscriber_logo" class="img-thumbnail" />
								</div>
							</div>
                            
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeTenant">Add New Tenant</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateTenant">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      	<!-- End Modal Add New User -->
	  	<div class="modal fade" id="tenantDeleteModal" tabindex="-1" aria-labelledby="tenantDeleteModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to delete?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="removeTenant">OK</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Batch Upload -->
		<div class="modal fade" id="batchModal" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		      <div class="modal-content">
		      <div class="modal-header">
		          <h5 class="modal-title" id="batchModalLabel">Batch Upload</h5>
		          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		          </button>
		      </div>
		      <div class="modal-body">
		          <form>
		              <div class="form-group col-md-12">
		                  <label>CSV File: <span class="text-danger">*</span></label>
		                  <div class="custom-file">
		                      <input type="file" ref="file" v-on:change="handleFileUpload()" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="custom-file-input" id="batchInput">
		                      <label class="custom-file-label" id="batchInputLabel" for="batchInput">Choose file</label>
		                  </div>
		              </div>
		          </form>
		      </div>
		      <div class="modal-footer justify-content-between">
		          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		          <button type="button" class="btn btn-primary" @click="storeBatch">Save changes</button>
		      </div>
		      </div>
		  </div>
		</div>

    </div>
</template>
<script> 
	var schedules = [];
	import Table from '../Helpers/Table';
    import Multiselect from 'vue-multiselect';
	// import the component
	import Treeselect from '@riophae/vue-treeselect'
	// import the styles
	import '@riophae/vue-treeselect/dist/vue-treeselect.css'

	export default {
        name: "Tenant",
        data() {
            return {
                tenant: {
                    id: '',
                    brand_id: '',
                    site_id: '',
                    site_building_id: '',
                    site_building_level_id: '',
                    company_id: '',
                    active: true,
                    is_subscriber: false,
					operational_hours: [],
                    address: '',
                    email: '',
                    contact_number: '',
                    facebook: '',
                    twitter: '',
                    instagram: '',
                    website: '',
                },
				id_to_deleted: 0,
                add_record: true,
                edit_record: false,
				subscriber_logo: '',
                brands: [],
                sites: [],
                buildings: [],
                floors: [],
                companies: [],
            	dataFields: {
					brand_logo: {
            			name: "Brand Logo", 
            			type:"logo", 
            		},            		
					brand_name: "Brand Name", 
					site_name: "Site Name",
                    building_name: "Building Name",
                    floor_name: "Floor Name",
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
            		is_subscriber: {
            			name: "Is Subscriber", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">No</span>', 
            				1: '<span class="badge badge-info">Yes</span>'
            			}
            		},                    
					updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/site/tenant/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Tenant',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'building.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Tenant',
            			name: 'Delete',
            			apiUrl: '/admin/site/tenant/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'custom_delete',
						v_on: 'DeleteTenant',
            		},
					link: {
            			title: 'Manage Product & Promos',
            			name: 'Link',
            			apiUrl: '/admin/site/tenant/products',
            			routeName: '',
            			button: '<i class="fa fa-link"></i> Manage Products',
            			method: 'link',
            			conditions: { is_subscriber:1 }
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Tenant',
						v_on: 'AddNewTenant',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Tenant',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
					batchUpload: {
                        title: 'Batch Upload',
                        v_on: 'modalBatchUpload',
                        icon: '<i class="fas fa-upload"></i> Batch Upload',
                        class: 'btn btn-primary btn-sm',
                        method: 'add'
		            },
				}
            };
        },

        created(){
			this.getSites();
            this.GetBrands();
			this.getCompany();
        },

        methods: {
			subscriberLogoChange: function(e) {
				const file = e.target.files[0];
      			this.subscriber_logo = URL.createObjectURL(file);
				this.tenant.subscriber_logo = file;
			},

			getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

            GetBrands: function() {
				axios.get('/admin/brand/get-all')
                .then(response => this.brands = JSON.stringify(response.data.data));
			},

			getCompany: function() {
				axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
			},

            getBuildings: function(id) {
				axios.get('/admin/site/get-buildings/'+id)
                .then(response => this.buildings = response.data.data);
			},

            getFloorLevel: function(id) {
				axios.get('/admin/site/floors/'+id)
                .then(response => this.floors = response.data.data);
            },

			addOperationalHours: function() {
				this.tenant.operational_hours.push({
					schedules: '',
					start_time: '',
					end_time: '',
				});
			},

			getChecked: function(item, index) {
				var position = (schedules[index]) ? schedules[index].indexOf(item) : -1;
				if(position >= 0) {
					schedules[index] = schedules[index].replace(", "+item, "").replace(item+",", "").replace(item, "");
				}
				else {
					if(schedules[index]) {
						schedules[index] += ', '+item;
					}
					else {
						schedules[index] = item;
					}
				}

				this.tenant.operational_hours[index].schedules = schedules[index];
			},

			AddNewTenant: function() {
				this.removeActiveStatus();
				schedules = [];
				this.add_record = true;
				this.edit_record = false;
                this.tenant.brand_id = '';
                this.tenant.site_id = '';
                this.tenant.site_building_id = '';
                this.tenant.site_building_level_id = '';
                this.tenant.company_id = null;
				this.tenant.operational_hours = [];
				this.tenant.subscriber_logo = '';
				this.tenant.active = true;
				this.tenant.is_subscriber = false;
				this.subscriber_logo = null;
				this.tenant.address = '';
				this.tenant.email = '';
				this.tenant.contact_number = '';
				this.tenant.facebook = '';
				this.tenant.twitter = '';
				this.tenant.instagram = '';
				this.tenant.website = '';			
				this.addOperationalHours();
              	$('#tenant-form').modal('show');
            },

            storeTenant: function() {
				let formData = new FormData();
				formData.append("brand_id", JSON.stringify(this.tenant.brand_id));
				formData.append("site_id", this.tenant.site_id);
				formData.append("site_building_id", this.tenant.site_building_id);
				formData.append("site_building_level_id", this.tenant.site_building_level_id);
				formData.append("company_id", this.tenant.company_id);
				formData.append("operational_hours", JSON.stringify(this.tenant.operational_hours));
				formData.append("active", this.tenant.active);
				formData.append("is_subscriber", this.tenant.is_subscriber);
				formData.append("subscriber_logo", this.tenant.subscriber_logo);
				formData.append("address", this.tenant.address);
				formData.append("email", this.tenant.email);
				formData.append("contact_number", this.tenant.contact_number);
				formData.append("facebook", this.tenant.facebook);
				formData.append("twitter", this.tenant.twitter);
				formData.append("instagram", this.tenant.instagram);
				formData.append("website", this.tenant.website);
                axios.post('/admin/site/tenant/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.tenantsDataTable.fetchData();
					$('#tenant-form').modal('hide');
				});
            },

			editTenant: function(id) {
                axios.get('/admin/site/tenant/'+id)
                .then(response => {
					this.tenant.operational_hours = [];
					schedules = [];

                    var tenant = response.data.data;
                    this.tenant.id = tenant.id;
                    this.tenant.brand_id = tenant.brand_details;
                    this.tenant.site_id = tenant.site_id;
                    this.tenant.site_building_id = tenant.site_building_id;

                    this.getBuildings(tenant.site_id);
                    this.getFloorLevel(tenant.site_building_id);

                    this.tenant.site_building_level_id = tenant.site_building_level_id;
                    this.tenant.company_id = (tenant.company_id) ? tenant.company_id : null;
                    this.tenant.active = tenant.active;
                    this.tenant.is_subscriber = tenant.is_subscriber;
					this.tenant.address = (tenant.tenant_details.address != 'undefined') ? tenant.tenant_details.address : '';
					this.tenant.email = (tenant.tenant_details.email != 'undefined') ? tenant.tenant_details.email : '';
					this.tenant.contact_number = (tenant.tenant_details.contact_number != 'undefined') ? tenant.tenant_details.contact_number : '';
					this.tenant.facebook = (tenant.tenant_details.facebook != 'undefined') ? tenant.tenant_details.facebook : '';
					this.tenant.twitter = (tenant.tenant_details.twitter != 'undefined') ? tenant.tenant_details.twitter : '';
					this.tenant.instagram = (tenant.tenant_details.instagram != 'undefined') ? tenant.tenant_details.instagram : '';
					this.tenant.website = (tenant.tenant_details.website != 'undefined') ? tenant.tenant_details.website : '';

					this.subscriber_logo = '';

					if(tenant.is_subscriber == true) {
						this.tenant.subscriber_logo = '';
						this.subscriber_logo = tenant.subscriber_logo;
					}

					if(tenant.operational_hours) {
						for (let i = 0; i < tenant.operational_hours.length; i++) {
							let operational = tenant.operational_hours[i];

							this.tenant.operational_hours.push({
								schedules: operational.schedules,
								start_time: operational.start_time,
								end_time: operational.end_time
							});

							schedules[i] = operational.schedules;
						}
					}
					else {
						this.addOperationalHours();
					}

					this.add_record = false;
					this.edit_record = true;

					$('#tenant-form').modal('show');
                });
            },

            updateTenant: function() {
				let formData = new FormData();
				formData.append("id", this.tenant.id);
				formData.append("brand_id", JSON.stringify(this.tenant.brand_id));
				formData.append("site_id", this.tenant.site_id);
				formData.append("site_building_id", this.tenant.site_building_id);
				formData.append("site_building_level_id", this.tenant.site_building_level_id);
				formData.append("company_id", this.tenant.company_id);
				formData.append("operational_hours", JSON.stringify(this.tenant.operational_hours));
				formData.append("active", this.tenant.active);
				formData.append("is_subscriber", this.tenant.is_subscriber);
				formData.append("subscriber_logo", this.tenant.subscriber_logo);
				formData.append("address", this.tenant.address);
				formData.append("email", this.tenant.email);
				formData.append("contact_number", this.tenant.contact_number);
				formData.append("facebook", this.tenant.facebook);
				formData.append("twitter", this.tenant.twitter);
				formData.append("instagram", this.tenant.instagram);
				formData.append("website", this.tenant.website);
                axios.post('/admin/site/tenant/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.tenantsDataTable.fetchData();
					$('#tenant-form').modal('hide');
				});
            },

			DeleteTenant: function(data) {
				this.id_to_deleted = data.id;
				$('#tenantDeleteModal').modal('show');
			},

			removeTenant: function() {
				axios.get('/admin/site/tenant/delete/'+this.id_to_deleted)
                .then(response => {
                    this.$refs.tenantsDataTable.fetchData();
                    this.id_to_deleted = 0;
                    $('#tenantDeleteModal').modal('hide');
                });
			},

			modalBatchUpload: function() {
	            $('#batchModal').modal('show');
	        },

			handleFileUpload: function(){
	            this.file = this.$refs.file.files[0];
	            $('#batchInputLabel').html(this.file.name)
	        },

			storeBatch: function() {
	            let formData = new FormData();
	            formData.append('file', this.file);

	            axios.post( '/admin/site/tenant/batch-upload' , formData,
	            {
	                headers: {
	                    'Content-Type': 'multipart/form-data'
	                }
	            }).then(response => {
					this.$refs.file.value = null;
	                this.$refs.tenantsDataTable.fetchData();
                    toastr.success(response.data.message);
	                $('#batchModal').modal('hide');
	                $('#batchInputLabel').html('Choose File');
					window.location.reload();
	            })
	        },

			schedule: function() {
				$(function() {
					$(".custom-btn").on('click',function(){
						if($(this).hasClass('active')) {
							$(this).removeClass('active');
						}
						else {
							$(this).addClass('active');
						}
					});
				});
			},

			removeActiveStatus: function() {
				$(function() {
					$(".custom-btn").removeClass('active');
				});
			},

			conditionActive: function(shedules, item, index) {
				if(shedules.indexOf(item) >= 0) {					
					return 'btn custom-btn active';
				}
				else {
					return 'btn custom-btn';
				}
			},

        },

		mounted() {
            
        },

        components: {
        	Table,
            Multiselect,
			Treeselect
 	    }
    };
</script>
