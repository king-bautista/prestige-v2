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
						v-on:AddNewCompany="AddNewCompany"
						v-on:editButton="editCompany"
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
							<h5 class="card-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Company</h5>
							<h5 class="card-title" v-show="edit_record"><i class="fas fa-edit"></i> Edit Company</h5>
						</div>
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Company Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="company.name" placeholder="Company Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Address <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="company.address" placeholder="Company Address" required></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Email <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="email" class="form-control" v-model="company.email" placeholder="Email" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Contact Number <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="email" class="form-control" v-model="company.contact_number" placeholder="Contact Number" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">TIN Number <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="company.tin" placeholder="TIN Number" required>
								</div>
							</div>							
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Classification <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="company.classification_id">
										<option value="">Select Classification</option>
										<option v-for="classification in classifications" :value="classification.id"> {{ classification.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Parent Company</label>
								<div class="col-sm-8">
									<treeselect v-model="company.parent_id" :options="parent_company" placeholder="Select Parent Company"/>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" v-model="company.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-right">
							<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
							<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeCompany">Add New Company</button>
							<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateCompany">Save Changes</button>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card mt-3 mr-3">
						<div class="card-header">
							<h5 class="card-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
							<button type="button" class="btn btn-primary btn-sm m-0 float-right" data-bs-toggle="modal" data-bs-target="#brand-list"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
						</div>
						<div class="card-body">
							<div class="table-responsive mt-2">
								<table class="table table-hover" style="width:100%">
									<tr v-for="(data, index) in company.brands" v-bind:key="index">
										<td><img class="img-thumbnail" :src="data.logo_image_path" /></td>
										<td class="align-middle">{{ data.name }}</td>
										<td class="align-middle"><button type="button" class="btn btn-outline-danger" @click="removeBrand(index)"><i class="fas fa-trash-alt"></i> Remove</button></td>
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

		<!-- Modal Add New / Edit User -->
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
      <!-- End Modal Add New User -->

    </div>
</template>
<script> 
	import Table from '../Helpers/Table';
	// import the component
	import Treeselect from '@riophae/vue-treeselect'
	// import the styles
	import '@riophae/vue-treeselect/dist/vue-treeselect.css'

	export default {
        name: "Users",
        data() {
            return {
                company: {
                    id: '',
                    parent_id: '',
                    classification_id: '',
                    name: '',
                    email: '',
                    contact_number: '',
                    address: '',
                    tin: '',
                    active: false,
					brands: [],
                },
				data_list: true,
				data_form: false,
                parent_company: [],
                classifications: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
					parent_company: "Parent Company",
                    classification_name: "Classification Name",
            		email: "Email", 
            		contact_number: "Contact Number", 
            		address: "Address", 
            		tin: "TIN Number", 
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
            	dataUrl: "/admin/company/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Company',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'company.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Company',
            			name: 'Delete',
            			apiUrl: '/admin/company/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Company',
						v_on: 'AddNewCompany',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Company',
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
            };
        },

        created(){
            this.getClassifications();
			this.getParentCompany();
        },

        methods: {
			getParentCompany: function() {
				axios.get('/admin/company/get-parent')
                .then(response => this.parent_company = response.data.data);
			},

            getClassifications: function() {
				axios.get('/admin/classification/get-all')
                .then(response => this.classifications = response.data.data);
			},

			AddNewCompany: function() {
				this.add_record = true;
				this.edit_record = false;
                this.company.parent_id = null;
                this.company.classification_id = '';
                this.company.name = '';
                this.company.email = '';
                this.company.contact_number = '';
                this.company.address = '';
                this.company.tin = '';
                this.company.active = false;				
				this.company.brands = [];
				this.data_list = false;
				this.data_form = true;
            },

            storeCompany: function() {
                axios.post('/admin/company/store', this.company)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
				})
            },

			editCompany: function(id) {
                axios.get('/admin/company/'+id)
                .then(response => {
                    var company = response.data.data;
                    this.company.id = id;
                    this.company.name = company.name;
                    this.company.parent_id = company.parent_id;
                    this.company.classification_id = company.classification_id;
                    this.company.email = company.email;
                    this.company.contact_number = company.contact_number;
                    this.company.address = company.address;
                    this.company.tin = company.tin;
                    this.company.active = company.active;
					this.company.brands = company.brands;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
                });
            },

            updateCompany: function() {
                axios.put('/admin/company/update', this.company)
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
			
			selectedBrand: function(data) {
				this.company.brands.push(data);
			},

			removeBrand: function(index) {
				this.company.brands.splice(index, 1);
			}

        },

        components: {
        	Table,
			Treeselect
 	    }
    };
</script> 
<style lang="scss" scoped>
    .img-thumbnail {
        max-width: 5rem;
    }

</style>