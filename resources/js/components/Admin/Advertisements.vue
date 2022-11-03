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
								<label for="firstName" class="col-sm-4 col-form-label">Material <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="material" @change="materialChange">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Online'">image max size is 1140 x 140 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Banners'">image max size is 470 x 1060 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Fullscreen'">image max size is 1920 x 1080 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Pop-Up'">image max size is 470 x 1060 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Events'">image max size is 286 x 286 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="material" :src="material" class="img-thumbnail" />
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Duration <span class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" v-model="advertisements.display_duration" placeholder="Duration"> 
									<footer class="blockquote-footer">In Seconds</footer>
								</div>
								<div class="col-sm-3">
                                    <date-picker v-model="advertisements.start_date" placeholder="YYYY/MM/DD" :config="options" autocomplete="off"></date-picker>
								</div>
								<div class="col-sm-3 text-center">
                                    <date-picker v-model="advertisements.end_date" placeholder="YYYY/MM/DD" :config="options" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<treeselect v-model="advertisements.company_id" :options="companies" placeholder="Select Company"/>
								</div>
							</div>
                            <div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Associate Sites <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="advertisements.sites"
										:options="sites"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Sites"
										label="name"
										track-by="name"
                                        @select="toggleSelected"
										@remove="toggleUnSelected">
									</multiselect> 
								</div>
							</div>
                            <div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Tenants</label>
								<div class="col-sm-8">
									<multiselect v-model="advertisements.tenants"
										:options="tenants"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Tenants"
										label="brand_site_name"
										track-by="brand_site_name"
										@select="toggleSelectedTenant"
										@remove="toggleUnSelectedTenant">
										<span slot="noOptions">
											Please select a sites
										</span>
									</multiselect> 
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Screens</label>
								<div class="col-sm-8">
									<multiselect v-model="advertisements.screens"
										:options="screens"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Screens"
										label="screen_type_name"
										track-by="screen_type_name"
										@select="toggleSelectedScreen"
										@remove="toggleUnSelectedScreen">
										<span slot="noOptions">
											Please select a sites
										</span>
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
    // Import date picker js
    import datePicker from 'vue-bootstrap-datetimepicker';    
    // Import date picker css
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
	// import the component
	import Treeselect from '@riophae/vue-treeselect'
	// import the styles
	import '@riophae/vue-treeselect/dist/vue-treeselect.css'
	
	export default {
        name: "Advertisements",
        props: {
        	ad_type: {
        		type: String,
        		required: true
        	},
        },
        data() {
            return {
                advertisements: {
                    id: '',
					company_id: '',
                    name: '',
					ad_type: '',
					file_path: '',
					display_duration: '',
					start_date: '',
					end_date: '',
					sites: '',
					tenants: '',
					screens: '',
                    active: true,           
                },
				material: '',
                companies: [],
                sites: [],
                site_ids: [],
                tenants: [],
                tenant_ids: [],
				screens: [],
				screen_ids: [],
                options: {
                    format: 'YYYY/MM/DD',
                    useCurrent: false,
                },
                add_record: true,
                edit_record: false,
            	dataFields: {
					material_image_path: {
            			name: "Preview", 
            			type:"logo", 
            		},
            		name: "Name", 
					company_name: "Company",
            		site_names: "Site Name/s", 
            		tenant_names: "Tenant/s", 
					screen_names: "Screen/s",
					duration: "Duration",
					air_dates: "Airdates",
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
                    created_at: "Date Created"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/advertisement/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Advertisements',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'advertisements.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Advertisements',
            			name: 'Delete',
            			apiUrl: '/admin/advertisements/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Advertisements',
						v_on: 'AddNewAdvertisements',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Advertisements',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
            };
        },

        created(){
            this.getSites();
			this.getCompany();
        },

        methods: {
			getCompany: function() {
				axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
			},

            getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

            getTenants: function() {
                var site_ids = '';
                for (var i = 0; i < this.site_ids.length; i++) {
                    site_ids += this.site_ids[i]+',';
                }
                axios.get('/admin/site/tenant/get-tenants/'+site_ids)
                .then(response => this.tenants = response.data.data);
            },

			getScreens: function() {
                var site_ids = '';
                for (var i = 0; i < this.site_ids.length; i++) {
                    site_ids += this.site_ids[i]+',';
                }
                axios.get('/admin/site/screen/get-screens/'+site_ids)
                .then(response => this.screens = response.data.data);
            },

            toggleSelected: function(value, id) {
				this.site_ids.push(value.id);
                this.getTenants();
				this.getScreens();
			},

			toggleUnSelected: function(value, id) {
				const index = this.site_ids.indexOf(value.id);
				if (index > -1) { // only splice array when item is found
					this.site_ids.splice(index, 1); // 2nd parameter means remove one item only
				}
			},

			toggleSelectedTenant: function(value, id) {
				this.tenant_ids.push(value.id);
			},

			toggleUnSelectedTenant: function(value, id) {
				const index = this.tenant_ids.indexOf(value.id);
				if (index > -1) { // only splice array when item is found
					this.tenant_ids.splice(index, 1); // 2nd parameter means remove one item only
				}
			},

			toggleSelectedScreen: function(value, id) {
				this.screen_ids.push(value.id);
			},

			toggleUnSelectedScreen: function(value, id) {
				const index = this.screen_ids.indexOf(value.id);
				if (index > -1) { // only splice array when item is found
					this.screen_ids.splice(index, 1); // 2nd parameter means remove one item only
				}
			},

			materialChange: function(e) {
				const file = e.target.files[0];
      			this.material = URL.createObjectURL(file);
				this.advertisements.material = file;
			},

			AddNewAdvertisements: function() {
				this.add_record = true;
				this.edit_record = false;
				this.advertisements.company_id = null;
                this.advertisements.name = '';
				this.advertisements.ad_type = this.ad_type;
				this.advertisements.file_path = '';
				this.advertisements.display_duration = '';
				this.advertisements.start_date = '';
				this.advertisements.end_date = '';
				this.advertisements.sites = [];
				this.advertisements.tenants = [];
				this.advertisements.screens = [];
                this.advertisements.active = true;				
				this.$refs.material.value = null;
				this.material = null;

              	$('#site_ad-form').modal('show');
            },

            storeAdvertisements: function() {
				let formData = new FormData();
				formData.append("company_id", this.advertisements.company_id);
				formData.append("name", this.advertisements.name);
				formData.append("ad_type", this.advertisements.ad_type);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("start_date", this.advertisements.start_date);
				formData.append("end_date", this.advertisements.end_date);
				formData.append("sites", this.site_ids);
				formData.append("tenants", this.tenant_ids);
				formData.append("screens", this.screen_ids);
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
				})
				
            },

			editAdvertisements: function(id) {
                axios.get('/admin/advertisement/'+id)
                .then(response => {
                    var advertisements = response.data.data;
					this.advertisements.id = advertisements.id;
					this.advertisements.company_id = advertisements.company_id;
					this.advertisements.name = advertisements.name;
					this.advertisements.ad_type = this.ad_type;
					this.advertisements.file_path = advertisements.name;
					this.advertisements.display_duration = advertisements.display_duration;
					this.advertisements.start_date = advertisements.start_date;
					this.advertisements.end_date = advertisements.end_date;
					this.advertisements.sites = advertisements.sites;
					this.advertisements.tenants = advertisements.tenants;
					this.advertisements.screens = advertisements.screens;
					this.advertisements.active = advertisements.active;
					this.$refs.material.value = null;
					this.advertisements.material = '';
					this.material = advertisements.material_image_path;

					advertisements.sites.forEach((value) => {
						this.site_ids.push(value.id);
                	});

					advertisements.tenants.forEach((value) => {
						this.tenant_ids.push(value.id);
                	});

					advertisements.screens.forEach((value) => {
						this.screen_ids.push(value.id);
                	});

					this.getTenants();
					this.getScreens();

					this.add_record = false;
					this.edit_record = true;

                    $('#site_ad-form').modal('show');
                });
            },

            updateAdvertisements: function() {
				let formData = new FormData();
				formData.append("id", this.advertisements.id);
				formData.append("company_id", this.advertisements.company_id);
				formData.append("name", this.advertisements.name);
				formData.append("ad_type", this.advertisements.ad_type);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("start_date", this.advertisements.start_date);
				formData.append("end_date", this.advertisements.end_date);
				formData.append("sites", this.site_ids);
				formData.append("tenants", this.tenant_ids);
				formData.append("screens", this.screen_ids);
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
			datePicker,
            Multiselect,
			Treeselect
 	   }
    };
</script> 
