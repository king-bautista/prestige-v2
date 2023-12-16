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
							<div class="col-sm-2">
								<img :src="company_image" class="img-fluid" />
							</div>
							<div class="col-md-4">
								
								<p><b style="font-weight: 600;">Company Name:</b> {{ company.name }}<br>
									<b style="font-weight: 600;">First Namer:</b>{{ company.first_name }}<br>
									<b style="font-weight: 600;">Last Name:</b>{{ company.last_name }}<br>
									<b style="font-weight: 600;">Email:</b> {{ company.user_email }}<br>
									<b style="font-weight: 600;">Contact Number:</b>{{ company.contact_number }}<br>
								</p>
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
			},
			company: [],
			concerns: [],
			add_record: true,
			primaryKey: "id",
			company_image: '/images/contact-us.png',
		};
	},

	created() {
		this.getCompany();
		this.getConcerns();
	},

	methods: {

		AddNewCustomerCare: function () {
			this.customer_care.conncern_id = '';
			this.customer_care.first_name = '';
			this.customer_care.last_name = '';
			this.customer_care.ticket_subject = '';
			this.customer_care.ticket_description = '';
			// $("#hello").val('').trigger('chosen:updated');
			// //$("#hello").empty();
			// $("#hello").removeAttr('selected');
			// $("#hello").attr("selected", false); 
			// $("#hello").removeAttr('selected');
			// // $("div.id_100 select").val("val2");
			// $('.custom-select').prop('selectedIndex',0);
			// $("#hello").val("");
			//	$('#hello').empty();    
			//s
			//$('#hello').val("");
			//$('#hello').filter('[value=val3]').attr('selected', true);
			//	$('#hello').append($('<option>',{text: 'Select Concern', value: '', selected: true}))	
			//this.customer_care.conncern_id = '3';
			//$('#hello').find('option:selected').remove();	
			$("#hello option[value='']").prop('selected', true);


		},

		storeCustomerCare: function () {
			let formData = new FormData();
			formData.append("concern_id", this.customer_care.concern_id);
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
					this.AddNewCustomerCare();
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
				});
		},
		getCompany: function () {
			axios.get('/portal/customer-care/get-company')
				.then(response => this.company = response.data.data);
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
 