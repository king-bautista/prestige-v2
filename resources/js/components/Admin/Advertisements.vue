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
									v-on:AddNewAdvertisements="AddNewAdvertisements" v-on:editButton="editAdvertisements"
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
		<div class="modal fade" id="site_ad-form" ref="form_modal" data-backdrop="static" tabindex="-1"
			aria-labelledby="site_ad-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Content</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Content</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="advertisement.name"
										placeholder="Advertisements Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-3 col-form-label">Company <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<multiselect v-model="advertisement.company_id" :options="companies" :multiple="false"
										:close-on-select="true" :searchable="true" :allow-empty="false"
										placeholder="Select Company" label="name" track-by="name" @select="companySelected">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Contract <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<multiselect v-model="advertisement.contract_id" :options="contracts" :multiple="false"
										:close-on-select="true" :searchable="true" :allow-empty="false" track-by="name"
										label="name" placeholder="Select Contract" @select="contractSelected">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Brands <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<multiselect v-model="advertisement.brand_id" :options="brands" :multiple="false"
										:close-on-select="true" :searchable="true" :allow-empty="false" track-by="name"
										label="name" placeholder="Select Brand">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Duration <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-2">
									<input type="text" class="form-control" v-model="advertisement.display_duration"
										placeholder="Duration">
									<footer class="blockquote-footer">In Seconds</footer>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-3 col-form-label">Active</label>
								<div class="col-sm-9">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="advertisement.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">SSP/s</label>
								<div class="col-sm-9">
									<ul v-if="screens.length > 0" class="list-group"
										style="max-height: 200px; height: auto; overflow: hidden; overflow-y: auto;">
										<li class="list-group-item" v-for="(screen, index) in screens" v-bind:key="index">{{
											screen.site_screen_location }}</li>
									</ul>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-12 col-form-label">Material <span
										class="font-italic text-danger"> *</span></label>
							</div>
							<div v-for="(material, index) in advertisement.materials" v-bind:key="index"
								v-if="advertisement.contract_id">
								<hr />
								<div class="form-group row">
									<label for="firstName" class="col-sm-3 col-form-label">{{ material.dimension }}</label>
									<div class="col-sm-5">
										<input type="file" accept="image/*" ref="materials"
											@change="fileUpload($event, index)" multiple>
										<footer class="blockquote-footer">Max file size is 15MB</footer>
										<footer class="blockquote-footer">Compatible file types: .jpg, .png, .ogv</footer>
									</div>
									<div class="col-sm-3 text-center">
										<span v-if="material.src && material.file_type == 'image'">
											<img v-if="material.src" :src="material.src" class="img-thumbnail" />
										</span>
										<span v-else-if="material.src && material.file_type == 'video'">
											<video muted="muted" class="img-thumbnail" @load="onImgLoad(index)" controls>
												<source :src="material.src" type="video/ogg">
												Your browser does not support the video tag.
											</video>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeAdvertisements">Add New Advertisements</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateAdvertisements">Save Changes</button>
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
	</div>
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import Multiselect from 'vue-multiselect';

