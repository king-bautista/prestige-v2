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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewConcern="AddNewConcern"
									v-on:editButton="editConcern" v-on:downloadCsv="downloadCsv"
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
		<div class="modal fade" id="concerns-form" data-backdrop="static" tabindex="-1" aria-labelledby="concerns-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Ticket Type</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Ticket Type</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="name" class="col-sm-4 col-form-label">Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="concerns.name" placeholder="Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="description" class="col-sm-4 col-form-label">Description <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" rows="5" v-model="concerns.description"
										placeholder="Description"></textarea>
								</div>
							</div>

							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="concerns.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeConcern">Add New Ticket Type</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateConcern">Save Changes</button>
						</div>
						<!-- /.card-body -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->



	</div>
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import Multiselect from 'vue-multiselect';

export default {
	name: "Concern",
	data() {
		return {
			helper: new Helpers(),
			concerns: {
				id: '',
				name: '',
				description: '',
				active: true,
			},
			add_record: true,
			edit_record: false,
			dataFields: {
				shorten_name: "Name",
				shorten_description: "Description",
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
			dataUrl: "/admin/customer-care/ticket-type/list",
			actionButtons: {
				edit: {
					title: 'Edit this Ticket Type',
					name: 'Edit',
					apiUrl: '',
					routeName: 'concerns.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Ticket Type',
					name: 'Delete',
					apiUrl: '/admin/customer-care/ticket-ype/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Ticket Type',
					v_on: 'AddNewConcern',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Ticket Type',
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

	methods: {

		AddNewConcern: function () {
			this.add_record = true;
			this.edit_record = false;
			this.concerns.name = '';
			this.concerns.description = '';
			this.concerns.active = true;
			$('#concerns-form').modal('show');
		},

		storeConcern: function () {
			let formData = new FormData();
			formData.append("name", this.concerns.name);
			formData.append("description", this.concerns.description);
			formData.append("active", this.concerns.active);
			axios.post('/admin/customer-care/ticket-ype/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#concerns-form').modal('hide');
				});
		},

		editConcern: function (id) {
			axios.get('/admin/customer-care/ticket-ype/' + id)
				.then(response => {
					var concerns = response.data.data;
					this.concerns.id = concerns.id;
					this.concerns.name = concerns.name;
					this.concerns.description = concerns.description;
					this.concerns.active = concerns.active;
					this.add_record = false;
					this.edit_record = true;

					$('#concerns-form').modal('show');
				});
		},

		updateConcern: function () {
			let formData = new FormData();
			formData.append("id", this.concerns.id);
			formData.append("name", this.concerns.name);
			formData.append("description", this.concerns.description);
			formData.append("active", this.concerns.active);
			axios.post('/admin/customer-care/ticket-type/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#concerns-form').modal('hide');
				})
		},
		downloadCsv: function () {
			axios.get('/admin/customer-care/ticket-ype/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		downloadTemplate: function () {
			axios.get('/admin/customer-care/ticket-ype/download-csv-template')
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
		Multiselect,
	}
};
</script> 