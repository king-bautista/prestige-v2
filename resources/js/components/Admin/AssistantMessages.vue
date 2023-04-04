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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewAssistantMessage="AddNewAssistantMessage"
									v-on:editButton="editAssistantMessage"  v-on:downloadCsv="downloadCsv" ref="dataTable">
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
		<div class="modal fade" id="assistant-message-form" data-backdrop="static" tabindex="-1" aria-labelledby="assistant-message-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Assitant Message</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Assitant Message</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="location" class="col-sm-4 col-form-label">Location <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="assistant_messages.location" placeholder="Location">
								</div>
							</div>
							<div class="form-group row">
								<label for="content" class="col-sm-4 col-form-label">Content <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<textarea class="form-control" rows="5" v-model="assistant_messages.content" placeholder="Content"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="content_language" class="col-sm-4 col-form-label">Content Langquage <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="assistant_messages.content_language" placeholder="Content Language">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeAssistantMessage">Add New Assistant Message</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateAssistantMessage">Save Changes</button>
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
	name: "Assitant_Messages",
	data() {
		return {
			helper: new Helpers(),
			assistant_messages: {
				id: '',
				location: '',
				content: '',
				content_language: '',
			},
			add_record: true,
			edit_record: false,
			dataFields: {
				location:"Location",
				content: "Content",
				content_language: "Content Language",
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/assistant-message/list",
			actionButtons: {
				edit: {
					title: 'Edit this Assistant Message',
					name: 'Edit',
					apiUrl: '',
					routeName: 'assistant_messages.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Assistant Message',
					name: 'Delete',
					apiUrl: '/admin/assistant-message/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Assistant Message',
					v_on: 'AddNewAssistantMessage',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Assistant Message',
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
			},
			
		};
	},

	methods: {

		AddNewAssistantMessage: function () {
			this.add_record = true;
			this.edit_record = false;
			this.assistant_messages.location = '';
			this.assistant_messages.content = '';
			this.assistant_messages.content_language = '';
			$('#assistant-message-form').modal('show');
		},

		storeAssistantMessage: function () {
			let formData = new FormData();
			formData.append("location", this.assistant_messages.location);
			formData.append("content", this.assistant_messages.content);
			formData.append("content_language", this.assistant_messages.content_language);
			axios.post('/admin/assistant-message/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#assistant-message-form').modal('hide');
				});
		},

		editAssistantMessage: function (id) {
			axios.get('/admin/assistant-message/' + id)
				.then(response => {
					var assistant_messages = response.data.data;
					this.assistant_messages.id = assistant_messages.id;
					this.assistant_messages.location = assistant_messages.location;
					this.assistant_messages.content = assistant_messages.content;
					this.assistant_messages.content_language = assistant_messages.content_language;
					this.add_record = false;
					this.edit_record = true;

					$('#assistant-message-form').modal('show');
				});
		},

		updateAssistantMessage: function () {
			let formData = new FormData(); 
			formData.append("id", this.assistant_messages.id);
			formData.append("location", this.assistant_messages.location);
			formData.append("content", this.assistant_messages.content);
			formData.append("content_language", this.assistant_messages.content_language);
			axios.post('/admin/assistant-message/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#assistant-message-form').modal('hide');
				})
		},
		downloadCsv: function () { 
			axios.get('/admin/assistant-message/download-csv')
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