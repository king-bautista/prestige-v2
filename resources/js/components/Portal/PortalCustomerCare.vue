<template>
	<div>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-phone-square"></i>&nbsp;&nbsp;Customer Care</h4>
					</div>
					<div class="card-body">
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">First Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="customer_care.first_name"
									placeholder="First Name" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-3 col-form-label">Last Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="customer_care.last_name"
									placeholder="Last Name" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="ticketSubject" class="col-sm-3 col-form-label">Ticket Subject <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="customer_care.ticket_subject"
									placeholder="Ticket Subject" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="ticketDescription" class="col-sm-3 col-form-label">Ticket Description <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="customer_care.ticket_description"
									placeholder="Ticket Description" required>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-primary btn-sm" v-show="add_record"
									@click="storeCustomerCare">Add New Customer Cares</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-copyright"></i>&nbsp;&nbsp;Company</h4>
					</div>
					<div class="card-body">
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Name:</label>
							<div class="col-sm-9">{{ company.name }}</div>
						</div>
						<div class="form-group row">
							<label for="email" class="col-sm-3 col-form-label">Email:</label>
							<div class="col-sm-9">{{ company.email }}</div>
						</div>
						<div class="form-group row">
							<label for="contact_number" class="col-sm-3 col-form-label">Contact Number:</label>
							<div class="col-sm-9">{{ company.contact_number }}</div>
						</div>
						<div class="form-group row">
							<label for="address" class="col-sm-3 col-form-label">Address:</label>
							<div class="col-sm-9">{{ company.address }}</div>
						</div>
					</div>
					<div></div>
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
	data() {
		return {
			helper: new Helpers(),
			customer_care: {
				id: '',
				first_name: '',
				last_name: '',
				ticket_subject: '',
				ticket_description: '',
			},
			company: [],
			add_record: true,
			primaryKey: "id",
		};
	},

	created() {
		this.getCompany();
	},

	methods: {

		AddNewCustomerCare: function () {
			this.customer_care.first_name = '';
			this.customer_care.last_name = '';
			this.customer_care.ticket_subject = '';
			this.customer_care.ticket_description = '';
		},

		storeCustomerCare: function () {
			let formData = new FormData();
			formData.append("first_name", this.customer_care.first_name);
			formData.append("last_name", this.customer_care.last_name);
			formData.append("ticket_subject", this.customer_care.ticket_subject);
			formData.append("ticket_description", this.customer_care.ticket_description);
			axios.post('/portal/customer-care/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
				});
		},
		getCompany: function () {
			axios.get('/portal/customer-care/get-company')
				.then(response => this.company = response.data.data);
		},
	},

	components: {
		Table,
		Multiselect,
	}
};
</script>
 