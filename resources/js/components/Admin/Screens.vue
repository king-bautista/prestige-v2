<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Screens</h3>
                    </div>
	    			<div class="card-body">
			          	<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:AddNewScreen="AddNewScreen"
						v-on:editButton="editScreen"
						v-on:DeleteScreen="DeleteScreen"
						v-on:DefaultScreen="DefaultScreen"
                        ref="screensDataTable">
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
		<div class="modal fade" id="screen-form" tabindex="-1" aria-labelledby="screen-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Screen</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Screen</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.name" placeholder="Screen Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Screen Type <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="screen.screen_type">
									    <option value="">Select Screen Type</option>
									    <option v-for="screen_type in screen_types" :value="screen_type"> {{ screen_type }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Orientation <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="screen.orientation">
									    <option value="">Select Orientation</option>
									    <option v-for="orientation in orientations" :value="orientation"> {{ orientation }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Product Application <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="screen.product_application">
										<option value="">Select Product Application</option>
										<option v-for="application in product_applications" :value="application"> {{ application }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="screen.site_id" @change="getBuildings($event.target.value)">
									    <option value="">Select Site</option>
									    <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="screen.site_building_id" @change="getFloorLevel($event.target.value)">
									    <option value="">Select Building</option>
									    <option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Floor <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="screen.site_building_level_id">
									    <option value="">Select Floor</option>
									    <option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Physical size diagonal</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.physical_size_diagonal" placeholder="43 inc" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Physical size width</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.physical_size_width" placeholder="43 inc" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Physical size height</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.physical_size_height" placeholder="43 inc" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Width</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.width" placeholder="1920" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Height</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.height" placeholder="1080" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Slots <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="screen.slots" placeholder="Slots" required>
								</div>
							</div>
							<div class="form-group row" v-if="(screen.screen_type == 'LED' || screen.screen_type == 'LFD') && screen.product_application == 'Digital Signage'">
								<label for="isExclusive" class="col-sm-4 col-form-label">Is Exclusive </label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_exclusive" v-model="screen.is_exclusive">
										<label class="custom-control-label" for="is_exclusive"></label>
									</div>
								</div>
							</div>
							<div class="form-group row" v-if="screen.is_exclusive">
								<label for="firstName" class="col-sm-4 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="company_index" @change="getBrands($event.target.value)">
										<option value="">Select Company</option>
										<option v-for="(company, index) in companies" :value="index"> {{ company.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row" v-if="screen.is_exclusive">
								<label for="firstName" class="col-sm-4 col-form-label">Brand <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="screen.brand">
										<option value="">Select Brand</option>
										<option v-for="brand in brands" :value="brand.id"> {{ brand.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row" v-if="screen.product_application == 'Directory'">
								<label for="isActive" class="col-sm-4 col-form-label">Is Default</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_default" v-model="screen.is_default">
										<label class="custom-control-label" for="is_exclusive"></label>
									</div>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="screen.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeScreen">Add New Screen</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateScreen">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      	<!-- End Modal Add New User -->

		<!-- Delete modal -->
	  	<div class="modal fade" id="screenDeleteModal" tabindex="-1" aria-labelledby="screenDeleteModal" aria-hidden="true">
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
						<button type="button" class="btn btn-primary" @click="removeScreen">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Delete modal -->

		<!-- Confirm modal -->
		<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to set this screen as default?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="setDefault">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Confirm modal -->

    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Screen",
        data() {
            return {
                screen: {
                    id: '',
					site_id: '',
                    site_building_id: '',
                    site_building_level_id: '',
                    name: '',
                    screen_type: '',
                    orientation: '',
					product_application: '',
                    physical_size_diagonal: '',
                    physical_size_width: '',
                    physical_size_height: '',
                    dimension: '',
                    width: '',
                    height: '',
                    slots: '',
					active: false,
					is_default: false,
					is_exclusive: false,
					company: '',
					brand: '',
                },
				id_to_deleted: 0,
				is_default: '',
                add_record: true,
                edit_record: false,
                sites: [],
                buildings: [],
                floors: [],
				companies: [],
				brands: [],
				company_index: '',
                screen_types: ['LED','LFD','LCD'],
                orientations: ['Landscape','Portrait'],
                product_applications: ['Directory','Digital Signage'],
            	dataFields: {
            		screen_location: "Location",
                    site_name: "Site Name",
            		screen_type: "Physical Configuration", 
            		orientation: "Orientation", 
            		product_application: "Product Application", 
            		slots: "Slots",
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
					is_exclusive: {
            			name: "Is exclusive", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">No</span>', 
            				1: '<span class="badge badge-info">Yes</span>'
            			}
            		},
					is_default: {
            			name: "Is Default", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">No</span>', 
            				1: '<span class="badge badge-info">Yes</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/site/screen/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Screen',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'building.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Screen',
            			name: 'Delete',
            			apiUrl: '/admin/site/screen/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'custom_delete',
						v_on: 'DeleteScreen',
            		},
					link: {
            			title: 'Manage Maps',
            			name: 'Manage Maps',
            			apiUrl: '/admin/site/manage-map',
            			routeName: '',
            			button: '<i class="fa fa-map" aria-hidden="true"></i> Manage Maps',
            			method: 'link',
						conditions: { product_application: 'Directory' }
            		},
					view: {
            			title: 'Set as Default',
            			name: 'Set as Default',
            			apiUrl: '',
            			routeName: '',
            			button: '<i class="fa fa-tag"></i> Set as Default',
            			method: 'view',
						v_on: 'DefaultScreen',
						conditions: { product_application: 'Directory' }
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Screen',
						v_on: 'AddNewScreen',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Screen',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
			this.getSites();
			this.getCompany();
        },

        methods: {
			getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

            getBuildings: function(id) {
				axios.get('/admin/site/get-buildings/'+id)
                .then(response => this.buildings = response.data.data);
			},

            getFloorLevel: function(id) {
				axios.get('/admin/site/floors/'+id)
                .then(response => this.floors = response.data.data);
            },

			getCompany: function() {
				axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
			},

			getBrands: function(index) {
				this.screen.company = this.companies[index].id;
				this.brands = this.companies[index].brands;
				this.screen.brand = '';
			},

			AddNewScreen: function() {
				this.add_record = true;
				this.edit_record = false;

                this.screen.site_id = '';
                this.screen.site_building_id = '';
                this.screen.site_building_level_id = '';
				this.screen.screen_type = '';
                this.screen.name = '';         
				this.screen.orientation = '';
				this.screen.product_application = '';
				this.screen.physical_size_diagonal = '';
				this.screen.physical_size_width = '';
				this.screen.physical_size_height = '';
				this.screen.dimension = '';
				this.screen.width = '';
				this.screen.height = '';
                this.screen.slots = '';         
                this.screen.active = false;         
                this.screen.is_default = false;         
                this.screen.is_exclusive = false;      

              	$('#screen-form').modal('show');
            },

            storeScreen: function() {
                axios.post('/admin/site/screen/store', this.screen)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.screensDataTable.fetchData();
					$('#screen-form').modal('hide');
				})
            },

			editScreen: function(id) {
                axios.get('/admin/site/screen/'+id)
                .then(response => {
					this.add_record = false;
					this.edit_record = true;

                    var screen = response.data.data;
					this.getBuildings(screen.site_id);
                    this.getFloorLevel(screen.site_building_id);

					this.screen.id = screen.id;
                    this.screen.site_id = screen.site_id;
					this.screen.site_building_id = screen.site_building_id;
                    this.screen.site_building_level_id = screen.site_building_level_id;
					this.screen.name = screen.name; 
                    this.screen.screen_type = screen.screen_type;
					this.screen.orientation = screen.orientation;
					this.screen.product_application = screen.product_application;
					this.screen.physical_size_diagonal = screen.physical_size_diagonal;
					this.screen.physical_size_width = screen.physical_size_width;
					this.screen.physical_size_height = screen.physical_size_height;
					this.screen.dimension = screen.dimension;
					this.screen.width = screen.width;
					this.screen.height = screen.height;
					this.screen.slots = screen.slots;   
					this.screen.active = screen.active;    
					this.screen.is_default = screen.is_default; 
					this.screen.is_exclusive = screen.is_exclusive;

					var index = this.companies.findIndex(company => company.id === screen.company_details.id);

					this.company_index = index;
					this.screen.company = screen.company_details.id;
					this.brands = screen.company_details.brands;
					this.screen.brand = screen.brand_id;

                    $('#screen-form').modal('show');
                });
            },

            updateScreen: function() {
                axios.put('/admin/site/screen/update', this.screen)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.screensDataTable.fetchData();
                        $('#screen-form').modal('hide');
                    })
            },

			DeleteScreen: function(data) {
				this.id_to_deleted = data.id;
				$('#screenDeleteModal').modal('show');
			},

			removeScreen: function() {
				axios.get('/admin/site/screen/delete/'+this.id_to_deleted)
                .then(response => {
                    this.$refs.screensDataTable.fetchData();
                    this.id_to_deleted = 0;
                    $('#screenDeleteModal').modal('hide');
                });
			},

			DefaultScreen: function(data) {
				this.is_default = data.id;
				$('#confirmModal').modal('show');
			},

			setDefault: function() {
				axios.get('/admin/site/screen/set-default/'+this.is_default)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.screensDataTable.fetchData();
                    $('#confirmModal').modal('hide');
				})
			}

        },

        components: {
        	Table
 	    }
    };
</script> 
