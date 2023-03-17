<template>
<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fa fa-building"></i>&nbsp;&nbsp;Advertisements</h4>
						<h4 v-show="add_record && data_form"><i class="nav-icon fas fa-user-plus"></i> Add New Advertisement</h4>
						<h4 v-show="edit_record && data_form"><i class="nav-icon fas fa-user-edit"></i> Edit Advertisement</h4>
					</div>
					<div class="card-body" v-show="data_list">
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
					<div class="card-body" v-show="data_form">
						<div class="form-group row">
							<label for="lastName" class="col-sm-3 col-form-label">Company <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
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
							<label for="firstName" class="col-sm-3 col-form-label">Brands <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
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
							<label for="firstName" class="col-sm-3 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" v-model="advertisements.name" placeholder="Advertisements Name" required>
								</div>
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Ad Type <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-9">
									<multiselect v-model="advertisements.ad_type" 
									:options="ad_types" 
									:searchable="false" 
									:close-on-select="false" 
									:show-labels="false" 
									placeholder="Select Type">
								    </multiselect>
								</div>
								<!-- <div class="col-sm-3 text-center">
									<span v-if="material && helper.getFileExtension(material_type) == 'image'">
										<img v-if="material" :src="material" class="img-thumbnail" /> 
									</span>
									<span v-else-if="material && helper.getFileExtension(material_type) == 'video'">
										<video muted="muted" class="img-thumbnail">
											<source :src="material" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									</span>
								</div> -->
							
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Material <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-3">
                                	<input type="file" accept="image/*" ref="material" @change="materialChange">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<!-- <footer class="blockquote-footer" v-if="ad_type=='Online'">image/video max size is 1140 x 140 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Banners'">image/video max size is 470 x 1060 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Fullscreen'">image/video max size is 1920 x 1080 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Pop-Up'">image/video max size is 470 x 1060 pixels</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Events'">image/video max size is 286 x 286 pixels</footer> -->
									<footer class="blockquote-footer" >image/video max size is 1920 x 1080 pixels</footer>
								</div>
								<!-- <div class="col-sm-3">
									<input type="file" accept="image/*" ref="subscriber_logo" @change="subscriberLogoChange">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer" v-if="ad_type=='Online'">image/video max size is 1140 x 140 pixels</footer>
										<footer class="blockquote-footer" v-if="ad_type=='Banners'">image/video max size is 470 x 1060 pixels</footer>
										<footer class="blockquote-footer" v-if="ad_type=='Fullscreen'">image/video max size is 1920 x 1080 pixels</footer>
										<footer class="blockquote-footer" v-if="ad_type=='Pop-Up'">image/video max size is 470 x 1060 pixels</footer>
										<footer class="blockquote-footer" v-if="ad_type=='Events'">image/video max size is 286 x 286 pixels</footer>
									<footer class="blockquote-footer">image max size is 550 x 550 pixels</footer>
								</div> -->
						</div>
						<div class="form-group row">
							<label for="firstName" class="col-sm-3 col-form-label">Duration <span class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-2">
									<div class="mb-4"><input type="text" class="form-control" v-model="advertisements.display_duration" placeholder="Duration"></div>
									<footer class="blockquote-footer">In Seconds</footer>
								</div>
							
						</div>
						<div class="form-group row">
							<label for="active" class="col-sm-3 col-form-label">Active</label>
								<div class="col-sm-9">
									<div class="form-check form-switch form-switch-md mb-3">
										<input type="checkbox" class="custom-control-input form-check-input" id="active" v-model="advertisements.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-secondary btn-sm" @click="backToList"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;Back to list</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="add_record" @click="storeAdvertisements">Add New Advertisement</button>
								<button type="button" class="btn btn-primary btn-sm" v-show="edit_record" @click="updateAdvertisements">Save Changes</button>
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
    import Multiselect from 'vue-multiselect';

	export default {
        name: "Advertisements",
        // props: {
        // 	ad_type: {
        // 		type: String,
        // 		required: true
        // 	},
        // },
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
				data_list: true,
				data_form: false,
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
            	//dataUrl: "/portal/create-ad/list/"+this.ad_type,
				dataUrl: "/portal/create-ad/list/",
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
				ad_types: ['Online','Banners','Fullscreens','Pop-Ups','Events','Promos'],
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
				this.advertisements.company_id = null;
				this.advertisements.brand_id = null;
                this.advertisements.name = '';
				this.advertisements.ad_type = '';
				this.advertisements.file_path = '';
				this.advertisements.display_duration = '';
                this.advertisements.active = true;				
				this.$refs.material.value = null;
				this.material = null;
				this.data_list = false;
				this.data_form = true;
				this.add_record = true;
				this.edit_record = false;
            },

            storeAdvertisements: function() { 
				let formData = new FormData();
				formData.append("company_id", (this.advertisements.company_id) ? JSON.stringify(this.advertisements.company_id) : '');
				formData.append("brand_id", (this.advertisements.brand_id) ? JSON.stringify(this.advertisements.brand_id) : '');
				formData.append("name", this.advertisements.name);
				formData.append("ad_type", this.advertisements.ad_type);
				formData.append("file_path", this.advertisements.material);
				formData.append("display_duration", this.advertisements.display_duration);
				formData.append("active", this.advertisements.active);
                axios.post('/portal/create-ad/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.data_list = true;
					this.data_form = false;
	              	
				});				
            },

			editAdvertisements: function(id) {
                axios.get('/portal/create-ad/'+id)
                .then(response => {
                    var advertisements = response.data.data;
					this.advertisements.id = advertisements.id;
					this.advertisements.company_id = advertisements.company_details;
					this.advertisements.brand_id = advertisements.brand_details;
					this.advertisements.ad_type = advertisements.ad_type;
					this.advertisements.name = advertisements.name;
					this.advertisements.file_path = advertisements.name;
					this.advertisements.display_duration = advertisements.display_duration;
					this.advertisements.active = advertisements.active;
					this.$refs.material.value = null;
					this.advertisements.material = '';
					this.material = advertisements.material_image_path;
					this.add_record = false;
					this.edit_record = true;
					this.data_list = false;
					this.data_form = true;
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
                axios.post('/portal/create-ad/update', formData, {
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
			backToList: function () {
				this.data_list = true;
				this.data_form = false;
			},

        },
		
        components: {
        	Table,
            Multiselect,
 	   }
    };
</script> 