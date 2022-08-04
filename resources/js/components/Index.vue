<template>
	<div>
		<!-- Content Header (Page header) -->
	    <section class="content-header">
	      <div class="container-fluid">
	        <div class="row mb-2">
	          <div class="col-sm-6">
	            <h1>Admin Users <a type="button" class="btn btn-outline-primary btn-sm" @click="addNewuser">Add New</a></h1>
	          </div>
	          <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><router-link :to="{ name: 'home' }">Home</router-link></li>
	              <li class="breadcrumb-item active">Admin Users</li>
	            </ol>
	          </div>
	        </div>
	      </div><!-- /.container-fluid -->
	    </section>

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
			          	:primaryKey="primaryKey"
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
              <h5 class="modal-title" id="addNewUser" v-show="addNewButton">Add New User</h5>
              <h5 class="modal-title" id="editUser" v-show="saveButton">Edit User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body">
                <div class="form-group row">
                  <label for="userName" class="col-sm-4 col-form-label">Username <span class="font-italic">(required)</span></label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="userName" v-model="user.userName" placeholder="Username">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="email" class="col-sm-4 col-form-label">Email<span class="font-italic"> (required)</span></label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="email" v-model="user.email" placeholder="Email">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="firstName" class="col-sm-4 col-form-label">First Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="firstName" v-model="user.firstName" placeholder="First Name">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="lastName" class="col-sm-4 col-form-label">Last Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="lastName" v-model="user.lastName" placeholder="Last Name">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-4 col-form-label">Password</label>
                  <div class="col-sm-6">
                    <button type="button" class="btn btn-block btn-outline-info btn-sm" v-show="displayButton" @click="showPassword">Show password</button>
                    
                    <div class="input-group mb-3" v-show="displayPassword">
                        <input 
                            type="text" 
                            v-model="user.password" 
                            id="password" 
                            class="form-control" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-sm">

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
                  <label for="inputPassword3" class="col-sm-4 col-form-label">Send User Notification</label>
                  <div class="col-sm-8">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="emailNotification" v-model="user.emailNotification" checked="">
                        <label for="emailNotification" class="custom-control-label font-normal">Send the new user an email about their account.</label>
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-4 col-form-label">Role</label>
                  <div class="col-sm-8">
                      <select class="custom-select" v-model="user.role">
                          <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                      </select>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" v-show="addNewButton" @click="storeUser">Add New User</button>
              <button type="button" class="btn btn-primary" v-show="saveButton" @click="updateUser">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Add New User -->
	</div>
</template>
<script>  
	import Table from '../../../components/Table';
    import Form from '../../../helpers/form';
    import { generatePassword } from '../../../helpers/generatePassword';

    export default {
        name: "Users",
        data() {
            return {
                user: {
                    id: '',
                    userName: '',
                    email: '',
                    firstName: '',
                    lastName: '',                   
                    password: '',
                    emailNotification: '',
                    role: '',
                    isActive: false                    
                },
                addNewButton: true,
                saveButton: false,
                error: null,
                displayPassword: false,
                displayButton: true,
                roles: [],                
            	dataFields: {
            		name: "Name", 
            		email: "Email", 
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
            	dataUrl: "/api/v1/user/list",
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
            			apiUrl: '/api/v1/user/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	}
            };
        },

        created(){
            axios.get('/api/v1/role/get-all')
                .then(response => this.roles = response.data.data);
        },

        methods: {

            addNewuser: function() {
              this.user.id = null;
              this.user.userName = null;
              this.user.email = null;
              this.user.firstName = null;
              this.user.lastName = null;
              this.user.role = null;
              this.user.isActive = false;
              this.addNewButton = true;
              this.saveButton = false;
              this.displayPassword = false;
              this.displayButton = true;              
              $('#user-form').modal('show');
            },

            editUser: function(id) {
                axios.get('/api/v1/user/'+id)
                .then(response => {
                    var user = response.data.data;
                    this.user.id = id;
                    this.user.userName = user.name;
                    this.user.email = user.email;
                    this.user.firstName = user.details.first_name;
                    this.user.lastName = user.details.last_name;
                    this.user.role = user.roles[0].id;
                    this.user.isActive = user.active;
                    this.addNewButton = false;
                    this.saveButton = true;
                    $('#user-form').modal('show');
                });
            },

            showPassword: function() {
                this.user.password = generatePassword(15);
                this.displayPassword = true;
                this.displayButton = false;
            },

            cancelPassword: function() {
                this.displayPassword = false;
                this.displayButton = true;
            },

            storeUser: function() {
                axios.post('/api/v1/user/store', this.user)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#user-form').modal('hide');
                    })
            },

            updateUser: function() {
                axios.put('/api/v1/user/update', this.user)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#user-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	   }
    };
</script>