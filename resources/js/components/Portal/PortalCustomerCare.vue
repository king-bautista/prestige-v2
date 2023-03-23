<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fas fa-photo-video"></i>&nbsp;&nbsp;Create Customer Care</h4>
						<h4 v-show="add_record && data_form"><i class="nav-icon fas fa-user-plus"></i> Add New Customer Care</h4>
						<h4 v-show="edit_record && data_form"><i class="nav-icon fas fa-user-edit"></i> Edit Customer Cares</h4>
					</div>
					<div class="card-body" v-show="data_list">
						<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:AddNewCustomerCare="AddNewCustomerCare"
						v-on:editButton="editCustomerCare"
                        ref="dataTable">
			          	</Table>
					</div>
					<div class="card-body" v-show="data_form">
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">First Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.first_name" placeholder="First Name" required>
								</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Last Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.last_name" placeholder="Last Name" required>
								</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Ticket Subject <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.ticket_subject" placeholder="Ticket Subject" required>
								</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Ticket Description</label> <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.ticket_description" placeholder="Ticket Description" required>
								</div>
						</div>
						<!-- <div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">First Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.first_name" placeholder="Advertisements Name" required>
								</div>
						</div> -->
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Assigned to ID <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.assigned_to_id" placeholder="Assigned to ID" required>
								</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Assigned to Alias <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="customer_care.assigned_to_alias" placeholder="Assigned to Alias" required>
								</div>
						</div>
						
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeCustomerCare">Add New Customer Care</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateCustomerCare">Save Changes</button>
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
                },
				data_list: true,
				data_form: false,
				// material: '',
				// material_type: '',
                // companies: [],
                // brands: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
					ticket_id: "Ticket ID",
            		first_name: "First Name", 
					last_name: "Last Name",
					ticket_subject: "Ticket Subject",
					ticket_description: "Ticket Description",
					
            		status_id: {
            			name: "Transaction Status", 
            			type:"Boolean", 
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

        // created(){
		// 	this.getCompany();
		// 	this.getBrands();
        // },

        methods: {
			// getCompany: function() {
			// 	axios.get('/portal/company/get-all')
            //     .then(response => this.companies = response.data.data);
			// },

			// getBrands: function() {
			// 	axios.get('/portal/brand/get-all')
            //     .then(response => this.brands = response.data.data);
			// },

			// materialChange: function(e) { 
			// 	const file = e.target.files[0];
			// 	this.material_type = e.target.files[0].type;
      		// 	this.material = URL.createObjectURL(file);
			// 	this.advertisements.material = file;
			// },

			AddNewCustomerCare: function() {
				// this.advertisements.company_id = null;
				// this.advertisements.brand_id = null;
                // this.advertisements.name = '';
				// this.advertisements.ad_type = '';
				// this.advertisements.file_path = '';
				// this.advertisements.display_duration = '';
                // this.advertisements.active = true;				
				// this.$refs.material.value = null;
				// this.material = null;
				this.customer_care.ticket_id = '';
				this.customer_care.first_name = '';
				this.customer_care.last_name = '';
				this.customer_care.ticket_subject = '';
				this.customer_care.ticket_description = '';
				this.customer_care.status_id = null;
				this.customer_care.assigned_to_id = '';
				this.customer_care.assigned_to_alias =  '';
				this.data_list = false;
				this.data_form = true;
				this.add_record = true;
				this.edit_record = false;
            },

            storeCustomerCare: function() { 
				let formData = new FormData();
				formData.append("first_name", this.customer_care.first_name);
				formData.append("last_name", this.customer_care.last_name);
				formData.append("ticket_subject", this.customer_care.ticket_subject);
				formData.append("ticket_description", this.customer_care.ticket_description);
				formData.append("assigned_to_id", this.customer_care.assigned_to_id);
				formData.append("assigned_to_alias", this.customer_care.assigned_to_alias);
				axios.post('/portal/customer-care/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
	              	
				});				
            },

			editCustomerCare: function(id) {
                axios.get('/portal/customer-care/'+id)
                .then(response => {
                    var customer_care = response.data.data;
					this.customer_care.id = customer_care.id;
					this.customer_care.first_name = customer_care.first_name;
					this.customer_care.last_name = customer_care.last_name;
					this.customer_care.ticket_subject = customer_care.ticket_subject;
					this.customer_care.ticket_description = customer_care.ticket_description;
					this.customer_care.status_id = null;
					this.customer_care.assigned_to_id = customer_care.assigned_to_id;
					this.customer_care.assigned_to_alias =  customer_care.assigned_to_alias;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
                });
            },

            updateCustomerCare: function() {
				let formData = new FormData();
				formData.append("id", this.customer_care.id);
				formData.append("first_name", this.customer_care.first_name);
				formData.append("last_name", this.customer_care.last_name);
				formData.append("ticket_subject", this.customer_care.ticket_subject);
				formData.append("ticket_description", this.customer_care.ticket_description);
				formData.append("assigned_to_id", this.customer_care.assigned_to_id);
				formData.append("assigned_to_alias", this.customer_care.assigned_to_alias);
				axios.post('/portal/customer-care/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
	              	
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