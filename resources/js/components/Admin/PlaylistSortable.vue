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
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:modalPlaylist="modalPlaylist"
									v-on:modalBatchUpload="modalBatchUpload" v-on:modalBatchUploadTest="modalBatchUploadTest" v-on:downloadCsv="downloadCsv"
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

		<!-- Batch Upload -->
		<div class="modal fade" id="batchModalTest" tabindex="-1" role="dialog" aria-labelledby="batchModalTestLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="batchModalTestLabel">Batch Upload Test</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-group col-md-12">
								<label>CSV File: <span class="text-danger">*</span></label>
								<div class="custom-file">
									<input type="file" ref="file" v-on:change="handleFileUploadTest()"
										accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
										class="custom-file-input" id="batchInput">
									<label class="custom-file-label" id="batchInputTestLabel" for="batchInput">Choose
										file</label>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="storeBatchTest">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Playlist Content -->
		<div class="modal fade" id="sortableContent" tabindex="-1" role="dialog" aria-labelledby="sortableContent"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><strong> {{ screen_location }} </strong></h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- start setting up tabs for full screen and banner ad -->
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation" v-for="(dimension, index) in dimensions">
								<button v-bind:class="(index == 0) ? 'active' : ''" class="nav-link"
									@click="dimension_value = dimension.dimension" :id="dimension" data-bs-toggle="tab"
									data-bs-target="#home" type="button" role="tab" aria-controls="home"
									aria-selected="true">{{ dimension.ad_type_name }}</button>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div v-for="(dimension, index) in dimensions" v-bind:class="(index == 0) ? 'show active' : ''"
								class="tab-pane fade" :id="dimension" role="tabpanel">&nbsp;</div>
						</div>
						<!-- end setting up tabs for full screen and banner ad -->
						
						<table class="table table-hover table-striped">
							<thead class="thead-dark">
								<tr>
									<th scope="col">ID</th>
									<th scope="col">Preview</th>
									<th scope="col">Parent Category</th>
									<th scope="col">Category Name</th>
									<th scope="col">Brand Name</th>
									<th scope="col">Company Name</th>
									<th scope="col">Start/End Date</th>
									<th scope="col">Duration</th>
									<th scope="col">Sequence</th>
									<th scope="col">Date Appoved</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(data, index) in playlist.filter(col => col.dimension == dimension_value)"
									v-bind:key="index" :id="data.id">
									<td data-container="body" data-toggle="tooltip" data-placement="top"
										title="Drag rows up and down">{{ data.content_serial_number }}</td>
									<td> <img :src="data.thumbnail_path"> </td>
									<td>{{ data.parent_category_name }}</td>
									<td>{{ data.category_name }}</td>
									<td>{{ data.brand_name }}</td>
									<td>{{ data.company_name }}</td>
									<td>{{ data.start_date }} <br />to<br /> {{ data.end_date }}</td>
									<td>{{ data.duration }}</td>
									<td>{{ data.sequence }}</td>
									
									<td>{{ data.updated_at }}</td>
								</tr>

							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="savePlaylist">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- show play list loop -->
		<div class="modal fade " id="loopModal" tabindex="-1" role="dialog" aria-labelledby="loopModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="loopModalLabel">show me the lists</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<table class="table table-striped table-responsive" style="width: 100%;">
								<thead>
									<th>content_id</th>
									<th>parent_category</th>
									<th>category_name</th>
									<th>brand_name</th>
									<th>site</th>
									<th>screen_location</th>
									<th>company_name</th>
									<th>advertisement_type</th>
								</thead>
								<tbody id="loops">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<!-- <button type="button" class="btn btn-primary" @click="storeBatchTest">Save changes</button> -->
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
import Table from '../Helpers/Table';

