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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:downloadCsv="downloadCsv"
									v-on:DefaultScreen="DefaultScreen"
									ref="screensDataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->

		<!-- Confirm modal -->
		<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to set this site screen as default?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="setDefault">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Confirm modal -->

	</div>
</template>
<script>
import Table from '../Helpers/Table';
export default {
	name: "Screen",
	data() {
		return {
			dataFields: {
				screen_location: "Location",
				site_name: "Site Name",
				orientation: "Orientation",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Deactivated</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
				is_default: {
					name: "Is Default",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">No</span>',
						1: '<span class="badge badge-info">Yes</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/admin/site/maps/list",
			actionButtons: {
				link: {
					title: 'Manage Maps',
					name: 'Manage Maps',
					apiUrl: '/admin/site/manage-map',
					routeName: '',
					button: '<i class="fa fa-map" aria-hidden="true"></i> Manage Maps',
					method: 'link',
				},
				view: {
					title: 'Set as Default',
					name: 'Link',
					apiUrl: '/admin/site/buildings',
					routeName: '',
					button: '<i class="fa fa-tag"></i> Set as Default',
					method: 'view',
					v_on: 'DefaultScreen',
				},
			},
			otherButtons: {
				download: {
					title: 'Download',
					v_on: 'downloadCsv',
					icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				}
			}
		};
	},

	methods: {
		DefaultScreen: function (data) {
			this.is_default = data.id;
			$('#confirmModal').modal('show');
		},

		setDefault: function () {
			axios.get('/admin/site/screen/set-default/' + this.is_default)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.screensDataTable.fetchData();
					$('#confirmModal').modal('hide');
				})
		},

		downloadCsv: function () { 
			axios.get('/admin/site/maps/download-csv')
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
