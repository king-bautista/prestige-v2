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
						v-on:AddNewCompany="AddNewCompany"
						v-on:editButton="editCompany"
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
		<div class="modal fade" id="company-form" tabindex="-1" aria-labelledby="company-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Company</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Company</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
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
								<label for="firstName" class="col-sm-4 col-form-label">TIN Number <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="company.tin" placeholder="Company Name" required>
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
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeCompany">Add New Company</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateCompany">Save Changes</button>
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
                company: {
                    id: '',
                    classification_id: '',
                    name: '',
                    address: '',
                    tin: '',
                    active: false,           
                },
                classifications: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
                    classification_name: "Classification Name",
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
                    created_at: "Date Created"
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
				}
            };
        },

        created(){
            this.getClassifications();
        },

        methods: {
            getClassifications: function() {
				axios.get('/admin/classification/get-all')
                .then(response => this.classifications = response.data.data);
			},

			AddNewCompany: function() {
				this.add_record = true;
				this.edit_record = false;
                this.company.classification_id = '';
                this.company.name = '';
                this.company.address = '';
                this.company.tin = '';
                this.company.active = false;				
              	$('#company-form').modal('show');
            },

            storeCompany: function() {
                axios.post('/admin/company/store', this.company)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#company-form').modal('hide');
				})
            },

			editCompany: function(id) {
                axios.get('/admin/company/'+id)
                .then(response => {
                    var company = response.data.data;
                    this.company.id = id;
                    this.company.name = company.name;
                    this.company.classification_id = company.classification_id;
                    this.company.address = company.address;
                    this.company.tin = company.tin;
                    this.company.active = company.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#company-form').modal('show');
                });
            },

            updateCompany: function() {
                axios.put('/admin/company/update', this.company)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#company-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	   }
    };
</script> 
