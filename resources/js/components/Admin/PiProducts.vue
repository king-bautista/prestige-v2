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
									v-on:editButton="editProduct" v-on:downloadCsv="downloadCsv"
									v-on:downloadTemplate="downloadTemplate" ref="dataTable">
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
							Product</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Product</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Product Application <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="pi_product.product_application">
										<option value="">Select Product Application </option>
										<option v-for="product_application in product_applications"
											:value="product_application"> {{ product_application }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Advertisement Type <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.ad_type"
										placeholder="Advertisement Type" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions</label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="pi_product.descriptions"
										placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Sec/Slot <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.sec_slot" placeholder="10"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Max Slots <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.slots" placeholder="40"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Remarks</label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="pi_product.remarks"
										placeholder="Remarks"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="pi_product.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="is_exclusive" class="col-sm-4 col-form-label">Is Exclusive?</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_exclusive"
											v-model="pi_product.is_exclusive">
										<label class="custom-control-label" for="is_exclusive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeProduct">Add New
							Product</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateScreen">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->

	</div>
</template>
<script>
import Table from '../Helpers/Table';

export default {
	name: "PiProduct",
	data() {
		return {
			pi_product: {
				id: '',
				product_application: '',
				ad_type: '',
				descriptions: '',
				remarks: '',
				sec_slot: '',
				slots: '',
				active: false,
				is_exclusive: false,
			},
			add_record: true,
			edit_record: false,
			product_applications: ['Digital Signage', 'Directory'],
			dataFields: {
				serial_number: "ID",
				product_application: "Product Application",
				ad_type: "Advertisement Type",
				descriptions: "Descriptions",
				remarks: "Remarks",
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
				is_exclusive: {
					name: "Is Exclusive",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">No</span>',
						1: '<span class="badge badge-info">Yes</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/site/pi-product/list",
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
					apiUrl: '/admin/site/pi-product/delete',
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

	methods: {
		AddNewProduct: function () {
			this.add_record = true;
			this.edit_record = false;

                this.pi_product.product_application = '';
                this.pi_product.ad_type = '';
                this.pi_product.descriptions = '';
                this.pi_product.remarks = '';
                this.pi_product.sec_slot = '10';
                this.pi_product.slots = '40';
                this.pi_product.active = false;
                this.pi_product.is_exclusive = false;

			$('#product-form').modal('show');
		},

		storeProduct: function () {
			axios.post('/admin/site/pi-product/store', this.pi_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})
		},

		editProduct: function (id) {
			axios.get('/admin/site/pi-product/' + id)
				.then(response => {
					this.add_record = false;
					this.edit_record = true;

					var pi_product = response.data.data;
					this.pi_product.id = pi_product.id;
					this.pi_product.product_application = pi_product.product_application;
					this.pi_product.ad_type = pi_product.ad_type;
					this.pi_product.descriptions = pi_product.descriptions;
					this.pi_product.remarks = pi_product.remarks;
					this.pi_product.sec_slot = pi_product.sec_slot;
					this.pi_product.slots = pi_product.slots;
					this.pi_product.active = pi_product.active;
					this.pi_product.is_exclusive = pi_product.is_exclusive;

					$('#product-form').modal('show');
				});
		},


		updateScreen: function () {
			axios.put('/admin/site/pi-product/update', this.pi_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})
		},

		downloadCsv: function () {
			axios.get('/admin/site/pi-product/download-csv')
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
			link.href = '/uploads/csv/pi-product-batch-upload.csv';
			link.setAttribute('downloadFile', '/uploads/csv/pi-product-batch-upload.csv'); //or any other extension
			document.body.appendChild(link);
			link.click();
		},
	},

	components: {
		Table,
	}
};
</script> 
