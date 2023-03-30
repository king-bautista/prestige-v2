<template>
	<div>
        <!-- Main content -->
	   
	      <div>
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fas fa-photo-video"></i>&nbsp;&nbsp;Upload Content</h4>
						
					</div>
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
	    
	    <!-- /.content -->

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="content-form" data-backdrop="static" tabindex="-1" aria-labelledby="content-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Content</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Content</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
									<div class="col-sm-3 text-center" id="ad-holder">
										<span v-if="helper.getFileExtension(content.advertisement_id.material_image_path) == 'image'">
											<img :src="content.advertisement_id.material_image_path" class="img-thumbnail" />
										</span>
										<span v-else-if="helper.getFileExtension(content.advertisement_id.material_image_path) == 'video'">
											<video muted="muted" class="img-thumbnail">
												<source :src="content.advertisement_id.material_image_path" type="video/ogg">
												Your browser does not support the video tag.
											</video>
										</span>

										<div class="edit-button"><a @click="content.advertisement_id = null" class="bg-success"><i class="fas fa-edit"></i> CHANGE </a></div>
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
									<label for="Active" class="col-sm-4 col-form-label">Active</label>
									<div class="col-sm-8">
										<div  class="form-check form-switch">
											<input type="checkbox" class="form-check-input" id="active" v-model="content.active">
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
                transaction_statuses: [],
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
            		ad_name: "Name", 
					company_name: "Company Name",
					brand_name: "Brand Name",
					site_name: "Site Name",
					uom: "No. of Slots",
					display_duration: "Duration (in sec)",
					dimension: "Dimension",
            		status_id: {
            			name: "Transaction Status", 
            			type:"Boolean", 
            			status: { 
            				1: '<span class="badge bg-primary">Draft</span>',
            				2: '<span class="badge bg-primary">New</span>',
            				3: '<span class="badge bg-info">Pending approval</span>',
            				4: '<span class="badge bg-danger">Disapprove</span>',
            				5: '<span class="badge bg-success">Approved</span>',
            				6: '<span class="badge bg-secondary">For review</span>',
            				7: '<span class="badge bg-info">Archive</span>',
            				8: '<span class="badge bg-success">Saved</span>',
            			}
            		},
					active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge bg-danger">Deactivated</span>', 
            				1: '<span class="badge bg-info">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/upload-content/list",
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
            			apiUrl: '/portal/upload-content/delete',
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
            	adsDataUrl: "/portal/create-ad/all",
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
			this.getStatuses();
        },

        methods: {
			getSites: function () {
				axios.get('/portal/property-details/get-all')
					.then(response => this.sites = response.data.data);
			},
			getTenants: function (value, id) {
				axios.get('/portal/tenant/get-tenants/' + value.id)
					.then(response => this.tenants = response.data.data);
				this.getScreens(value.id)
			},
			getScreens: function (id) {
				axios.get('/portal/maps/get-screens/' + id)
					.then(response => this.screens = response.data.data);
			},
			getStatuses: function (id) {
				axios.get('/portal/upload-content/transaction-statuses')
					.then(response => this.transaction_statuses = response.data.data);
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
                axios.post('/portal/upload-content/store', this.content)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#content-form').modal('hide');
				});				
            },

			editContent: function(id) {
                axios.get('/portal/upload-content/'+id)
                .then(response => {
                    var content = response.data.data;

					this.getTenants(content.site_details);
					this.content.id = content.id;
					this.content.advertisement_id = content.advertisement_details;
					this.content.site_id = content.site_details;
					this.content.site_screen_id = content.screens;
					this.content.site_tenant_id = content.tenant_details;
					this.content.start_date = content.start_date;
					this.content.end_date = content.end_date;
					this.content.uom = content.uom;
					this.content.display_duration = content.advertisement_details.display_duration;
					this.content.status_id = content.status_details;
					this.content.active = content.active;

					this.add_record = false;
					this.edit_record = true;

                    $('#content-form').modal('show');
                });
            },

            updateContent: function() {
                axios.put('/portal/upload-content/update', this.content)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#content-form').modal('hide');
				})
            },

			selectedAd: function(id) {
				axios.get('/portal/create-ad/'+id)
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
<style scoped>
	#ad-holder {
		position: relative;
	}

	.edit-button {
		position: absolute;
		width: 100%;
		left: 0;
		top: 45%;
		text-align: center;
		opacity: 0;
		transition: opacity .35s ease;
	}

	.edit-button a {
		width: 200px;
		padding: 12px 16px;
		text-align: center;
		color: white;
		border: solid 2px white;
		border-radius: 5px;
		z-index: 1;
	}

	#ad-holder:hover .edit-button {
		opacity: 1;
	}
</style>