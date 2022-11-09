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
						v-on:AddNewAmenities="AddNewAmenities"
						v-on:editButton="editAmenities"
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
		<div class="modal fade" id="amenities-form" tabindex="-1" aria-labelledby="amenities-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Amenities</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Amenities</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Amenity Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="amenity.name" placeholder="Amenities Name" required>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" v-model="amenity.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeAmenities">Add New Amenities</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateAmenities">Save Changes</button>
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
        name: "Users",
        data() {
            return {
                amenity: {
                    id: '',
                    name: '',
                    active: false,           
                },
                parent_links: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
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
            	dataUrl: "/admin/amenity/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Amenities',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'amenity.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Amenities',
            			name: 'Delete',
            			apiUrl: '/admin/amenity/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Amenities',
						v_on: 'AddNewAmenities',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Amenities',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
        },

        methods: {
			AddNewAmenities: function() {
				this.add_record = true;
				this.edit_record = false;
                this.amenity.name = '';
                this.amenity.active = false;				
              	$('#amenities-form').modal('show');
            },

            storeAmenities: function() {
                axios.post('/admin/amenity/store', this.amenity)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#amenities-form').modal('hide');
				})
            },

			editAmenities: function(id) {
                axios.get('/admin/amenity/'+id)
                .then(response => {
                    var amenity = response.data.data;
                    this.amenity.id = id;
                    this.amenity.name = amenity.name;
                    this.amenity.active = amenity.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#amenities-form').modal('show');
                });
            },

            updateAmenities: function() {
                axios.put('/admin/amenity/update', this.amenity)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#amenities-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	   }
    };
</script> 
