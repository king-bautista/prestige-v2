<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
									:otherButtons="otherButtons" :primaryKey="primaryKey"
									v-on:AddNewCustomerCare="AddNewCustomerCare" v-on:editButton="editCustomerCare"
									v-on:downloadCsv="downloadCsv" ref="dataTable">
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
		<div class="modal fade" id="customer-care-form" data-backdrop="static" tabindex="-1"
			aria-labelledby="customer-care-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							CustomerCare</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit CustomerCare</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="User" class="col-sm-3 col-form-label">User Name</label>
								<div class="col-sm-9">
									<multiselect v-model="customer_care.user_id" track-by="full_name" label="full_name"
										placeholder="User Name" :options="users" :searchable="true" :allow-empty="false">
									</multiselect>
									<!-- <multiselect v-model="tenant.brand_id" track-by="name" label="name" 
										placeholder="Select Brand" :options="brands" :searchable="true" :allow-empty="false">
                                    </multiselect>  -->
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">First Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.first_name"
										placeholder="First Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Last Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.last_name"
										placeholder="Last Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Ticket Subject <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.ticket_subject"
										placeholder="Ticket Subject" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Ticket Description <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<textarea class="form-control" rows="5" v-model="customer_care.ticket_description"
										placeholder="Ticket Description"></textarea>

								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Assigned to ID <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.assigned_to_id"
										placeholder="Assigned to ID" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Assigned to Alias <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.assigned_to_alias"
										placeholder="Assigned to Alias" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="Status" class="col-sm-3 col-form-label">Change Status</label>
								<div class="col-sm-9">
									<multiselect v-model="customer_care.status_id" track-by="name" label="name"
										placeholder="Change Status" :multiple="false" :options="transaction_statuses"
										:searchable="true" :allow-empty="false">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="active" class="col-sm-3 col-form-label">Active</label>
								<div class="col-sm-9">
									<div class="form-check form-switch form-switch-md mb-3">
										<input type="checkbox" class="custom-control-input form-check-input" id="active"
											v-model="customer_care.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeCustomerCare">Add New Customer Care</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateCustomerCare">Save Changes</button>
						</div>
						<!-- /.card-body -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->



	</div>
</template>

<script>
import Table from '../Helpers/Table';
// Import this component
import Multiselect from 'vue-multiselect';

export default {
	name: "Customer_Care",
	data() {
		return {
			helper: new Helpers(),
			customer_care: {
				id: '',
				ticket_id: '',
				user_id: '',
				first_name: '',
				last_name: '',
				ticket_subject: '',
				ticket_description: '',
				ticket_description: '',
				assigned_to_id: '',
				assigned_to_alias: '',
				status_id: '',
				active: true,
			},
			transaction_statuses: [],
			users: [],
			add_record: true,
			edit_record: false,
			dataFields: {
				ticket_id: "Ticket ID",
				first_name: "First Name",
				last_name: "Last Name",
				ticket_subject: "Ticket Subject",
				ticket_description: "Ticket Description",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge bg-danger">Deactivated</span>',
						1: '<span class="badge bg-info">Active</span>'
					}
				},
				status_id: {
					name: "Transaction Status",
					type: "Boolean",
					status: {
						1: '<span class="badge bg-info">Draft</span>',
						2: '<span class="badge bg-primary">New</span>',
						3: '<span class="badge bg-info">Pending approval</span>',
						4: '<span class="badge bg-danger">Disapprove</span>',
						5: '<span class="badge bg-success">Approved</span>',
						6: '<span class="badge bg-secondary">For review</span>',
						7: '<span class="badge bg-info">Archive</span>',
						8: '<span class="badge bg-success">Saved</span>',
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/customer-care/list/",
			actionButtons: {
				edit: {
					title: 'Edit this Customer Care',
					name: 'Edit',
					apiUrl: '',
					routeName: 'customer_care.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Customer Care',
					name: 'Delete',
					apiUrl: '/admin/customer_care/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Customer Care',
					v_on: 'AddNewCustomerCare',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Customer Care',
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
			//ad_types: ['Online','Banners','Fullscreens','Pop-Ups','Events','Promos'],
		};
	},

	created() {
		this.getStatuses();
		this.getUsers();
	},

	methods: {
		getStatuses: function (id) {
			axios.get('/admin/content-management/transaction-statuses')
				.then(response => this.transaction_statuses = response.data.data);
		},
		getUsers: function () {
			axios.get('/admin/customer-care/users')
				.then(response => this.users = response.data.data);
		},

		AddNewCustomerCare: function () {
			this.customer_care.ticket_id = '';
			this.customer_care.first_name = '';
			this.customer_care.last_name = '';
			this.customer_care.ticket_subject = '';
			this.customer_care.ticket_description = '';
			this.customer_care.assigned_to_id = '';
			this.customer_care.assigned_to_alias = '';
			this.customer_care.status_id = '';
			this.customer_care.user_id = '';
			this.customer_care.active = true;
			$('#customer-care-form').modal('show');
		},

		storeCustomerCare: function () {
			var status_id = this.customer_care.status_id.id;
			var user_id = this.customer_care.user_id.id;
			let formData = new FormData();
			formData.append("first_name", this.customer_care.first_name);
			formData.append("last_name", this.customer_care.last_name);
			formData.append("ticket_subject", this.customer_care.ticket_subject);
			formData.append("ticket_description", this.customer_care.ticket_description);
			formData.append("assigned_to_id", this.customer_care.assigned_to_id);
			formData.append("assigned_to_alias", this.customer_care.assigned_to_alias);
			formData.append("status_id", status_id);
			formData.append("user_id", user_id);
			formData.append("active", this.customer_care.active);
			axios.post('/admin/customer-care/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#customer-care-form').modal('hide');
				});
		},

		editCustomerCare: function (id) {
			axios.get('/admin/customer-care/' + id)
				.then(response => {
					var customer_care = response.data.data;
					this.customer_care.id = customer_care.id;
					this.getStatuses(customer_care.id);
					this.customer_care.first_name = customer_care.first_name;
					this.customer_care.last_name = customer_care.last_name;
					this.customer_care.ticket_subject = customer_care.ticket_subject;
					this.customer_care.ticket_description = customer_care.ticket_description;
					this.customer_care.status_id = customer_care.status_id;
					this.customer_care.assigned_to_id = customer_care.assigned_to_id;
					this.customer_care.assigned_to_alias = customer_care.assigned_to_alias;
					this.customer_care.active = customer_care.active;
					$('#customer-care-form').modal('show');
				});
		},

		updateCustomerCare: function () {
			let formData = new FormData();
			formData.append("id", this.customer_care.id);
			formData.append("first_name", this.customer_care.first_name);
			formData.append("last_name", this.customer_care.last_name);
			formData.append("ticket_subject", this.customer_care.ticket_subject);
			formData.append("ticket_description", this.customer_care.ticket_description);
			formData.append("assigned_to_id", this.customer_care.assigned_to_id);
			formData.append("assigned_to_alias", this.customer_care.assigned_to_alias);
			formData.append("active", this.customer_care.active);
			axios.post('/admin/customer-care/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#customer-care-form').modal('hide');
				})
		},
		downloadCsv: function () { 
			axios.get('/admin/customer-care/download-csv')
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
		Multiselect,
	}
};
</script>
 