export default {
	name: "PlayList",

	data() {
		return {
			file: '',
			screen_location: '',
			dimensions: [],
			dimension_value: '',
			playlist: [],
			sorted_data: [],
			dataFields: {
				site_screen_location: "Screen Location",
				site_name: "Site",
				product_application: "Product Application",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge badge-danger">Inactive</span>',
						1: '<span class="badge badge-info">Active</span>'
					}
				},
			},
			primaryKey: "id",
			dataUrl: "/admin/play-list/list",
			actionButtons: {
				edit: {
					title: 'Manage Playlist',
					name: 'Edit',
					v_on: 'modalPlaylist',
					button: '<i class="fa fa-link"></i> Manage Playlist',
					method: 'view'
				},
			},
			otherButtons: {
				// batchUpload: {
				// 	title: 'Batch Upload',
				// 	v_on: 'modalBatchUpload',
				// 	icon: '<i class="fas fa-upload"></i> Batch Upload',
				// 	class: 'btn btn-primary btn-sm',
				// 	method: 'add'
				// },
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
				// batchUploadTest: {
				// 	title: 'Batch Upload Test',
				// 	v_on: 'modalBatchUploadTest',
				// 	icon: '<i class="fas fa-upload"></i> Batch Upload Test',
				// 	class: 'btn btn-primary btn-sm',
				// 	method: 'add'
				// },
			},
		};
	},

	methods: {
		modalBatchUpload: function () {
			$('#batchModal').modal('show');
		},
		modalBatchUploadTest: function () {
			$('#batchModalTest').modal('show');
			// $('#loopModal').modal('show');
		},

		handleFileUpload: function () {
			this.file = this.$refs.file.files[0];
			$('#batchInputLabel').html(this.file.name)
		},

		handleFileUploadTest: function () {
			this.file = this.$refs.file.files[0];
			$('#batchInputTestLabel').html(this.file.name)
		},

		storeBatch: function () {
			let formData = new FormData();
			formData.append('file', this.file);

			axios.post('/admin/play-list/batch-upload', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then(response => {
				this.$refs.file.value = null;
				this.$refs.dataTable.fetchData();
				toastr.success(response.data.message);
				$('#batchModal').modal('hide');
				$('#batchInputLabel').html('Choose File');
				window.location.reload();
			})
		},

		storeBatchTest: function () {
			// alert("you've summoned me");
			let formData = new FormData();
			formData.append('file', this.file);

			axios.post('/admin/play-list/batch-upload-test', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then(response => {
				this.$refs.file.value = null;
				this.$refs.dataTable.fetchData();
				// toastr.success(response.data.message);
				console.log(response.data.data.play_lists);
				$('#batchModalTest').modal('hide');
				$('#batchInputTestLabel').html('Choose File');
				$('#loopModal').modal('show');
				$('#loops').empty();
				$.each(response.data.data.play_lists, function(index, value){
					$('#loops').append('<tr>\
						<td>'+ value.content_id +'</td>\
						<td>'+ value.parent_category_id +'</td>\
						<td>'+ value.category_id +'</td>\
						<td>'+ value.brand_id +'</td>\
						<td>'+ value.site_screen_id +'</td>\
						<td>'+ value.screen_location +'</td>\
						<td>'+ value.company_id +'</td>\
						<td>'+ value.dimension +'</td>\
						<td>'+ value.loop_number +'</td>\
						</tr>');
				});
				// window.location.reload();
			})
		},

		modalPlaylist: function (data) {
			this.playlist = [];
			this.screen_location = data.site_screen_location;
			this.dimensions = data.dimensions;
			this.dimension_value = data.dimensions[0].dimension;
			this.playlist = data.playlist;

			// console.log(this.playlist);

			$("#sortableContent").modal('show');
		},

		savePlaylist: function () {
			var sorted_data = [];
			$('tbody tr').each(function () {
				var id = $(this).attr('id');
				if (id) {
					sorted_data.push(id);
				}
			});

			axios.post('/admin/play-list/update-sequence', { sorted_data: sorted_data })
				.then(response => {
					if (response.data) {
						$("#sortableContent").modal('hide');
					}
				});
		},

		downloadCsv: function () {
			$('.fa-download').removeClass('fa-download').addClass("fa-spinner fa-spin");
			axios.get('/admin/play-list/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
					$('.fa-spinner').removeClass('fa-spinner fa-spin').addClass("fa-download");
				})
		},
		
		downloadTemplate: function () {
			$('.fa-download').removeClass('fa-download').addClass("fa-spinner fa-spin");
			axios.get('/admin/play-list/download-csv-template')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
					$('.fa-spinner').removeClass('fa-spinner fa-spin').addClass("fa-download");
				})
		},

	},

	mounted() {
		$(function () {
			$("tbody").sortable();
		});
	},

	components: {
		Table,
	}
};
</script>