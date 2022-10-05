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
						v-on:AddNewMap="AddNewMap"
						v-on:editButton="editMap"
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

        <!-- Manage map -->
		<div class="modal fade" id="map-form" tabindex="-1" aria-labelledby="map-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Map</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Map</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
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
									<input type="text" class="form-control" v-model="map_form.position_x" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position Y <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.position_y" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position Z <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.position_z" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Text Y Position <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.text_y_position" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Desktop Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom_desktop" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Mobile Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom_mobile" placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="is_default" class="col-sm-4 col-form-label">Is Default</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_default" v-model="map_form.is_default">
										<label class="custom-control-label" for="is_default"></label>
									</div>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_active" v-model="map_form.active">
										<label class="custom-control-label" for="is_active"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="updateScreen">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Manage map -->
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Maps",
        data() {
            return {
				map_form: {
                    id: '',
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
				},
                map_preview: '',
                add_record: true,
                edit_record: false,
            	dataFields: {
            		map_file: {
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
            	dataUrl: "/admin/site/manage-map/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Map',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'building.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Map',
            			name: 'Delete',
            			apiUrl: '/admin/site/screen/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'custom_delete',
						v_on: 'DeleteMap',
            		},
					link: {
            			title: 'Manage Maps',
            			name: 'Manage Maps',
            			apiUrl: '/admin/site/manage/map',
            			routeName: '',
            			button: '<i class="fa fa-map-marker" aria-hidden="true"></i> Manage',
            			method: 'link',
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Map',
						v_on: 'AddNewMap',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Map',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
        },

        methods: {
            mapFile: function(e) {
				const file = e.target.files[0];
				this.map_form.map_file = file;
			},

            mapChange: function(e) {
				const file = e.target.files[0];
                this.map_preview = URL.createObjectURL(file);
				this.map_form.map_preview = file;
			},

			AddNewMap: function() {
				this.add_record = true;
				this.edit_record = false;
                this.map_form.map_file = '';
                this.map_form.map_preview = '';
                this.map_form.position_x = '10.00';
                this.map_form.position_y = '0.20';
                this.map_form.position_z = '5.00';
                this.map_form.text_y_position = '4.00';
                this.map_form.default_zoom = '0.40';
                this.map_form.default_zoom_desktop = '0.40';
                this.map_form.default_zoom_mobile = '0.40';
                this.map_form.is_default = false;         
                this.map_preview = '';
				this.$refs.mapFile.value = null;
				this.$refs.mapPreview.value = null;
              	$('#map-form').modal('show');
            },

            storeMap: function() {
                let formData = new FormData();
                formData.append("map_file", this.map_form.map_file);
				formData.append("map_preview", this.map_form.map_preview);
				formData.append("position_x", this.map_form.position_x);
				formData.append("position_y", this.map_form.position_y);
				formData.append("position_z", this.map_form.position_z);
				formData.append("text_y_position", this.map_form.text_y_position);
				formData.append("default_zoom", this.map_form.default_zoom);
				formData.append("default_zoom_desktop", this.map_form.default_zoom_desktop);
				formData.append("default_zoom_mobile", this.map_form.default_zoom_mobile);
				formData.append("is_default", this.map_form.is_default);

                axios.post('/admin/brand/product/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#product-form').modal('hide');
				});

            },

			editMap: function(id) {
                this.GetBuildings();
                axios.get('/admin/site/screen/'+id)
                .then(response => {
                    var screen = response.data.data;
                    this.screen.id = screen.id;
                    this.screen.site_building_id = screen.site_building_id;

                    this.getFloorLevel(screen.site_building_id);

                    this.screen.site_building_level_id = screen.site_building_level_id;
                    this.screen.site_point_id = screen.site_point_id;
                    this.screen.screen_type = screen.screen_type;
                    this.screen.name = screen.name;
					this.add_record = false;
					this.edit_record = true;
                    $('#map-form').modal('show');
                });
            },

            updateScreen: function() {
                let formData = new FormData();
                this.map_form.map_file = map_form.map_details.map_file;
                this.map_form.map_preview = map_form.map_details.map_preview;
                this.map_form.position_x = map_form.map_details.position_x;
                this.map_form.position_y = map_form.map_details.position_y;
                this.map_form.position_z = map_form.map_details.position_z;
                this.map_form.text_y_position = map_form.map_details.text_y_position;
                this.map_form.default_zoom = map_form.map_details.default_zoom;
                this.map_form.default_zoom_desktop = map_form.map_details.default_zoom_desktop;
                this.map_form.default_zoom_mobile = map_form.map_details.default_zoom_mobile;
                this.map_form.is_default = map_form.map_details.is_default;

                axios.post('/admin/brand/product/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#product-form').modal('hide');
				});

            },

        },

        components: {
        	Table
 	    }
    };
</script> 
