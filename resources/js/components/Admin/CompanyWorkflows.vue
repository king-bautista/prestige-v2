<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row" v-show="data_form">
					<div class="col-md-12">
						<div class="card m-3">
							<div class="card-header">
								<h5 class="card-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add
									New Company</h5>
								<h5 class="card-title" v-show="edit_record"><i class="fas fa-edit"></i> Company Info</h5>
								<button type="button" class="btn btn-secondary btn-sm float-right" @click="backToList"><i
										class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-5">
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Company Name</label>
											<div class="col-sm-8">
												{{ company.name }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Classification</label>
											<div class="col-sm-8">
												{{ company.classification_name }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Email</label>
											<div class="col-sm-8">
												{{ company.email }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Parent Company</label>
											<div class="col-sm-8">
												{{ company.parent_company }}
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">TIN Number</label>
											<div class="col-sm-8">
												{{ company.tin }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Contact Number</label>
											<div class="col-sm-8">
												{{ company.contact_number }}
											</div>
										</div>
										<div class="form-group row mb-0">
											<label for="firstName" class="col-sm-4">Address</label>
											<div class="col-sm-8">
												{{ company.address }}
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="row" v-show="data_form">
					<div class="col-md-12">
						<div class="card m-3">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-file-contract"></i> WorkFlow</h5>
								<button type="button" class="btn btn-primary btn-sm m-2 float-right"
									@click="AddNewWorkflow"><i class="fa fa-plus"
										aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
								<button type="button" class="btn btn-primary btn-sm m-2 float-right" @click="downloadCsv"><i
										class="fa fa-download" aria-hidden="true"></i> Download CSV</button>
							</div>
							<div class="card-body" v-if="workflows1.length">
								<div class="table-responsive mt-2">
									<label for="level1">Level 1</label>
									<table class="table table-hover" id="dataTable" style="width:100%">
										<thead class="table-dark">
											<tr>
												<th>ID</th>
												<th>User/s</th>
												<th>Permission Level</th>
												<th>Condition</th>
												<th>Active</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(data1, index1) in workflows1" v-bind:key="index1">
												<td class="align-middle">{{ data1.id }}</td>
												<td class="align-middle">{{ data1.user_name }}</td>
												<td class="align-middle">{{ data1.permission_level }}</td>
												<td class="align-middle">{{ data1.condition }}</td>
												<td class="align-middle">
													<span v-if="data1.active" class="badge badge-info">Yes</span>
													<span v-else class="badge badge-info">No</span>
												</td>
												<td class="align-middle text-nowrap">
													<button type="button" class="btn btn-outline-danger"
														@click="deleteModal('removeWorkflow1', index1)" title="Delete"><i
															class="fas fa-trash-alt"></i></button>
													<button type="button" class="btn btn-outline-danger"
														@click="editWorkflow(data1.id)" title="Edit"><i
															class="fas fa-edit"></i></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-body" v-if="workflows2.length">
								<div class="table-responsive mt-2">
									<label for="level2">Level 2</label>
									<table class="table table-hover" id="dataTable" style="width:100%">
										<thead class="table-dark">
											<tr>
												<th>ID</th>
												<th>User/s</th>
												<th>Permission Level</th>
												<th>Condition</th>
												<th>Active</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(data2, index2) in workflows2" v-bind:key="index2">
												<td class="align-middle">{{ data2.id }}</td>
												<td class="align-middle">{{ data2.user_name }}</td>
												<td class="align-middle">{{ data2.permission_level }}</td>
												<td class="align-middle">{{ data2.condition }}</td>
												<td class="align-middle">
													<span v-if="data2.active" class="badge badge-info">Yes</span>
													<span v-else class="badge badge-info">No</span>
												</td>
												<td class="align-middle text-nowrap">
													<button type="button" class="btn btn-outline-danger"
														@click="deleteModal('removeWorkflow2', index2)" title="Delete"><i
															class="fas fa-trash-alt"></i></button>
													<button type="button" class="btn btn-outline-danger"
														@click="editWorkflow(data2.id)" title="Edit"><i
															class="fas fa-edit"></i></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-body" v-if="workflows3.length">
								<div class="table-responsive mt-2">
									<label for="level3">Level 3</label>
									<table class="table table-hover" id="dataTable" style="width:100%">
										<thead class="table-dark">
											<tr>
												<th>ID</th>
												<th>User/s</th>
												<th>Permission Level</th>
												<th>Condition</th>
												<th>Active</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(data3, index3) in workflows3" v-bind:key="index3">
												<td class="align-middle">{{ data3.id }}</td>
												<td class="align-middle">{{ data3.user_name }}</td>
												<td class="align-middle">{{ data3.permission_level }}</td>
												<td class="align-middle">{{ data3.condition }}</td>
												<td class="align-middle">
													<span v-if="data3.active" class="badge badge-info">Yes</span>
													<span v-else class="badge badge-info">No</span>
												</td>
												<td class="align-middle text-nowrap">
													<button type="button" class="btn btn-outline-danger"
														@click="deleteModal('removeWorkflow3', index3)" title="Delete"><i
															class="fas fa-trash-alt"></i></button>
													<button type="button" class="btn btn-outline-danger"
														@click="editWorkflow(data3.id)" title="Edit"><i
															class="fas fa-edit"></i></button>
												</td>
											</tr>
										</tbody>
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

		<div class="modal fade" id="workflow-form" tabindex="-1" aria-labelledby="workflow-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fas fa-file-contract"></i> Workflow Details</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
							<label for="CompanyName" class="col-sm-4 col-form-label">Company Name</label>
							<div class="col-sm-8">
								{{ company.name }}
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">User <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<select class="custom-select" v-model="workflow.user_id">
									<option value="">Select User</option>
									<option v-for="user in users" :value="user.id"> {{
										user.full_name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="role" class="col-sm-4 col-form-label">Permission Level <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<select class="custom-select" v-model="workflow.permission_level">
									<option value="">Select Permission Level</option>
									<option v-for="permission_level in permission_levels" :value="permission_level"> {{
										permission_level }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="role" class="col-sm-4 col-form-label">Condition<span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<select class="custom-select" v-model="workflow.condition">
									<option value="">Select Condition</option>
									<option v-for="condition in conditions" :value="condition"> {{
										condition }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="active" class="col-sm-4 col-form-label">Active</label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="active"
										v-model="company.active">
									<label class="custom-control-label" for="active"></label>
								</div>
							</div>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm float-right"
							data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm float-right" v-show="add_workflow"
							@click="storeWorkflow">Add New Workflow</button>
						<button type="button" class="btn btn-primary btn-sm float-right" v-show="edit_workflow"
							@click="updateWorkflow">Save Changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="delete-record" tabindex="-1" aria-labelledby="delete-record" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to delete?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="deleteRecord">OK</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
import Table from '../Helpers/Table';
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import Multiselect from 'vue-multiselect';

export default {
	name: "Users",
	data() {
		return {
			company: {
				id: '',
				parent_id: '',
				classification_id: '',
				classification_name: '',
				name: '',
				email: '',
				contact_number: '',
				address: '',
				tin: '',
				parent_company: '',
				active: false,
				brands: [],
				contracts: [],
			},
			workflow: {
				id: '',
				company_id: '',
				user_id: '',
				permission_level: '',
				condition: '',
				active: false,

			},
			permission_levels: ['Level 1', 'Level 2', 'Level 3'],
			conditions: ['Strick', 'Any'],
			users: [],
			workflows1: [],
			workflows2: [],
			workflows3: [],
			data_list: false,
			data_form: true,
			add_record: true,
			edit_record: false,
			add_workflow: true,
			edit_workflow: false,
			delete_action: '',
			delete_index: '',
			primaryKey: "id",
		};
	},

	created() {
		this.getCompanyDetails();
		this.getUsers();
		this.getWorkflows1();
		this.getWorkflows2();
		this.getWorkflows3();

	},

	methods: {
		getWorkflows1: function () {
			axios.get('/admin/company/workflow/get-list/' + 1)
				.then(response => this.workflows1 = response.data.data);
		},
		getWorkflows2: function () {
			axios.get('/admin/company/workflow/get-list/' + 2)
				.then(response => this.workflows2 = response.data.data);
		},
		getWorkflows3: function () {
			axios.get('/admin/company/workflow/get-list/' + 3)
				.then(response => this.workflows3 = response.data.data);
		},
		getUsers: function (id) {
			axios.get('/admin/company/workflow/get-users')
				.then(response => this.users = response.data.data);
		},
		getCompanyDetails: function () {

			axios.get('/admin/company/workflow/get-company-details')
				.then(response => {
					var company = response.data.data;
					this.company.id = company.id;
					this.company.name = company.name;
					this.company.parent_id = company.parent_id;
					this.company.classification_id = company.classification_id;
					this.company.classification_name = company.classification_name;
					this.company.email = company.email;
					this.company.contact_number = company.contact_number;
					this.company.address = company.address;
					this.company.tin = company.tin;
					this.company.parent_company = company.parent_company;
					this.company.active = company.active;
					this.company.brands = company.brands;
					this.company.contracts = company.contracts;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
				});
		},
				
		editCompany: function (id) {
			axios.get('/admin/company/' + id)
				.then(response => {
					var company = response.data.data;
					this.company.id = id;
					this.company.name = SScompany.name;
					this.company.parent_id = company.parent_id;
					this.company.classification_id = company.classification_id;
					this.company.classification_name = company.classification_name;
					this.company.email = company.email;
					this.company.contact_number = company.contact_number;
					this.company.address = company.address;
					this.company.tin = company.tin;
					this.company.parent_company = company.parent_company;
					this.company.active = company.active;
					this.company.brands = company.brands;
					this.company.contracts = company.contracts;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
				});
		},

		backToList: function () {
			this.data_list = true;
			this.data_form = false;
		},

		AddNewWorkflow: function () {
			this.workflow.company_id = this.company.id;
			this.workflow.user_id = '',
			this.workflow.permission_level = '',
			this.workflow.condition = '',
			this.workflow.active = false;
			this.add_workflow = true;
			this.edit_workflow = false;
			$('#workflow-form').modal('show');
		},

		storeWorkflow: function () {
			axios.post('/admin/company/workflow/store', this.workflow)
				.then(response => {
					toastr.success(response.data.message);
					this.getWorkflows1();
					this.getWorkflows2();
					this.getWorkflows3();
					$('#workflow-form').modal('hide');
				})
		},

		updateWorkflow: function () {
			axios.put('/admin/company/workflow/update', this.workflow)
				.then(response => {
					toastr.success(response.data.message);
					this.editCompany(this.company.id);
					this.getWorkflows1();
					this.getWorkflows2();
					this.getWorkflows3();
					$('#workflow-form').modal('hide');
				})
		},

		removeWorkflow1: function (index1) {
			axios.get('/admin/company/workflow/delete/' + this.workflows1[index1].id)
				.then(response => {
					if (response.data.data) {
						this.workflows1.splice(index1, 1);
						toastr.success('User workflow level has been removed.');
					}
				});
		},

		removeWorkflow2: function (index2) {
			axios.get('/admin/company/workflow/delete/' + this.workflows2[index2].id)
				.then(response => {
					if (response.data.data) {
						this.workflows2.splice(index2, 1);
						toastr.success('User workflow level has been removed.');
					}
				});
		},

		removeWorkflow3: function (index3) {
			axios.get('/admin/company/workflow/delete/' + this.workflows3[index3].id)
				.then(response => {
					if (response.data.data) {
						this.workflows3.splice(index3, 1);
						toastr.success('User workflow level has been removed.');
					}
				});
		},

		deleteModal: function (action, index) {
			this.delete_action = action;
			this.delete_index = index;
			$('#delete-record').modal('show');
		},

		deleteRecord: function () {
			if (this.delete_action == 'removeWorkflow1') {
				this.removeWorkflow1(this.delete_index);
			}
			else if (this.delete_action == 'removeWorkflow2') {
				this.removeWorkflow2(this.delete_index);
			} else {
				this.removeWorkflow3(this.delete_index);
			}
			this.delete_action = '';
			this.delete_index = '';
			$('#delete-record').modal('hide');
		},

		editWorkflow: function (id) {
			axios.get('/admin/company/workflowzzz/' + id)
				.then(response => {
					var workflow = response.data.data;
					this.workflow.id = workflow.id;
					this.workflow.user_id = workflow.user_id;
					this.workflow.permission_level = workflow.permission_level;
					this.workflow.condition = workflow.condition;
					this.workflow.active = workflow.active;
					this.add_workflow = false;
					this.edit_workflow = true;
					$('#workflow-form').modal('show');
				});
		},

		downloadCsv: function () {
			axios.get('/admin/company/workflow/download-csv')
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
		Treeselect,
		Multiselect
	}
};
</script> 
<style lang="scss" scoped>
.img-thumbnail {
	max-width: 4rem;
}
</style>