<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
	    			<div class="card-body">
			          	<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:modalPlaylist="modalPlaylist"
						v-on:modalBatchUpload="modalBatchUpload"
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

		<!-- Playlist Content -->
		<div class="modal fade" id="sortableContent" tabindex="-1" role="dialog" aria-labelledby="sortableContent" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Ad Playlist :<strong> {{ screen_location }} </strong><br>Ad Type :<strong> {{ ad_type }}</strong></h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<table class="table table-hover table-striped">
							<thead class="thead-dark">
								<tr>
									<th scope="col">ID</th>
									<!-- <th scope="col">Preview</th> -->
									<th scope="col">Parent Category</th>
									<th scope="col">Category Name</th>
									<th scope="col">Brand Name</th>
									<th scope="col">Company Name</th>
									<th scope="col">Duration(s)</th>
									<th scope="col">Start Date</th>
									<th scope="col">End Date</th>
									<th scope="col">Status</th>
									<th scope="col">Duration</th>
									<th scope="col">Date Appoved</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(data, index) in playlist" v-bind:key="index" :id="data.id">
									<td data-container="body" data-toggle="tooltip" data-placement="top" title="Drag rows up and down">{{ data.material_serial_number }}</td>
									<!-- <td>{{ data.material_path }}</td> -->
									<td>{{ data.parent_category_name }}</td>
									<td>{{ data.category_name }}</td>
									<td>{{ data.brand_name }}</td>
									<td>{{ data.company_name }}</td>
									<td>{{ data.display_duration }}</td>
									<td>{{ data.start_date }}</td>
									<td>{{ data.end_date }}</td>
									<td>
										<span v-if="data.active" class="badge badge-info">Active</span>
										<span v-else class="badge badge-info">Deactivated</span>
									</td>
									<td>{{ data.duration }}</td>
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
				ad_type: '',
				playlist: [],
				sorted_data: [],
            	dataFields: {
					serial_number: "ID",
                    site_screen_location: "Screen Location",
					site_name: "Site",
					ad_type: "Ad Type",
					active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
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
					batchUpload: {
						title: 'Batch Upload',
						v_on: 'modalBatchUpload',
						icon: '<i class="fas fa-upload"></i> Batch Upload',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
            };
        },

		methods: {
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

			modalPlaylist: function(data) {
				console.log(data);
				this.playlist = [];
				this.screen_location = data.site_screen_location;
				this.ad_type = data.ad_type;
				this.playlist = data.playlist;

				$("#sortableContent").modal('show');
			},

			savePlaylist: function() {
				var sorted_data = [];
				$('tbody tr').each(function(){
					var id = $(this).attr('id');
					if(id) {
						sorted_data.push(id);
					}
				});

				axios.post('/admin/play-list/update-sequence', {sorted_data : sorted_data})
                .then(response => {
					if(response.data) {
						$("#sortableContent").modal('hide');
					}
				});
			}

		},

		mounted() {
			$( function() {
				$( "tbody" ).sortable();
			} );
		},

        components: {
        	Table,
     	}
    };
</script>