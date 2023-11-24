<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row" v-show="data_list">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewCompany="AddNewCompany"
									v-on:editButton="editCompany" v-on:downloadCsv="downloadCsv"
									v-on:downloadTemplate="downloadTemplate"  
									ref="dataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<div class="row" v-show="data_form">
					<div class="col-md-12">
						<div class="card m-3">
							<div class="card-header">
								<h5 class="card-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add
									New Company</h5>
								<h5 class="card-title" v-show="edit_record"><i class="fas fa-edit"></i> Edit Company</h5>
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
									<div class="coll-md-1">
										<button type="button" class="btn btn-outline-secondary btn-sm" @click="modalAdd"><i
												class="fas fa-pen"></i></button>
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
								<h5 class="card-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
								<button type="button" class="btn btn-primary btn-sm m-0 float-right" data-bs-toggle="modal"
									data-bs-target="#brand-list"><i class="fa fa-plus"
										aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
							</div>
							<div class="card-body">
								<div class="table-responsive mt-2">
									<table class="table table-hover" id="dataTable" style="width:100%">
										<thead class="table-dark">
											<tr>
												<th>Logo</th>
												<th>Name</th>
												<th>Category Name</th>
												<th>Status</th>
												<th>Last Updated</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(data, index) in company.brands" v-bind:key="index">
												<td><img class="img-thumbnail" :src="data.logo_image_path" /></td>
												<td class="align-middle">{{ data.name }}</td>
												<td class="align-middle">{{ data.category_name }}</td>
												<td class="align-middle">
													<span v-if="data.active" class="badge badge-info">Active</span>
													<span v-else class="badge badge-info">Active</span>
												</td>
												<td class="align-middle">{{ data.updated_at }}</td>
												<td class="align-middle"><button type="button"
														class="btn btn-outline-danger"
														@click="deleteModal('removeBrand', index)" title="Delete"><i
															class="fas fa-trash-alt"></i></button></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row" v-show="data_form">
					<div class="col-md-12">
						<div class="card m-3">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-file-contract"></i> Contracts</h5>
								<button type="button" class="btn btn-primary btn-sm m-0 float-right"
									@click="AddNewContract"><i class="fa fa-plus"
										aria-hidden="true"></i>&nbsp;&nbsp;Add</button>
							</div>
							<div class="card-body">
								<div class="table-responsive mt-2">
									<table class="table table-hover" id="dataTable" style="width:100%">
										<thead class="table-dark">
											<tr>
												<th>ID</th>
												<th>Brand/s</th>
												<th>Name</th>
												<th>Screen Location</th>
												<th>Duration (no. of days)</th>
												<th>No. of slots per loop</th>
												<th>Exposure per Day</th>
												<th>Is Exclusive?</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(data, index) in company.contracts" v-bind:key="index">
												<td class="align-middle">{{ data.serial_number }}</td>
												<td>
													<img v-for="(brand, index) in data.brands" class="img-thumbnail"
														:src="brand.logo_image_path" />
												</td>
												<td class="align-middle">{{ data.name }}</td>
												<td class="align-middle">{{ data.screen_locations }}</td>
												<td class="align-middle">{{ data.display_duration }}</td>
												<td class="align-middle">{{ data.slots_per_loop }}</td>
												<td class="align-middle">{{ data.exposure_per_day }}</td>
												<td class="align-middle">
													<span v-if="data.is_exclusive" class="badge badge-info">Yes</span>
													<span v-else class="badge badge-info">No</span>
												</td>
												<td class="align-middle">
													<span v-if="data.active" class="badge badge-info">Active</span>
													<span v-else class="badge badge-info">Deactivated</span>
												</td>
												<td class="align-middle text-nowrap">
													<button type="button" class="btn btn-outline-danger"
														@click="deleteModal('removeContract', index)" title="Delete"><i
															class="fas fa-trash-alt"></i></button>
													<button type="button" class="btn btn-outline-danger"
														@click="copyModal(index)" title="Duplicate"><i
															class="fas fa-copy"></i></button>
													<button type="button" class="btn btn-outline-danger"
														@click="editContract(data.id)" title="Edit"><i
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

		<div class="modal fade" id="company-details" tabindex="-1" aria-labelledby="company-details" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="card-title">Company Details</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Company Name <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="company.name" placeholder="Company Name"
									required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Address <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<textarea class="form-control" v-model="company.address" placeholder="Company Address"
									required></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Email <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="email" class="form-control" v-model="company.email" placeholder="Email"
									required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Contact Number <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="email" class="form-control" v-model="company.contact_number"
									placeholder="Contact Number" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">TIN Number <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="company.tin" placeholder="TIN Number"
									required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Classification <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<select class="custom-select" v-model="company.classification_id">
									<option value="">Select Classification</option>
									<option v-for="classification in classifications" :value="classification.id"> {{
										classification.name }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Parent Company</label>
							<div class="col-sm-8">
								<treeselect v-model="company.parent_id" :options="parent_company"
									placeholder="Select Parent Company" />
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
						<button type="button" class="btn btn-secondary btn-sm float-right" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm float-right" v-show="add_record"
							@click="storeCompany">Add New Company</button>
						<button type="button" class="btn btn-primary btn-sm float-right" v-show="edit_record"
							@click="updateCompany">Save Changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="brand-list" tabindex="-1" aria-labelledby="brand-list" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-tags" aria-hidden="true"></i> Brands</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<Table :dataFields="brandsDataFields" :dataUrl="brandDataUrl" :primaryKey="brandPrimaryKey"
								:actionButtons="brandsActionButtons" v-on:editButton="selectedBrand" :rowPerPage=5
								ref="brandsDataTable">
							</Table>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm float-right" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="contract-form" tabindex="-1" aria-labelledby="contract-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fas fa-file-contract"></i> Contract Details</h5>
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
							<label for="Classification" class="col-sm-4">Classification</label>
							<div class="col-sm-8">
								{{ company.classification_name }}
							</div>
						</div>
						<div class="form-group row">
							<label for="Email" class="col-sm-4">Email</label>
							<div class="col-sm-8">
								{{ company.email }}
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="contract.name" placeholder="Contract Name" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<multiselect v-model="contract.brands" :options="company.brands" :multiple="true"
									:close-on-select="true" placeholder="Select Brands" label="name" track-by="name">
								</multiselect>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">SSP <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<multiselect v-model="contract.screens" :options="screens" :multiple="false"
									:close-on-select="true" placeholder="Select Screens" label="site_screen_location"
									track-by="site_screen_location">
								</multiselect>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Duration (no. of days) <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" :disabled="contract.is_indefinite == 1"
									v-model="contract.display_duration" placeholder="0" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">No. of slots per loop <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="contract.slots_per_loop" placeholder="0"
									required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Exposure per day <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="contract.exposure_per_day" placeholder="0"
									required>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-4 col-form-label">Reference Code <span
									class="font-italic text-danger"> *</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" v-model="contract.reference_code" placeholder="Reference Code"
									required>
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-4 col-form-label">Remarks</label>
							<div class="col-sm-8">
								<textarea class="form-control" v-model="contract.remarks" placeholder="Remarks"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label for="userName" class="col-sm-4 col-form-label">Start Date</label>
							<div class="col-sm-8">
								<date-picker v-model="contract.start_date" placeholder="YYYY-MM-DD" :config="options" id="date_from" autocomplete="off"></date-picker>
							</div>
						</div>
						<div class="form-group row">
							<label for="userName" class="col-sm-4 col-form-label">End Date</label>
							<div class="col-sm-8">
								<date-picker v-model="contract.end_date" placeholder="YYYY-MM-DD" :config="options" id="date_to" autocomplete="off"></date-picker>
							</div>
						</div>
						<div class="form-group row">
							<label for="isExclusive" class="col-sm-4 col-form-label">Is Indefinite? </label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="is_indefinite"
										v-model="contract.is_indefinite">
									<label class="custom-control-label" for="is_indefinite"></label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="isExclusive" class="col-sm-4 col-form-label">Is Exclusive </label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="is_exclusive"
										v-model="contract.is_exclusive">
									<label class="custom-control-label" for="is_exclusive"></label>
								</div>
							</div>
						</div>
						<div class="form-group row" v-show="edit_record">
							<label for="isActive" class="col-sm-4 col-form-label">Active</label>
							<div class="col-sm-8">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="isActive"
										v-model="contract.active">
									<label class="custom-control-label" for="isActive"></label>
								</div>
							</div>
						</div>
					</div><!-- /.card-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm float-right" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm float-right" v-show="add_contract"
							@click="storeContract">Add New Contract</button>
						<button type="button" class="btn btn-primary btn-sm float-right" v-show="edit_contract"
							@click="updateContract">Save Changes</button>
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
						<button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary btn-sm " @click="deleteRecord">OK</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="copy-record" tabindex="-1" aria-labelledby="copy-record" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Duplicate</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to duplicate this record?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary btn-sm " @click="duplicateContract">Yes</button>
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

import datePicker from 'vue-bootstrap-datetimepicker';    
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

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
			contract: {
				id: '',
				name: '',
				reference_code: '',
				remarks: '',
				company_id: '',
				brands: [],
				screens: '',
				display_duration: '',
				slots_per_loop: '',
				exposure_per_day: '',
				start_date: '',
				end_date: '',
				is_indefinite: false,
				is_exclusive: false,
				active: false,
			},
			data_list: true,
			data_form: false,
			parent_company: [],
			classifications: [],
			screens: [],
			options: {
				format: 'YYYY/MM/DD',
				useCurrent: false,
			},
			add_record: true,
			edit_record: false,
			add_contract: true,
			edit_contract: false,
			delete_action: '',
			delete_index: '',
			dataFields: {
				name: "Name",
				parent_company: "Parent Company",
				classification_name: "Classification Name",
				email: "Email",
				contact_number: "Contact Number",
				address: "Address",
				tin: "TIN Number",
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
				link: {
					title: 'Workflow',
					name: 'Link',
					apiUrl: '/admin/company/workflows',
					routeName: '',
					button: '<i class="fa fa-link"></i>Workflow',
					method: 'link'
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
			brandsDataFields: {
				logo_image_path: {
					name: "Logo",
					type: "logo",
				},
				name: "Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
			},
			brandsActionButtons: {
				edit: {
					title: 'Add',
					name: 'Edit',
					apiUrl: '',
					routeName: 'content.edit',
					button: '<i class="far fa-check-circle"></i> Add',
					method: 'view'
				},
			},
			brandPrimaryKey: "id",
			brandDataUrl: "/admin/brand/list",
		};
	},

	created() {
		this.getClassifications();
		this.getParentCompany();
		this.getScreens();
	},

	methods: {
		getScreens: function (id) {
			axios.get('/admin/site/screen/get-all')
				.then(response => this.screens = response.data.data);
		},

		getParentCompany: function () {
			axios.get('/admin/company/get-parent')
				.then(response => this.parent_company = response.data.data);
		},

		getClassifications: function () {
			axios.get('/admin/classification/get-all')
				.then(response => this.classifications = response.data.data);
		},

		AddNewCompany: function () {
			this.add_record = true;
			this.edit_record = false;
			this.company.parent_id = null;
			this.company.classification_id = '';
			this.company.name = '';
			this.company.email = '';
			this.company.contact_number = '';
			this.company.address = '';
			this.company.tin = '';
			this.company.active = false;
			this.company.brands = [];
			this.company.contracts = [];
			this.data_list = false;
			this.data_form = true;
			$('#company-details').modal('show');	
		},

		modalAdd: function () {
			$('#company-details').modal('show');
		},

		storeCompany: function () {
			axios.post('/admin/company/store', this.company)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#company-details').modal('hide');
				})
		},

		editCompany: function (id) {
			axios.get('/admin/company/' + id)
				.then(response => {
					this.company.brands = [];
					this.company.contracts = [];

					var company = response.data.data;
					this.company.id = id;
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

		updateCompany: function () {
			axios.put('/admin/company/update', this.company)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#company-details').modal('hide');
				})
		},

		backToList: function () {
			this.data_list = true;
			this.data_form = false;
		},

		selectedBrand: function (data) {
			data.company_id = this.company.id;
			axios.post('/admin/company/brand/store', data)
				.then(response => {
					if (response.data.data.brand_id) {
						toastr.success('Brand ' + data.name + ' has been added.');
						this.company.brands.push(data);
					}
				});
		},

		removeBrand: function (index) {
			axios.get('/admin/company/brand/delete/' + this.company.brands[index].id + '/' + this.company.id)
				.then(response => {
					if (response.data.data) {
						this.company.brands.splice(index, 1);
						toastr.success('Brand has been removed.');
					}
				});
		},

		AddNewContract: function () {
			this.contract.company_id = this.company.id;
			this.contract.name = '';
			this.contract.reference_code = '';
			this.contract.remarks = '';
			this.contract.brands = '';
			this.contract.screens = '';
			this.contract.display_duration = '';
			this.contract.slots_per_loop = '';
			this.contract.exposure_per_day = '';
			this.contract.start_date = '';
			this.contract.end_date = '';
			this.contract.is_indefinite = false;
			this.contract.is_exclusive = false;
			this.contract.active = false;
			this.add_contract = true;
			this.edit_contract = false;		
			$('#contract-form').modal('show');
		},

		storeContract: function () {
			axios.post('/admin/company/contract/store', this.contract)
				.then(response => {
					toastr.success(response.data.message);
					this.company.contracts.push(response.data.data);
					$('#contract-form').modal('hide');
				})
		},

		updateContract: function () {
			axios.put('/admin/company/contract/update', this.contract)
				.then(response => {
					toastr.success(response.data.message);
					this.editCompany(this.company.id);
					$('#contract-form').modal('hide');
				})
		},

		removeContract: function (index) {
			axios.get('/admin/company/contract/delete/' + this.company.contracts[index].id)
				.then(response => {
					if (response.data.data) {
						this.company.contracts.splice(index, 1);
						toastr.success('Contract has been removed.');
					}
				});
		},

		duplicateContract: function () {
			axios.get('/admin/company/contract/duplicate/' + this.company.contracts[this.delete_index].id)
				.then(response => {
					if (response.data.data) {
						this.company.contracts.push(response.data.data);
						toastr.success('Contract has been copy.');
						$('#copy-record').modal('hide');
						this.editContract(response.data.data.id);
					}
				});
		},

		deleteModal: function (action, index) {
			this.delete_action = action;
			this.delete_index = index;
			$('#delete-record').modal('show');
		},

		deleteRecord: function () {
			if (this.delete_action == 'removeContract') {
				this.removeContract(this.delete_index);
			}
			else {
				this.removeBrand(this.delete_index);
			}
			this.delete_action = '';
			this.delete_index = '';
			$('#delete-record').modal('hide');
		},

		copyModal: function (index) {
			this.delete_index = index;
			$('#copy-record').modal('show');
		},

		editContract: function (id) {
			axios.get('/admin/company/contract/' + id)
				.then(response => {
					var contract = response.data.data;
					this.contract.id = contract.id;
					this.contract.company_id = this.company.id;
					this.contract.brands = contract.brands;
					this.contract.name = contract.name;
					this.contract.reference_code = contract.reference_code;
					this.contract.remarks = contract.remarks;
					this.contract.screens = contract.screens;
					this.contract.display_duration = contract.display_duration;
					this.contract.slots_per_loop = contract.slots_per_loop;
					this.contract.exposure_per_day = contract.exposure_per_day;
					this.contract.start_date = contract.start_date;
					this.contract.end_date = contract.end_date;
					this.contract.is_indefinite = contract.is_indefinite;
					this.contract.is_exclusive = contract.is_exclusive;
					this.contract.active = contract.active;
					this.add_contract = false;
					this.edit_contract = true;
					$('#contract-form').modal('show');
				});
		},

		downloadCsv: function () {
			axios.get('/admin/company/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		downloadTemplate: function () {
			const link = document.createElement('a');
			link.href = '/uploads/csv/company-batch-upload.csv';
			link.setAttribute('downloadFile', '/uploads/csv/company-batch-upload.csv'); //or any other extension
			document.body.appendChild(link);
			link.click();
		},

	},

	components: {
		Table,
		Treeselect,
		Multiselect,
		datePicker
	}
};
</script> 
<style lang="scss" scoped>.img-thumbnail {
	max-width: 4rem;
}</style>