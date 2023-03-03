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