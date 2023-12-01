<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fa fa-building"></i>&nbsp;&nbsp;Property Details</h4>
						<h4 v-show="data_form"><i class="nav-icon fas fa-user-edit"></i> Edit Site</h4>
					</div>
					<div class="card-body" v-show="data_list">
						<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
                        :primaryKey="primaryKey"
						v-on:editButton="editSite"
                        ref="dataTable">
			          	</Table>
					</div>
					<div class="card-body" v-show="data_form">
						<div class="form-group row">
							<img :src="site.site_banner_path" />
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Site Name <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="site.name" placeholder="Site Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="lastName" class="col-sm-3 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" v-model="site.descriptions" placeholder="Descriptions" rows="8"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Facebook</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="site.facebook" placeholder="Facebook link">
							</div>
						</div>       
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Instagram</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="site.instagram" placeholder="Instagram link">
							</div>
						</div>   
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Twitter</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="site.twitter" placeholder="Twitter link">
							</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Website</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" v-model="site.website" placeholder="Website">
							</div>
						</div>          
						<hr/>      
						<div class="form-group row">
							<label for="is_subscriber" class="col-sm-3 col-form-label">Operational Hours</label>
							<div class="col-sm-9">
								<div class="row mb-3 mx-0" v-for="(operational, index)  in site.operational_hours">
									<div class="col-9 d-flex">
										<div class="btn-group-toggle mr-2" data-toggle="buttons">
											<label v-bind:class="conditionActive(operational.schedules, 'Sun', index)"
												@click="getChecked('Sun', index)">SU</label>
											<label v-bind:class="conditionActive(operational.schedules, 'Mon', index)"
												@click="getChecked('Mon', index)">M</label>
											<label v-bind:class="conditionActive(operational.schedules, 'Tue', index)"
												@click="getChecked('Tue', index)">T</label>
											<label v-bind:class="conditionActive(operational.schedules, 'Wed', index)"
												@click="getChecked('Wed', index)">W</label>
											<label v-bind:class="conditionActive(operational.schedules, 'Thu', index)"
												@click="getChecked('Thu', index)">TH</label>
											<label v-bind:class="conditionActive(operational.schedules, 'Fri', index)"
												@click="getChecked('Fri', index)">F</label>
											<label v-bind:class="conditionActive(operational.schedules, 'Sat', index)"
												@click="getChecked('Sat', index)">S</label>
										</div>
										<input type="time" v-model="operational.start_time" class="form-control ml-1 time mr-2" style="width: 120px; height: 34px; margin: 0 5px 0 5px;">
										<p class="m-0 pt-2">to</p>
										<input type="time" v-model="operational.end_time" class="form-control time ml-2" style="width: 120px; height: 34px; margin: 0 5px 0 5px;">
									</div>
									<div class="col-3">
										<i>{{ operational.schedules }} <span v-if="operational.start_time">|</span> {{operational.start_time }} <span v-if="operational.end_time">to</span> {{ operational.end_time }}</i>
									</div>
								</div>
								<div class="form-group">
									<div class="col-12">
										<button class="btn btn-link" style="padding-left:0px"
											@click="addOperationalHours">Add Hours +</button>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
								<button type="button" class="btn btn-primary btn-sm" @click="updateSite">Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
    </div>
</template>
<script> 
	var schedules = [];
	import Table from '../Helpers/Table';
	// Import this component
    import datePicker from 'vue-bootstrap-datetimepicker';    
    // Import date picker css
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
	
	export default {
        name: "Sites",
        data() {
            return {
				site: {
					id: '',
                    name: '',
					descriptions: '',
					facebook: '',
					instagram: '',
					twitter: '',
					time_from: '',
					time_to: '',
					website: '',
					active: false,
					operational_hours: [],
					site_banner_path: '',
				},
                data_list: true,
				data_form: false,
				is_default: '',
				options: {
                    format: 'hh:mm A',
                    useCurrent: false,
                },
            	dataFields: {
            		name: "Name", 
            		descriptions_ellipsis: "Descriptions", 
                    site_logo_path: {
            			name: "Logo", 
            			type:"image", 
            		},
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge bg-danger">Deactivated</span>', 
            				1: '<span class="badge bg-info text-dark">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/property-details/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Site',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'brand.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
					link: {
            			title: 'Manage Site',
            			name: 'Link',
            			apiUrl: '/portal/property-details/buildings',
            			routeName: '',
            			button: '<i class="fa fa-link"></i> Manage Site',
            			method: 'link'
            		},
            	},
            };
        },

        created(){

        },

        methods: {
			editSite: function(id) {
                axios.get('/portal/property-details/'+id)
                .then(response => {
                    var site = response.data.data;
					schedules = [];

                    this.site.id = id;
                    this.site.name = site.name;
                    this.site.descriptions = site.descriptions;
                    this.site.facebook = site.details.facebook;
                    this.site.instagram = site.details.instagram;
                    this.site.twitter = site.details.twitter;
                    this.site.time_from = site.details.time_from;
                    this.site.time_to = site.details.time_to;
                    this.site.website = site.details.website;
					this.site.active = site.active;
					this.site.operational_hours = [];
					this.site.site_banner_path = site.site_banner_path;

					if (site.details.length == 0 || site.details.schedules === 'null' || site.details.schedules == undefined) {
						this.site.operational_hours = [];
						this.addOperationalHours();
					}
					else {
						this.site.operational_hours = JSON.parse(site.details.schedules);
					}

					this.data_list = false;
					this.data_form = true;
                });
            },

            updateSite: function() {
				let formData = new FormData();
				formData.append("id", this.site.id);
				formData.append("name", this.site.name);
				formData.append("descriptions", this.site.descriptions);
				formData.append("facebook", this.site.facebook);
				formData.append("instagram", this.site.instagram);
				formData.append("twitter", this.site.twitter);
				formData.append("time_from", this.site.time_from);
				formData.append("time_to", this.site.time_to);
				formData.append("website", this.site.website);
				formData.append("operational_hours", JSON.stringify(this.site.operational_hours));

                axios.post('/portal/property-details/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    this.data_list = true;
					this.data_form = false;
				})
            },

			backToList: function() {
				this.data_list = true;
				this.data_form = false;
			},

			addOperationalHours: function () {
				this.site.operational_hours.push({
					schedules: '',
					start_time: '',
					end_time: '',
				});
			},

			conditionActive: function (shedules, item, index) {
				if (shedules.indexOf(item) >= 0) {
					return 'btn custom-btn active';
				}
				else {
					return 'btn custom-btn';
				}
			},

			getChecked: function (item, index) {
				var position = (schedules[index]) ? schedules[index].indexOf(item) : -1;
				if (position >= 0) {
					schedules[index] = schedules[index].replace(", " + item, "").replace(item + ",", "").replace(item, "");
				}
				else {
					if (schedules[index]) {
						schedules[index] += ', ' + item;
					}
					else {
						schedules[index] = item;
					}
				}

				this.site.operational_hours[index].schedules = schedules[index];
			},

        },

        components: {
        	Table,
			datePicker
 	   }
    };
</script> 
