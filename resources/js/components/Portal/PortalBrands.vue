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
						v-on:AddNewBrand="AddNewBrand"
						v-on:editButton="editBrand"
						v-on:modalBatchUpload="modalBatchUpload"
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
		<div class="modal fade" id="brand-form" data-backdrop="static" tabindex="-1" aria-labelledby="brand-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Brand</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Brand</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row mb-4">
								<label for="firstName" class="col-sm-4 col-form-label">Logo</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="logo" @change="logoChange">
									<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="logo" :src="logo" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row mb-4">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="brand.name" placeholder="Brand Name" required>
								</div>
							</div>
							<div class="form-group row mb-4">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="brand.descriptions" placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row mb-4">
								<label for="lastName" class="col-sm-4 col-form-label">Category <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<treeselect v-model="brand.category_id"
										:options="categories"
										placeholder="Select Category"/>
								</div>
							</div>
							<div class="form-group row mb-4">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Supplementals</label>
								<div class="col-sm-8">
									<multiselect v-model="brand.supplementals"
										:options="supplementals"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Supplementals"
										label="name"
										track-by="name"
										@select="toggleSelected"
										@remove="toggleUnSelected">
									</multiselect> 
								</div>
							</div>
							<div class="form-group row mb-4">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Tags</label>
								<div class="col-sm-8">
									<multiselect v-model="brand.tags"
										:options="tags"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Tags"
										label="name"
										track-by="name"
										@select="toggleSelectedTags"
										@remove="toggleUnSelectedTags">
									</multiselect> 
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="form-check form-switch  form-switch-md mb-3">
										<input type="checkbox" class="custom-control-input form-check-input" id="active" v-model="brand.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary pull-right" v-show="add_record" @click="storeBrand">Add New Brand</button>
							<button type="button" class="btn btn-primary pull-right" v-show="edit_record" @click="updateBrand">Save Changes</button>
						</div>
					<!-- /.card-body -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->

		<!-- Batch Upload -->
		<div class="modal fade" id="batchModal" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		      <div class="modal-content">
		      <div class="modal-header">
		          <h5 class="modal-title" id="batchModalLabel">Batch Upload</h5>
		          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		          </button>
		      </div>
		      <div class="modal-body">
		          <form>
		              <div class="form-group col-md-12">
		                  <label>CSV File: <span class="text-danger">*</span></label>
		                  <div class="custom-file">
		                      <input type="file" ref="file" v-on:change="handleFileUpload()" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="custom-file-input" id="batchInput">
		                      <label class="custom-file-label" id="batchInputLabel" for="batchInput">Choose file</label>
		                  </div>
		              </div>
		          </form>
		      </div>
		      <div class="modal-footer justify-content-between">
		          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		          <button type="button" class="btn btn-primary" @click="storeBatch">Save changes</button>
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
	import Multiselect from 'vue-multiselect';
	// import the styles
	import '@riophae/vue-treeselect/dist/vue-treeselect.css'
	
	export default {
        name: "Brands",
        data() {
            return {
                brand: {
                    id: '',
					category_id: null,
                    name: '',
					descriptions: '',
					logo: '/images/no-image-available.png',
					supplementals: [],
					tags: [],
                    active: false,           
                },
				logo: '',
				categories: [],
				supplementals: [],
				supplemental_ids: [],
				tags_ids: [],
				tags: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
					logo_image_path: {
            			name: "Logo", 
            			type:"logo", 
            		},
            		name: "Name", 
            		descriptions: "Descriptions", 
					category_name: "Category Name",
					supplemental_names: "Supplementals",
					tag_names: "Tags",					
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
            	dataUrl: "/portal/brand/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Brand',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'brand.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Brand',
            			name: 'Delete',
            			apiUrl: '/portal/brand/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
					link: {
            			title: 'Manage Products and Promos',
            			name: 'Manage Products and Promos',
            			apiUrl: '/portal/brand/products',
            			routeName: '',
            			button: '<i class="fa fa-link"></i> Manage Products',
            			method: 'link'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Brand',
						v_on: 'AddNewBrand',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Brand',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
					batchUpload: {
                        title: 'Batch Upload',
                        v_on: 'modalBatchUpload',
                        icon: '<i class="fas fa-upload"></i> Batch Upload',
                        class: 'btn btn-primary btn-sm',
                        method: 'add'
		            },
				},
            };
        },

        created(){ 
			this.GetCategories(); 
			this.GetSupplementals();
			this.getTags();
        },

        methods: {
			logoChange: function(e) {
				const file = e.target.files[0];
      			this.logo = URL.createObjectURL(file);
				this.brand.logo = file;
			},

			GetCategories: function() {
				axios.get('/portal/category/get-parent')
                .then(response => this.categories = response.data.data);
			},

			GetSupplementals: function() {
				axios.get('/portal/supplemental/get-child')
                .then(response => this.supplementals = response.data.data);
			},

			getTags: function() {
				axios.get('/portal/brand/get-tags')
                .then(response => this.tags = response.data.data);
			},

			toggleSelected: function(value, id) {
				this.supplemental_ids.push(value.id);
			},

			toggleUnSelected: function(value, id) {
				const index = this.supplemental_ids.indexOf(value.id);
				if (index > -1) { // only splice array when item is found
					this.supplemental_ids.splice(index, 1); // 2nd parameter means remove one item only
				}
			},

			toggleSelectedTags: function(value, id) {
				this.tags_ids.push(value.id);
			},

			toggleUnSelectedTags: function(value, id) {
				const index = this.tags_ids.indexOf(value.id);
				if (index > -1) { // only splice array when item is found
					this.tags_ids.splice(index, 1); // 2nd parameter means remove one item only
				}
			},

			AddNewBrand: function() {
				this.add_record = true;
				this.edit_record = false;
                this.brand.name = '';
				this.brand.description = '';
                this.brand.category_id = null;
                this.brand.logo = '/images/no-image-available.png';
                this.brand.supplementals = [];
                this.brand.tags = [];
                this.brand.active = false;				
				this.$refs.logo.value = null;

              	$('#brand-form').modal('show');
            },

            storeBrand: function() {
				let formData = new FormData();
				formData.append("name", this.brand.name);
				formData.append("category_id", this.brand.category_id);
				formData.append("descriptions", this.brand.descriptions);
				formData.append("logo", this.brand.logo);
				formData.append("supplementals", this.supplemental_ids);
				formData.append("tags", this.tags_ids);

                axios.post('/portal/brand/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#brand-form').modal('hide');
				})
				
            },

			editBrand: function(id) {
                axios.get('/portal/brand/'+id)
                .then(response => {
                    var brand = response.data.data;
                    this.brand.id = id;
                    this.brand.category_id = brand.category_id;
                    this.brand.name = brand.name;
                    this.brand.descriptions = brand.descriptions;
					this.brand.supplementals = brand.supplementals;
					this.brand.tags = brand.tags;
                    this.brand.active = brand.active;
					this.add_record = false;
					this.edit_record = true;
					if(brand.logo) {
						this.logo = brand.logo_image_path;
					}
					else {
						this.logo = this.brand.logo;
					}

					this.$refs.logo.value = null;
					this.product_view = true;
					
					brand.supplementals.forEach((value) => {
						this.supplemental_ids.push(value.id);
                	});

					brand.tags.forEach((value) => {
						this.tags_ids.push(value.id);
                	});		

                    $('#brand-form').modal('show');
                });
            },

            updateBrand: function() {
				let formData = new FormData();
				formData.append("id", this.brand.id);
				formData.append("name", this.brand.name);
				formData.append("category_id", this.brand.category_id);
				formData.append("descriptions", this.brand.descriptions);
				formData.append("logo", this.brand.logo);
				formData.append("supplementals", this.supplemental_ids);
				formData.append("tags", this.tags_ids);

                axios.post('/portal/brand/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
	              	$('#brand-form').modal('hide');
				})
            },

			modalBatchUpload: function() {
	            $('#batchModal').modal('show');
	        },

			handleFileUpload: function(){
	            this.file = this.$refs.file.files[0];
	            $('#batchInputLabel').html(this.file.name)
	        },

			storeBatch: function() {
	            let formData = new FormData();
	            formData.append('file', this.file);

	            axios.post( '/portal/brand/batch-upload' , formData,
	            {
	                headers: {
	                    'Content-Type': 'multipart/form-data'
	                }
	            }).then(response => {
					this.$refs.file.value = null;
	                this.$refs.dataTable.fetchData();
                    toastr.success(response.data.message);
	                $('#batchModal').modal('hide');
	                $('#batchInputLabel').html('Choose File');
					window.location.reload();
	            })
	        },

        },

        components: {
        	Table,
			Treeselect,
			Multiselect
 	   }
    };
</script> 
