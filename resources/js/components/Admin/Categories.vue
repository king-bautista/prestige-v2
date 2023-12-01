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
									v-on:addNewCategory="addNewCategory" v-on:editButton="editCategory"
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
		<div class="modal fade" id="category-form" tabindex="-1" aria-labelledby="category-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Category</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Category</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="category.name"
										placeholder="Category Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" v-model="category.descriptions"
										placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Parent Category</label>
								<div class="col-sm-8">
									<treeselect v-model="category.parent_id" :options="parent_category"
										placeholder="Select Parent Category" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Class Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="category.class_name"
										placeholder="Class Name">
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="category.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeCategory">Add New
							Category</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateCategory">Save
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
	name: "Categories",
	data() {
		return {
			category: {
				id: '',
				parent_id: null,
				name: '',
				descriptions: '',
				class_name: '',
				category_type: 1,
				active: false,
			},
			parent_category: [],
			companies: [],
			site_list: [],
			add_record: true,
			edit_record: false,
			dataFields: {
				name: "Name",
				descriptions: "Descriptions",
				parent_category: "Parent Category",
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
			dataUrl: "/admin/category/list",
			actionButtons: {
				edit: {
					title: 'Edit this category',
					name: 'Edit',
					apiUrl: '',
					routeName: 'category.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this category',
					name: 'Delete',
					apiUrl: '/admin/category/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Category',
					v_on: 'addNewCategory',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Category',
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
		this.getParentCategory();
		this.getSites();
	},

	methods: {
		getParentCategory: function () {
			axios.get('/admin/category/get-parent')
				.then(response => this.parent_category = response.data.data);
		},

		getCompanies: function () {
			axios.get('/admin/company/get-all')
				.then(response => this.companies = response.data.data);
		},

		addNewCategory: function () {
			this.add_record = true;
			this.edit_record = false;
			this.category.parent_id = null;
			this.category.name = '';
			this.category.descriptions = '';
			this.category.class_name = '';
			this.category.active = false;
			$('#category-form').modal('show');
		},

		storeCategory: function () {
			axios.post('/admin/category/store', this.category)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.getParentCategory();
					$('#category-form').modal('hide');
				});
		},

		editCategory: function (id) {
			axios.get('/admin/category/' + id)
				.then(response => {
					var category = response.data.data;
					this.category.id = category.id;
					this.category.parent_id = (category.parent_id) ? category.parent_id : null;
					this.category.name = category.name;
					this.category.descriptions = category.descriptions;
					this.category.class_name = category.class_name;
					this.category.active = category.active;
					this.add_record = false;
					this.edit_record = true;
					$('#category-form').modal('show');
				});
		},

		updateCategory: function () {
			axios.post('/admin/category/update', this.category)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.getParentCategory();
					$('#category-form').modal('hide');
				})

		},

		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.site_list = response.data.data);
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

			axios.post('/admin/category/batch-upload', formData,
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
			    })
		},

		downloadCsv: function () {
			axios.get('/admin/category/download-csv')
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
			link.href = '/uploads/csv/category-batch-upload.csv';
			link.setAttribute('downloadFile', '/uploads/csv/category-batch-upload.csv'); //or any other extension
			document.body.appendChild(link);
			link.click();
		},
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