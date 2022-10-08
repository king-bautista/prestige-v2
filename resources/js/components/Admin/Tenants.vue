<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tenants</h3>
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
                        ref="tenantsDataTable">
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
		<div class="modal fade" id="tenant-form" tabindex="-1" aria-labelledby="tenant-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
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
								<label for="firstName" class="col-sm-4 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="tenant.brand_id" track-by="name" label="name" placeholder="Select Brand" :options="brands" :searchable="true" :allow-empty="false">
                                    </multiselect> 
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="tenant.site_building_id" @change="getFloorLevel($event.target.value)">
									    <option value="">Select Building</option>
									    <option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Floor <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="tenant.site_building_level_id">
									    <option value="">Select Floor</option>
									    <option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
								    </select>
								</div>
							</div>						
                            <div class="form-group row">
								<label for="is_subscriber" class="col-sm-4 col-form-label">Is Subscriber</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_subscriber" v-model="tenant.is_subscriber">
										<label class="custom-control-label" for="is_subscriber"></label>
									</div>
								</div>
							</div>
                            <div class="form-group row" >
								<label for="tennat_active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="tennat_active" v-model="tenant.active">
										<label class="custom-control-label" for="tennat_active"></label>
									</div>
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

    </div>
</template>
<script> 
	import Table from '../Helpers/Table';
    import Multiselect from 'vue-multiselect';

	export default {
        name: "Tenant",
        data() {
            return {
                tenant: {
                    id: '',
                    brand_id: '',
                    site_building_id: '',
                    site_building_level_id: '',
                    active: true,
                    is_subscriber: '',
                },
				id_to_deleted: 0,
                add_record: true,
                edit_record: false,
                brands: [],
                buildings: [],
                floors: [],
            	dataFields: {
            		brand_name: "Brand Name", 
					brand_logo: {
            			name: "Brand Logo", 
            			type:"logo", 
            		},
                    floor_name: "Floor Name",
                    building_name: "Building Name",
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
					created_at: "Date Created"
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
            	},
				otherButtons: {
					addNew: {
						title: 'New Tenant',
						v_on: 'AddNewTenant',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Tenant',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
            this.GetBrands();
        },

        methods: {
            GetBrands: function() {
				axios.get('/admin/brand/get-all')
                .then(response => this.brands = response.data.data);
			},

            GetBuildings: function() {
				axios.get('/admin/site/buildings')
                .then(response => this.buildings = response.data.data);
                this.tenant.site_building_level_id = '';
			},

            getFloorLevel: function(id) {
				axios.get('/admin/site/floors/'+id)
                .then(response => this.floors = response.data.data);
            },

			AddNewTenant: function() {
                this.GetBuildings();
				this.add_record = true;
				this.edit_record = false;
                this.tenant.brand_id = '';
                this.tenant.site_building_id = '';
                this.tenant.site_building_level_id = '';
              	$('#tenant-form').modal('show');
            },

            storeTenant: function() {
                axios.post('/admin/site/tenant/store', this.tenant)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.tenantsDataTable.fetchData();
					$('#tenant-form').modal('hide');
				})
            },

			editTenant: function(id) {
                this.GetBuildings();
                axios.get('/admin/site/tenant/'+id)
                .then(response => {
                    var tenant = response.data.data;
                    this.tenant.id = tenant.id;
                    this.tenant.brand_id = tenant.brand_details;
                    this.tenant.site_building_id = tenant.site_building_id;

                    this.getFloorLevel(tenant.site_building_id);

                    this.tenant.site_building_level_id = tenant.site_building_level_id;
					this.add_record = false;
					this.edit_record = true;
                    $('#tenant-form').modal('show');
                });
            },

            updateTenant: function() {
                axios.put('/admin/site/tenant/update', this.tenant)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.tenantsDataTable.fetchData();
                        $('#tenant-form').modal('hide');
                    })
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
			}

        },

        components: {
        	Table,
            Multiselect
 	    }
    };
</script> 