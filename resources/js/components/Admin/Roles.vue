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
						v-on:addNewRole="addNewRole"
						v-on:editButton="editRole"
						v-on:downloadCsv="downloadCsv"
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
								<label for="firstName" class="col-sm-4 col-form-label">Role Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="role.name" placeholder="Role Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="role.description" placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Type <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="role.type" @change="setPermissions($event.target.value)">
									    <option value="">Select Type</option>
									    <option v-for="data in types" :value="data"> {{ data }} </option>
								    </select>
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
							<div class="row">
								<label for="lastName" class="col-sm-4 col-form-label">Permissions</label>
							</div>
							<div class="row">
								<div class="col-12">
									<div class="table-responsive">
										<table class="table table-hover" style="width:100%">
											<thead>
												<th>Modules</th>
												<th class="text-center">Can View</th>
												<th class="text-center">Can Add</th>
												<th class="text-center">Can Edit</th>
												<th class="text-center">Can Delete</th>
											</thead>
											<tbody>
												<tr v-for="(module) in role.permissions">
													<td v-bind:class="(module.parent_id) ? 'pl-4':''"><i :class="module.class_name"></i> {{ module.name }}</td>
													<td class="text-center"><input :class="'view_'+module.str_class" type="checkbox" v-model="module.can_view" v-on="(!module.parent_id) ? { click: () => checkedAll('view_',module.id) } : { click: () => uncheckedAll('view_',module.parent_id) }"></td>
													<td class="text-center"><input :class="'add_'+module.str_class" type="checkbox" v-model="module.can_add" v-on="(!module.parent_id) ? { click: () => checkedAll('add_',module.id) } : { click: () => uncheckedAll('add_',module.parent_id)}"></td>
													<td class="text-center"><input :class="'edit_'+module.str_class" type="checkbox" v-model="module.can_edit" v-on="(!module.parent_id) ? { click: () => checkedAll('edit_',module.id) } : { click: () => uncheckedAll('edit_',module.parent_id)}"></td>
													<td class="text-center"><input :class="'delete_'+module.str_class" type="checkbox" v-model="module.can_delete" v-on="(!module.parent_id) ? { click: () => checkedAll('delete_',module.id) } : { click: () => uncheckedAll('delete_',module.parent_id)}"></td>
												</tr>
											</tbody>
										</table>
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
                    type: '',                   
                    isActive: false,
					permissions: [],
                },
                add_record: true,
                edit_record: false,
				types: ['Admin','Portal'],
            	dataFields: {
            		name: "Name", 
            		description: "Description", 
            		type: "Type", 
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
						v_on: 'addNewRole',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Role',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
					download: {
					title: 'Download',
					v_on: 'downloadCsv',
					icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
				}
            };
        },

		// created(){
        //     this.setPermissions();
        // },

        methods: {
			checkedAll: function(action, id) {
				if($("."+action+'all_'+id).is(':checked')) {
					$("."+action+id).each(function () {
						if(!$(this).is(":checked")) {
							$(this).click();
						}
					});
				} else {
					$("."+action+id).each(function () {
						if($(this).is(":checked")) {
							$(this).click();
						}
					});
				}
			},

			uncheckedAll: function(action, id) {
				if ($('.'+action+id+':checked').length == $('.'+action+id+'').length) {
					$("."+action+'all_'+id).click();
				} else {
					$("."+action+'all_'+id).prop('checked', false);
				}
			},
			
			setPermissions: function(type) {
				this.role.permissions = [];
				axios.get('/admin/roles/modules', { params: {type: type} })
                .then(response => {
					var modules = response.data.data;
					modules.forEach((key, index) => {
						this.addPermissions(key, 'all_'+key.id);

						if(key.child_modules) {
							key.child_modules.forEach((key_child, index) => {
								this.addPermissions(key_child, key_child.parent_id);
							});
						}
					});
				});
			},

			addPermissions: function(data, str_class) {
				this.role.permissions.push({
					id: data.id, 
					parent_id: data.parent_id, 
					name: data.name,
					class_name: data.class_name,
					str_class: str_class,
					can_view: data.can_view,
					can_add: data.can_add,
					can_edit: data.can_edit,
					can_delete: data.can_delete
				});
			},

			addNewRole: function() {
				this.add_record = true;
				this.edit_record = false;
                this.role.name = '';
                this.role.description = '';
                this.role.type = '';
                this.role.isActive = false;
				this.role.permissions = [];
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
                    this.role.type = role.type;
                    this.role.isActive = role.active;
					this.add_record = false;
					this.edit_record = true;
					// clear initial permissions
					if(role.permissions != null && role.permissions.length > 0) {
						this.role.permissions = [];
						// put new permission with data
						role.permissions.forEach((key, index) => {
							this.addPermissions(key, 'all_'+key.id);

							if(key.child_modules) {
								key.child_modules.forEach((key_child, index) => {
									this.addPermissions(key_child, key_child.parent_id);
								});
							}

						});
					} 
					else {
						this.role.permissions = [];
						this.setPermissions(role.type);
					}

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

			downloadCsv: function () {
			axios.get('/admin/roles/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

        },

        components: {
        	Table
 	   }
    };
</script> 
