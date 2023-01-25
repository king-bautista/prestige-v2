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
						v-on:AddNewContent="AddNewContent"
						v-on:editButton="editContent"
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
		<div class="modal fade" id="content-form" data-backdrop="static" tabindex="-1" aria-labelledby="content-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Content</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Content</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="row" v-if="!content.advertisement_id">
								<div class="col-sm-12">
									<Table 
									:dataFields="adsDataFields"
									:dataUrl="adsDataUrl"
									:actionButtons="adsActionButtons"
									:primaryKey="adsPrimaryKey"
									v-on:editButton="selectedAd"
									ref="AdvertisementdataTable">
									</Table>
								</div>
							</div>
							<div v-if="content.advertisement_id">
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Material</label>
									<div class="col-sm-3 text-center">
										<span v-if="helper.getFileExtension(content.advertisement_id.material_image_path) == 'image'">
											<img :src="content.advertisement_id.material_image_path" class="img-thumbnail" />
										</span>
										<span v-else-if="helper.getFileExtension(content.advertisement_id.material_image_path) == 'video'">
											<video muted="muted" class="img-thumbnail">
												<source :src="content.advertisement_id.material_image_path" type="video/ogg">
												Your browser does not support the video tag.
											</video>
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Ad Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_id.name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Brand Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_id.brand_name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Company Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_id.company_name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Dimension</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_id.dimension }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="Site" class="col-sm-4 col-form-label">Site <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<multiselect v-model="content.site_id"
											:options="sites"
											:multiple="false"
											:close-on-select="true"
											placeholder="Select Site"
											label="name"
											track-by="name"
											@select="getTenants">
										</multiselect>
									</div>
								</div>
								<div class="form-group row">
									<label for="Tenant" class="col-sm-4 col-form-label">Tenant <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<multiselect v-model="content.site_tenant_id" 
										track-by="brand_site_name" 
										label="brand_site_name" 
										placeholder="Select Tenant" 
										:options="tenants" 
										:searchable="true" 
										:allow-empty="false">
										</multiselect> 
									</div>
								</div>
								<div class="form-group row">
									<label for="Screen" class="col-sm-4 col-form-label">Screen <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<multiselect v-model="content.site_screen_id" 
										track-by="screen_type_name" 
										label="screen_type_name" 
										placeholder="Select Screen" 
										:multiple="true"
										:options="screens" 
										:searchable="true" 
										:allow-empty="false">
										</multiselect> 
									</div>
								</div>
								<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Duration <span class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" v-model="content.display_duration" placeholder="Duration" readonly> 
									<footer class="blockquote-footer">In Seconds</footer>
								</div>
								<div class="col-sm-3">
                                    <date-picker v-model="content.start_date" placeholder="YYYY/MM/DD" :config="options" autocomplete="off"></date-picker>
								</div>
								<div class="col-sm-3 text-center">
                                    <date-picker v-model="content.end_date" placeholder="YYYY/MM/DD" :config="options" autocomplete="off"></date-picker>
								</div>
							</div>
								<div class="form-group row">
									<label for="uom" class="col-sm-4 col-form-label">No. of Slots <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-2">
										<input type="number" class="form-control" v-model="content.uom">
									</div>
								</div>
								<div class="form-group row" v-show="edit_record">
									<label for="Status" class="col-sm-4 col-form-label">Status</label>
									<div class="col-sm-8">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" id="active" v-model="content.active">
											<label class="custom-control-label" for="active"></label>
										</div>
									</div>
								</div>
								<div class="form-group row" v-show="edit_record">
									<label for="Active" class="col-sm-4 col-form-label">Active</label>
									<div class="col-sm-8">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" id="active" v-model="content.active">
											<label class="custom-control-label" for="active"></label>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-if="content.advertisement_id" v-show="add_record" @click="storeContent">Add New Content</button>
							<button type="button" class="btn btn-primary pull-right" v-if="content.advertisement_id" v-show="edit_record" @click="updateContent">Save Changes</button>
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

	export default {
        name: "Content",
        data() {
            return {
                helper: new Helpers(),
                content: {
                    id: '',
					advertisement_id: '',
					site_id: '',
					site_screen_id: '',
                    site_tenant_id: '',
                    start_date: '',
					end_date: '',
					uom: '',
					status_id: '',
                    active: true,           
                },
                sites: [],
                tenants: [],
                screens: [],
				options: {
                    format: 'YYYY/MM/DD',
                    useCurrent: false,
                },
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
            	dataUrl: "/admin/content-management/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Content',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'content.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Content',
            			name: 'Delete',
            			apiUrl: '/admin/content/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'Add New Content',
						v_on: 'AddNewContent',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> Add New Content',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},

				adsDataFields: {
					material_image_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
            		name: "Name", 
					company_name: "Company Name",
					brand_name: "Brand Name",
					display_duration: "Duration (in sec)",
            	},
				adsPrimaryKey: "id",
            	adsDataUrl: "/admin/advertisement/all",
				adsActionButtons: {
            		edit: {
            			title: 'Add',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'content.edit',
            			button: '<i class="far fa-check-circle"></i> Add',
            			method: 'edit'
            		},
            	},

            };
        },

        created(){
			this.getSites();
        },

        methods: {
			getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

			getTenants: function(value, id) {
                axios.get('/admin/site/tenant/get-tenants/'+value.id)
                .then(response => this.tenants = response.data.data);

                this.getScreens(value.id)
            },

            getScreens: function(id) {
                axios.get('/admin/site/screen/get-screens/'+id)
                .then(response => this.screens = response.data.data);
            },

			AddNewContent: function() {
				this.add_record = true;
				this.edit_record = false;
				this.content.advertisement_id = null;
				this.content.site_id = null;
                this.content.site_screen_id = '';
				this.content.site_tenant_id = ''
				this.content.start_date = '';
				this.content.end_date = '';
				this.content.uom = '';
				this.content.status_id = '';
                this.content.active = true;				
              	$('#content-form').modal('show');
            },

            storeContent: function() {
				let formData = new FormData();
				formData.append("company_id", JSON.stringify(this.content.company_id));
				formData.append("brand_id", JSON.stringify(this.content.brand_id));
				formData.append("name", this.content.name);
				formData.append("ad_type", this.content.ad_type);
				formData.append("file_path", this.content.material);
				formData.append("display_duration", this.content.display_duration);
				formData.append("active", this.content.active);
                axios.post('/admin/advertisement/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#content-form').modal('hide');
				});				
            },

			editContent: function(id) {
                axios.get('/admin/advertisement/'+id)
                .then(response => {
                    var content = response.data.data;
					this.content.id = content.id;
					this.content.company_id = content.company_details;
					this.content.brand_id = content.brand_details;
					this.content.ad_type = this.ad_type;
					this.content.name = content.name;
					this.content.file_path = content.name;
					this.content.display_duration = content.display_duration;
					this.content.active = content.active;
					this.$refs.material.value = null;
					this.content.material = '';
					this.material = content.material_image_path;
					this.add_record = false;
					this.edit_record = true;

                    $('#content-form').modal('show');
                });
            },

            updateContent: function() {
				let formData = new FormData();
				formData.append("id", this.content.id);
				formData.append("company_id", JSON.stringify(this.content.company_id));
				formData.append("brand_id", JSON.stringify(this.content.brand_id));
				formData.append("ad_type", this.content.ad_type);
				formData.append("name", this.content.name);
				formData.append("file_path", this.content.material);
				formData.append("display_duration", this.content.display_duration);
				formData.append("active", this.content.active);
                axios.post('/admin/advertisement/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#content-form').modal('hide');
				})
            },

			selectedAd: function(id) {
				axios.get('/admin/advertisement/'+id)
                .then(response => {
                    this.content.advertisement_id = response.data.data;
					this.content.display_duration = this.content.advertisement_id.display_duration;
					console.log(this.content.advertisement_id);
                });
			}

        },

        components: {
        	Table,
            Multiselect,
			datePicker
 	   }
    };
</script> 