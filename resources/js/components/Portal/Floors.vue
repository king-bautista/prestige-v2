<template>
	<div>
        <!-- Main content -->
		<div class="row">
			<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title"><i class="nav-icon fas fa-layer-group"></i>&nbsp;&nbsp;Building Floors</h4>
				</div>
				<div class="card-body">
					<Table 
					:dataFields="dataFields"
					:dataUrl="dataUrl"
					:actionButtons="flooractionButtons"
					:otherButtons="otherButtons"
					:primaryKey="primaryKey"
					v-on:AddNewFloor="AddNewFloor"
					v-on:editButton="editFloor"
					v-on:DeleteFloor="DeleteFloor"
					ref="floorsDataTable">
					</Table>
				</div>
			</div>
			</div>
		</div>
		<!-- /.row -->

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="floor-form" tabindex="-1" aria-labelledby="floor-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Floor</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Floor</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="card-body">
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="form-select" v-model="floor.site_building_id">
									    <option value="">Select Building</option>
									    <option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.name" placeholder="Floor Name" required>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_active" v-model="floor.active">
										<label class="custom-control-label" for="is_active"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeFloor">Add New Floor</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateFloor">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
	  <!-- Modal -->
		<div class="modal fade" id="levelDeleteModal" tabindex="-1" aria-labelledby="levelDeleteModal" aria-hidden="true">
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
						<button type="button" class="btn btn-primary" @click="removeFloor">OK</button>
					</div>
				</div>
			</div>
		</div>
		
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Floor",
        data() {
            return {
                floor: {
                    id: '',
                    site_building_id: '',
                    name: '',
                    active: false,           
                },
                buildings: [],
                map_preview: '',
                map_file: '',
                add_record: true,
                edit_record: false,
				id_to_deleted: 0,
            	dataFields: {
            		name: "Floor Name", 
					building_name: "Building Name",
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge bg-danger">Deactivated</span>', 
            				1: '<span class="badge bg-info text-dark">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/property-details/floor/list",
            	flooractionButtons: {
            		edit: {
            			title: 'Edit this Floor',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'floor.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Floor',
            			name: 'Delete',
            			apiUrl: '/portal/property-details/floor/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'custom_delete',
						v_on: 'DeleteFloor',
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Floor',
						v_on: 'AddNewFloor',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Floor',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
        },

        methods: {
            GetBuildings: function() {
				axios.get('/portal/property-details/buildings')
                .then(response => this.buildings = response.data.data);
			},

			AddNewFloor: function() {
                this.GetBuildings();
				this.add_record = true;
				this.edit_record = false;
                this.floor.site_building_id = '';
                this.floor.name = '';
                this.floor.active = true;
              	$('#floor-form').modal('show');
            },

            storeFloor: function() {
                axios.post('/portal/property-details/floor/store', this.floor)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.floorsDataTable.fetchData();
	              	$('#floor-form').modal('hide');
				})
            },

			editFloor: function(id) {
				this.GetBuildings();
                axios.get('/portal/property-details/floor/'+id)
                .then(response => {
                    var floor = response.data.data;
					this.floor.id= floor.id;
					this.floor.site_building_id = floor.site_building_id;
					this.floor.name = floor.name;
					this.floor.active = floor.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#floor-form').modal('show');
                });
            },

            updateFloor: function() {
                axios.post('/portal/property-details/floor/update', this.floor)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.floorsDataTable.fetchData();
	              	$('#floor-form').modal('hide');
				})
            },

			DeleteFloor: function(data) {
				this.id_to_deleted = data.id;
				$('#levelDeleteModal').modal('show');
			},

			removeFloor: function() {
				axios.get('/portal/property-details/floor/delete/'+this.id_to_deleted)
                .then(response => {
                    this.$refs.floorsDataTable.fetchData();
                    this.id_to_deleted = 0;
                    $('#levelDeleteModal').modal('hide');
                });
			}

        },

        components: {
        	Table
 	    }
    };
</script> 
