<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fas fa-user-tag"></i>&nbsp;&nbsp;Property Details</h4>
						<h4 v-show="add_record && data_form"><i class="nav-icon fas fa-user-plus"></i> Add New Site</h4>
						<h4 v-show="edit_record && data_form"><i class="nav-icon fas fa-user-edit"></i> Edit Site</h4>
					</div>
					<div class="card-body" v-show="data_list">
						<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :actionButtons="actionButtons"
						:otherButtons="otherButtons"
                        :primaryKey="primaryKey"
						v-on:AddNewSite="AddNewSite"
						v-on:editButton="editSite"
						v-on:DefaultScreen="DefaultScreen"
                        ref="dataTable">
			          	</Table>
					</div>
					<div class="card-body" v-show="data_form">
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
								<label for="firstName" class="col-sm-3 col-form-label">Logo</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="site_logo" @change="siteLogoChange">
									<footer class="blockquote-footer">image max size is 155 x 155 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="site_logo" :src="site_logo" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Banner Image</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="site_banner" @change="siteBannerChange">
									<footer class="blockquote-footer">image max size is 1451 x 440 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="site_banner" :src="site_banner" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Background Image</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="site_background" @change="siteBackgroundChange">
									<footer class="blockquote-footer">image max size is 1920 x 1080 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="site_background" :src="site_background" class="img-thumbnail" />
								</div>
							</div>
							<hr/> 
							<div class="form-group row">
								<label for="firstName" class="col-sm-12 col-form-label"><strong>Social Media:</strong></label>
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
							<hr/>      
							<div class="form-group row">
								<label for="firstName" class="col-sm-12 col-form-label"><strong>Mall Information:</strong></label>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Mall Hours</label>
								<label class="col-sm-1 col-form-label text-center">From:</label>
								<div class="col-sm-3">
									<date-picker v-model="site.time_from" placeholder="HH:MM" :config="options" autocomplete="off"></date-picker>
								</div>
								<label class="col-sm-1 col-form-label text-center">To:</label>
								<div class="col-sm-3">
									<date-picker v-model="site.time_to" placeholder="HH:MM" :config="options" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-3 col-form-label">Website</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="site.website" placeholder="Website">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12 text-right">
									<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
									<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeSite">Add New Site</button>
									<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateSite">Save Changes</button>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
		
    </div>
</template>
<script> 
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
                    site_logo: '',
                    site_banner: '',
                    site_background: '',
					facebook: '',
					instagram: '',
					twitter: '',
					time_from: '',
					time_to: '',
					website: '',
					active: false,
					is_default: false,
				},
                site_logo: '/images/no-image-available.png',
                site_banner: '/images/no-image-available.png',
                site_background: '/images/no-image-available.png',
                data_list: true,
				data_form: false,
                add_record: true,
                edit_record: false,
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
            		delete: {
            			title: 'Delete this Site',
            			name: 'Delete',
            			apiUrl: '/portal/property-details/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
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
				otherButtons: {
					addNew: {
						title: 'New Site',
						v_on: 'AddNewSite',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Site',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
            };
        },

        created(){

        },

        methods: {
			siteLogoChange: function(e) {
				const file = e.target.files[0];
      			this.site_logo = URL.createObjectURL(file);
				this.site.site_logo = file;
			},

            siteBannerChange: function(e) {
				const file = e.target.files[0];
      			this.site_banner = URL.createObjectURL(file);
				this.site.site_banner = file;
			},

			siteBackgroundChange: function(e) {
				const file = e.target.files[0];
      			this.site_background = URL.createObjectURL(file);
				this.site.site_background = file;
			},

			AddNewSite: function() {
				this.add_record = true;
				this.edit_record = false;
                this.site.name = '';
				this.site.descriptions = '';
                this.site.site_logo = '/images/no-image-available.png';
                this.site.site_banner = '/images/no-image-available.png';
                this.site.site_background = '/images/no-image-available.png';
                this.site.active = false;				
                this.site_logo = '/images/no-image-available.png';		
                this.site_banner = '/images/no-image-available.png';				
                this.site_background = '/images/no-image-available.png';				
				this.$refs.site_logo.value = null;
				this.$refs.site_banner.value = null;
				this.data_list = false;
				this.data_form = true;
            },

            storeSite: function() {
				let formData = new FormData();
				formData.append("name", this.site.name);
				formData.append("descriptions", this.site.descriptions);
				formData.append("site_logo", this.site.site_logo);
				formData.append("site_banner", this.site.site_banner);
				formData.append("site_background", this.site.site_background);
				formData.append("facebook", this.site.facebook);
				formData.append("instagram", this.site.instagram);
				formData.append("twitter", this.site.twitter);
				formData.append("time_from", this.site.time_from);
				formData.append("time_to", this.site.time_to);
				formData.append("website", this.site.website);

                axios.post('/portal/property-details/store', formData, {
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

			editSite: function(id) {
                axios.get('/portal/property-details/'+id)
                .then(response => {
                    var site = response.data.data;
                    this.site.id = id;
                    this.site.name = site.name;
                    this.site.descriptions = site.descriptions;
                    this.site.site_logo = site.site_logo;
                    this.site.site_banner = site.site_banner;
                    this.site.site_background = site.site_background;
                    this.site.facebook = site.details.facebook;
                    this.site.instagram = site.details.instagram;
                    this.site.twitter = site.details.twitter;
                    this.site.time_from = site.details.time_from;
                    this.site.time_to = site.details.time_to;
                    this.site.website = site.details.website;
					this.site.active = site.active;
					this.site.is_default = site.is_default;
					this.add_record = false;
					this.edit_record = true;

					if(site.site_logo) {
						this.site_logo = site.site_logo_path;
					}
					else {
						this.site_logo = this.site.site_logo;
					}

                    if(site.site_banner) {
						this.site_banner = site.site_banner_path;
					}
					else {
						this.site_banner = this.site.site_banner;
					}

					if(site.site_background) {
						this.site_background = site.site_background_path;
					}
					else {
						this.site_background = this.site.site_background;
					}

                    this.$refs.site_logo.value = null;
                    this.$refs.site_logo.value = null;
					
					this.data_list = false;
					this.data_form = true;
                });
            },

            updateSite: function() {
				let formData = new FormData();
				formData.append("id", this.site.id);
				formData.append("name", this.site.name);
				formData.append("descriptions", this.site.descriptions);
				formData.append("site_logo", this.site.site_logo);
				formData.append("site_banner", this.site.site_banner);
				formData.append("site_background", this.site.site_background);
				formData.append("facebook", this.site.facebook);
				formData.append("instagram", this.site.instagram);
				formData.append("twitter", this.site.twitter);
				formData.append("time_from", this.site.time_from);
				formData.append("time_to", this.site.time_to);
				formData.append("website", this.site.website);

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

        },

        components: {
        	Table,
			datePicker
 	   }
    };
</script> 
