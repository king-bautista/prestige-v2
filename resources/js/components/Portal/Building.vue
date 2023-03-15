<template>
	<div>
        <!-- Main content -->
		<div class="row">
			<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title"><i class="nav-icon fa fa-building"></i>&nbsp;&nbsp;Buildings</h4>					
				</div>
				<div class="card-body">
					<Table 
					:dataFields="dataFields"
					:dataUrl="dataUrl"
					:actionButtons="actionButtons"
					:otherButtons="otherButtons"
					:primaryKey="primaryKey"
					v-on:AddNewBuilding="AddNewBuilding"
					v-on:editButton="editBuilding"
					ref="dataTable">
					</Table>
				</div>
			</div>
			</div>
		</div>
		<!-- /.row -->
	    <!-- /.content -->

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="building-form" tabindex="-1" aria-labelledby="building-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Building</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Building</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="building.name" placeholder="Building Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="building.descriptions" placeholder="Building Name" required>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" v-model="building.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeBuilding">Add New Building</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateBuilding">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Building",
        data() {
            return {
                building: {
                    id: '',
                    name: '',
                    descriptions: '',
                    active: false,           
                },
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Building Name", 
            		descriptions: "Descriptions", 
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
            	dataUrl: "/portal/property-details/building/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Building',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'building.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Building',
            			name: 'Delete',
            			apiUrl: '/admin/site/buildings/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Building',
						v_on: 'AddNewBuilding',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Building',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
        },

        methods: {
			AddNewBuilding: function() {
				this.add_record = true;
				this.edit_record = false;
                this.building.name = '';
                this.building.descriptions = '';
                this.building.active = false;				
              	$('#building-form').modal('show');
            },

            storeBuilding: function() {
                axios.post('/portal/property-details/building/store', this.building)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#building-form').modal('hide');
				})
            },

			editBuilding: function(id) {
                axios.get('/portal/property-details/building/'+id)
                .then(response => {
                    var building = response.data.data;
                    this.building.id = id;
                    this.building.name = building.name;
					this.building.descriptions = building.descriptions;
                    this.building.active = building.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#building-form').modal('show');
                });
            },

            updateBuilding: function() {
                axios.put('/portal/property-details/building/update', this.building)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#building-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	    }
    };
</script> 
