<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Building Floors</h3>
                    </div>
	    			<div class="card-body">
			          	<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="flooractionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:AddNewFloor="AddNewFloor"
						v-on:editButton="editFloor"
						v-on:DeleteFloor="DeleteFloor"
                        ref="floorsDataTable">
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
		<div class="modal fade" id="floor-form" tabindex="-1" aria-labelledby="floor-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Floor</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Floor</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="floor.site_building_id">
									    <option value="">Select Building</option>
									    <option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.name" placeholder="Floor Name" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Map File <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <input type="file" ref="mapFile" @change="mapFile">
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Map Preview <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="mapPreview" @change="mapChange">
									<footer class="blockquote-footer">image max size is 1250 x 600 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="map_preview" :src="map_preview" class="img-thumbnail" />
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position X <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.position_x" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position Y <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.position_y" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position Z <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.position_z" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Text Y Position <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.text_y_position" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.default_zoom" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Desktop Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.default_zoom_desktop" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Mobile Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="floor.default_zoom_mobile" placeholder="0.00" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="is_default" class="col-sm-4 col-form-label">Is Default</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_default" v-model="floor.is_default">
										<label class="custom-control-label" for="is_default"></label>
									</div>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_active" v-model="floor.active">
										<label class="custom-control-label" for="is_active"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeFloor">Add New Floor</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateFloor">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
	  <!-- Modal -->
		<div class="modal fade" id="levelDeleteModal" tabindex="-1" aria-labelledby="levelDeleteModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to delete?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="removeFloor">OK</button>
					</div>
				</div>
			</div>
		</div>
		
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Floor",
        data() {
            return {
                floor: {
                    id: '',
                    site_building_id: '',
                    name: '',
                    map_file: '',
                    map_preview: '',
                    position_x: '10.00',
                    position_y: '0.20',
                    position_z: '5.00',
                    text_y_position: '4.00',
                    default_zoom: '0.40',
                    default_zoom_desktop: '0.40',
                    default_zoom_mobile: '0.40',
                    is_default: '',
                    active: false,           
                },
                buildings: [],
                map_preview: '',
                map_file: '',
                add_record: true,
                edit_record: false,
				id_to_deleted: 0,
            	dataFields: {
            		name: "Floor Name", 
					building_name: "Building Name",
					map_file: "Map File",
					map_preview_path: {
            			name: "Map Preview", 
            			type:"image", 
            		},
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
					is_default: {
            			name: "Is Default", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">No</span>', 
            				1: '<span class="badge badge-info">Yes</span>'
            			}
            		},
                    created_at: "Date Created"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/site/floor/list",
            	flooractionButtons: {
            		edit: {
            			title: 'Edit this Floor',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'floor.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Floor',
            			name: 'Delete',
            			apiUrl: '/admin/site/floor/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'custom_delete',
						v_on: 'DeleteFloor',
            		},
					link: {
            			title: 'Manage Map',
            			name: 'Delete',
            			apiUrl: '/admin/site/map',
            			routeName: '',
            			button: '<i class="fa fa-map" aria-hidden="true"></i> Manage Map',
            			method: 'link',
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Floor',
						v_on: 'AddNewFloor',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Floor',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
        },

        methods: {
            GetBuildings: function() {
				axios.get('/admin/site/buildings')
                .then(response => this.buildings = response.data.data);
			},

            mapFile: function(e) {
				const file = e.target.files[0];
				this.floor.map_file = file;
			},

            mapChange: function(e) {
				const file = e.target.files[0];
                this.map_preview = URL.createObjectURL(file);
				this.floor.map_preview = file;
			},

			AddNewFloor: function() {
                this.GetBuildings();
				this.add_record = true;
				this.edit_record = false;
                this.floor.site_building_id = '';
                this.floor.name = '';
                this.floor.map_file = '';
                this.floor.map_preview = '';
                this.floor.position_x = '10.00';
                this.floor.position_y = '0.20';
                this.floor.position_z = '5.00';
                this.floor.text_y_position = '4.00';
                this.floor.default_zoom = '0.40';
                this.floor.default_zoom_desktop = '0.40';
                this.floor.default_zoom_mobile = '0.40';
                this.floor.is_default = false;
                this.floor.active = true;
				
				this.map_preview = '';
				this.$refs.mapFile.value = null;
				this.$refs.mapPreview.value = null;
              	$('#floor-form').modal('show');
            },

            storeFloor: function() {

                let formData = new FormData();
				formData.append("site_building_id", this.floor.site_building_id);
				formData.append("name", this.floor.name);
				formData.append("map_file", this.floor.map_file);
				formData.append("map_preview", this.floor.map_preview);
				formData.append("position_x", this.floor.position_x);
				formData.append("position_y", this.floor.position_y);
				formData.append("position_z", this.floor.position_z);
				formData.append("text_y_position", this.floor.text_y_position);
				formData.append("default_zoom", this.floor.default_zoom);
				formData.append("default_zoom_desktop", this.floor.default_zoom_desktop);
				formData.append("default_zoom_mobile", this.floor.default_zoom_mobile);
				formData.append("is_default", this.floor.is_default);
				formData.append("active", this.floor.active);

                axios.post('/admin/site/floor/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.floorsDataTable.fetchData();
	              	$('#floor-form').modal('hide');
				})
            },

			editFloor: function(id) {
				this.GetBuildings();
                axios.get('/admin/site/floor/'+id)
                .then(response => {
                    var floor = response.data.data;
					this.floor.id= floor.id;
					this.floor.site_building_id = floor.site_building_id;
					this.floor.name = floor.name;
					this.floor.map_file = floor.map_details.map_file;
					this.floor.map_preview = floor.map_details.map_preview;
					this.floor.position_x = floor.map_details.position_x;
					this.floor.position_y = floor.map_details.position_y;
					this.floor.position_z = floor.map_details.position_z;
					this.floor.text_y_position = floor.map_details.text_y_position;
					this.floor.default_zoom = floor.map_details.default_zoom;
					this.floor.default_zoom_desktop = floor.map_details.default_zoom_desktop;
					this.floor.default_zoom_mobile = floor.map_details.default_zoom_mobile;
					this.floor.is_default = floor.map_details.is_default;
					this.floor.active = floor.active;

					this.map_preview = floor.map_preview_path;
					this.$refs.mapFile.value = null;
					this.$refs.mapPreview.value = null;

					this.add_record = false;
					this.edit_record = true;
                    $('#floor-form').modal('show');
                });
            },

            updateFloor: function() {
                let formData = new FormData();
				formData.append("id", this.floor.id);
				formData.append("site_building_id", this.floor.site_building_id);
				formData.append("name", this.floor.name);
				formData.append("map_file", this.floor.map_file);
				formData.append("map_preview", this.floor.map_preview);
				formData.append("position_x", this.floor.position_x);
				formData.append("position_y", this.floor.position_y);
				formData.append("position_z", this.floor.position_z);
				formData.append("text_y_position", this.floor.text_y_position);
				formData.append("default_zoom", this.floor.default_zoom);
				formData.append("default_zoom_desktop", this.floor.default_zoom_desktop);
				formData.append("default_zoom_mobile", this.floor.default_zoom_mobile);
				formData.append("is_default", this.floor.is_default);
				formData.append("active", this.floor.active);

                axios.post('/admin/site/floor/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.floorsDataTable.fetchData();
	              	$('#floor-form').modal('hide');
				})
            },

			DeleteFloor: function(data) {
				this.id_to_deleted = data.id;
				$('#levelDeleteModal').modal('show');
			},

			removeFloor: function() {
				axios.get('/admin/site/floor/delete/'+this.id_to_deleted)
                .then(response => {
                    this.$refs.floorsDataTable.fetchData();
                    this.id_to_deleted = 0;
                    $('#levelDeleteModal').modal('hide');
                });
			}

        },

        components: {
        	Table
 	    }
    };
</script> 
