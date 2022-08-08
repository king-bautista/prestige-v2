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
						v-on:AddNewRole="AddNewRole"
						v-on:editButton="editRole"
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
		<div class="modal fade" id="role-form" tabindex="-1" aria-labelledby="role-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Role</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Role</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Role Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="role.name" placeholder="Role Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions</label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="role.description" placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="role.isActive">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeRole">Add New Role</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateRole">Save Changes</button>
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
        name: "Users",
        data() {
            return {
                role: {
                    id: '',
                    name: '',
                    description: '',                   
                    isActive: false,           
                },
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
            		description: "Description", 
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
            	dataUrl: "/admin/roles/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this role',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'role.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this role',
            			name: 'Delete',
            			apiUrl: '/admin/roles/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Role',
						v_on: 'AddNewRole',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Role',
						class: 'btn btn-primary btn-sm'
					},
				}
            };
        },

        methods: {
			AddNewRole: function() {
				this.add_record = true;
				this.edit_record = false;
                this.role.name = '';
                this.role.description = '';
                this.role.isActive = false;				
              	$('#role-form').modal('show');
            },

            storeRole: function() {
                axios.post('/admin/roles/store', this.role)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#role-form').modal('hide');
				})
            },

			editRole: function(id) {
                axios.get('/admin/roles/'+id)
                .then(response => {
                    var role = response.data.data;
                    this.role.id = id;
                    this.role.name = role.name;
                    this.role.description = role.description;
                    this.role.isActive = role.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#role-form').modal('show');
                });
            },

            updateRole: function() {
                axios.put('/admin/roles/update', this.role)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#role-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	   }
    };
</script> 
