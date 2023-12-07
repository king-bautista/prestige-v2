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
			<div class="modal-dialog modal-dialog-centered modal-xl">
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
							<div class="row" v-if="!content.advertisement_details">
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
							<div v-if="content.advertisement_details">
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Material</label>
									<div class="col-sm-8 text-left" id="ad-holder">
										<div v-for="(material, index) in content.advertisement_details.materials" v-bind:key="index" class="material_thumbnails bg-light">
											<span><strong>{{ material.dimension }}</strong></span>
											<img :src="material.material_thumbnails_path" class="img-thumbnail" />
										</div>

										<div class="edit-button"><a @click="content.advertisement_details = null" class="bg-success"><i class="fas fa-edit"></i> CHANGE </a></div>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Ad Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Brand Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.brand_name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Company Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.company_name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Contract ID</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.contract_details.serial_number }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Contract Name</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.contract_details.name }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Contract Remarks</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.contract_details.remarks }}
										</span>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Display Duration</label>
									<div class="col-sm-8">
										<span>
											{{ content.advertisement_details.display_duration }}
										</span>
									</div>
								</div>								
								<div class="form-group row">
									<label for="Screen" class="col-sm-4 col-form-label">Screen/s <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<multiselect v-model="content.site_screen_ids" 
										track-by="site_screen_location" 
										label="site_screen_location" 
										placeholder="Select Screen" 
										:multiple="true"
										:options="screens" 
										:searchable="true" 
										:allow-empty="true">
										</multiselect> 
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Status <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<select class="custom-select" v-model="content.status_id">
											<option value="">Select Status </option>
											<option v-for="status in statuses" :value="status.id"> {{ status.name }}
											</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="userName" class="col-sm-4 col-form-label">Start Date <span class="font-italic text-danger">*</span></label>
									<div class="col-sm-8">
										<date-picker v-model="content.start_date" placeholder="YYYY-MM-DD" :config="options" id="date_from" autocomplete="off"></date-picker>
									</div>
								</div>
								<div class="form-group row">
									<label for="userName" class="col-sm-4 col-form-label">End Date <span class="font-italic text-danger">*</span></label>
									<div class="col-sm-8">
										<date-picker v-model="content.end_date" placeholder="YYYY-MM-DD" :config="options" id="date_to" autocomplete="off"></date-picker>
									</div>
								</div>
								<div class="form-group row">
									<label for="Active" class="col-sm-4 col-form-label">Active <span class="font-italic text-danger">*</span></label>
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
							<button type="button" class="btn btn-primary pull-right" v-if="content.advertisement_details" v-show="add_record" @click="storeContent">Add New Content</button>
							<button type="button" class="btn btn-primary pull-right" v-if="content.advertisement_details" v-show="edit_record" @click="updateContent">Save Changes</button>
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
					status_id: '',
					advertisement_details: '',
                    start_date: '',
					end_date: '',
					site_screen_ids: [],
                    active: true,           
                },
                screens: [],
				statuses: [],
				options: {
                    format: 'YYYY/MM/DD',
                    useCurrent: false,
                },
                add_record: true,
                edit_record: false,
            	dataFields: {
					serial_number: "ID",
					material_thumbnails_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
            		ad_name: "Ad Name", 
					company_name: "Company Name",
					brand_name: "Brand Name",
					air_dates: "Airdates",
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
            			apiUrl: '/admin/content-management/delete',
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
            		serial_number: "ID",
					material_thumbnails_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
            		name: "Name",
					company_name: "Company Name",
					brand_name: "Brand Name",
            	},
				adsPrimaryKey: "id",
            	adsDataUrl: "/admin/manage-ads/list",
				adsActionButtons: {
            		edit: {
            			title: 'Add',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'content.edit',
            			button: '<i class="far fa-check-circle"></i> Add',
            			method: 'view'
            		},
            	},

            };
        },

        created(){
			this.getSites();
			this.getStatuses();
        },

        methods: {
			getScreens: function (id) {
				axios.post('/admin/site/site-screen-product/get-screens', {contract_id: id})
                .then(response => {
					this.screens = response.data.data
				});				
			},

			getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

			getStatuses: function() {
				axios.get('/admin/transaction/statuses/get-all')
                .then(response => this.statuses = response.data.data);
			},

			AddNewContent: function() {
				this.add_record = true;
				this.edit_record = false;
				this.content.advertisement_id = '';
				this.content.status_id = '';
				this.content.advertisement_details = '';
				this.content.start_date = '';
				this.content.end_date = '';
				this.content.site_screen_ids = [];
                this.content.active = true;				
              	$('#content-form').modal('show');
            },

			selectedAd: function(data) {
				this.screens = [];
				this.content.site_screen_ids = [];
				this.content.advertisement_id = data.id
				this.content.advertisement_details = data;
				this.getScreens(data.contract_id);
			},

            storeContent: function() {
                axios.post('/admin/content-management/store', this.content)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#content-form').modal('hide');
				});				
            },

			editContent: function(id) {
                axios.get('/admin/content-management/'+id)
                .then(response => {
                    var content = response.data.data;
					this.screens = [];
					this.getScreens(content.advertisement_details.contract_id);

					this.content.id = content.id;
					this.content.advertisement_id = content.advertisement_id;
					this.content.advertisement_details = content.advertisement_details;
					this.content.site_screen_ids = content.screens;
					this.content.status_id = content.status_id;
					this.content.start_date = content.start_date;
					this.content.end_date = content.end_date;
					this.content.active = content.active;

					this.add_record = false;
					this.edit_record = true;

                    $('#content-form').modal('show');
                });
            },

            updateContent: function() {
                axios.put('/admin/content-management/update', this.content)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#content-form').modal('hide');
				})
            },
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

	.material_thumbnails {
		width: 160px;
		text-align: center;
		border: solid 1px;
		padding: 5px;
		margin: 0 5px;
		border-radius: 5px;
		display: inline-block;
	}
</style>