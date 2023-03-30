<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-copyright"></i>&nbsp;&nbsp;Customer Care</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-2">
                                        <img :src="company_image" class="img-fluid" />
                                    </div>
							<div class="col-md-4">
								<p><b style="font-weight: 600;">Office Address:</b> {{ company.address }}<br>
									<b style="font-weight: 600;">Mobile Number:</b>{{ company.contact_number }}<br>
									<b style="font-weight: 600;">Email:</b> {{ company.email }}
								</p>
							</div>
							<div class="col-md-6">
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
									<label for="ticketSubject" class="col-sm-3 col-form-label">Subject <span
											class="font-italic text-danger"> *</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" v-model="customer_care.ticket_subject"
											placeholder="Subject" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="ticketDescription" class="col-sm-3 col-form-label">Description <span
											class="font-italic text-danger"> *</span></label>
									<div class="col-sm-9">
										<textarea class="form-control" rows="5" v-model="customer_care.ticket_description"
											placeholder="Description"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12 text-right">
										<button type="button" class="btn btn-primary btn-sm" v-show="add_record"
											@click="storeCustomerCare">Submit</button>
									</div>
								</div>
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
			company_image: '/images/contact-us.png',
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
 