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
									v-on:editButton="editProduct" v-on:modalBatchUpload="modalBatchUpload"
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
								<label for="firstName" class="col-sm-4 col-form-label">Product Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="product.name"
										placeholder="Product Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="product.descriptions"
										placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Type <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="form-control" aria-label="Default select example" v-model="product.type">
										<option value="">Select Type</option>
										<option value="product">Product</option>
										<option value="promo">Promo</option>
										<option value="banner">Banner</option>
									</select>
								</div>
							</div>
							<div class="form-group row" v-if="product.type == 'promo'">
								<label for="userName" class="col-sm-4 col-form-label">Date From <span
										class="font-italic text-danger">*</span></label>
								<div class="col-sm-8">
									<date-picker v-model="product.date_from" placeholder="YYYY-MM-DD" :config="options"
										id="date_from" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row" v-if="product.type == 'promo'">
								<label for="userName" class="col-sm-4 col-form-label">Date To <span
										class="font-italic text-danger">*</span></label>
								<div class="col-sm-8">
									<date-picker v-model="product.date_to" placeholder="YYYY-MM-DD" :config="options"
										id="date_to" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Banner Image<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" accept="image/*" id="img_url" ref="image_url" @change="image_urlChange">
									<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="image_url" :src="image_url" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="product.active">
										<label class="custom-control-label" for="isActive"></label>
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
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateProduct">Save
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
// Import this component
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';;

export default {
	name: "Products",
	data() {
		return {
			product: {
				id: '',
				name: '',
				descriptions: '',
				type: '',
				image_url: '',
				date_from: '',
				date_to: '',
			},
			options: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			image_url: '/images/no-image-available.png',
			add_record: true,
			edit_record: false,
			image_width: 0,
			image_height: 0,
			dataFields: {
				thumbnail_path: {
					name: "Thumbnail",
					type: "logo",
				},
				name: "Name",
				descriptions: "Descriptions",
				type: "Type",
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
			dataUrl: "/admin/brand/product/list",
			actionButtons: {
				edit: {
					title: 'Edit this Product',
					name: 'Edit',
					apiUrl: '',
					routeName: 'brand.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Product',
					name: 'Delete',
					apiUrl: '/admin/brand/product/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
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
			},
		};
	},

	created() {

	},

	methods: {
		image_urlChange: function (e) {
			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.image_url = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {
					this.image_width = this.width;
					this.image_height = this.height;
					if (this.image_width == 120 && this.image_height == 120) {
						obj.product.image_url = this.file;
					} else {
						$('#img_url').val('');
						obj.image_url = null;
						obj.product.image_url = '';
						toastr.error("Invalid Image Size! Must be width: 120 and height: 120. Current width: " + this.image_width + " and height: " + this.image_height);
					};
				}
			} else {
				$('#img_url').val('');
				this.image_url = null;
				this.product.image_url = '';
				toastr.error("The image must be a file type: bmp,jpeg,png.");
			}
		},

		AddNewProduct: function () {
			this.add_record = true;
			this.edit_record = false;
			this.product.name = '';
			this.product.descriptions = '';
			this.product.type = '';
			this.product.date_from = '';
			this.product.date_to = '';
			this.product.thumbnail = '/images/no-image-available.png';
			this.product.image_url = '';
			this.product.active = false;
			this.$refs.image_url.value = null;
			this.thumbnail = '/images/no-image-available.png';
			this.image_url = '';

			$('#product-form').modal('show');
		},

		storeProduct: function () {
			let formData = new FormData();
			formData.append("name", this.product.name);
			formData.append("descriptions", this.product.descriptions);
			formData.append("type", this.product.type);
			formData.append("image_url", this.product.image_url);
			formData.append("date_from", this.product.date_from);
			formData.append("date_to", this.product.date_to);

			axios.post('/admin/brand/product/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})

		},

		editProduct: function (id) {
			axios.get('/admin/brand/product/' + id)
				.then(response => {
					var product = response.data.data;
					this.product.id = id;
					this.product.name = product.name;
					this.product.descriptions = product.descriptions;
					this.product.type = product.type;
					this.product.date_from = product.date_from;
					this.product.date_to = product.date_to;
					this.product.image_url = product.image_url;
					this.product.active = product.active;
					this.add_record = false;
					this.edit_record = true;

					if (product.image_url) {
						this.image_url = product.image_url_path;
					}
					else {
						this.image_url = this.product.image_url;
					}
					this.$refs.image_url.value = null;

					$('#product-form').modal('show');
				});
		},

		updateProduct: function () {
			let formData = new FormData();
			formData.append("id", this.product.id);
			formData.append("name", this.product.name);
			formData.append("descriptions", this.product.descriptions);
			formData.append("type", this.product.type);
			formData.append("image_url", this.product.image_url);
			formData.append("date_from", this.product.date_from);
			formData.append("date_to", this.product.date_to);
			formData.append("active", this.product.active);

			axios.post('/admin/brand/product/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
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

			axios.post('/admin/brand/product/batch-upload', formData,
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
					window.location.reload();
				})
		},

		downloadCsv: function () {
			axios.get('/admin/brand/product/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		downloadTemplate: function () {
			axios.get('/admin/brand/product/download-csv-template')
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
		datePicker,
	}
};
</script> 
