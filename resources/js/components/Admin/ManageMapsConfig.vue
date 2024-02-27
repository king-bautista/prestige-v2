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
						v-on:AddNewMapConfig="AddNewMapConfig"
						v-on:editButton="editMapConfig"
						v-on:DefaultMap="DefaultMap"
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

        <!-- Manage map -->
		<div class="modal fade" id="map-form" tabindex="-1" aria-labelledby="map-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Map Config</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Map Config</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="row" v-if="!map_form.map_details">
								<div class="col-sm-12">
									<Table 
										:dataFields="mapDataFields" 
										:dataUrl="mapDataUrl"
										:actionButtons="mapActionButtons" 
										:primaryKey="mapPrimaryKey"
										v-on:editButton="selectedMap"
										ref="mapdataTable">
									</Table>
								</div>
							</div>
							<div v-if="map_form.map_details">
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Map Preview <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8 text-center" id="ad-holder">
										<img v-if="map_form.map_details.map_preview_path" :src="map_form.map_details.map_preview_path" class="img-thumbnail" />
										<div class="edit-button"><a @click="map_form.map_details = null" class="bg-success"><i class="fas fa-edit"></i> CHANGE </a></div>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Site Name</label>
									<div class="col-sm-8">
										{{ map_form.map_details.site_name }}
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Screen Location</label>
									<div class="col-sm-8">
										{{ map_form.map_details.building_name }}, {{ map_form.map_details.building_floor_name }}
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Map Type</label>
									<div class="col-sm-8">
										{{ map_form.map_details.map_type }}
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Screen <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<select class="custom-select" v-model="map_form.site_screen_id">
											<option value="">Select Screen</option>
											<option v-for="screen in screens" :value="screen.id">{{ screen.serial_number }} - {{ screen.name }}</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Origin Point <span class="font-italic text-danger"> *</span></label>
									<div class="col-sm-8">
										<multiselect v-model="map_form.origin_point" track-by="origin_name" label="origin_name"
											placeholder="Select Origin Point" :options="tenants" :searchable="true"
											:allow-empty="false" :loading="isLoading" @search-change="getTenants">
										</multiselect>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '2D'">
									<label for="firstName" class="col-sm-4 col-form-label">Start Scale</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.start_scale" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '2D'">
									<label for="firstName" class="col-sm-4 col-form-label">Start X</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.start_x" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '2D'">
									<label for="firstName" class="col-sm-4 col-form-label">Start Y</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.start_y" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="firstName" class="col-sm-4 col-form-label">Default Zoom</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.default_zoom" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '2D'">
									<label for="firstName" class="col-sm-4 col-form-label">Default X</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.default_x" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '2D'">
									<label for="firstName" class="col-sm-4 col-form-label">Default Y</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.default_y" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Name Angle</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.tilt_angle" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">View Angle</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.view_angle" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Building Label Height</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.building_label_height" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Building Label Space</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.building_label_space" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Building Animation Height</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.building_animation_height" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Floor Label Height</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.floor_label_height" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Floor Label Space</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.floor_label_space" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Floor Animation Height</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.floor_animation_height" placeholder="0.00" required>
									</div>
								</div>
								<div class="form-group row" v-if="map_form.map_details.map_type == '3D'">
									<label for="firstName" class="col-sm-4 col-form-label">Player Speed</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="map_form.player_speed" placeholder="0.00" required>
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
								<div class="form-group row" v-show="edit_record">
									<label for="active" class="col-sm-4 col-form-label">Is Default</label>
									<div class="col-sm-8">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" id="is_default" v-model="map_form.is_default">
											<label class="custom-control-label" for="is_default"></label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeMap">Add New Map Config</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateMap">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Manage map -->

		<!-- Confirm modal -->
		<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to set this map as default?</h6>
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
	import Multiselect from 'vue-multiselect';

	export default {
        name: "ManageMaps",
		props: {
        	site_id: {
        		type: Number,
        		required: true
        	}
        },
        data() {
            return {
				map_form: {
                    id: '',
                    map_details: '',
                    site_screen_id: '',
					origin_point: '',
					start_scale: '',
					start_x: '',
					start_y: '',
					default_zoom: '',
					default_x: '',
					default_y: '',
					tilt_angle: '',
					view_angle: '',
					building_label_height: '',
					building_label_space: '',
					building_animation_height: '',
					floor_label_height: '',
					floor_label_space: '',
					floor_animation_height: '',
					player_speed: 0.6,
					active: '',
					is_default: '',
				},
				screens: [],
				tenants: [],
                add_record: true,
                edit_record: false,
            	dataFields: {            
					serial_number: "ID",		
					site_screen_name: "Site Screen Name",
					site_screen_location: "Screen Location",
					map_preview_path: {
            			name: "Map Preview", 
            			type:"logo", 
            		},
					map_type: "Map Type",
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Inactive</span>', 
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
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/site/manage-config/list/"+this.site_id,
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
            			apiUrl: '/admin/site/manage-config/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete',
            		},
					view: {
            			title: 'Set as Default',
            			name: 'Set as Default',
            			apiUrl: '',
            			routeName: '',
            			button: '<i class="fa fa-tag"></i> Set as Default',
            			method: 'view',
						v_on: 'DefaultMap',
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'Create Map Config',
						v_on: 'AddNewMapConfig',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> Create Map Config',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
					genRoutes: {
						title: 'Generate Routes',
						v_on: 'GenRoutes',
						icon: '<i class="fab fa-connectdevelop"></i> Generate Routes',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},

				mapDataFields: {
					map_preview_path: {
            			name: "Map Preview", 
            			type:"logo", 
            		},
					building_name: "Building Name",
					floor_name: "Floor Name",
					map_type: "Map Type",
				},
				mapPrimaryKey: "id",
				mapDataUrl: "/admin/site/manage-map/list/"+this.site_id,
				mapActionButtons: {
					edit: {
						title: 'Add',
						name: 'Edit',
						apiUrl: '',
						routeName: 'content.edit',
						button: '<i class="far fa-check-circle"></i> Add',
						method: 'view'
					},
				},
            };
        },

        created(){
			this.getScreens();
        },

        methods: {
			getScreens: function (id) {
				axios.get('/admin/site/screen/get-screens/'+this.site_id)
					.then(response => {
						this.screens = response.data.data
					});
			},

			AddNewMapConfig: function() {
				this.add_record = true;
				this.edit_record = false;
                this.map_form.map_details = '';
				this.map_form.site_screen_id = '';
				this.map_form.origin_point = '';
				this.map_form.start_scale = '';
				this.map_form.start_x = '';
				this.map_form.start_y = '';
				this.map_form.default_zoom = '';
				this.map_form.default_x = '';
				this.map_form.default_y = '';
				this.map_form.tilt_angle = '';
				this.map_form.view_angle = '';
				this.map_form.building_label_height = '';
				this.map_form.building_label_space = '';
				this.map_form.building_animation_height = '';
				this.map_form.floor_label_height = '';
				this.map_form.floor_label_space = '';
				this.map_form.floor_animation_height = '';
				this.map_form.player_speed = 0.6;
				this.map_form.active = true;
				this.map_form.is_default = false;
              	$('#map-form').modal('show');
            },

            storeMap: function() {
                let formData = new FormData();
                formData.append("map_details", this.map_form.map_details.id);
                formData.append("site_screen_id", this.map_form.site_screen_id);
				formData.append("origin_point", JSON.stringify(this.map_form.origin_point));
                formData.append("start_scale", this.map_form.start_scale);
                formData.append("start_x", this.start_x);
				formData.append("start_y", this.map_form.start_y);
				formData.append("default_zoom", this.map_form.default_zoom);
				formData.append("default_x", this.map_form.default_x);
				formData.append("default_y", this.map_form.default_y);
				formData.append("tilt_angle", this.map_form.tilt_angle);
				formData.append("view_angle", this.map_form.view_angle);
				formData.append("building_label_height", this.map_form.building_label_height);
				formData.append("building_label_space", this.map_form.building_label_space);
				formData.append("building_animation_height", this.map_form.building_animation_height);
				formData.append("floor_label_height", this.map_form.floor_label_height);
				formData.append("floor_label_space", this.map_form.floor_label_space);
				formData.append("floor_animation_height", this.map_form.floor_animation_height);
				formData.append("player_speed", this.map_form.player_speed);
				formData.append("active", this.map_form.active);
				formData.append("is_default", this.map_form.is_default);

                axios.post('/admin/site/manage-config/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#map-form').modal('hide');
				});

            },

			editMapConfig: function(id) {
                axios.get('/admin/site/manage-config/details/'+id)
                .then(response => {
                    var site_config = response.data.data;
                    this.map_form.id = site_config.id;
                    this.map_form.map_details = site_config.map_details;
					this.map_form.site_screen_id = site_config.site_screen_id;
					this.map_form.origin_point = site_config.site_point_details;
					this.map_form.start_scale = site_config.start_scale;
					this.map_form.start_x = site_config.start_x;
					this.map_form.start_y = site_config.start_y;
					this.map_form.default_zoom = site_config.default_zoom;
					this.map_form.default_x = site_config.default_x;
					this.map_form.default_y = site_config.default_y;
					this.map_form.tilt_angle = site_config.tilt_angle;
					this.map_form.view_angle = site_config.view_angle;
					this.map_form.building_label_height = site_config.building_label_height;
					this.map_form.building_label_space = site_config.building_label_space;
					this.map_form.building_animation_height = site_config.building_animation_height;
					this.map_form.floor_label_height = site_config.floor_label_height;
					this.map_form.floor_label_space = site_config.floor_label_space;
					this.map_form.floor_animation_height = site_config.floor_animation_height;
					this.map_form.player_speed = site_config.player_speed;
					this.map_form.active = site_config.active;
					this.map_form.is_default = site_config.is_default;
					this.add_record = false;
					this.edit_record = true;
                    $('#map-form').modal('show');
                });
            },

			updateMap: function() {
				let formData = new FormData();
                formData.append("id", this.map_form.id);
                formData.append("map_details", this.map_form.map_details.id);
                formData.append("site_screen_id", this.map_form.site_screen_id);
				formData.append("origin_point", JSON.stringify(this.map_form.origin_point));
                formData.append("start_scale", this.map_form.start_scale);
                formData.append("start_x", this.start_x);
				formData.append("start_y", this.map_form.start_y);
				formData.append("default_zoom", this.map_form.default_zoom);
				formData.append("default_x", this.map_form.default_x);
				formData.append("default_y", this.map_form.default_y);
				formData.append("tilt_angle", this.map_form.tilt_angle);
				formData.append("view_angle", this.map_form.view_angle);
				formData.append("building_label_height", this.map_form.building_label_height);
				formData.append("building_label_space", this.map_form.building_label_space);
				formData.append("building_animation_height", this.map_form.building_animation_height);
				formData.append("floor_label_height", this.map_form.floor_label_height);
				formData.append("floor_label_space", this.map_form.floor_label_space);
				formData.append("floor_animation_height", this.map_form.floor_animation_height);
				formData.append("player_speed", this.map_form.player_speed);
				formData.append("active", this.map_form.active);
				formData.append("is_default", this.map_form.is_default);

                axios.post('/admin/site/manage-config/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#map-form').modal('hide');
				})
            },

			DefaultMap: function(data) {
				this.map_default_id = data.id;
				$('#confirmModal').modal('show');
			},

			setDefault: function() {
				axios.get('/admin/site/manage-config/set-default/'+this.map_default_id)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#confirmModal').modal('hide');
				})
			},

			GenRoutes: function() {
				axios.get('/admin/site/map/generate-routes/'+this.site_id+'/'+this.site_screen_id)
				.then(response => {
					toastr.success(response.data.message);
				})
			},

			selectedMap: function (data) {
				this.map_form.map_details = data;
				console.log(this.map_form.map_details);
			},

			getTenants: function (query) {
				this.isLoading = true;
				axios.get('/admin/site/manage-config/origin-point', {
					params: {
						search: query,
						map_details: JSON.stringify(this.map_form.map_details)
					}
				})
				.then(response => {
					this.tenants = response.data.data;
					this.isLoading = false;
				});
			},

        },

        components: {
        	Table,
			Multiselect,
 	    }
    };
</script> 

<style scoped>
#ad-holder {
	position: relative;
}

.edit-button {
	position: absolute;
	width: 100%;
	left: 0;
	top: 45%;
	text-align: center;
	opacity: 0;
	transition: opacity .35s ease;
}

.edit-button a {
	width: 200px;
	padding: 12px 16px;
	text-align: center;
	color: white;
	border: solid 2px white;
	border-radius: 5px;
	z-index: 1;
}

#ad-holder:hover .edit-button {
	opacity: 1;
}
</style>