export default {
	name: "Advertisements",
	data() {
		return {
			helper: new Helpers(),
			advertisement: {
				id: '',
				company_id: '',
				contract_id: '',
				brand_id: '',
				name: '',
				active: true,
				display_duration: 10,
				materials: []
			},
			companies: [],
			contracts: [],
			brands: [],
			screens: [],
			add_record: true,
			edit_record: false,
			dataFields: {
				serial_number: "ID",
				material_thumbnails_path: {
					name: "Preview",
					type: "logo",
				},
				name: "Name",
				company_name: "Company Name",
				brand_name: "Brand Name",
				display_duration: "Duration (in sec)",
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
			dataUrl: "/admin/manage-ads/list",
			actionButtons: {
				edit: {
					title: 'Edit this Content',
					name: 'Edit',
					apiUrl: '',
					routeName: 'advertisement.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Content',
					name: 'Delete',
					apiUrl: '/admin/manage-ads/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'Add Content',
					v_on: 'AddNewAdvertisements',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> Add Content',
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
		this.getCompany();
	},

	methods: {
		getScreens: function (id) {
			axios.post('/admin/site/site-screen-product/get-screens', { contract_id: id })
				.then(response => {
					this.screens = response.data.data;
					this.getMaterialSize();
				});
		},

		getCompany: function () {
			axios.get('/admin/company/get-all')
				.then(response => this.companies = response.data.data);
		},

		companySelected: function (company) {
			this.contracts = company.contracts;
		},

		contractSelected: function (contract) {
			this.brands = contract.brands;
			this.getScreens(contract.id);
		},

		fileUpload: function (e, index) {
			var file = e.target.files[0];
			if (e.target.files[0].type == 'image/jpeg' || e.target.files[0].type == 'image/jpg' || e.target.files[0].type == 'image/png' || e.target.files[0].type == 'video/ogg') {
				var file_type = e.target.files[0].type.split("/");
				var file_path = URL.createObjectURL(file);
				var obj = this;
				var material;

				this.advertisement.materials[index].file = file;
				this.advertisement.materials[index].name = file.name.replace(/\s+/g, '-');
				this.advertisement.materials[index].size = file.size;
				this.advertisement.materials[index].src = file_path;
				this.advertisement.materials[index].file_type = file_type[0];

				if (file_type[0] == 'image') {
					material = new Image;
					material.onload = function () {
						obj.setfilter(index, material.height, material.width);
					};

					material.src = file_path;
				}
				else if (file_type[0] == 'video') {
					material = document.createElement("video");
					material.src = file_path;
					material.addEventListener("loadedmetadata", function () {
						obj.advertisement.display_duration = this.duration;
						obj.setfilter(index, this.videoHeight, this.videoWidth, file_type[0]);
					});
				}
			}
			else {
				toastr.error('Invalid file type. ');
				this.$refs.materials[index].value = null;
				return false;
			}
		},

		setfilter: function (index, height, width, file_type) {
			var up_dimension = width + 'x' + height;
			if (this.advertisement.materials[index].dimension != up_dimension && file_type == 'image') {
				toastr.error('Invalid file dimension.');
				this.$refs.materials[index].value = null;
				this.advertisement.materials[index].src = '';
				return false;
			}

			this.advertisement.materials[index].height = height;
			this.advertisement.materials[index].width = width;
		},

		getMaterialSize: function () {
			this.advertisement.materials = [];
			axios.post('/admin/site/site-screen-product/get-screen-size', this.screens)
				.then(response => {
					var screens = response.data.data;
					screens.forEach(
						key => {
							this.advertisement.materials.push({
								id: '',
								file: '',
								name: '',
								dimension: key.dimension,
								size: '',
								src: '',
								file_type: '',
								width: '',
								height: '',
							});
						}
					);
				});
		},

		AddNewAdvertisements: function () {
			this.advertisement.name = '';
			this.advertisement.company_id = '';
			this.advertisement.contract_id = '';
			this.advertisement.brand_id = '';
			this.advertisement.display_duration = 10;
			this.advertisement.active = true;
			this.advertisement.materials = [];
			this.screens = [];
			this.add_record = true;
			this.edit_record = false;
			$('#site_ad-form').modal('show');
		},

		storeAdvertisements: function () {
			let formData = new FormData();
			formData.append("name", this.advertisement.name);
			formData.append("company_id", (this.advertisement.company_id) ? JSON.stringify(this.advertisement.company_id) : '');
			formData.append("contract_id", (this.advertisement.contract_id) ? JSON.stringify(this.advertisement.contract_id) : '');
			formData.append("brand_id", (this.advertisement.brand_id) ? JSON.stringify(this.advertisement.brand_id) : '');
			formData.append("display_duration", this.advertisement.display_duration);
			formData.append("active", this.advertisement.active);

			for (let index = 0; index < this.advertisement.materials.length; index++) {
				formData.append('files[]', this.advertisement.materials[index].file);
			}

			formData.append("materials", (this.advertisement.materials.length > 0) ? JSON.stringify(this.advertisement.materials) : '');

			axios.post('/admin/manage-ads/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#site_ad-form').modal('hide');
				});
		},

		editAdvertisements: function (id) {
			axios.get('/admin/manage-ads/' + id)
				.then(response => {
					this.add_record = false;
					this.edit_record = true;
					this.advertisement.materials = [];

					var advertisement = response.data.data;
					this.contracts = advertisement.company_details.contracts;
					this.brands = advertisement.contract_details.brands;

					this.advertisement.id = advertisement.id;
					this.advertisement.name = advertisement.name;
					this.advertisement.company_id = advertisement.company_details;
					this.advertisement.contract_id = advertisement.contract_details;
					this.advertisement.brand_id = advertisement.brand_details;
					this.advertisement.display_duration = advertisement.display_duration;
					this.advertisement.active = advertisement.active;

					var obj = this;
					advertisement.materials.forEach(function (material) {
						obj.advertisement.materials.push({
							id: material.id,
							file: '',
							name: '',
							dimension: material.dimension,
							size: material.file_size,
							src: material.material_path,
							file_type: material.file_type,
							width: material.width,
							height: material.height
						});
					});

					axios.post('/admin/site/site-screen-product/get-screens', { contract_id: advertisement.contract_id })
						.then(response => {
							this.screens = response.data.data;
							axios.post('/admin/site/site-screen-product/get-screen-size', this.screens)
								.then(response => {
									var screens = response.data.data;
									screens.forEach(
										key => {
											let obj = advertisement.materials.find(index => index.dimension === key.dimension);
											if (!obj) {
												this.advertisement.materials.push({
													id: '',
													file: '',
													name: '',
													dimension: key.dimension,
													size: '',
													src: '',
													file_type: '',
													width: '',
													height: '',
												});
											}
										}
									);
								});
						});

					$('#site_ad-form').modal('show');
				});
		},

		updateAdvertisements: function () {
			let formData = new FormData();
			formData.append("id", this.advertisement.id);
			formData.append("name", this.advertisement.name);
			formData.append("company_id", (this.advertisement.company_id) ? JSON.stringify(this.advertisement.company_id) : '');
			formData.append("contract_id", (this.advertisement.contract_id) ? JSON.stringify(this.advertisement.contract_id) : '');
			formData.append("brand_id", (this.advertisement.brand_id) ? JSON.stringify(this.advertisement.brand_id) : '');
			formData.append("display_duration", this.advertisement.display_duration);
			formData.append("active", this.advertisement.active);

			for (let index = 0; index < this.advertisement.materials.length; index++) {
				if (this.advertisement.materials[index].file) {
					formData.append('files[]', this.advertisement.materials[index].file);
				}
				else {
					formData.append('files[]', this.advertisement.materials[index].src);
				}
			}

			formData.append("materials", (this.advertisement.materials.length > 0) ? JSON.stringify(this.advertisement.materials) : '');

			axios.post('/admin/manage-ads/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#site_ad-form').modal('hide');
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

			axios.post('/admin/modules/batch-upload', formData,
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
			axios.get('/admin/manage-ads/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		downloadTemplate: function () {
			axios.get('/admin/manage-ads/download-csv-template')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		modalOnHidden() {
			this.advertisement.materials = [];
		}

	},

	mounted() {
		$(this.$refs.form_modal).on("hidden.bs.modal", this.modalOnHidden);
	},

	components: {
		Table,
		Multiselect,
	}
};
</script> 