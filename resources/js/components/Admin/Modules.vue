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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewModule="AddNewModule"
									v-on:editButton="editModule" v-on:downloadCsv="downloadCsv"
									v-on:DeleteModule="DeleteModule" ref="dataTable">
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
		<div class="modal fade" id="module-form" tabindex="-1" aria-labelledby="module-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Module</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Module</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Module Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="module.name" placeholder="Module Name"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Link <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="module.link" placeholder="Link"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Icon Class Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="module.class_name"
										placeholder="CSS Class Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Parent Link</label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="module.parent_id">
										<option value="">Select Parent Link</option>
										<option v-for="link in parent_links" :value="link.id"> {{ link.role }} - {{
											link.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="role" class="col-sm-4 col-form-label">Role <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="module.role">
										<option value="">Select role</option>
										<option v-for="role in roles" :value="role"> {{ role }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="module.isActive">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeModule">Add New
							Module</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateModule">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->
		<div class="modal fade" id="moduleDeleteModal" tabindex="-1" aria-labelledby="moduleDeleteModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">{{ modal_label }}</h5>
					</div>
					<div class="modal-body">
						<h6>{{ modal_message }}</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" v-show="delete_record" @click="removeModule">OK</button>
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
			module: {
				id: '',
				parent_id: '',
				role: '',
				name: '',
				link: '',
				class_name: '',
				isActive: false,
			},
			parent_links: [],
			add_record: true,
			edit_record: false,
			delete_record: false,
			id_to_deleted: 0,
			modal_label: '',
			modal_message: "Do you really want to delete?",
			roles: ['Admin', 'Portal'],
			dataFields: {
				name: "Name",
				class_name: {
					name: "Icon Class Name",
					type: "icon",
				},
				parent_link: "Parent Link",
				role: "Role",
				link: "Link",
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
			dataUrl: "/admin/modules/list",
			actionButtons: {
				edit: {
					title: 'Edit this module',
					name: 'Edit',
					apiUrl: '',
					routeName: 'module.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this module',
					name: 'Delete',
					apiUrl: '/admin/modules/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'custom_delete',
					v_on: 'DeleteModule',
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Module',
					v_on: 'AddNewModule',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Module',
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
			}
		};
	},

	created() {
		this.GetParentLinks();
	},

	methods: {
		GetParentLinks: function () {
			axios.get('/admin/modules/get-all-links')
				.then(response => this.parent_links = response.data.data);
		},

		AddNewModule: function () {
			this.add_record = true;
			this.edit_record = false;
			this.module.name = '';
			this.module.parent_id = '';
			this.module.class_name = '';
			this.module.link = '';
			this.module.role = '';
			this.module.isActive = false;
			$('#module-form').modal('show');
		},

		storeModule: function () {
			axios.post('/admin/modules/store', this.module)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentLinks();
					$('#module-form').modal('hide');
				})
		},

		editModule: function (id) {
			axios.get('/admin/modules/' + id)
				.then(response => {
					var module = response.data.data;
					this.module.id = id;
					this.module.name = module.name;
					this.module.parent_id = module.parent_id;
					this.module.class_name = module.class_name;
					this.module.link = module.link;
					this.module.role = module.role;
					this.module.isActive = module.active;
					this.add_record = false;
					this.edit_record = true;
					$('#module-form').modal('show');
				});
		},

		updateModule: function () {
			axios.put('/admin/modules/update', this.module)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentLinks();
					$('#module-form').modal('hide');
				})
		},

		downloadCsv: function () {
			axios.get('/admin/modules/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		DeleteModule: function (data) {
			axios.get('/admin/modules/get-parent/' + data.id)
				.then(response => {
					var parent_id_count = response.data.data;  alert(parent_id_count);
					if (parent_id_count == 0) {
						this.id_to_deleted = data.id;
						this.modal_label = "Confirm";
						this.delete_record = true;
					}else{
						this.modal_label = "Error";
						this.modal_message = "You cannot delete the record.";
						this.delete_record = false;
					}

					$('#moduleDeleteModal').modal('show');
				})

		},

		removeModule: function () {
			axios.get('/admin/modules/delete/' + this.id_to_deleted)
				.then(response => {
					this.$refs.dataTable.fetchData();
					this.id_to_deleted = 0;
					$('#moduleDeleteModal').modal('hide');
				});
		},

	},

	components: {
		Table
	}
};
</script> 
