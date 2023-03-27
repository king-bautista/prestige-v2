<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fas fa-photo-video"></i>&nbsp;&nbsp;Customer Care</h4>
						<h4 v-show="add_record && data_form"><i class="nav-icon fas fa-user-plus"></i> Add New Customer Care
						</h4>
						<h4 v-show="edit_record && data_form"><i class="nav-icon fas fa-user-edit"></i> Edit Customer Cares
						</h4>
					</div>
					<div class="card-body" v-show="data_list">
						<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
							:otherButtons="otherButtons" :primaryKey="primaryKey"
							v-on:AddNewCustomerCare="AddNewCustomerCare" v-on:editButton="editCustomerCare" ref="dataTable">
						</Table>
					</div>
					<div class="card-body" v-show="data_form">
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
								<input type="text" class="form-control" v-model="customer_care.ticket_description"
									placeholder="Ticket Description" required>
							</div>
						</div>
						<!-- <div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">First Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.first_name" placeholder="Advertisements Name" required>
								</div>
						</div> -->
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
						<div class="form-group row" v-show="edit_record">
							<label for="Active" class="col-sm-4 col-form-label">Active</label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="active"
										v-model="customer_care.active">
									<label class="custom-control-label" for="active"></label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i
										class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="add_record"
									@click="storeCustomerCare">Add New Customer Care</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="edit_record"
									@click="updateCustomerCare">Save Changes</button>
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
// Import this component
import Multiselect from 'vue-multiselect';

export default {
	name: "Customer_Care",
	// props: {
	// 	ad_type: {
	// 		type: String,
	// 		required: true
	// 	},
	// },
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
			data_list: true,
			data_form: false,
			// material: '',
			// material_type: '',
			// companies: [],
			// brands: [],
			transaction_statuses: [],
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
			//dataUrl: "/portal/create-ad/list/"+this.ad_type,
			dataUrl: "/portal/customer-care/list/",
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
					apiUrl: '/portal/customer_care/delete',
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
			},
			//ad_types: ['Online','Banners','Fullscreens','Pop-Ups','Events','Promos'],
		};
	},

	created() {
		this.getStatuses();
	},

	methods: {
		getStatuses: function (id) {
			//axios.get('/admin/content-management/transaction-statuses')
			axios.get('/portal/upload-content/transaction-statuses')
			.then(response => this.transaction_statuses = response.data.data);
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
			this.customer_care.active = true;
			this.data_list = false;
			this.data_form = true;
			this.add_record = true;
			this.edit_record = false;
		},

		storeCustomerCare: function () {
			var status_id = this.customer_care.status_id.id;
			let formData = new FormData();
			formData.append("first_name", this.customer_care.first_name);
			formData.append("last_name", this.customer_care.last_name);
			formData.append("ticket_subject", this.customer_care.ticket_subject);
			formData.append("ticket_description", this.customer_care.ticket_description);
			formData.append("assigned_to_id", this.customer_care.assigned_to_id);
			formData.append("assigned_to_alias", this.customer_care.assigned_to_alias);
			formData.append("status_id", status_id);
			formData.append("active", this.customer_care.active);
			axios.post('/portal/customer-care/store', formData, {
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
			axios.get('/portal/customer-care/' + id)
				.then(response => {
					var customer_care = response.data.data;
					this.customer_care.id = customer_care.id;
					this.customer_care.first_name = customer_care.first_name;
					this.customer_care.last_name = customer_care.last_name;
					this.customer_care.ticket_subject = customer_care.ticket_subject;
					this.customer_care.ticket_description = customer_care.ticket_description;
					this.customer_care.status_id = customer_care.status_id;
					this.customer_care.assigned_to_id = customer_care.assigned_to_id;
					this.customer_care.assigned_to_alias = customer_care.assigned_to_alias;
					this.customer_care.active = customer_care.active;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
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
			axios.post('/portal/customer-care/update', formData, {
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
		backToList: function () {
			this.data_list = true;
			this.data_form = false;
		},

	},

	components: {
		Table,
		Multiselect,
	}
};
</script> 