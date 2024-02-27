<template>
	<div>
		<!-- Main content -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"><i class="nav-icon fas fa-map"></i>&nbsp;&nbsp;Maps
							<a type="button" href="/portal/maps" class="btn btn-outline-primary btn-sm float-end"><i
									class="fas fa-arrow-left"></i>&nbsp;Back to Screens Maps</a>
						</h4>
					</div>
					<div class="card-body">
						<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
							:primaryKey="primaryKey" v-on:AddNewMap="AddNewMap" v-on:GenRoutes="GenRoutes"
							v-on:editButton="editMap" v-on:DefaultMap="DefaultMap" ref="dataTable">
						</Table>
					</div>
				</div>
			</div>
		</div>
		<!-- /.row -->

		<!-- Manage map -->
		<div class="modal fade" id="map-form" tabindex="-1" aria-labelledby="map-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Map</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Map</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="map_form.site_building_id"
										@change="getFloorLevel($event.target.value)">
										<option value="">Select Building</option>
										<option v-for="building in buildings" :value="building.id"> {{ building.name }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Floor <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="map_form.site_building_level_id">
										<option value="">Select Floor</option>
										<option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Map File <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="file" id="img_map_file" ref="mapFile" @change="mapFile">
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Map Preview <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" id="img_map_preview" accept="image/*" ref="mapPreview"
										@change="mapChange">
									<footer class="blockquote-footer">image max size is 1250 x 600 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="map_preview" :src="map_preview" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position X <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.position_x" placeholder="0.00"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position Y <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.position_y" placeholder="0.00"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Position Z <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.position_z" placeholder="0.00"
										required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Text Y Position <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.text_y_position"
										placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Default Zoom <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom"
										placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Desktop Default Zoom <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom_desktop"
										placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Mobile Default Zoom <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="map_form.default_zoom_mobile"
										placeholder="0.00" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="is_default" class="col-sm-4 col-form-label">Is Default</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_default"
											v-model="map_form.is_default">
										<label class="custom-control-label" for="is_default"></label>
									</div>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_active"
											v-model="map_form.active">
										<label class="custom-control-label" for="is_active"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeMap">Add New
							Map</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateMap">Save
							Changes</button>
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
		<div class="modal" id="errorModal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="alert alert-block alert-danger">
							<p>{{ error_message }}</p>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
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
		},
		site_screen_id: {
			type: Number,
			required: true
		},
	},
	data() {
		return {
			map_form: {
				id: '',
				site_building_id: '',
				site_building_level_id: '',
				map_file: '',
				map_preview: '',
				position_x: '10.00',
				position_y: '0.20',
				position_z: '5.00',
				text_y_position: '4.00',
				default_zoom: '0.40',
				default_zoom_desktop: '0.40',
				default_zoom_mobile: '0.40',
				active: '',
				is_default: '',
			},
			map_preview: '',
			add_record: true,
			edit_record: false,
			image_width: 0,
			image_height: 0,
			error_message: '',
			buildings: [],
			floors: [],
			map_default_id: '',
			dataFields: {
				map_file_path: {
					name: "Map Preview",
					type: "logo",
				},
				site_name: "Site Name",
				building_name: "Building Name",
				floor_name: "Floor Name",
				active: {
					name: "Status",
					type: "Boolean",
					status: {
						0: '<span class="badge bg-danger">Inactive</span>',
						1: '<span class="badge bg-info">Active</span>'
					}
				},
				is_default: {
					name: "Is Default",
					type: "Boolean",
					status: {
						0: '<span class="badge bg-danger">No</span>',
						1: '<span class="badge bg-info">Yes</span>'
					}
				},
				updated_at: "Last Updated"
			},
			primaryKey: "id",
			dataUrl: "/portal/manage-map/list/" + this.site_screen_id,
			actionButtons: {
				link: {
					title: 'Edit Maps',
					name: 'Manage Maps',
					apiUrl: '/portal/map',
					routeName: '',
					button: '<i class="fas fa-edit"></i> Edit Map',
					method: 'link',
				},
			},
		};
	},

	created() {
	},

	methods: {
		GetBuildings: function () {
			axios.get('/admin/site/get-buildings/' + this.site_id)
				.then(response => this.buildings = response.data.data);
		},

		getFloorLevel: function (id) {
			axios.get('/admin/site/floors/' + id)
				.then(response => this.floors = response.data.data);
		},

		mapFile: function (e) {
			const file = e.target.files[0];
			this.map_form.map_file = file;
		},

		mapChange: function (e) {
			const file = e.target.files[0];
			this.map_preview = URL.createObjectURL(file);
			this.map_form.map_preview = file;
		},

		AddNewMap: function () {
			this.GetBuildings();
			this.add_record = true;
			this.edit_record = false;
			this.map_form.site_building_id = '';
			this.map_form.site_building_level_id = '';
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

		storeMap: function () {
			let formData = new FormData();
			formData.append("site_id", this.site_id);
			formData.append("site_building_id", this.map_form.site_building_id);
			formData.append("site_building_level_id", this.map_form.site_building_level_id);
			formData.append("site_screen_id", this.site_screen_id);
			formData.append("map_file", this.map_form.map_file);
			formData.append("map_preview", this.map_form.map_preview);
			formData.append("position_x", this.map_form.position_x);
			formData.append("position_y", this.map_form.position_y);
			formData.append("position_z", this.map_form.position_z);
			formData.append("text_y_position", this.map_form.text_y_position);
			formData.append("default_zoom", this.map_form.default_zoom);
			formData.append("default_zoom_desktop", this.map_form.default_zoom_desktop);
			formData.append("default_zoom_mobile", this.map_form.default_zoom_mobile);
			formData.append("active", this.map_form.active);
			formData.append("is_default", this.map_form.is_default);

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

		editMap: function (id) {
			this.GetBuildings();
			axios.get('/admin/site/manage-map/details/' + id)
				.then(response => {
					var site_map = response.data.data;
					this.map_form.id = site_map.id;
					this.map_form.site_building_id = site_map.site_building_id;
					this.getFloorLevel(site_map.site_building_id);

					this.map_form.site_building_level_id = site_map.site_building_level_id;
					this.map_form.position_x = site_map.position_x;
					this.map_form.position_y = site_map.position_y;
					this.map_form.position_z = site_map.position_z;
					this.map_form.text_y_position = site_map.text_y_position;
					this.map_form.default_zoom = site_map.default_zoom;
					this.map_form.default_zoom_desktop = site_map.default_zoom_desktop;
					this.map_form.default_zoom_mobile = site_map.default_zoom_mobile;
					this.map_form.active = site_map.active;
					this.map_form.is_default = site_map.is_default;
					this.map_preview = site_map.map_preview_path;
					this.$refs.mapFile.value = null;
					this.$refs.mapPreview.value = null;
					this.add_record = false;
					this.edit_record = true;
					$('#map-form').modal('show');
				});
		},

		updateMap: function () {
			let formData = new FormData();
			formData.append("id", this.map_form.id);
			formData.append("site_id", this.site_id);
			formData.append("site_building_id", this.map_form.site_building_id);
			formData.append("site_building_level_id", this.map_form.site_building_level_id);
			formData.append("site_screen_id", this.site_screen_id);
			formData.append("map_file", this.map_form.map_file);
			formData.append("map_preview", this.map_form.map_preview);
			formData.append("position_x", this.map_form.position_x);
			formData.append("position_y", this.map_form.position_y);
			formData.append("position_z", this.map_form.position_z);
			formData.append("text_y_position", this.map_form.text_y_position);
			formData.append("default_zoom", this.map_form.default_zoom);
			formData.append("default_zoom_desktop", this.map_form.default_zoom_desktop);
			formData.append("default_zoom_mobile", this.map_form.default_zoom_mobile);
			formData.append("active", this.map_form.active);
			formData.append("is_default", this.map_form.is_default);

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

		DefaultMap: function (data) {
			this.map_default_id = data.id;
			$('#confirmModal').modal('show');
		},

		setDefault: function () {
			axios.get('/admin/site/map/set-default/' + this.map_default_id)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#confirmModal').modal('hide');
				})
		},

		GenRoutes: function () {
			axios.get('/admin/site/map/generate-routes/' + this.site_id + '/' + this.site_screen_id)
				.then(response => {
					toastr.success(response.data.message);
				})
		},

	},

	components: {
		Table
	}
};
</script> 
