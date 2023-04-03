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
									v-on:AddNewTranslations="AddNewTranslations" v-on:editButton="editTranslations"
									ref="dataTable">
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
		<div class="modal fade" id="translation-form" data-backdrop="static" tabindex="-1"
			aria-labelledby="translation-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Translation</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit FAQs</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="language" class="col-sm-4 col-form-label">Language <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="translations.language"
										placeholder="Langquage">
								</div>
							</div>
							<div class="form-group row">
								<label for="english" class="col-sm-4 col-form-label">English <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="translations.english"
										placeholder="English">
								</div>
							</div>
							<div class="form-group row">
								<label for="translated" class="col-sm-4 col-form-label">Translated <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="translations.translated"
										placeholder="Translated">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeTranslation">Add New Translations</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateTranslation">Save Changes</button>
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
	name: "Translations",
	data() {
		return {
			helper: new Helpers(),
			translations: {
				id: '',
				language: '',
				english: '',
				translated: '',
			},
			add_record: true,
			edit_record: false,
			dataFields: {
				language: "Language",
				english: "English",
				translated: "Translated",
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/translation/list",
			actionButtons: {
				edit: {
					title: 'Edit this Translation',
					name: 'Edit',
					apiUrl: '',
					routeName: 'translations.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Translation',
					name: 'Delete',
					apiUrl: '/admin/translation/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Translation',
					v_on: 'AddNewTranslations',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Translation',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			},
		};
	},

	methods: {

		AddNewTranslations: function () {
			this.add_record = true;
			this.edit_record = false;
			this.translations.language = '';
			this.translations.english = '';
			this.translations.translated = '';
			$('#translation-form').modal('show');
		},

		storeTranslation: function () {
			let formData = new FormData();
			formData.append("language", this.translations.language);
			formData.append("english", this.translations.english);
			formData.append("translated", this.translations.translated);
			axios.post('/admin/translation/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#translation-form').modal('hide');
				});
		},

		editTranslations: function (id) {
			axios.get('/admin/translation/' + id)
				.then(response => {
					var translations = response.data.data;
					this.translations.id = translations.id;
					this.translations.language = translations.language;
					this.translations.english = translations.english;
					this.translations.translated = translations.translated;
					this.add_record = false;
					this.edit_record = true;

					$('#translation-form').modal('show');
				});
		},

		updateTranslation: function () {
			let formData = new FormData();
			formData.append("id", this.translations.id);
			formData.append("language", this.translations.language);
			formData.append("english", this.translations.english);
			formData.append("translated", this.translations.translated);
			console.log(this.translations);
			axios.post('/admin/translation/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#translation-form').modal('hide');
				})
		},

	},

	components: {
		Table,
		Multiselect,
	}
};
</script> 