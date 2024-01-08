<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-phone-square"></i>&nbsp;&nbsp;Contact Us</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-5">
								<div class="form-group row">
									<label for="ticketSubject" class="col-sm-3 col-form-label">Company Name</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly v-model="contact.company" style="background-color:#f5f5f5;"
											placeholder="Company" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="ticketSubject" class="col-sm-3 col-form-label">First Name</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly v-model="contact.first_name" style="background-color:#f5f5f5;"
											placeholder="First Name" required>
									</div>
								</div>

								<div class="form-group row">
									<label for="ticketSubject" class="col-sm-3 col-form-label">Last Name</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly v-model="contact.last_name" style="background-color:#f5f5f5;"
											placeholder="Last Name" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="ticketSubject" class="col-sm-3 col-form-label">Email</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" v-model="contact.email"
											placeholder="Email" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="ticketSubject" class="col-sm-3 col-form-label">Contact Number</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" v-model="contact.number"
											placeholder="Contact Number" required>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row mb-4">
									<label for="concern" class="col-sm-3 col-form-label">Ticket Type <span
											class="font-italic text-danger"> *</span></label>
									<div class="col-sm-9">
										<select class="form-control custom-select" id="hello"
											v-model="customer_care.concern_id">
											<option value="">Select Ticket</option>
											<option v-for="concern in concerns" :value="concern.id"> {{ concern.name }}
											</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="ticketSubject" class="col-sm-3 col-form-label">Subject <span
											class="font-italic text-danger"> *</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" v-model="customer_care.ticket_subject"
											placeholder="Ticket Subject" required>
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
									<label for="firstName" class="col-sm-4 col-form-label">Image <span
											class="font-italic text-danger"> *</span></label>
									<div class="col-sm-5">
										<input type="file" id="fileinput" accept="image/*" ref="image"
											@change="ImageChange">
										<footer class="blockquote-footer">Image max size is 5MB</footer>
									</div>
									<div class="col-sm-3 text-center">
										<img v-if="image" :src="image" class="img-thumbnail" />
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
import Treeselect from '@riophae/vue-treeselect'
import Multiselect from 'vue-multiselect';
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
	name: "Customer_Care",
	data() {
		return {
			helper: new Helpers(),
			customer_care: {
				id: '',
				concern_id: '',
				first_name: '',
				last_name: '',
				ticket_subject: '',
				ticket_description: '',
				image: '/images/no-image-available.png',
			},
			contact: {
				company: '',
				first_name: '',
				last_name: '',
				email: '',
				number: '',
			},
			image: '',
			company: [],
			concerns: [],
			add_record: true,
			primaryKey: "id",
			company_image: '/images/contact-us.png',
		};
	},

	created() {
		this.getConcerns();
		this.AddNewCustomerCare();
	},

	methods: {
		ImageChange: function (e) {
			const file = e.target.files[0];
			this.image = URL.createObjectURL(file);
			this.customer_care.image = file;
		},

		AddNewCustomerCare: function () {
			this.customer_care.conncern_id = '';
			this.customer_care.first_name = '';
			this.customer_care.last_name = '';
			this.customer_care.ticket_subject = '';
			this.customer_care.ticket_description = '';
			this.customer_care.image = '/images/no-image-available.png';
			axios.get('/portal/customer-care/get-company')
				.then(response => {
					var company = response.data.data;
					this.contact.company = company.name;
					this.contact.first_name = company.first_name;
					this.contact.last_name = company.last_name;
					this.contact.email = company.user_email;
					this.contact.number = company.contact_number;
					}
				);
		},

		storeCustomerCare: function () {
			let formData = new FormData();
			formData.append("contact_email", this.contact.email);
			formData.append("contact_number", this.contact.number);
			formData.append("concern_id", this.customer_care.concern_id);
			formData.append("first_name", this.customer_care.first_name);
			formData.append("last_name", this.customer_care.last_name);
			formData.append("ticket_subject", this.customer_care.ticket_subject);
			formData.append("ticket_description", this.customer_care.ticket_description);
			formData.append("image", this.customer_care.image);
			axios.post('/portal/customer-care/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					this.AddNewCustomerCare();
					this.image = '';
					$("#fileinput").val('');
					$("#hello option[value='']").prop('selected', true);
					this.customer_care.concern_id = '';
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
				});
		},
		getConcerns: function () {
			axios.get('/portal/customer-care/get-concerns')
				.then(response => this.concerns = response.data.data);
		},
	},

	components: {
		Table,
		Multiselect,
		Treeselect,
	}
};
</script>
 