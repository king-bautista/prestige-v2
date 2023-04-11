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
									:otherButtons="otherButtons" :primaryKey="primaryKey"
									v-on:AddNewAmenities="AddNewAmenities" v-on:editButton="editAmenities"
									v-on:downloadCsv="downloadCsv" ref="dataTable">
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
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Amenities</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Amenities</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Amenity Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="amenity.name"
										placeholder="Amenities Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Icon <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" accept="image/*" ref="icon" @change="IconChange">
									<footer class="blockquote-footer">image max size is 170 x 170 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="icon" :src="icon" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="amenity.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeAmenities">Add New
							Amenities</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateAmenities">Save
							Changes</button>
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
				icon: '/images/no-image-available.png',
				active: false,
			},
			parent_links: [],
			icon: '',
			add_record: true,
			edit_record: false,
			dataFields: {
				name: "Name",
				icon_path: {
					name: "Icon",
					type: "logo",
				},
				active: {
					name: "Status",
					type: "Boolean",
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
				download: {
					title: 'Download',
					v_on: 'downloadCsv',
					icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			}
		};
	},

	created() {
	},

	methods: {
		IconChange: function (e) {
			const file = e.target.files[0];
			this.icon = URL.createObjectURL(file);
			this.amenity.icon = file;
		},

		AddNewAmenities: function () {
			this.add_record = true;
			this.edit_record = false;
			this.amenity.name = '';
			this.amenity.icon = '/images/no-image-available.png';
			this.amenity.active = false;
			$('#amenities-form').modal('show');
		},

		storeAmenities: function () {
			let formData = new FormData();
			formData.append("name", this.amenity.name);
			formData.append("icon", this.amenity.icon);

			axios.post('/admin/amenity/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#amenities-form').modal('hide');
				})
		},

		editAmenities: function (id) {
			axios.get('/admin/amenity/' + id)
				.then(response => {
					var amenity = response.data.data;
					this.amenity.id = id;
					this.amenity.name = amenity.name;
					this.amenity.active = amenity.active;
					if (amenity.icon) {
						this.icon = amenity.icon_path;
					}
					else {
						this.icon = this.amenity.icon;
					}
					this.$refs.icon.value = null;
					this.add_record = false;
					this.edit_record = true;
					$('#amenities-form').modal('show');
				});
		},

		updateAmenities: function () {
			let formData = new FormData();
			formData.append("id", this.amenity.id);
			formData.append("name", this.amenity.name);
			formData.append("icon", this.amenity.icon);

			axios.post('/admin/amenity/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#amenities-form').modal('hide');
				})
		},

		downloadCsv: function () {
			axios.get('/admin/amenity/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

	},

	components: {
		Table
	}
};
</script> 
