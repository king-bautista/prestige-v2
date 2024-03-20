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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewBrand="AddNewBrand"
									v-on:editButton="editBrand" v-on:modalBatchUpload="modalBatchUpload"
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
		<div class="modal fade" id="brand-form" data-backdrop="static" tabindex="-1" aria-labelledby="brand-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Brand</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Brand</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Logo<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_logo" accept="image/*" ref="logo" @change="logoChange">
									<footer class="blockquote-footer">image max size is 550 x 550 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="logo" :src="logo" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="brand.name" placeholder="Brand Name"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="brand.descriptions"
										placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Category <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<treeselect v-model="brand.category_id" :options="categories"
										placeholder="Select Category" />
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Supplementals</label>
								<div class="col-sm-8">
									<multiselect v-model="brand.supplementals" :options="supplementals" :multiple="true"
										:close-on-select="true" placeholder="Select Supplementals" label="name"
										track-by="name" @select="toggleSelected" @remove="toggleUnSelected">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Tags</label>
								<div class="col-sm-8">
									<multiselect v-model="brand.tags" :options="tags" :multiple="true"
										:close-on-select="true" placeholder="Select Tags" label="name" track-by="name"
										@select="toggleSelectedTags" @remove="toggleUnSelectedTags">
									</multiselect>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="brand.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeBrand">Add New Brand</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateBrand">Save Changes</button>
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
// import the component
import Treeselect from '@riophae/vue-treeselect'
import Multiselect from 'vue-multiselect';
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
	name: "Brands",
	data() {
		return {
			brand: {
				id: '',
				category_id: null,
				name: '',
				descriptions: '',
				logo: '',
				supplementals: [],
				tags: [],
				active: false,
			},
			brand_range: '',
			logo: '',
			categories: [],
			supplementals: [],
			supplemental_ids: [],
			tags_ids: [],
			tags: [],
			add_record: true,
			edit_record: false,
			image_width: 0,
			image_height: 0,
			dataFields: {
				logo_image_path: {
					name: "Logo",
					type: "logo",
				},
				name: "Name",
				category_name: "Category Name",
				supplemental_names: "Supplementals",
				//tag_names: "Tags",
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
			dataUrl: "/admin/brand/list",
			actionButtons: {
				edit: {
					title: 'Edit this Brand',
					name: 'Edit',
					apiUrl: '',
					routeName: 'brand.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Brand',
					name: 'Delete',
					apiUrl: '/admin/brand/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
				link: {
					title: 'Manage Products and Promos',
					name: 'Manage Products and Promos',
					apiUrl: '/admin/brand/products',
					routeName: '',
					button: '<i class="fa fa-link"></i> Manage Products',
					method: 'link'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Brand',
					v_on: 'AddNewBrand',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Brand',
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
		this.GetCategories();
		this.GetSupplementals();
		this.getTags();
	},

	methods: {
		logoChange: function (e) {

			const file = e.target.files[0];
			if (file.type == 'image/jpeg' || file.type == 'image/bmp' || file.type == 'image/png') {
				this.logo = URL.createObjectURL(file);
				var _URL = window.URL || window.webkitURL;
				const img = new Image();
				img.src = _URL.createObjectURL(file);
				img.file = file;
				var obj = this;
				img.onload = function () {
					this.image_width = this.width;
					this.image_height = this.height;
					if (this.image_width == 550 && this.image_height == 550) {
						obj.brand.logo = this.file;
					} else {
						$('#img_logo').val('');
						obj.logo = '';
						obj.brand.logo = '';
						toastr.error("Invalid Image Size! Must be width: 550 and height: 550. Current width: " + this.image_width + " and height: " + this.image_height);
					};
				}
			} else {
				$('#img_logo').val('');
				this.logo = '';
				this.brand.logo = '';
				toastr.error("The image must be a file type: bmp,jpeg,png.");
			}
		},

		GetCategories: function () {
			axios.get('/admin/category/get-parent')
				.then(response => this.categories = response.data.data);
		},

		GetSupplementals: function () {
			axios.get('/admin/supplemental/get-child')
				.then(response => this.supplementals = response.data.data);
		},

		getTags: function () {
			axios.get('/admin/brand/get-tags')
				.then(response => this.tags = response.data.data);
		},

		toggleSelected: function (value, id) {
			this.supplemental_ids.push(value.id);
		},

		toggleUnSelected: function (value, id) {
			const index = this.supplemental_ids.indexOf(value.id);
			if (index > -1) { // only splice array when item is found
				this.supplemental_ids.splice(index, 1); // 2nd parameter means remove one item only
			}
		},

		toggleSelectedTags: function (value, id) {
			this.tags_ids.push(value.id);
		},

		toggleUnSelectedTags: function (value, id) {
			const index = this.tags_ids.indexOf(value.id);
			if (index > -1) { // only splice array when item is found
				this.tags_ids.splice(index, 1); // 2nd parameter means remove one item only
			}
		},

		AddNewBrand: function () {
			this.add_record = true;
			this.edit_record = false;
			this.brand.name = '';
			this.brand.descriptions = '';
			this.brand.category_id = null;
			this.logo = '';
			this.brand.logo = '';
			this.brand.supplementals = [];
			this.brand.tags = [];
			this.brand.active = false;
			this.$refs.logo.value = null;
			$('#brand-form').modal('show');
		},

		storeBrand: function () {
			let formData = new FormData();
			console.log(this.encodeEntities(this.brand.name));
			console.log(this.decodeEntities(this.brand.name));
			console.log(this.encodeEntities(this.brand.name));
			formData.append("name", this.brand.name);
			formData.append("category_id", this.brand.category_id);
			formData.append("descriptions", this.brand.descriptions);
			formData.append("logo", this.brand.logo);
			formData.append("logo_hidden", this.brand.logo);
			formData.append("supplementals", this.supplemental_ids);
			formData.append("tags", this.tags_ids);
			formData.append("active", this.brand.active);

			axios.post('/admin/brand/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#brand-form').modal('hide');
				})

		},

		editBrand: function (id) {
			//var obj = this;
			//alert(this.decodeEntities("/!@#$%^&amp;AMP;*/()_+{}|:&quot;&amp;LT;&amp;GT;?JORDANP/"));
			axios.get('/admin/brand/' + id)
				.then(response => {
					var brand = response.data.data;// alert(this.decodeEntities(brand.name));
					this.brand.id = id;
					this.brand.category_id = brand.category_id;
					this.brand.name = this.decodeEntities(brand.name);
					this.brand.descriptions = brand.descriptions;
					this.brand.supplementals = brand.brand_details.supplementals;
					this.brand.tags = brand.brand_details.tags;
					this.brand.active = brand.active;
					this.add_record = false;
					this.edit_record = true;
					if (brand.logo) {
						this.logo = brand.logo_image_path;
					}
					else {
						this.logo = this.brand.logo;
					}

					this.$refs.logo.value = null;
					this.product_view = true;

					brand.brand_details.supplementals.forEach((value) => {
						this.supplemental_ids.push(value.id);
					});

					brand.brand_details.tags.forEach((value) => {
						this.tags_ids.push(value.id);
					});

					$('#brand-form').modal('show');
				});
		},

		updateBrand: function () {
			let formData = new FormData();
			formData.append("id", this.brand.id);
			formData.append("name", this.brand.name);
			formData.append("category_id", this.brand.category_id);
			formData.append("descriptions", this.brand.descriptions);
			formData.append("logo", this.brand.logo);
			formData.append("logo_hidden", this.logo);
			formData.append("supplementals", this.supplemental_ids);
			formData.append("tags", this.tags_ids);
			formData.append("active", this.brand.active);

			axios.post('/admin/brand/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#brand-form').modal('hide');
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

			axios.post('/admin/brand/batch-upload', formData,
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
			axios.get('/admin/brand/count')
				.then(response => {
					let count = response.data.data;
					const arr = [];
					for (let i = 0; i < count; i++) {
						arr.push(i);
					}
					const res = [];
					const chunkSize = 1000;
					for (let ii = 0; ii < arr.length; ii += chunkSize) {
						const chunk = arr.slice(ii, ii + chunkSize);
						res.push(chunk[0] + '_' + chunk[chunk.length - 1]);
						var range = chunk[0] + '_' + chunk[chunk.length - 1];
					}
					let i = 0;
					while (i < res.length) {
						this.loop(i, res[i]);
						i++;
					}
				})
		},

		downloadTemplate: function () {
			axios.get('/admin/brand/download-csv-template')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		loop: function (i, range) {
			setTimeout(function () {
					axios.get('/admin/brand/download-csv/' + range)
					 .then(response => {
					 	const link = document.createElement('a');
					 	link.href = response.data.data.filepath;
					 	link.setAttribute('download', response.data.data.filename); //or any other extension
					 	document.body.appendChild(link);
						link.click();
					 });

			}, 9000 * i)
		},
		decodeEntities: function(encodedString) {
			var textArea = document.createElement('textarea');
			textArea.innerHTML = encodedString;
			return textArea.value;
		},

		encodeEntities: function(string) {
			return $('<div>').text(string).html();
		},
	},

	components: {
		Table,
		Treeselect,
		Multiselect
	}
};
</script> 
