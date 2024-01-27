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
									:otherButtons="otherButtons" :primaryKey="primaryKey"
									v-on:AddNewLandmark="AddNewLandmark" v-on:editButton="editLandmark"
									v-on:modalBatchUpload="modalBatchUpload" v-on:downloadCsv="downloadCsv"
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
		<div class="modal fade" id="landmark-form" data-backdrop="static" tabindex="-1" aria-labelledby="landmark-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Landmark</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Landmark</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Banner <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_banner" accept="image/*" ref="imgBanner"
										@change="bannerChange">
									<footer class="blockquote-footer">image max size is 355 x 660 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="imgBanner" :src="imgBanner" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Banner Thumbnail <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_banner_thumbnail" accept="image/*" ref="imgBannerThumbnail"
										@change="bannerThumbnailChange">
									<footer class="blockquote-footer">image max size is 315 x 265 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="imgBannerThumbnail" :src="imgBannerThumbnail" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="landmark.site_id">
										<option value="">Select Site</option>
										<option v-for="site in site_list" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Landmark<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="landmark.landmark"
										placeholder="Landmark Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" rows="5" v-model="landmark.descriptions"
										placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="landmark.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeLandmark">Add New Landmark</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateLandmark">Save Changes</button>
						</div>
						<!-- /.card-body -->
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
		<div class="modal" id="errorModal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="alert alert-block alert-danger">
							<p>{{ error_message }}</p>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
import Multiselect from 'vue-multiselect';
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
	name: "Landmarks",
	data() {
		return {
			landmark: {
				id: '',
				site_id: null,
				landmark: '',
				descriptions: '',
				name: '',
				title: '',
				image_url: '/images/no-image-available.png',
				image_thumbnail_url: '/images/no-image-available.png',
				active: false,
			},
			imgBanner: '',
			imgBannerThumbnail: '',
			site_list: [],
			add_record: true,
			edit_record: false,
			dataFields: {
				image_thumbnail_url_path: {
					name: "Banner",
					type: "logo",
				},
				site_name: "Site Name",
				landmark: "Landmark",
				descriptions: "Descriptions",
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
			dataUrl: "/admin/landmark/list",
			actionButtons: {
				edit: {
					title: 'Edit this Landmark',
					name: 'Edit',
					apiUrl: '',
					routeName: 'landmark.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Landmark',
					name: 'Delete',
					apiUrl: '/admin/landmark/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Landmark',
					v_on: 'AddNewLandmark',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Landmark',
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
		this.getSites();
	},

	methods: {
		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.site_list = response.data.data);
		},

		bannerChange: function (e) {
			// const file = e.target.files[0];
			// this.imgBanner = URL.createObjectURL(file);
			// this.landmark.image_url = file;

			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.imgBanner = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {
					this.image_width = this.width;
					this.image_height = this.height;
					if (this.image_width == 355 && this.image_height == 660) {
						obj.landmark.image_url = this.file;
					} else {
						$('#img_banner').val('');
						obj.imgBanner = null;
						obj.landmark.image_url = '';
						obj.error_message = "Invalid Image Size! Must be width: 355 and height: 600 Current width: " + this.image_width + " and height: " + this.image_height;
						$('#errorModal').modal('show');
					};
				}
			} else {
				$('#img_banner').val('');
				this.imgBanner = null;
				this.landmark.image_url = '';
				this.error_message = "The image must be a file type: bmp,jpeg,png.";
				$('#errorModal').modal('show');
			}
		},

		bannerThumbnailChange: function (e) {
			// const file = e.target.files[0];
			// this.imgBannerThumbnail = URL.createObjectURL(file);
			// this.landmark.image_thumbnail_url = file;
			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.imgBannerThumbnail = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {
					this.image_width = this.width;
					this.image_height = this.height;
					if (this.image_width == 315 && this.image_height == 265) {
						obj.landmark.image_thumbnail_url = this.file;
					} else {
						$('#img_banner_thumbnail').val('');
						obj.imgBannerThumbnail = null;
						obj.landmark.image_thumbnail_url = '';
						obj.error_message = "Invalid Image Size! Must be width: 315 and height: 265 Current width: " + this.image_width + " and height: " + this.image_height;
						$('#errorModal').modal('show');
					};
				}
			} else {
				$('#img_banner_thumbnail').val('');
				this.imgBannerThumbnail = null;
				this.landmark.image_thumbnail_url = '';
				this.error_message = "The image must be a file type: bmp,jpeg,png.";
				$('#errorModal').modal('show');
			}
		},

		AddNewLandmark: function () {
			this.add_record = true;
			this.edit_record = false;
			this.landmark.site_id = '';
			this.landmark.landmark = '';
			this.landmark.descriptions = '';
			this.landmark.image_url = '';
			this.landmark.image_thumbnail_url = '';
			this.imgBanner = '/images/no-image-available.png';
			this.imgBannerThumbnail = '/images/no-image-available.png';
			this.landmark.active = false;
			this.$refs.imgBanner.value = null;
			this.$refs.imgBannerThumbnail.value = null;

			$('#landmark-form').modal('show');
		},

		storeLandmark: function () {
			let formData = new FormData();
			formData.append("site_id", this.landmark.site_id);
			formData.append("landmark", this.landmark.landmark);
			formData.append("descriptions", this.landmark.descriptions);
			formData.append("imgBanner", this.landmark.image_url);
			formData.append("imgBannerThumbnail", this.landmark.image_thumbnail_url);

			axios.post('/admin/landmark/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#landmark-form').modal('hide');
				})
		},

		editLandmark: function (id) {
			axios.get('/admin/landmark/' + id)
				.then(response => {
					var landmark = response.data.data;
					this.landmark.id = id;
					this.landmark.site_id = landmark.site_id;
					this.landmark.landmark = landmark.landmark;
					this.landmark.descriptions = landmark.descriptions;
					this.imgBanner = landmark.image_url_path;
					this.imgBannerThumbnail = landmark.image_thumbnail_url_path;
					this.landmark.active = landmark.active;
					this.$refs.imgBanner.value = null;
					this.$refs.imgBannerThumbnail.value = null;

					this.add_record = false;
					this.edit_record = true;
					$('#landmark-form').modal('show');
				});
		},

		updateLandmark: function () {
			let formData = new FormData();
			formData.append("id", this.landmark.id);
			formData.append("site_id", this.landmark.site_id);
			formData.append("landmark", this.landmark.landmark);
			formData.append("descriptions", this.landmark.descriptions);
			formData.append("imgBanner", this.landmark.image_url);
			formData.append("imgBannerThumbnail", this.landmark.image_thumbnail_url);
			formData.append("active", this.landmark.active);

			axios.post('/admin/landmark/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#landmark-form').modal('hide');
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

			axios.post('/admin/landmark/batch-upload', formData,
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
			axios.get('/admin/landmark/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		downloadTemplate: function () {
			axios.get('/admin/landmark/download-csv-template')
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
		Treeselect,
		Multiselect
	}
};
</script> 
