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
                                    v-on:AddNewEvent="AddNewEvent"
									v-on:editButton="editEvent"
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
		<div class="modal fade" id="event-form" data-backdrop="static" tabindex="-1" aria-labelledby="event-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Event</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Event</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Banner <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
									<input type="file" accept="image/*" ref="fileImgBanner" @change="bannerChange">
									<footer class="blockquote-footer">image max size is 355 x 660 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<img v-if="imgBanner" :src="imgBanner" class="img-thumbnail" />
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="event.site_id">
										<option value="">Select Site</option>
										<option v-for="site in site_list" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Event<span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="event.event_name" placeholder="Event Name" maxlength="28" required>
								</div>
							</div>
							<!-- <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Location <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="event.location" placeholder="Location" required>
								</div>
							</div> -->
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Event Date <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="event.event_date" placeholder="Event Date" maxlength="28" required>
								</div>
							</div>
                            <div class="form-group row">
                                <label for="userName" class="col-sm-4 col-form-label">Start Date</label>
                                <div class="col-sm-8">
                                    <date-picker v-model="event.start_date" placeholder="YYYY-MM-DD" :config="options" id="date_from" autocomplete="off"></date-picker>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="userName" class="col-sm-4 col-form-label">End Date</label>
                                <div class="col-sm-8">
                                    <date-picker v-model="event.end_date" placeholder="YYYY-MM-DD" :config="options" id="date_to" autocomplete="off"></date-picker>
                                </div>
                            </div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active"
											v-model="event.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record"
								@click="storeEvent">Add New Event</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record"
								@click="updateEvent">Save Changes</button>
						</div>
						<!-- /.card-body -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->

	</div>
</template>
<script>
import Table from '../Helpers/Table';
import datePicker from 'vue-bootstrap-datetimepicker';    
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

export default {
	name: "Events",
	data() {
		return {
			event: {
				id: '',
				site_id: null,
				event_name: '',
				location: '',
				event_date: '',
				image_url: '/images/no-image-available.png',
				start_date: '',
				end_date: '',
				active: false,
			},
			imgBanner: '',
            site_list: [],
            options: {
				format: 'YYYY/MM/DD',
				useCurrent: false,
			},
			add_record: true,
			edit_record: false,            
			dataFields: {
				image_url_path: {
					name: "Banner",
					type: "logo",
				},
				site_name: "Site Name",
				event_name: "Event Name",
				location: "Location",
				start_date: "Start Date",
				end_date: "End Date",
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
			dataUrl: "/admin/event/list",
			actionButtons: {
				edit: {
					title: 'Edit this Event',
					name: 'Edit',
					apiUrl: '',
					routeName: 'event.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Event',
					name: 'Delete',
					apiUrl: '/admin/event/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Event',
					v_on: 'AddNewEvent',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Event',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			},
		};
	},

	created() {
        this.getSites();
	},

	methods: {
        getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.site_list = response.data.data);
		},

		bannerChange: function (e) {
			const file = e.target.files[0];
			this.imgBanner = URL.createObjectURL(file);
			this.event.image_url = file;
		},

		AddNewEvent: function () {
			this.add_record = true;
			this.edit_record = false;
			this.event.site_id = '';
			this.event.event_name = '';
			this.event.location = '';
			this.event.event_date = '';
			this.event.start_date = '';
			this.event.end_date = '';
			this.event.imgBanner = '';
			this.imgBanner = '/images/no-image-available.png';
			this.event.active = false;
			this.$refs.fileImgBanner.value = null;
			$('#event-form').modal('show');
		},

		storeEvent: function () {
			let formData = new FormData();
			formData.append("site_id", this.event.site_id);
			formData.append("event_name", this.event.event_name);
			formData.append("location", this.event.location);
			formData.append("event_date", this.event.event_date);
			formData.append("start_date", this.event.start_date);
			formData.append("end_date", this.event.end_date);
			formData.append("imgBanner", this.event.image_url);

			axios.post('/admin/event/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
            .then(response => {
                toastr.success(response.data.message);
                this.$refs.dataTable.fetchData();
                $('#event-form').modal('hide');
            })
		},

		editEvent: function (id) {
			axios.get('/admin/event/' + id)
				.then(response => {
					var event = response.data.data;
					this.event.id = id;
					this.event.site_id = event.site_id;
                    this.event.event_name = event.event_name;
                    this.event.location = event.location;
                    this.event.event_date = event.event_date;
                    this.event.start_date = event.start_date;
                    this.event.end_date = event.end_date;
                    this.imgBanner = event.image_url_path;
					this.event.image_url = '',
                    this.event.active = event.active;
                    this.$refs.fileImgBanner.value = '';

					this.add_record = false;
					this.edit_record = true;
					$('#event-form').modal('show');
				});
		},

		updateEvent: function () {
			console.log(this.event.image_url);
			let updateFormData = new FormData();
			updateFormData.append("id", this.event.id);
			updateFormData.append("site_id", this.event.site_id);
			updateFormData.append("event_name", this.event.event_name);
			updateFormData.append("location", this.event.location);
			updateFormData.append("event_date", this.event.event_date);
			updateFormData.append("start_date", this.event.start_date);
			updateFormData.append("end_date", this.event.end_date);
			updateFormData.append("imgBanner", this.event.image_url);
			updateFormData.append("active", this.event.active);

			axios.post('/admin/event/update', updateFormData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
            .then(response => {
                toastr.success(response.data.message);
                this.$refs.dataTable.fetchData();
                $('#event-form').modal('hide');
            })
        },
	},

	components: {
		Table,
        datePicker
	}
};
</script> 
