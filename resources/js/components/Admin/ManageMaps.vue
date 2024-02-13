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
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Map</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Map</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="map_form.site_building_id" @change="getFloorLevel($event.target.value)">
									    <option value="">Select Building</option>
									    <option v-for="building in buildings" :value="building.id"> {{ building.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Floor <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="map_form.site_building_level_id">
									    <option value="">Select Floor</option>
									    <option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Map Type <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="map_form.map_type">
									    <option value="">Select Map Type</option>
									    <option value="2D"> 2D</option>
									    <option value="3D"> 3D</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Zoom <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom" placeholder="0.00" required>
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
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeMap">Add New Map</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateMap">Save Changes</button>
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
                    site_building_id: '',
                    site_building_level_id: '',
					map_type: '',
					default_zoom: '',
					map_file: '',
					map_preview: '',
					active: '',
				},
                map_preview: '',
                add_record: true,
                edit_record: false,
				buildings: [],
                floors: [],
				map_default_id: '',
            	dataFields: {
            		map_preview_path: {
            			name: "Map Preview", 
            			type:"logo", 
            		},
					building_name: "Building Name",
					floor_name: "Floor Name",
					map_type: "Map Type",
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
            	dataUrl: "/admin/site/manage-map/list/"+this.site_id,
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
            			apiUrl: '/admin/site/manage-map/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete',
            		},
					link: {
            			title: 'Manage Maps',
            			name: 'Manage Maps',
            			apiUrl: '/admin/site/map',
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
			GetBuildings: function() {
				axios.get('/admin/site/get-buildings/'+this.site_id)
                .then(response => this.buildings = response.data.data);
			},

            getFloorLevel: function(id) {
				axios.get('/admin/site/floors/'+id)
                .then(response => this.floors = response.data.data);
            },

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
				this.GetBuildings();
				this.add_record = true;
				this.edit_record = false;
                this.map_form.site_building_id = '';
                this.map_form.site_building_level_id = '';
                this.map_form.default_zoom = '';				
                this.map_form.map_type = '';
				this.map_form.map_file = '';
                this.map_form.map_preview = '';      
                this.map_preview = '';
				this.$refs.mapFile.value = null;
				this.$refs.mapPreview.value = null;
              	$('#map-form').modal('show');
            },

            storeMap: function() {
                let formData = new FormData();
                formData.append("site_id", this.site_id);
                formData.append("site_building_id", this.map_form.site_building_id);
                formData.append("site_building_level_id", this.map_form.site_building_level_id);
                formData.append("site_screen_id", this.site_screen_id);
                formData.append("default_zoom", this.map_form.default_zoom);				
				formData.append("map_type", this.map_form.map_type);
				formData.append("map_file", this.map_form.map_file);
				formData.append("map_preview", this.map_form.map_preview);
				formData.append("active", this.map_form.active);

                axios.post('/admin/site/manage-map/store', formData, {
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

			editMap: function(id) {
                this.GetBuildings();
                axios.get('/admin/site/manage-map/details/'+id)
                .then(response => {
                    var site_map = response.data.data;
                    this.map_form.id = site_map.id;
                    this.map_form.site_building_id = site_map.site_building_id;
                    this.getFloorLevel(site_map.site_building_id);
                    this.map_form.site_building_level_id = site_map.site_building_level_id;
					this.map_form.map_type = site_map.map_type;
					this.map_form.default_zoom = site_map.default_zoom;
					this.map_form.active = site_map.active;  
					this.map_preview = site_map.map_preview_path; 
					this.$refs.mapFile.value = null;
					this.$refs.mapPreview.value = null;
					this.add_record = false;
					this.edit_record = true;
                    $('#map-form').modal('show');
                });
            },

			updateMap: function() {
				let formData = new FormData();
                formData.append("id", this.map_form.id);
                formData.append("site_id", this.site_id);
                formData.append("site_building_id", this.map_form.site_building_id);
                formData.append("site_building_level_id", this.map_form.site_building_level_id);
                formData.append("site_screen_id", this.site_screen_id);
				formData.append("map_type", this.map_form.map_type);
                formData.append("default_zoom", this.map_form.default_zoom);				
				formData.append("map_file", this.map_form.map_file);
				formData.append("map_preview", this.map_form.map_preview);
				formData.append("active", this.map_form.active);

                axios.post('/admin/site/manage-map/update', formData, {
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

        },

        components: {
        	Table
 	    }
    };
</script> 
