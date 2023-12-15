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
						v-on:AddNewContent="AddNewContent"
						v-on:editButton="editContent"
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
		
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "PlayList",
		
        data() {
            return {
				file: '',
            	dataFields: {
					material_serial_number: "Content ID",
					// material_path: {
            		// 	name: "Preview", 
            		// 	type: "logo", 
            		// },
					ad_type: "Ad Type",
					parent_category_name: "Parent Category",
					category_name: "Category Name",
					brand_name: "Brand Name",
					site_name: "Site",
                    screen_location: "Screen Location",
					company_name: "Company Name",
					display_duration: "Duration(s)",
					start_date: "Start Date",
					end_date: "End Date",
					active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
                    duration: "Duration",	
                    updated_at: "Date Appoved"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/play-list/list",
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
		},

        components: {
        	Table,
     	}
    };
</script>