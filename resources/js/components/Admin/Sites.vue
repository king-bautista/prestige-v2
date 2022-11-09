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
						v-on:AddNewSite="AddNewSite"
						v-on:editButton="editSite"
						v-on:DefaultScreen="DefaultScreen"
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

		<div class="modal fade" id="site-form" tabindex="-1" aria-labelledby="site-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Site</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Site</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site.name" placeholder="Site Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="site.descriptions" placeholder="Descriptions" rows="5"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Logo</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="site_logo" @change="siteLogoChange">
									<footer class="blockquote-footer">image max size is 155 x 155 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="site_logo" :src="site_logo" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Banner Image</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="site_banner" @change="siteBannerChange">
									<footer class="blockquote-footer">image max size is 1451 x 440 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="site_banner" :src="site_banner" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="site.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="isActive" class="col-sm-4 col-form-label">Is Default</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_default" v-model="site.is_default">
										<label class="custom-control-label" for="is_default"></label>
									</div>
								</div>
							</div>
							<hr/> 
							<div class="form-group row">
								<label for="firstName" class="col-sm-12 col-form-label"><strong>Social Media:</strong></label>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Facebook</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site.facebook" placeholder="Facebook link">
								</div>
							</div>       
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Instagram</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site.instagram" placeholder="Instagram link">
								</div>
							</div>   
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Twitter</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site.twitter" placeholder="Twitter link">
								</div>
							</div>            
							<hr/>      
							<div class="form-group row">
								<label for="firstName" class="col-sm-12 col-form-label"><strong>Mall Information:</strong></label>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Mall Hours</label>
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
								<label for="firstName" class="col-sm-4 col-form-label">Website</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="site.website" placeholder="Website">
								</div>
							</div> 
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeSite">Add New Site</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateSite">Save Changes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Confirm modal -->
		<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to set this site as default?</h6>
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
                    site_banner_path: {
            			name: "Banner", 
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
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/site/list",
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
            			apiUrl: '/admin/site/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
					link: {
            			title: 'Manage Site',
            			name: 'Link',
            			apiUrl: '/admin/site/buildings',
            			routeName: '',
            			button: '<i class="fa fa-link"></i> Manage Site',
            			method: 'link'
            		},
					view: {
            			title: 'Set as Default',
            			name: 'Link',
            			apiUrl: '/admin/site/buildings',
            			routeName: '',
            			button: '<i class="fa fa-tag"></i> Set as Default',
            			method: 'view',
						v_on: 'DefaultScreen',
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

			AddNewSite: function() {
				this.add_record = true;
				this.edit_record = false;
                this.site.name = '';
				this.site.descriptions = '';
                this.site.site_logo = '/images/no-image-available.png';
                this.site.site_banner = '/images/no-image-available.png';
                this.site.active = false;				
                this.site_logo = '/images/no-image-available.png';		
                this.site_banner = '/images/no-image-available.png';				
				this.$refs.site_logo.value = null;
				this.$refs.site_banner.value = null;

              	$('#site-form').modal('show');
            },

            storeSite: function() {
				let formData = new FormData();
				formData.append("name", this.site.name);
				formData.append("descriptions", this.site.descriptions);
				formData.append("site_logo", this.site.site_logo);
				formData.append("site_banner", this.site.site_banner);
				formData.append("facebook", this.site.facebook);
				formData.append("instagram", this.site.instagram);
				formData.append("twitter", this.site.twitter);
				formData.append("time_from", this.site.time_from);
				formData.append("time_to", this.site.time_to);
				formData.append("website", this.site.website);
				formData.append("is_default", this.site.is_default);

                axios.post('/admin/site/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#site-form').modal('hide');
				})
				
            },

			editSite: function(id) {
                axios.get('/admin/site/'+id)
                .then(response => {
                    var site = response.data.data;
                    this.site.id = id;
                    this.site.name = site.name;
                    this.site.descriptions = site.descriptions;
                    this.site.site_logo = site.site_logo;
                    this.site.site_banner = site.site_banner;
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

                    this.$refs.site_logo.value = null;
                    this.$refs.site_logo.value = null;
					
                    $('#site-form').modal('show');
                });
            },

            updateSite: function() {
				let formData = new FormData();
				formData.append("id", this.site.id);
				formData.append("name", this.site.name);
				formData.append("descriptions", this.site.descriptions);
				formData.append("site_logo", this.site.site_logo);
				formData.append("site_banner", this.site.site_banner);
				formData.append("facebook", this.site.facebook);
				formData.append("instagram", this.site.instagram);
				formData.append("twitter", this.site.twitter);
				formData.append("time_from", this.site.time_from);
				formData.append("time_to", this.site.time_to);
				formData.append("website", this.site.website);
				formData.append("active", this.site.active);
				formData.append("is_default", this.site.is_default);				

                axios.post('/admin/site/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#site-form').modal('hide');
				})
            },

			DefaultScreen: function(data) {
				this.is_default = data.id;
				$('#confirmModal').modal('show');
			},

			setDefault: function() {
				axios.get('/admin/site/set-default/'+this.is_default)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#confirmModal').modal('hide');
				})
			}

        },

        components: {
        	Table,
			datePicker
 	   }
    };
</script> 
