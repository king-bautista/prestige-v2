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
						v-on:AddNewModule="AddNewModule"
						v-on:editButton="editModule"
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
		<div class="modal fade" id="module-form" tabindex="-1" aria-labelledby="module-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Module</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Module</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Module Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="module.name" placeholder="Module Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Link</label>
								<div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="module.link" placeholder="Link">
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Icon Class Name</label>
								<div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="module.class_name" placeholder="CSS Class Name">
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Parent Link</label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="module.parent_id">
									    <option value="">Select Parent Link</option>
									    <option v-for="link in parent_links" :value="link.id">{{ link.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="module.isActive">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeModule">Add New Module</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateModule">Save Changes</button>
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
                module: {
                    id: '',
                    parent_id: '',
                    name: '',
                    link: '',                   
                    class_name: '',                   
                    isActive: false,           
                },
                parent_links: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
                    class_name: {
            			name: "Icon Class Name", 
            			type:"icon", 
            		},            		
                    parent_link: "Parent Link", 
                    link: "Link", 
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
            	dataUrl: "/admin/modules/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this module',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'module.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this module',
            			name: 'Delete',
            			apiUrl: '/admin/modules/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Module',
						v_on: 'AddNewModule',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Module',
						class: 'btn btn-primary btn-sm'
					},
				}
            };
        },

        created(){
            this.GetParentLinks();
        },

        methods: {
			GetParentLinks: function() {
				axios.get('/admin/modules/get-all-links')
                .then(response => this.parent_links = response.data.data);
			},

			AddNewModule: function() {
				this.add_record = true;
				this.edit_record = false;
                this.module.name = '';
                this.module.parent_id = '';
                this.module.class_name = '';
                this.module.link = '';
                this.module.isActive = false;				
              	$('#module-form').modal('show');
            },

            storeModule: function() {
                axios.post('/admin/modules/store', this.module)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentLinks();
					$('#module-form').modal('hide');
				})
            },

			editModule: function(id) {
                axios.get('/admin/modules/'+id)
                .then(response => {
                    var module = response.data.data;
                    this.module.id = id;
                    this.module.name = module.name;
                    this.module.parent_id = module.parent_id;
                    this.module.class_name = module.class_name;
                    this.module.link = module.link;
                    this.module.isActive = module.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#module-form').modal('show');
                });
            },

            updateModule: function() {
                axios.put('/admin/modules/update', this.module)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
						this.GetParentLinks();
                        $('#module-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	   }
    };
</script> 
