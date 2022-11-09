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
						v-on:AddNewUser="AddNewUser"
						v-on:editButton="editUser"
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
		<div class="modal fade" id="user-form" tabindex="-1" aria-labelledby="user-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fas fa-user-plus"></i> Add New User</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fas fa-user-edit"></i> Edit User</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="email" class="col-sm-4 col-form-label">Email <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="email" class="form-control" v-model="user.email" placeholder="Email">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">First Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="user.first_name" placeholder="First Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Last Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="user.last_name" placeholder="Last Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Birth Date <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="user.birth_date" placeholder="Last Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Password <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-6">
									<button type="button" class="btn btn-block btn-outline-info btn-sm" v-show="displayButton" @click="showPassword">Show password</button>
									
									<div class="input-group mb-3" v-show="displayPassword">
										<input type="text" class="form-control" v-model="user.password" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
										<div class="input-group-append">                              
										<button class="btn btn-outline-secondary" type="button" @click="showPassword"><i class="fas fa-sync-alt"></i></button>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-block btn-outline-secondary btn-sm" v-show="displayPassword" @click="cancelPassword" style="margin-top: 4px;">Cancel</button>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Roles <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="user.roles"
										:options="role_list"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Roles"
										label="name"
										track-by="name">
									</multiselect> 
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="user.isActive">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label"></label>
								<div class="col-sm-8">
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" type="checkbox" id="emailNotification" v-model="user.emailNotification">
										<label for="emailNotification" class="custom-control-label font-normal">Send the new user an email about their account.</label>
									</div>
								</div>
							</div>
							
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeUser">Add New User</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateUser">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';
	import Multiselect from 'vue-multiselect';
    import { generatePassword } from '../Helpers/GeneratePassword';

	export default {
        name: "Users",
        data() {
            return {
                user: {
                    id: '',
                    email: '',
                    first_name: '',
                    last_name: '',                   
                    password: '',
                    password_confirmation: '',
                    roles: [],
                    isActive: false,           
                    emailNotification: '',
                },
                add_record: true,
                edit_record: false,
                displayPassword: false,
                displayButton: true,
                role_list: [],                
            	dataFields: {
            		full_name: "Full Name", 
            		email: "Email", 
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
            	dataUrl: "/admin/users/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this user',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'user.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this user',
            			name: 'Delete',
            			apiUrl: '/admin/users/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New User',
						v_on: 'AddNewUser',
						icon: '<i class="fas fa-user-plus"></i> New User',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
            axios.get('/admin/roles/get-all')
                .then(response => this.role_list = response.data.data);
        },

        methods: {
			AddNewUser: function() {
				this.add_record = true;
				this.edit_record = false;
                this.user.email = '';
                this.user.first_name = '';
                this.user.last_name ='';                 
                this.user.password = '';
                this.user.password_confirmation = '';
                this.user.roles = [];
                this.user.isActive = false;				
              	$('#user-form').modal('show');
            },

            showPassword: function() {
                this.user.password = generatePassword(15);
                this.user.password_confirmation = this.user.password;
                this.displayPassword = true;
                this.displayButton = false;
            },			

           	cancelPassword: function() {
                this.displayPassword = false;
                this.displayButton = true;
            },	
			
            storeUser: function() {
                axios.post('/admin/users/store', this.user)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#user-form').modal('hide');
				})
            },

			editUser: function(id) {
                axios.get('/admin/users/'+id)
                .then(response => {
                    var user = response.data.data;
                    this.user.id = id;
                    this.user.email = user.email;
                    this.user.first_name = user.details.first_name;
                    this.user.last_name = user.details.last_name;
                    this.user.roles = user.roles;
                    this.user.isActive = user.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#user-form').modal('show');
                });
            },

            updateUser: function() {
                axios.put('/admin/users/update', this.user)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#user-form').modal('hide');
                    })
            },

        },

        components: {
        	Table, 
			Multiselect
 	   }
    };
</script> 
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>