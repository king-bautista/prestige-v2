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
									v-on:AddNewSiteCode="AddNewSiteCode" v-on:editButton="editSiteCode"
									v-on:downloadCsv="downloadCsv" ref="dataTable">
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
		<div class="modal fade" id="site_codes-form" tabindex="-1" aria-labelledby="site_codes-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Site Code</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Site Code</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="site_code.site_id">
										<option value="">Select Site</option>
										<option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Cinema ID <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site_code.cinema_id"
										placeholder="SiteCode Label" required>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeSiteCode">Add New
							Site Code</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateSiteCode">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->
	</div>
</template>
<script>
import Table from '../Helpers/Table';

export default {
	name: "SiteCode",
	data() {
		return {
			site_code: {
				id: '',
				site_id: '',
				cinema_id: '',
			},
			add_record: true,
			edit_record: false,
			sites: [],
			dataFields: {
				site_name: "Site Name",
				cinema_id: "Cinema ID",
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/cinema/site-code/list",
			actionButtons: {
				edit: {
					title: 'Edit this Site Code',
					name: 'Edit',
					apiUrl: '',
					routeName: 'site_code.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Site Code',
					name: 'Delete',
					apiUrl: '/admin/cinema/site-code/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New SiteCode',
					v_on: 'AddNewSiteCode',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Site Code',
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
		this.getSites();
	},

	methods: {
		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.sites = response.data.data);
		},

		AddNewSiteCode: function () {
			this.add_record = true;
			this.edit_record = false;
			this.site_code.site_id = '';
			this.site_code.cinema_id = '';
			$('#site_codes-form').modal('show');
		},

		storeSiteCode: function () {
			axios.post('/admin/cinema/site-code/store', this.site_code)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#site_codes-form').modal('hide');
				})
		},

		editSiteCode: function (id) {
			axios.get('/admin/cinema/site-code/' + id)
				.then(response => {
					var site_code = response.data.data;
					this.site_code.id = id;
					this.site_code.site_id = site_code.site_id;
					this.site_code.cinema_id = site_code.cinema_id;
					this.add_record = false;
					this.edit_record = true;
					$('#site_codes-form').modal('show');
				});
		},

		updateSiteCode: function () {
			axios.put('/admin/cinema/site-code/update', this.site_code)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#site_codes-form').modal('hide');
				})
		},
		downloadCsv: function () {
			axios.get('/admin/cinema/site-code/download-csv')
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
