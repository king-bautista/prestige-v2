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
									v-on:addNewIllustration="addNewIllustration" v-on:editButton="editIllustration"
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
		<div class="modal fade" id="Illustration-form" tabindex="-1" aria-labelledby="Illustration-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Site Category</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Site Category</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Category / Supplemental <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="illustration.category_id"
										@change="getSubCategories($event.target.value)">
										<option value="">Select Category / Supplemental</option>
										<option v-for="category in categories" :value="category.id">
											<span v-if="category.supplemental_category_id">Supplemental - </span>
											<span v-else="category.supplemental_category_id">Category - </span>
											<span>{{ category.label }}</span>
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Sub Category / Supplemental</label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="illustration.sub_category_id">
										<option value="">Select Sub Category / Supplemental</option>
										<option v-for="sub_category in sub_categories" :value="sub_category.id"> {{
											sub_category.label }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Company</label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="illustration.company_id">
										<option value="">Select Company</option>
										<option v-for="company in companies" :value="company.id"> {{ company.name }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="illustration.site_id">
										<option value="">Select Site</option>
										<option v-for="site in site_list" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Label</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="illustration.label"
										placeholder="Label">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Kiosk Primary <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_kiosk_primary" accept="image/*" ref="kiosk_image_primary"
										@change="kioskPrimary">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer"
										v-if="illustration.category_id && !illustration.sub_category_id">image max size is
										349 x 528 pixels</footer>
									<footer class="blockquote-footer"
										v-if="illustration.category_id && illustration.sub_category_id">image max size is
										320 x 90 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="kiosk_image_primary" :src="kiosk_image_primary" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Kiosk Top <span
										v-if="illustration.category_id && illustration.sub_category_id"
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_kiosk_top" accept="image/*" ref="kiosk_image_top"
										@change="kioskTop"
										:disabled="illustration.category_id && !illustration.sub_category_id">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer"
										v-if="illustration.category_id && !illustration.sub_category_id">No top image for
										main category</footer>
									<footer class="blockquote-footer"
										v-if="illustration.category_id && illustration.sub_category_id">image max size is
										1470 x 72 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="kiosk_image_top" :src="kiosk_image_top" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="illustration.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeIllustration">Add New
							Category</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateIllustration">Save
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
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
	name: "Illustration",
	data() {
		return {
			illustration: {
				id: '',
				company_id: '',
				category_id: '',
				sub_category_id: '',
				site_id: '',
				label: '',
				active: false,
				kiosk_image_primary: '',
				kiosk_image_top: '',
				online_image_primary: '',
				online_image_top: '',
				mobile_image_primary: '',
				mobile_image_top: '',
			},
			companies: [],
			categories: [],
			sub_categories: [],
			site_list: [],
			kiosk_image_primary: '',
			kiosk_image_top: '',
			online_image_primary: '',
			online_image_top: '',
			mobile_image_primary: '',
			mobile_image_top: '',
			add_record: true,
			edit_record: false,
			image_width: 0,
			image_height: 0,
			dataFields: {
				category_name: "Name",
				site_name: "Site",
				company_name: "Company",
				kiosk_image_primary_path: {
					name: "Kiosk Primary Image",
					type: "logo",
				},
				kiosk_image_top_path: {
					name: "Kiosk Top Image",
					type: "image",
				},
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
			dataUrl: "/admin/site-category/list",
			actionButtons: {
				edit: {
					title: 'Edit this illustration',
					name: 'Edit',
					apiUrl: '',
					routeName: 'category.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this illustration',
					name: 'Delete',
					apiUrl: '/admin/site-category/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Category',
					v_on: 'addNewIllustration',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Illustration',
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
		this.getCompanies();
		this.getCategories();
		this.getSites();
	},

	methods: {
		getCompanies: function () {
			axios.get('/admin/company/get-all')
				.then(response => this.companies = response.data.data);
		},

		getCategories: function () {
			axios.get('/admin/category/get-all')
				.then(response => this.categories = response.data.data);
		},

		getSubCategories: function (id) {
			axios.get('/admin/category/get-all/' + id)
				.then(response => this.sub_categories = response.data.data);
		},

		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.site_list = response.data.data);
		},

		kioskPrimary: function (e) {
			// const file = e.target.files[0];
			// this.kiosk_image_primary = URL.createObjectURL(file);
			// this.illustration.kiosk_image_primary = file;
			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.kiosk_image_primary = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {

					this.image_width = this.width;
					this.image_height = this.height;

					if (this.image_width == obj.getPrimaryPixel()['width'] && this.image_height == obj.getPrimaryPixel()['height']) {
						obj.illustration.kiosk_image_primary = this.file;
					} else {
						$('#img_kiosk_primary').val('');
						obj.kiosk_image_primary = '';
						obj.illustration.kiosk_image_primary = '';
						toastr.error("Invalid Image Size! Must be width: " + obj.getPrimaryPixel()['width'] + " and height: " + obj.getPrimaryPixel()['height'] + " Current width: " + this.image_width + " and height: " + this.image_height);
					};
				}
			} else {
				$('#img_kiosk_primary').val('');
				this.kiosk_image_primary = '';
				this.illustration.kiosk_image_primary = '';
				toastr.error("The image must be a file type: bmp,jpeg,png.");
			}
		},

		kioskTop: function (e) {
			// const file = e.target.files[0];
			// this.kiosk_image_top = URL.createObjectURL(file);
			// this.illustration.kiosk_image_top = file;
			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.kiosk_image_top = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {
					this.image_width = this.width;
					this.image_height = this.height;
					if (this.image_width == obj.getTopPixel()['width'] && this.image_height == obj.getTopPixel()['height']) {
						obj.illustration.kiosk_image_top = this.file;
					} else {
						$('#img_kiosk_top').val('');
						obj.kiosk_image_top = '';
						obj.illustration.kiosk_image_top = '';
						toastr.error("Invalid Image Size! Must be width: " + obj.getPrimaryPixel()['width'] + " and height: " + obj.getPrimaryPixel()['height'] + " Current width: " + this.image_width + " and height: " + this.image_height);
					};
				}
			} else {
				$('#img_kiosk_top').val('');
				this.kiosk_image_top = '';
				this.illustration.kiosk_image_top = '';
				toastr.error("The image must be a file type: bmp,jpeg,png.");
			}
		},

		addNewIllustration: function () {
			this.add_record = true;
			this.edit_record = false;
			this.illustration.company_id = '';
			this.illustration.category_id = '';
			this.illustration.sub_category_id = '';
			this.illustration.site_id = '';
			this.illustration.label = '';
			this.illustration.kiosk_image_primary = '';
			this.illustration.kiosk_image_top = '';
			this.illustration.online_image_primary = '';
			this.illustration.online_image_top = '';
			this.illustration.mobile_image_primary = '';
			this.illustration.mobile_image_top = '';

			this.$refs.kiosk_image_primary.value = null;
			this.kiosk_image_primary = '';

			this.$refs.kiosk_image_top.value = null;
			this.kiosk_image_top = '';

			this.illustration.active = false;
			$('#Illustration-form').modal('show');
		},

		storeIllustration: function () {
			let formData = new FormData();
			formData.append("company_id", this.illustration.company_id);
			formData.append("category_id", this.illustration.category_id);
			formData.append("sub_category_id", this.illustration.sub_category_id);
			formData.append("site_id", this.illustration.site_id);
			formData.append("label", this.illustration.label);
			formData.append("kiosk_image_primary", this.illustration.kiosk_image_primary);
			formData.append("kiosk_image_top", this.illustration.kiosk_image_top);
			formData.append("kiosk_image_primary_hidden", this.illustration.kiosk_image_primary);
			formData.append("kiosk_image_top_hidden", this.illustration.kiosk_image_top);
			formData.append("active", this.illustration.active);
			axios.post('/admin/site-category/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#Illustration-form').modal('hide');
				})
		},

		editIllustration: function (id) {
			axios.get('/admin/site-category/' + id)
				.then(response => {
					var illustration = response.data.data;
					this.illustration.id = illustration.id;
					this.illustration.company_id = (illustration.company_id) ? illustration.company_id : '';
					this.illustration.category_id = (illustration.category_id) ? illustration.category_id : '';
					this.illustration.sub_category_id = (illustration.sub_category_id) ? illustration.sub_category_id : '';
					this.illustration.site_id = (illustration.site_id) ? illustration.site_id : '';
					this.illustration.label = (illustration.label) ? illustration.label : '';
					this.illustration.active = illustration.active;
					this.$refs.kiosk_image_primary.value = null;
					this.kiosk_image_primary = illustration.kiosk_image_primary_path;

					this.$refs.kiosk_image_top.value = null;
					this.kiosk_image_top = illustration.kiosk_image_top_path;

					this.getSubCategories(illustration.category_id);
					this.add_record = false;
					this.edit_record = true;
					$('#Illustration-form').modal('show');
				});
		},

		updateIllustration: function () {
			let formData = new FormData();
			formData.append("id", this.illustration.id);
			formData.append("company_id", this.illustration.company_id);
			formData.append("category_id", this.illustration.category_id);
			formData.append("sub_category_id", this.illustration.sub_category_id);
			formData.append("site_id", this.illustration.site_id);
			formData.append("label", this.illustration.label);
			formData.append("kiosk_image_primary", this.illustration.kiosk_image_primary);
			formData.append("kiosk_image_top", this.illustration.kiosk_image_top); 
			formData.append("kiosk_image_primary_hidden", this.kiosk_image_primary);
			formData.append("kiosk_image_top_hidden", this.kiosk_image_top);
			formData.append("active", this.illustration.active);
			axios.post('/admin/site-category/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#Illustration-form').modal('hide');
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

			axios.post('/admin/site-category/batch-upload', formData,
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
			axios.get('/admin/site-category/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		downloadTemplate: function () {
			axios.get('/admin/site-category/download-csv-template')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		getPrimaryPixel: function () {
			var pixel = new Object();
			if (this.illustration.category_id && !this.illustration.sub_category_id) {
				pixel['width'] = 349;
				pixel['height'] = 528;
				return pixel;
			}
			pixel['width'] = 320;
			pixel['height'] = 90;
			return pixel;
		},
		getTopPixel: function () {
			var pixel = new Object();
			if (this.illustration.category_id && !this.illustration.sub_category_id) {
				pixel['width'] = '';
				pixel['height'] = '';
				return pixel;
			}
			pixel['width'] = 1470;
			pixel['height'] = 72;
			return pixel;
		}
	},

	components: {
		Table,
		Treeselect
	}
};
</script>
<style lang="scss" scoped>
#preview img {
	max-width: 100%;
	max-height: 500px;
}
</style>