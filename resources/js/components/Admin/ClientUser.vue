<template>
    <div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row" v-show="data_list">
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
						v-on:downloadCsv="downloadCsv"
                        ref="dataTable">
			          	</Table>
		          	</div>
		        </div>
	          </div>
	        </div>
            <div class="row" v-show="data_form">
                <div class="col-md-6">
					<div class="card m-3">
						<div class="card-header">
							<h5 class="card-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New User</h5>
							<h5 class="card-title" v-show="edit_record"><i class="fas fa-edit"></i> Edit User</h5>
						</div>
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
                            <hr>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="user.company"
										:options="companies"
										:multiple="false"
										:close-on-select="true"
										placeholder="Select Company"
										label="name"
										track-by="name"
                                        @input="companyDetail">
									</multiselect>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Classification</label>
                                <div class="col-sm-8">
                                    <span>
                                        {{ user.company.classification_name }}
                                    </span>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <span>
                                        {{ user.company.email }}
                                    </span>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Contact Number</label>
                                <div class="col-sm-8">
                                    <span>
                                        {{ user.company.contact_number }}
                                    </span>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Address</label>
                                <div class="col-sm-8">
                                    <span>
                                        {{ user.company.address }}
                                    </span>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">TIN</label>
                                <div class="col-sm-8">
                                    <span>
                                        {{ user.company.tin }}
                                    </span>
								</div>
							</div>
						</div>
						<div class="card-footer text-right">
							<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
							<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeUser">Add New User</button>
							<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateUser">Save Changes</button>
						</div>
					</div>
				</div>
                <div class="col-md-6">
					<div class="card mt-3 mr-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" @click="modalBrands"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" style="width:100%">
									<tr v-for="(data, index) in user.brands" v-bind:key="index">
										<td><img class="img-thumbnail" :src="data.logo_image_path" /></td>
										<td class="align-middle">{{ data.name }}</td>
										<td class="align-middle text-right"><button type="button" class="btn btn-outline-danger pull-right" @click="removeBrand(index)"><i class="fas fa-trash-alt"></i> Remove</button></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
                    <div class="card mt-3 mr-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-sitemap" aria-hidden="true"></i> Sites</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" @click="modalSites"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" style="width:100%">
									<tr v-for="(data, index) in user.sites" v-bind:key="index">
										<td><img class="img-thumbnail-site" :src="data.site_logo_path" /></td>
										<td class="align-middle">{{ data.name }}</td>
										<td class="align-middle text-right"><button type="button" class="btn btn-outline-danger pull-right" @click="removeSite(index)"><i class="fas fa-trash-alt"></i> Remove</button></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
                    <div class="card mt-3 mr-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-desktop" aria-hidden="true"></i> Screens</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" @click="modalScreens"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" style="width:100%">
									<tr v-for="(data, index) in user.screens" v-bind:key="index">
										<td class="align-middle">{{ data.name }}</td>
										<td class="align-middle text-right"><button type="button" class="btn btn-outline-danger pull-right" @click="removeScreen(index)"><i class="fas fa-trash-alt"></i> Remove</button></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
            </div>
	        <!-- /.row -->
	      </div><!-- /.container-fluid -->
	    </section>
	    <!-- /.content -->

        <div class="modal fade" id="brand-list" tabindex="-1" aria-labelledby="brand-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table 
							:dataFields="brandsDataFields"
							:dataUrl="brandDataUrl"
							:primaryKey="brandPrimaryKey"
							:actionButtons="brandsActionButtons"
							v-on:editButton="selectedBrand"
							:rowPerPage=5
							ref="brandsDataTable">
							</Table>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

        <div class="modal fade" id="site-list" tabindex="-1" aria-labelledby="site-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-sitemap" aria-hidden="true"></i> Sites</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table 
							:dataFields="sitesDataFields"
							:dataUrl="siteDataUrl"
							:primaryKey="sitePrimaryKey"
							:actionButtons="sitesActionButtons"
							v-on:editButton="selectedSite"
							:rowPerPage=5
							ref="sitesDataTable">
							</Table>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

        <div class="modal fade" id="screen-list" tabindex="-1" aria-labelledby="screen-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-desktop" aria-hidden="true"></i> Screens</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table 
							:dataFields="screensDataFields"
							:dataUrl="screenDataUrl"
							:primaryKey="screenPrimaryKey"
							:actionButtons="screensActionButtons"
							v-on:editButton="selectedScreen"
							:rowPerPage=5
							ref="screensDataTable">
							</Table>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right" data-bs-dismiss="modal">Close</button>
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
        name: "Client_Users",
        data() {
            return {
                filter: {
                    company_id: '',
                    site_ids: [],
                },
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
                    company: '',
                    brands: [],
                    sites: [],
                    screens: [],
                },
                data_list: true,
				data_form: false,
                add_record: true,
                edit_record: false,
                displayPassword: false,
                displayButton: true,
                role_list: [],                
                companies: [],
                sites: [],
                screens: [],
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
            	dataUrl: "/admin/client/users/list",
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
            			apiUrl: '/admin/client/users/delete',
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
					download: {
					title: 'Download',
					v_on: 'downloadCsv',
					icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
				},
                brandsDataFields: {
            		logo_image_path: {
            			name: "Logo", 
            			type:"logo", 
            		},
            		name: "Name", 
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
            	},
				brandsActionButtons: {
            		edit: {
            			title: 'Add',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'content.edit',
            			button: '<i class="far fa-check-circle"></i> Add',
            			method: 'view'
            		},
            	},
            	brandPrimaryKey: "id",
            	brandDataUrl: "/admin/brand/list",
                sitesDataFields: {
            		site_logo_path: {
            			name: "Logo", 
            			type:"image", 
            		},
            		name: "Name", 
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
            	},
				sitesActionButtons: {
            		edit: {
            			title: 'Add',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'content.edit',
            			button: '<i class="far fa-check-circle"></i> Add',
            			method: 'view'
            		},
            	},
            	sitePrimaryKey: "id",
            	siteDataUrl: "/admin/site/list",
                screensDataFields: {
            		name: "Name", 
                    site_name: "Site Name",
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
            	},
				screensActionButtons: {
            		edit: {
            			title: 'Add',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'content.edit',
            			button: '<i class="far fa-check-circle"></i> Add',
            			method: 'view'
            		},
            	},
            	screenPrimaryKey: "id",
            	screenDataUrl: "/admin/site/screen/list",
            };
        },

        created(){
            axios.get('/admin/roles/get-portal')
                .then(response => this.role_list = response.data.data);

            axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
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
                this.user.brands = [];
                this.user.sites = [];
                this.user.screens = [];
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
                axios.post('/admin/client/users/store', this.user)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})
            },

			editUser: function(id) {
                axios.get('/admin/client/users/'+id)
                .then(response => {
					this.user.roles = [];
					this.user.brands = [];
					this.user.sites = [];
					this.user.screens = [];

                    var user = response.data.data;
                    this.user.id = id;
                    this.user.company = user.company;
                    this.user.email = user.email;
                    this.user.first_name = user.details.first_name;
                    this.user.last_name = user.details.last_name;
                    this.user.roles = user.roles;
                    this.user.isActive = user.active;
					this.user.brands = user.brands;
					this.user.sites = user.sites;
					this.user.screens = user.screens;
					this.add_record = false;
					this.edit_record = true;
                    this.data_list = false;
					this.data_form = true;
                });
            },

            updateUser: function() {
                axios.put('/admin/client/users/update', this.user)
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

            companyDetail: function(company) {
                this.filter.company_id = company.id;
			},

            modalBrands: function() {
                if(this.user.company) {
                    this.$refs.brandsDataTable.filters = this.filter;
    				this.$refs.brandsDataTable.fetchData();
                    $('#brand-list').modal('show');
                }
                else {
                    toastr.error('Please select company first before adding brands.');
                }
            },

            selectedBrand: function(data) {
				this.user.brands.push(data);
			},

			removeBrand: function(index) {
				this.user.brands.splice(index, 1);
			},

            modalSites: function() {
                $('#site-list').modal('show');
            },

            selectedSite: function(data) {
				this.user.sites.push(data);
			},

			removeSite: function(index) {
				this.user.sites.splice(index, 1);
			},

            modalScreens: function() {
				if(this.user.sites.length > 0) {
					this.filter.site_ids = [];
					for (var i=0; i < this.user.sites.length; i++){
						let site = this.user.sites[i];
						this.filter.site_ids.push(site.id);
					}
					this.$refs.screensDataTable.filters = this.filter;
					this.$refs.screensDataTable.fetchData();

					$('#screen-list').modal('show');
                }
                else {
                    toastr.error('Please select site first before adding screens.');
                }
            },

            selectedScreen: function(data) {
				this.user.screens.push(data);
			},

			removeScreen: function(index) {
				this.user.screens.splice(index, 1);
			},

			downloadCsv: function () {
			axios.get('/admin/client/users/download-csv')
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
        	Table, 
			Multiselect
 	   }
    };
</script> 
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss" scoped>
    .img-thumbnail {
        max-width: 5rem;
    }

    .img-thumbnail-site {
        max-width: 8rem;
    }

</style>