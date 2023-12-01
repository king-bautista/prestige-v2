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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewProduct="AddNewProduct"
									v-on:editButton="editProduct"  v-on:modalBatchUpload="modalBatchUpload" 
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

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="product-form" tabindex="-1" aria-labelledby="product-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Site Screen Product</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Site Screen Product</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site Screen <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="site_screen_product.site_screen_id" :options="screens"
										:multiple="false" :close-on-select="true" placeholder="Select Screens"
										label="site_screen_location" track-by="site_screen_location" @input="screenDetails">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Site Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.site_name"
										placeholder="Site Name" readonly />
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Screen type</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.screen_type"
										placeholder="Screen type" readonly />
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Physical Size (WxH)</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.physical_size_wh"
										placeholder="43x43 inches" readonly />
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Physical size (Diagonal)</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.physical_size_d"
										placeholder="52 inches" readonly />
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Orientation</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.orientation"
										placeholder="Orientation" readonly />
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Ad Type</label>
								<div class="col-sm-8">
									<input type="text" class="form-control"
										v-model="site_screen_product.product_application" placeholder="Ad Type" readonly />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Product Type <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="site_screen_product.ad_type"
										@change="adTypeDetails(site_screen_product.ad_type)">
										<option value="">Select Product Type</option>
										<option v-for="ad_type in ad_types_temp" :value="ad_type"> {{ ad_type.ad_type }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions</label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="site_screen_product.description"
										placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Width <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.width"
										placeholder="0" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Height <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.height"
										placeholder="0" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Sec/Slot <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.sec_slot"
										placeholder="0" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Max Slots <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_screen_product.slots"
										placeholder="0" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="site_screen_product.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="is_exclusive" class="col-sm-4 col-form-label">Is Exclusive?</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_exclusive"
											v-model="site_screen_product.is_exclusive">
										<label class="custom-control-label" for="is_exclusive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeScreen">Add New
							Product</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateScreen">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->
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

export default {
	name: "SiteScreenProduct",
	data() {
		return {
			site_screen_product: {
				id: '',
				site_screen_id: '',
				site_name: '',
				screen_type: '',
				physical_size_wh: '',
				physical_size_d: '',
				orientation: '',
				product_application: '',
				ad_type: '',
				description: '',
				width: '',
				height: '',
				sec_slot: '',
				slots: '',
				active: false,
				is_exclusive: false,
			},
			add_record: true,
			edit_record: false,
			screens: [],
			ad_types: [],
			ad_types_temp: [],
			dataFields: {
				serial_number: "ID",
				site_screen_location: "Screen Location",
				ad_type: "Ad Type",
				dimension: "Dimension",
				sec_slot: "Sec/Slot",
				slots: "Slot",
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
			dataUrl: "/admin/site/site-screen-product/list",
			actionButtons: {
				edit: {
					title: 'Edit this Product',
					name: 'Edit',
					apiUrl: '',
					routeName: 'building.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Product',
					name: 'Delete',
					apiUrl: '/admin/site/site-screen-product/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete',
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Product',
					v_on: 'AddNewProduct',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Product',
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
			}
		};
	},

	created() {
		this.getScreens();
		this.getPiProducts();
	},

	methods: {
		getScreens: function (id) {
			axios.get('/admin/site/screen/get-all')
				.then(response => this.screens = response.data.data);
		},

		getPiProducts: function () {
			axios.get('/admin/site/pi-product/get-products')
				.then(response => this.ad_types = response.data.data);
		},

		adTypeDetails: function (ad_type) {
			if (ad_type) {
				this.site_screen_product.sec_slot = ad_type.sec_slot;
				this.site_screen_product.slots = ad_type.slots;
				this.site_screen_product.is_exclusive = ad_type.is_exclusive;
			}
		},

		screenDetails: function (screen) {
			this.site_screen_product.site_name = screen.site_name;
			this.site_screen_product.screen_type = screen.screen_type;
			this.site_screen_product.physical_size_wh = screen.physical_size_width + ' x ' + screen.physical_size_height + ' inches';
			this.site_screen_product.physical_size_d = screen.physical_size_diagonal;
			this.site_screen_product.orientation = screen.orientation;
			this.site_screen_product.product_application = screen.product_application;
			this.ad_types_temp = this.ad_types.filter(option => option.product_application == screen.product_application);
		},

		AddNewProduct: function () {
			this.add_record = true;
			this.edit_record = false;
			this.site_screen_product.site_screen_id = '';
			this.site_screen_product.site_name = '';
			this.site_screen_product.screen_type = '';
			this.site_screen_product.physical_size_wh = '';
			this.site_screen_product.physical_size_d = '';
			this.site_screen_product.orientation = '';
			this.site_screen_product.product_application = '';
			this.site_screen_product.ad_type = '';
			this.site_screen_product.description = '';
			this.site_screen_product.width = '';
			this.site_screen_product.height = '';
			this.site_screen_product.sec_slot = '';
			this.site_screen_product.slots = '';
			this.site_screen_product.active = false;
			this.site_screen_product.is_exclusive = false;
			$('#product-form').modal('show');
		},

		storeScreen: function () {
			axios.post('/admin/site/site-screen-product/store', this.site_screen_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})
		},

		editProduct: function (id) {
			axios.get('/admin/site/site-screen-product/' + id)
				.then(response => {
					this.add_record = false;
					this.edit_record = true;
					var site_screen_product = response.data.data;
					this.site_screen_product.id = site_screen_product.id;
					this.site_screen_product.site_screen_id = site_screen_product.site_screen_details;
					this.site_screen_product.site_name = site_screen_product.site_screen_details.site_name;
					this.site_screen_product.screen_type = site_screen_product.site_screen_details.screen_type;
					this.site_screen_product.physical_size_wh = site_screen_product.site_screen_details.physical_size_width + ' x ' + site_screen_product.site_screen_details.physical_size_height;
					this.site_screen_product.physical_size_d = site_screen_product.site_screen_details.physical_size_diagonal;
					this.site_screen_product.orientation = site_screen_product.site_screen_details.orientation;
					this.site_screen_product.product_application = site_screen_product.site_screen_details.product_application;
					this.site_screen_product.description = site_screen_product.description;
					this.site_screen_product.width = site_screen_product.width;
					this.site_screen_product.height = site_screen_product.height;
					this.site_screen_product.sec_slot = site_screen_product.sec_slot;
					this.site_screen_product.slots = site_screen_product.slots;
					this.site_screen_product.active = site_screen_product.active;
					this.site_screen_product.is_exclusive = site_screen_product.is_exclusive;

					this.ad_types_temp = this.ad_types.filter(option => option.product_application == this.site_screen_product.product_application);
					this.site_screen_product.ad_type = this.ad_types.filter(function (option) {
						return option.ad_type == site_screen_product.ad_type && option.product_application == site_screen_product.site_screen_details.product_application;
					})[0];

					$('#product-form').modal('show');
				});
		},

		updateScreen: function () {
			axios.put('/admin/site/site-screen-product/update', this.site_screen_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})
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

			axios.post('/admin/amenity/batch-upload', formData,
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
			axios.get('/admin/site/site-screen-product/download-csv')
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
			link.href = '/uploads/csv/ssp-batch-upload.csv';
			link.setAttribute('downloadFile', '/uploads/csv/ssp-batch-upload.csv'); //or any other extension
			document.body.appendChild(link);
			link.click();
		},

	},

	components: {
		Table,
		Multiselect
	}
};
</script> 
