<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :otherButtons="otherButtons"
									:primaryKey="primaryKey" v-on:ScheduleModal="ScheduleModal"
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

		<!-- Batch Upload -->
		<div class="modal fade" id="batchModal" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="batchModalLabel">Full Schedules</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-group col-md-12">
								<label>Site: <span class="text-danger">*</span></label>
								<select class="custom-select" v-model="site_code.site_id">
									<option value="">Select Site</option>
									<option v-for="site in sites" :value="site.id"> {{ site.site_name }}</option>
								</select>
							</div>
						</form>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="storeCinemaSchedule">Save changes</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Table from '../Helpers/Table';

export default {
	name: "Schedules",
	data() {
		return {
			site_code: {
				site_id: '',
			},
			sites: [],
			dataFields: {
				title: "Title",
				site_name: "Site Name",
				rating: "Rating",
				screen_name: "Screen Name",
				genre_name: "Genre",
				show_time: "Time Slot",
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/cinema/schedule/list",
			otherButtons: {
				addNew: {
					title: 'Get Schedules',
					v_on: 'ScheduleModal',
					icon: '<i class="fa fa-calendar" aria-hidden="true"></i> Get Schedules',
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
			axios.get('/admin/cinema/schedule/site-codes')
				.then(response => this.sites = response.data.data);
		},

		ScheduleModal: function () {
			$('#batchModal').modal('show');
		},

		storeCinemaSchedule: function () {
			axios.post('/admin/cinema/schedule/store', this.site_code)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#batchModal').modal('hide');
				})
		},
		downloadCsv: function () {
			axios.get('/admin/cinema/schedule/download-csv')
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
