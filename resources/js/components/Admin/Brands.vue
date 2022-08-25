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
		<div class="modal fade" id="brand-form" tabindex="-1" aria-labelledby="brand-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Brand</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Brand</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Logo</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="logo" @change="logoChange">
									<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="brand.logo" :src="brand.logo" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="brand.name" placeholder="Brand Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Category <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<treeselect v-model="brand.category_id"
										:options="categories"
										placeholder="Select Category"/>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Supplementals</label>
								<div class="col-sm-8">
									<multiselect v-model="brand.supplementals"
										:options="supplementals"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Supplementals"
										label="name"
										track-by="name">
									</multiselect> 
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Tags</label>
								<div class="col-sm-8">
									<multiselect v-model="brand.tags"
										:options="tags"
										:multiple="true"
										:close-on-select="true"
										placeholder="Select Tags"
										label="name"
										track-by="name">
									</multiselect> 
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="active" v-model="brand.active">
										<label class="custom-control-label" for="active"></label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="row">
								<div class="col-12 text-right">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary pull-right" v-show="add_record" @click="storeBrand">Add New Brand</button>
									<button type="button" class="btn btn-primary pull-right" v-show="edit_record" @click="updateBrand">Save Changes</button>
								</div>
							</div>
							<hr v-show="product_view"/>
							<div class="form-group row" v-show="product_view">
								<label for="inputPassword3" class="col-sm-12 col-form-label">Products and Promos</label>
							</div>
							<div class="form-group row" v-show="product_view">
								<div class="col-sm-12">
									<Table 
									:dataFields="productFields"
									:dataUrl="productUrl"
									:actionButtons="productButtons"
									:otherButtons="productOtherButtons"
									:primaryKey="productKey"
									v-on:AddNewBrand="AddNewProduct"
									v-on:editButton="editProduct"
									ref="dataTable">
									</Table>
								</div>
							</div>
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
					logo: '/images/no-image-available.png',
					supplementals: [],
					tags: [],
                    active: false,           
                },
				logo: '',
				categories: [],
				supplementals: [],
				tags: [],
                add_record: true,
                edit_record: false,
				product_view: false,
            	dataFields: {
            		name: "Name", 
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
            	dataUrl: "/admin/brand/list",
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
            			apiUrl: '/admin/brand/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
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
				},
				// product and promos
				productFields: {
            		name: "Name", 
            		descriptions: "Descriptions", 
            		type: "Type", 
            		thumbnail: "Thumbnail", 
            		image_url: "Product Image", 
            		date_from: "Date From", 
            		date_to: "Date To", 
					sequence: "Sequence",
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
            	productKey: "id",
            	productUrl: "/admin/brand/product/list",
				productButtons: {
            		edit: {
            			title: 'Edit this Product',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'brand.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Product',
            			name: 'Delete',
            			apiUrl: '/admin/brand/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				productOtherButtons: {
					addNew: {
						title: 'New Product',
						v_on: 'AddNewProduct',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Product',
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
				axios.get('/admin/category/get-all-categories')
                .then(response => this.categories = response.data.data);
			},

			GetSupplementals: function() {
				axios.get('/admin/brand/get-supplementals')
                .then(response => this.supplementals = response.data.data);
			},

			getTags: function() {
				axios.get('/admin/brand/get-tags')
                .then(response => this.tags = response.data.data);
			},

			AddNewBrand: function() {
				this.add_record = true;
				this.edit_record = false;
                this.brand.name = '';
                this.brand.category_id = null;
                this.brand.logo = '/images/no-image-available.png';
                this.brand.supplementals = [];
                this.brand.tags = [];
                this.brand.active = false;				
              	$('#brand-form').modal('show');
            },

            storeBrand: function() {
                axios.post('/admin/brand/store', this.brand)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#brand-form').modal('hide');
				})
            },

			editBrand: function(id) {
                axios.get('/admin/brand/'+id)
                .then(response => {
                    var brand = response.data.data;
                    this.brand.id = id;
                    this.brand.name = brand.name;
                    this.brand.active = brand.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#brand-form').modal('show');
                });
            },

            updateBrand: function() {
                axios.put('/admin/brand/update', this.brand)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#brand-form').modal('hide');
                    })
            },

			AddNewProduct: function() {
            },

			editProduct: function() {
            },

        },

        components: {
        	Table,
			Treeselect,
			Multiselect
 	   }
    };
</script> 
