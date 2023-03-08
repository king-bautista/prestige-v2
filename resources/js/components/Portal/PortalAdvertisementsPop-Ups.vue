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
						v-on:AddNewAdvertisements="AddNewAdvertisements"
						v-on:editButton="editAdvertisements"
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
		<div class="modal fade" id="site-ad-pop-up-form" data-backdrop="static" tabindex="-1" aria-labelledby="site_ad-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Advertisements</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Advertisements</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="advertisements.company_id"
										:options="companies"
										:multiple="false"
										:close-on-select="true"
										placeholder="Select Company"
										label="name"
										track-by="name">
									</multiselect>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <multiselect v-model="advertisements.brand_id" 
									track-by="name" 
									label="name" 
									placeholder="Select Brand" 
									:options="brands" 
									:searchable="true" 
									:allow-empty="false">
                                    </multiselect> 
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="advertisements.name" placeholder="Advertisements Name" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Material <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="material" @change="materialChange">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Online'">image/video max size is 1140 x 140 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Banners'">image/video max size is 470 x 1060 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Fullscreen'">image/video max size is 1920 x 1080 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Pop-Up'">image/video max size is 470 x 1060 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Events'">image/video max size is 286 x 286 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
									<span v-if="material && helper.getFileExtension(material_type) == 'image'">
										<img v-if="material" :src="material" class="img-thumbnail" />
									</span>
									<span v-else-if="material && helper.getFileExtension(material_type) == 'video'">
										<video muted="muted" class="img-thumbnail">
											<source :src="material" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Duration <span class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" v-model="advertisements.display_duration" placeholder="Duration"> 
									<footer class="blockquote-footer">In Seconds</footer>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" v-model="advertisements.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record" @click="storeAdvertisements">Add New Advertisements</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record" @click="updateAdvertisements">Save Changes</button>
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
    // Import this component
    import Multiselect from 'vue-multiselect';

	export default {
        name: "Advertisements",
        props: {
        	ad_type: {
        		type: String,
        		required: true
        	},
        },
        data() {
            return {
                helper: new Helpers(),
                advertisements: {
                    id: '',
					company_id: '',
					brand_id: '',
					ad_type: '',
                    name: '',
                    descriptions: '',
					file_path: '',
					display_duration: '',
                    active: true,           
                },
				material: '',
				material_type: '',
                companies: [],
                brands: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
					material_image_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
            		name: "Name", 
					company_name: "Company Name",
					brand_name: "Brand Name",
					display_duration: "Duration (in sec)",
					dimension: "Dimension",
            		status_id: {
            			name: "Transaction Status", 
            			type:"Boolean", 
            			status: { 
            				1: '<span class="badge badge-primary">Draft</span>',
            				2: '<span class="badge badge-primary">New</span>',
            				3: '<span class="badge badge-info">Pending approval</span>',
            				4: '<span class="badge badge-danger">Disapprove</span>',
            				5: '<span class="badge badge-success">Approved</span>',
            				6: '<span class="badge badge-secondary">For review</span>',
            				7: '<span class="badge badge-info">Archive</span>',
            				8: '<span class="badge badge-success">Saved</span>',
            			}
            		},
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
            	dataUrl: "/portal/advertisement/list/"+this.ad_type,
            	actionButtons: {
            		edit: {
            			title: 'Edit this Advertisements',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'advertisements.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Advertisements',
            			name: 'Delete',
            			apiUrl: '/portal/advertisements/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Advertisements',
						v_on: 'AddNewAdvertisements',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Advertisements',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
            };
        },

        created(){
			this.getCompany();
			this.getBrands();
        },

        methods: {
			getCompany: function() {
				axios.get('/portal/company/get-all')
                .then(response => this.companies = response.data.data);
			},

			getBrands: function() {
				axios.get('/portal/brand/get-all')
                .then(response => this.brands = response.data.data);
			},

			materialChange: function(e) {
				const file = e.target.files[0];
				this.material_type = e.target.files[0].type;
      			this.material = URL.createObjectURL(file);
				this.advertisements.material = file;
			},

			AddNewAdvertisements: function() {
				this.add_record = true;
				this.edit_record = false;
				this.advertisements.company_id = null;
				this.advertisements.brand_id = null;
                this.advertisements.name = '';
				this.advertisements.ad_type = this.ad_type;
				this.advertisements.file_path = '';
				this.advertisements.display_duration = '';
                this.advertisements.active = true;				
				this.$refs.material.value = null;
				this.material = null;
              	$('#site-ad-pop-up-form').modal('show');
            },

            storeAdvertisements: function() {
				let formData = new FormData();
				formData.append("company_id", JSON.stringify(this.advertisements.company_id));
				formData.append("brand_id", JSON.stringify(this.advertisements.brand_id));
				formData.append("name", this.advertisements.name);
				formData.append("ad_type", this.advertisements.ad_type);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("active", this.advertisements.active);
                axios.post('/portal/advertisement/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#site-ad-pop-up-form').modal('hide');
				});				
            },

			editAdvertisements: function(id) {
                axios.get('/portal/advertisement/'+id)
                .then(response => {
                    var advertisements = response.data.data;
					this.advertisements.id = advertisements.id;
					this.advertisements.company_id = advertisements.company_details;
					this.advertisements.brand_id = advertisements.brand_details;
					this.advertisements.ad_type = this.ad_type;
					this.advertisements.name = advertisements.name;
					this.advertisements.file_path = advertisements.name;
					this.advertisements.display_duration = advertisements.display_duration;
					this.advertisements.active = advertisements.active;
					this.$refs.material.value = null;
					this.advertisements.material = '';
					this.material = advertisements.material_image_path;
					this.add_record = false;
					this.edit_record = true;

                    $('#site-ad-pop-up-form').modal('show');
                });
            },

            updateAdvertisements: function() {
				let formData = new FormData();
				formData.append("id", this.advertisements.id);
				formData.append("company_id", JSON.stringify(this.advertisements.company_id));
				formData.append("brand_id", JSON.stringify(this.advertisements.brand_id));
				formData.append("ad_type", this.advertisements.ad_type);
				formData.append("name", this.advertisements.name);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("active", this.advertisements.active);
                axios.post('/portal/advertisement/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#site-ad-pop-up-form').modal('hide');
				})
            },

        },

        components: {
        	Table,
            Multiselect,
 	   }
    };
</script> 