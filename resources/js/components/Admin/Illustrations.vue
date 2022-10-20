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
						v-on:addNewIllustration="addNewIllustration"
						v-on:editButton="editIllustration"
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
		<div class="modal fade" id="Illustration-form" tabindex="-1" aria-labelledby="Illustration-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Illustration</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Illustration</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Category / Supplemental <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="illustration.category_id" @change="getSubCategories($event.target.value)">
									    <option value="">Select Category / Supplemental</option>
									    <option v-for="category in categories" :value="category.id"> {{ category.type_category_name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Sub Category / Supplemental</label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="illustration.sub_category_id">
									    <option value="">Select Sub Category / Supplemental</option>
									    <option v-for="sub_category in sub_categories" :value="sub_category.id"> {{ sub_category.name }}</option>
								    </select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Company</label>
								<div class="col-sm-8">
                                    <select class="custom-select" v-model="illustration.company_id">
									    <option value="">Select Company</option>
									    <option v-for="company in companies" :value="company.id"> {{ company.name }}</option>
								    </select>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site</label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="illustration.site_id">
										<option value="">Select Site</option>
										<option v-for="site in site_list" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Kiosk Primary <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="kiosk_image_primary" @change="kioskPrimary">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer" v-if="illustration.category_id && !illustration.sub_category_id">image max size is 349 x 528 pixels</footer>
                                    <footer class="blockquote-footer" v-if="illustration.category_id && illustration.sub_category_id">image max size is 320 x 90 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="kiosk_image_primary" :src="kiosk_image_primary" class="img-thumbnail" />
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Kiosk Top <span v-if="illustration.category_id && illustration.sub_category_id" class="font-italic text-danger"> *</span></label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="kiosk_image_top" @change="kioskTop" :disabled="illustration.category_id && !illustration.sub_category_id">
									<footer class="blockquote-footer">Max file size is 15MB</footer>
									<footer class="blockquote-footer" v-if="illustration.category_id && !illustration.sub_category_id">No top image for main category</footer>
                                    <footer class="blockquote-footer" v-if="illustration.category_id && illustration.sub_category_id">image max size is 1470 x 72 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="kiosk_image_top" :src="kiosk_image_top" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="illustration.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeIllustration">Add New Category</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateIllustration">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';
	// import the component
	import Treeselect from '@riophae/vue-treeselect'
	// import the styles
	import '@riophae/vue-treeselect/dist/vue-treeselect.css'

	export default {
        name: "Illustration",
        data() {
            return {
                illustration: {
                    id: '',
                    company_id: '',
                    category_id: '',
                    sub_category_id: '',                   
                    site_id: '',                   
                    active: false,                   
                    kiosk_image_primary: '',           
                    kiosk_image_top: '',           
                    online_image_primary: '',           
                    online_image_top: '',           
                    mobile_image_primary: '',           
                    mobile_image_top: '',                
                },
                companies: [],
                categories: [],
				sub_categories: [],
                site_list:[],
                kiosk_image_primary: '',           
                kiosk_image_top: '',           
                online_image_primary: '',           
                online_image_top: '',           
                mobile_image_primary: '',           
                mobile_image_top: '',
                add_record: true,
                edit_record: false,
            	dataFields: {
                    kiosk_image_primary_path: {
            			name: "Kiosk Primary Image", 
            			type:"logo", 
            		},
                    kiosk_image_top_path: {
            			name: "Kiosk Top Image", 
            			type:"logo", 
            		},
            		company_name: "Company", 
            		category_name: "Category",          		
                    sub_category_name: "Sub-Category", 
                    site_name: "Site", 
                    // online_image_primary_path: {
            		// 	name: "Online Primary Image", 
            		// 	type:"image", 
            		// },
                    // online_image_top_path: {
            		// 	name: "Online Top Image", 
            		// 	type:"image", 
            		// },
                    // mobile_image_primary_path: {
            		// 	name: "Online Primary Image", 
            		// 	type:"image", 
            		// },
                    // mobile_image_top_path: {
            		// 	name: "Online Top Image", 
            		// 	type:"image", 
            		// },
            		active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
                    created_at: "Date Created"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/Illustration/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this illustration',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'category.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this illustration',
            			name: 'Delete',
            			apiUrl: '/min/Illustration/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Category',
						v_on: 'addNewIllustration',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Illustration',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
            this.getCompanies();
			this.getCategories();
			this.getSites();
        },

        methods: {
            getCompanies: function() {
				axios.get('/admin/company/get-all')
                .then(response => this.companies = response.data.data);
			},

			getCategories: function() {
				axios.get('/admin/category/get-parent')
                .then(response => this.categories = response.data.data);
			},

            getSubCategories: function(id) {
				axios.get('/admin/category/get-all/'+id)
                .then(response => this.sub_categories = response.data.data);
			},

            getSites: function() {
				axios.get('/admin/site/get-all')
                .then(response => this.site_list = response.data.data);
			},

            kioskPrimary: function(e) {
				const file = e.target.files[0];
      			this.kiosk_image_primary = URL.createObjectURL(file);
				this.illustration.kiosk_image_primary = file;
			},

            kioskTop: function(e) {
				const file = e.target.files[0];
      			this.online_image_top = URL.createObjectURL(file);
				this.illustration.online_image_top = file;
			},

			addNewIllustration: function() {
				this.add_record = true;
				this.edit_record = false;
                this.illustration.company_id = '';
                this.illustration.category_id = '';
                this.illustration.sub_category_id = '';                   
                this.illustration.site_id = '';          
                this.illustration.kiosk_image_primary = '';
                this.illustration.kiosk_image_top = '';
                this.illustration.online_image_primary = '';
                this.illustration.online_image_top = '';
                this.illustration.mobile_image_primary = '';
                this.illustration.mobile_image_top = '';

				this.$refs.kiosk_image_primary.value = null;
				this.kiosk_image_primary = '';

                this.illustration.active = false;
              	$('#Illustration-form').modal('show');
            },

            storeIllustration: function() {
                let formData = new FormData();
				formData.append("company_id", this.illustration.company_id);
				formData.append("category_id", this.illustration.category_id);
				formData.append("sub_category_id", this.illustration.sub_category_id);
				formData.append("site_id", this.illustration.site_id);
				formData.append("kiosk_image_primary", this.illustration.kiosk_image_primary);
				formData.append("kiosk_image_top", this.illustration.kiosk_image_top);
				formData.append("active", this.illustration.active);
                axios.post('/admin/Illustration/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#Illustration-form').modal('hide');
				})
            },

			editIllustration: function(id) {
                axios.get('/admin/Illustration/'+id)
                .then(response => {
                    var illustration = response.data.data;
                    this.illustration.id = illustration.id;
                    this.illustration.company_id = (illustration.company_id) ? illustration.company_id : '';
                    this.illustration.category_id = (illustration.category_id) ? illustration.category_id : '';
                    this.illustration.sub_category_id = (illustration.sub_category_id) ? illustration.sub_category_id : '';            
                    this.illustration.site_id = (illustration.site_id) ? illustration.site_id : '';
                    this.illustration.active = illustration.active;
                    // this.illustration.kiosk_image_primary = '';
                    // this.illustration.kiosk_image_top = '';
                    this.$refs.kiosk_image_primary.value = null;
					this.kiosk_image_primary = illustration.kiosk_image_primary_path;

					this.getSubCategories(illustration.category_id);
                    this.add_record = false;
					this.edit_record = true;
                    $('#Illustration-form').modal('show');
                });
            },

            updateIllustration: function() {
                let formData = new FormData();
				formData.append("id", this.illustration.id);
				formData.append("company_id", this.illustration.company_id);
				formData.append("category_id", this.illustration.category_id);
				formData.append("sub_category_id", this.illustration.sub_category_id);
				formData.append("site_id", this.illustration.site_id);
				formData.append("kiosk_image_primary", this.illustration.kiosk_image_primary);
				formData.append("kiosk_image_top", this.illustration.kiosk_image_top);
				formData.append("active", this.illustration.active);
                axios.post('/admin/Illustration/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#Illustration-form').modal('hide');
				})
            },
        },

        components: {
        	Table,
			Treeselect
 	   }
    };
</script>
<style lang="scss" scoped>
    #preview img {
		max-width: 100%;
		max-height: 500px;
	}
</style>