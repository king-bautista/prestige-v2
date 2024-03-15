<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div :style="{ 'font-size': 20 + 'px' }">{{ site_name }}</div>	
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :otherButtons="otherButtons"
									:primaryKey="primaryKey" v-on:reportModal="reportModal" v-on:downloadCsv="downloadCsv"
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

		<!-- Batch Upload -->
		<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="filterModalLabel">Filter By</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-group col-md-12">
								<label>Site: <span class="text-danger">*</span></label>
								<select class="custom-select" v-model="filter.site_id" @change="getSiteName">
									<option value="">Select Site</option>
									<option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
								</select>
							</div>
							<div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">Start Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.start_date" placeholder="YYYY/MM/DD" :config="options"
										id="date_from" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">End Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.end_date" placeholder="YYYY/MM/DD" :config="options"
										id="date_to" autocomplete="off"></date-picker>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="filterReport">Filter</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
import Table from '../Helpers/Table';
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

export default {
	name: "Reports",
	data() {
		return {
			filter: {
				site_id: '',
				start_date: '',
				end_data:'',
			},
			site_name: 'All',
			site_name_temp: 'All',
			sites: [],
			options: {
				format: 'YYYY/MM/DD',
				useCurrent: false,
			},
			dataFields: {
				brand_logo: {
					name: "Brand Logo",
					type: "logo",
				},
				brand_name: "Brand Name",
				main_category_name: "Category",
				tenant_count: "Tenant Count",
				category_percentage: "% Total Over Category",
				tenant_percentage: "% Total Over Tenant"
			},
			primaryKey: "id",
			dataUrl: "/admin/reports/top-tenant-search/list",
			otherButtons: {
				addNew: {
					title: 'Filter',
					v_on: 'reportModal',
					icon: '<i class="fa fa-filter" aria-hidden="true"></i> Filter',
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
		getSiteName: function(event) {
				var option_text = event.target[event.target.selectedIndex].text; 
				this.site_name_temp = (option_text == 'Select Site' || !option_text)?'All':option_text;
			},
		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.sites = response.data.data);
		},

		reportModal: function () {
			this.filter.site_id = '';
			$('#filterModal').modal('show');
		},

		filterReport: function () {
			this.$refs.dataTable.filters = this.filter;
			this.$refs.dataTable.fetchData();
			var filter = this.filter; 
			this.site_name = (filter.site_id == "")? 'All': this.site_name_temp;
			$('#filterModal').modal('hide');
		},

		downloadCsv: function () {
			axios.get('/admin/reports/top-tenant-search/download-csv', { params: { filters: this.filter } })
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
		datePicker
	}
};
</script> 
