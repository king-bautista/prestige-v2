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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewFAQs="AddNewFAQs"
									v-on:modalBatchUpload="modalBatchUpload"
									v-on:editButton="editFAQs" v-on:downloadCsv="downloadCsv"
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
		<div class="modal fade" id="faqs-form" data-backdrop="static" tabindex="-1" aria-labelledby="faqs-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							FAQs</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit FAQs</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="question" class="col-sm-4 col-form-label">Question <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="faqs.question" placeholder="Question">
								</div>
							</div>
							<div class="form-group row">
								<label for="answer" class="col-sm-4 col-form-label">Answer <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" rows="5" v-model="faqs.answer"
										placeholder="Answer"></textarea>
								</div>
							</div>

							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="faqs.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeFAQs">Add New FAQs</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateFAQs">Save Changes</button>
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
	name: "FAQs",
	data() {
		return {
			helper: new Helpers(),
			faqs: {
				id: '',
				question: '',
				answer: '',
				active: true,
			},
			add_record: true,
			edit_record: false,
			dataFields: {
				shorten_question: "Question",
				shorten_answer: "Answer",
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
			dataUrl: "/admin/faq/list",
			actionButtons: {
				edit: {
					title: 'Edit this FAQs',
					name: 'Edit',
					apiUrl: '',
					routeName: 'faqs.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this FAQs',
					name: 'Delete',
					apiUrl: '/admin/faq/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New FAQs',
					v_on: 'AddNewFAQs',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New FAQs',
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

	methods: {

		AddNewFAQs: function () {
			this.add_record = true;
			this.edit_record = false;
			this.faqs.question = '';
			this.faqs.answer = '';
			this.faqs.active = true;
			$('#faqs-form').modal('show');
		},

		storeFAQs: function () {
			let formData = new FormData();
			formData.append("question", this.faqs.question);
			formData.append("answer", this.faqs.answer);
			formData.append("active", this.faqs.active);
			axios.post('/admin/faq/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#faqs-form').modal('hide');
				});
		},

		editFAQs: function (id) {
			axios.get('/admin/faq/' + id)
				.then(response => {
					var faqs = response.data.data;
					this.faqs.id = faqs.id;
					this.faqs.question = faqs.question;
					this.faqs.answer = faqs.answer;
					this.faqs.active = faqs.active;
					this.add_record = false;
					this.edit_record = true;

					$('#faqs-form').modal('show');
				});
		},

		updateFAQs: function () {
			let formData = new FormData();
			formData.append("id", this.faqs.id);
			formData.append("question", this.faqs.question);
			formData.append("answer", this.faqs.answer);
			formData.append("active", this.faqs.active);
			axios.post('/admin/faq/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#faqs-form').modal('hide');
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

			axios.post('/admin/faq/batch-upload', formData,
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
			axios.get('/admin/faq/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},
		downloadTemplate: function () {
			axios.get('/admin/faq/download-csv-template')
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