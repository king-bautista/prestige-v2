<template>
	<div>
        <!-- Main content -->
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="fas fa-play-circle"></i>&nbsp;&nbsp;Ad Playlist</h4>
						<h4 v-show="add_record && data_form">Ad Playlist :<strong> {{ screen_location }} </strong><br>Ad Type :<strong> {{ ad_type }}</strong></h4>
					</div>
	    			<div class="card-body" v-show="data_list">
			          	<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
                        :primaryKey="primaryKey"
						v-on:modalPlaylist="modalPlaylist"
						v-on:modalBatchUpload="modalBatchUpload"
                        ref="dataTable">
			          	</Table>
		          	</div>
					<div class="card-body" v-show="data_form">
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
							<tbody id="playlist_rows">
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
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
							</div>
						</div>
					</div>
		        </div>
	          </div>
	        </div>
	        <!-- /.row -->
	    <!-- /.content -->
		
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
				data_list: true,
				data_form: false,
                add_record: true,
                edit_record: false,
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
            	dataUrl: "/portal/play-list/list",
				actionButtons: {
            		edit: {
            			title: 'Manage Playlist',
            			name: 'Edit',
						v_on: 'modalPlaylist',
            			button: '<i class="fa fa-link"></i> Manage Playlist',
            			method: 'view'
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

			modalPlaylist: function(data) {
				console.log(data);
				this.playlist = [];
				this.screen_location = data.site_screen_location;
				this.ad_type = data.ad_type;
				this.playlist = data.playlist;

				this.data_list = false;
				this.data_form = true;
				this.add_record = true;
				this.edit_record = false;
			},

			savePlaylist: function() {
				var sorted_data = [];
				$('tbody tr').each(function(){
					var id = $(this).attr('id');
					if(id) {
						sorted_data.push(id);
					}
				});

				axios.post('/portal/play-list/update-sequence', {sorted_data : sorted_data})
                .then(response => {
					if(response.data) {
						$("#sortableContent").modal('hide');
					}
				});
			},

			backToList: function () {
				this.data_list = true;
				this.data_form = false;
			},

		},

		mounted() {
			$( function() {
				$( "#playlist_rows" ).sortable();
			} );
		},

        components: {
        	Table,
     	}
    };
</script>