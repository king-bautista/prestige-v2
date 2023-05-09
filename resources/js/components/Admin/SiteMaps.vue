<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" 
									:dataUrl="dataUrl" 
									:actionButtons="actionButtons"
									:otherButtons="otherButtons" 
									:primaryKey="primaryKey" 
									v-on:downloadCsv="downloadCsv"
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
					type:"Boolean", 
					status: { 
						0: '<span class="badge badge-danger">Deactivated</span>', 
						1: '<span class="badge badge-info">Active</span>'
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
			},
			otherButtons: {
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

	methods: {
		downloadCsv: function () {
			axios.get('/admin/site/screen/download-csv')
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
