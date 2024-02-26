<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fas fa-user-tag"></i>&nbsp;&nbsp;Manage Account</h4>
						<h4 v-show="add_record && data_form"><i class="nav-icon fas fa-user-plus"></i> Add New User</h4>
						<h4 v-show="edit_record && data_form"><i class="nav-icon fas fa-user-edit"></i> Edit User</h4>
					</div>
					<div class="card-body" v-show="data_list">
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
					<div class="card-body" v-show="data_form">
						<div class="form-group row">
							<label for="email" class="col-sm-2 col-form-label">Email <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-4">
								<input type="email" class="form-control" v-model="user.email" placeholder="Email">
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-2 col-form-label">First Name <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" v-model="user.first_name" placeholder="First Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-2 col-form-label">Last Name <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" v-model="user.last_name" placeholder="Last Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-2 col-form-label">Password <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-3">
								<button type="button" class="btn btn-block btn-outline-info btn-sm" v-show="displayButton" @click="showPassword">Show password</button>
								
								<div class="input-group mb-3" v-show="displayPassword">
									<input type="text" class="form-control" v-model="user.password" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
									<div class="input-group-append">                              
									<button class="btn btn-outline-secondary" type="button" @click="showPassword"><i class="fas fa-sync-alt"></i></button>
									</div>
								</div>
							</div>
							<div class="col-sm-1">
								<button type="button" class="btn btn-block btn-outline-secondary btn-sm" v-show="displayPassword" @click="cancelPassword" style="margin-top: 4px;">Cancel</button>
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="isActive" class="col-sm-2 col-form-label">Active</label>
							<div class="col-sm-4">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="isActive" v-model="user.isActive">
									<label class="custom-control-label" for="isActive"></label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeUser">Add New User</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateUser">Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

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
                    isActive: false,           
                    emailNotification: '',
					profile_image: '',
                    mobile: '',
                    address: '',
                },
                data_list: true,
				data_form: false,
                add_record: true,
                edit_record: false,
                displayPassword: false,
                displayButton: true,
            	dataFields: {
            		full_name: "Full Name", 
            		email: "Email", 
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge bg-danger">Inactive</span>',
            				1: '<span class="badge bg-info">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/manage-account/list",
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
            			apiUrl: '/portal/users/delete',
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
                this.user.isActive = false;				
                this.data_list = false;
				this.data_form = true;
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
                axios.post('/portal/manage-account/store', this.user)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})
            },

			editUser: function(id) {
                axios.get('/portal/manage-account/'+id)
                .then(response => {
                    var user = response.data.data;
                    this.user.id = id;
                    this.user.email = user.email;
                    this.user.first_name = user.details.first_name;
                    this.user.last_name = user.details.last_name;
                    this.user.isActive = user.active;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
                });
            },

            updateUser: function() {
                axios.put('/portal/manage-account/update', this.user)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
						this.data_list = true;
						this.data_form = false;
                    })
            },

			backToList: function() {
				this.data_list = true;
				this.data_form = false;
			},

        },

        components: {
        	Table, 
			Multiselect
 	   }
    };
</script> 
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>