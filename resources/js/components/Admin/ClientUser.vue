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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewUser="AddNewUser"
									v-on:editButton="editUser" v-on:modalBatchUpload="modalBatchUpload"
									v-on:downloadCsv="downloadCsv" v-on:downloadTemplate="downloadTemplate" ref="dataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->

		<div class="modal fade" id="user-form" tabindex="-1" aria-labelledby="user-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="card-title">User Details</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
							<label for="email" class="col-sm-4 col-form-label">Email <span class="font-italic text-danger">
									*</span></label>
							<div class="col-sm-8">
								<input type="email" class="form-control" v-model="user.email" placeholder="Email">
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">First Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="user.first_name" placeholder="First Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Last Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="user.last_name" placeholder="Last Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Password <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-6">
								<button type="button" class="btn btn-block btn-outline-info btn-sm" v-show="displayButton"
									@click="showPassword">Show password</button>

								<div class="input-group mb-3" v-show="displayPassword">
									<input type="text" class="form-control" v-model="user.password"
										aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="button" @click="showPassword"><i
												class="fas fa-sync-alt"></i></button>
									</div>
								</div>
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-block btn-outline-secondary btn-sm"
									v-show="displayPassword" @click="cancelPassword"
									style="margin-top: 4px;">Cancel</button>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Roles <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<multiselect v-model="user.roles" :options="role_list" :multiple="true"
									:close-on-select="true" placeholder="Select Roles" label="name" track-by="name">
								</multiselect>
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="isActive" class="col-sm-4 col-form-label">Active</label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="isActive"
										v-model="user.isActive">
									<label class="custom-control-label" for="isActive"></label>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Company <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<multiselect v-model="user.company" :options="companies" :multiple="false"
									:close-on-select="true" placeholder="Select Company" label="name" track-by="name"
									@input="companyDetail">
								</multiselect>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Classification</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.classification_name }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Email</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.email }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Contact Number</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.contact_number }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Address</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.address }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">TIN</label>
							<div class="col-sm-8">
								<span>
									{{ user.company.tin }}
								</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Brands</label>
							<div class="col-sm-8">
								<img v-for="(brand, index) in user.company.brands" class="img-thumbnail"
									:src="brand.logo_image_path" />
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Contracts</label>
							<div class="col-sm-8">
								<ul class="pl-3">
									<li v-for="(contract, index) in user.company.contracts">
										{{ contract.serial_number }} - {{ contract.name }}
									</li>
								</ul>
							</div>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary float-right btn-sm"
							data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeUser">Add New
							User</button>
						<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateUser">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Batch Upload -->
		<div class="modal fade" id="batchModal" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="batchModalLabel">Batch Upload</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-group col-md-12">
								<label>CSV File: <span class="text-danger">*</span></label>
								<div class="custom-file">
									<input type="file" ref="file" v-on:change="handleFileUpload()"
										accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
										class="custom-file-input" id="batchInput">
									<label class="custom-file-label" id="batchInputLabel" for="batchInput">Choose
										file</label>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="storeBatch">Save changes</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Table from '../Helpers/Table';
import Multiselect from 'vue-multiselect';
import { generatePassword } from '../Helpers/GeneratePassword';

export default {
	name: "Client_Users",
	data() {
		return {
			filter: {
				company_id: '',
				site_ids: [],
				brand_ids: [],
			},
			user: {
				id: '',
				email: '',
				first_name: '',
				last_name: '',
				password: '',
				password_confirmation: '',
				roles: [],
				isActive: false,
				emailNotification: '',
				company: '',
			},
			add_record: true,
			edit_record: false,
			displayPassword: false,
			displayButton: true,
			role_list: [],
			companies: [],
			dataFields: {
				full_name: "Full Name",
				email: "Email",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/client/users/list",
			actionButtons: {
				edit: {
					title: 'Edit this user',
					name: 'Edit',
					apiUrl: '',
					routeName: 'user.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this user',
					name: 'Delete',
					apiUrl: '/admin/client/users/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New User',
					v_on: 'AddNewUser',
					icon: '<i class="fas fa-user-plus"></i> New User',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
				batchUpload: {
					title: 'Batch Upload',
					v_on: 'modalBatchUpload',
					icon: '<i class="fas fa-upload"></i> Batch Upload',
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
				downloadCsv: {
					title: 'Download',
					v_on: 'downloadTemplate',
					icon: '<i class="fa fa-download" aria-hidden="true"></i> Template',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			},
		};
	},

	created() {
		axios.get('/admin/roles/get-portal')
			.then(response => this.role_list = response.data.data);

		axios.get('/admin/company/get-all')
			.then(response => this.companies = response.data.data);
	},

	methods: {
		AddNewUser: function () {
			this.add_record = true;
			this.edit_record = false;
			this.user.email = '';
			this.user.first_name = '';
			this.user.last_name = '';
			this.user.password = '';
			this.user.password_confirmation = '';
			this.user.roles = [];
			this.user.isActive = false;
			this.user.company = '';
			$('#user-form').modal('show');
		},

		showPassword: function () {
			this.user.password = generatePassword(15);
			this.user.password_confirmation = this.user.password;
			this.displayPassword = true;
			this.displayButton = false;
		},

		cancelPassword: function () {
			this.displayPassword = false;
			this.displayButton = true;
		},

		storeUser: function () {
			axios.post('/admin/client/users/store', this.user)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#user-form').modal('hide');
				})
		},

		editUser: function (id) {
			axios.get('/admin/client/users/' + id)
				.then(response => {
					this.user.roles = [];
					var user = response.data.data;
					this.user.id = id;
					this.user.company = user.company;
					this.user.email = user.email;
					this.user.first_name = user.details.first_name;
					this.user.last_name = user.details.last_name;
					this.user.roles = user.roles;
					this.user.isActive = user.active;
					this.add_record = false;
					this.edit_record = true;
					$('#user-form').modal('show');

				});
		},

		updateUser: function () {
			axios.put('/admin/client/users/update', this.user)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#user-form').modal('hide');
				})
		},

		backToList: function () {
			this.data_list = true;
			this.data_form = false;
		},

		companyDetail: function (company) {
			this.filter.company_id = company.id;
		},
		modalBatchUpload: function () {
			$('#batchModal').modal('show');
		},

		handleFileUpload: function () {
			this.file = this.$refs.file.files[0];
			$('#batchInputLabel').html(this.file.name)
		},

		storeBatch: function () {
			let formData = new FormData();
			formData.append('file', this.file);

			axios.post('/admin/client/users/batch-upload', formData,
				{
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then(response => {
					this.$refs.file.value = null;
					this.$refs.dataTable.fetchData();
					toastr.success(response.data.message);
					$('#batchModal').modal('hide');
					$('#batchInputLabel').html('Choose File');
					//window.location.reload();
				})
		},

		downloadCsv: function () {
			axios.get('/admin/client/users/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		downloadTemplate: function () {
			axios.get('/admin/client/users/download-csv-template')
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
		Multiselect
	}
};
</script> 
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss" scoped>
.img-thumbnail {
	max-width: 4rem;
}

.img-thumbnail-site {
	max-width: 8rem;
}
</style>