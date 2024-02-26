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
									v-on:AddNewAmenities="AddNewAmenities" v-on:editButton="editAmenities"
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
		<div class="modal fade" id="amenities-form" tabindex="-1" aria-labelledby="amenities-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Amenities</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Amenities</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Amenity Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="amenity.name"
										placeholder="Amenities Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Icon <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_icon" accept="image/*" ref="icon" @change="IconChange">
									<footer class="blockquote-footer">image max size is 170 x 170 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="icon" :src="icon" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site</label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="amenity.site_id">
										<option value="">Select Site</option>
										<option v-for="site in site_list" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="amenity.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeAmenities">Add New
							Amenities</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateAmenities">Save
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

export default {
	name: "Users",
	data() {
		return {
			amenity: {
				id: '',
				name: '',
				icon:'',
				site_id: '',
				active: false,
			},
			parent_links: [],
			icon: '',
			site_list: [],
			add_record: true,
			edit_record: false,
			image_width: 0,
			image_height: 0,
			dataFields: {
				name: "Name",
				icon_path: {
					name: "Icon",
					type: "logo",
				},
				site_name: "Site Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Inactive</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/amenity/list",
			actionButtons: {
				edit: {
					title: 'Edit this Amenities',
					name: 'Edit',
					apiUrl: '',
					routeName: 'amenity.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Amenities',
					name: 'Delete',
					apiUrl: '/admin/amenity/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Amenities',
					v_on: 'AddNewAmenities',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Amenities',
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
		this.getSites();
	},

	methods: {
		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.site_list = response.data.data);
		},
		IconChange: function (e) {
			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.icon = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {
					this.image_width = this.width;
					this.image_height = this.height;
					if (this.image_width == 170 && this.image_height == 170) {
						obj.amenity.icon = this.file;
					} else {
						$('#img_icon').val('');
						obj.icon = '';
						obj.amenity.icon = '';
						toastr.error("Invalid Image Size! Must be width: 170 and height: 170. Current width: " + this.image_width + " and height: " + this.image_height);
					};
				}
			} else {
				$('#img_icon').val('');
				this.icon = '';
				this.amenity.icon = '';
				toastr.error("The image must be a file type: bmp,jpeg,png.");
			}
		},

		AddNewAmenities: function () {
			this.add_record = true;
			this.edit_record = false;
			this.amenity.name = '';
			this.amenity.site_id = '';
			this.amenity.icon = '';
			this.amenity.active = false;
			$('#amenities-form').modal('show');
		},

		storeAmenities: function () {
			let formData = new FormData();
			formData.append("name", this.amenity.name);
			formData.append("site_id", this.amenity.site_id);
			formData.append("icon", this.amenity.icon);
			formData.append("icon_hidden", this.amenity.icon); 

			axios.post('/admin/amenity/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#amenities-form').modal('hide');
				})
		},

		editAmenities: function (id) {
			axios.get('/admin/amenity/' + id)
				.then(response => {
					var amenity = response.data.data;
					this.amenity.id = id;
					this.amenity.name = amenity.name;
					this.amenity.site_id = amenity.site_id;
					this.amenity.active = amenity.active;
					if (amenity.icon) {
						this.icon = amenity.icon_path;
					}
					else {
						this.icon = this.amenity.icon;
					}
					this.$refs.icon.value = null;
					this.add_record = false;
					this.edit_record = true;
					$('#amenities-form').modal('show');
				});
		},

		updateAmenities: function () {
			let formData = new FormData();
			formData.append("id", this.amenity.id);
			formData.append("name", this.amenity.name);
			formData.append("site_id", this.amenity.site_id);
			formData.append("icon", this.amenity.icon); 
			formData.append("icon_hidden", this.icon); 
			formData.append("active", this.amenity.active);

			axios.post('/admin/amenity/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#amenities-form').modal('hide');
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
			axios.get('/admin/amenity/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		downloadTemplate: function () {
			axios.get('/admin/amenity/download-csv-template')
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
		Table
	}
};
</script> 
