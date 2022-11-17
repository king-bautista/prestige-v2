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
						v-on:AddNewProduct="AddNewProduct"
						v-on:editButton="editProduct"
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

		<div class="modal fade" id="product-form" tabindex="-1" aria-labelledby="product-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Product</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Product</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Product Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="product.name" placeholder="Product Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="product.descriptions" placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Type <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <select class="form-control" aria-label="Default select example" v-model="product.type">
										<option value="">Select Type</option>
										<option value="product">Product</option>
										<option value="promo">Promo</option>
									</select>
								</div>
							</div>
                            <div class="form-group row" v-if="product.type == 'promo'">
                                <label for="userName" class="col-sm-4 col-form-label">Date From <span class="font-italic text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <date-picker v-model="product.date_from" placeholder="YYYY-MM-DD" :config="options" id="date_from" autocomplete="off"></date-picker>
                                </div>
                            </div>
                            <div class="form-group row" v-if="product.type == 'promo'">
                                <label for="userName" class="col-sm-4 col-form-label">Date To <span class="font-italic text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <date-picker v-model="product.date_to" placeholder="YYYY-MM-DD" :config="options" id="date_to" autocomplete="off"></date-picker>
                                </div>
                            </div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Thumbnail</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="thumbnail" @change="thumbnailChange">
									<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="thumbnail" :src="thumbnail" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Banner Image</label>
								<div class="col-sm-5">
                                    <input type="file" accept="image/*" ref="image_url" @change="image_urlChange">
									<footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
								</div>
								<div class="col-sm-3 text-center">
                                    <img v-if="image_url" :src="image_url" class="img-thumbnail" />
								</div>
							</div>                            
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="product.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeProduct">Add New Product</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateProduct">Save Changes</button>
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
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';;
	
	export default {
        name: "Products",
        data() {
            return {
				product: {
					id: '',
                    name: '',
					descriptions: '',
					type: '',
                    thumbnail: '',
                    image_url: '',
					date_from: '',
					date_to: '',				
				},
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                },
                thumbnail: '/images/no-image-available.png',
                image_url: '/images/no-image-available.png',
				add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
            		descriptions: "Descriptions", 
            		type: "Type", 
                    thumbnail_path: {
            			name: "Thumbnail", 
            			type:"logo", 
            		},
                    image_url_path: {
            			name: "Product Image", 
            			type:"logo", 
            		},
                    date_from: "Date From", 
            		date_to: "Date To", 
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
            	dataUrl: "/admin/brand/product/list",
            	actionButtons: {
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
            			apiUrl: '/admin/brand/product/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
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

        },

        methods: {
			thumbnailChange: function(e) {
				const file = e.target.files[0];
      			this.thumbnail = URL.createObjectURL(file);
				this.product.thumbnail = file;
			},

            image_urlChange: function(e) {
				const file = e.target.files[0];
      			this.image_url = URL.createObjectURL(file);
				this.product.image_url = file;
			},

			AddNewProduct: function() {
				this.add_record = true;
				this.edit_record = false;
                this.product.name = '';
				this.product.descriptions = '';
				this.product.type = '';
				this.product.date_from = '';
				this.product.date_to = '';
                this.product.thumbnail = '/images/no-image-available.png';
                this.product.image_url = '/images/no-image-available.png';
                this.product.active = false;				
				this.$refs.thumbnail.value = null;
				this.$refs.image_url.value = null;
                this.thumbnail = '/images/no-image-available.png';
                this.image_url = '/images/no-image-available.png';

              	$('#product-form').modal('show');
            },

            storeProduct: function() {
				let formData = new FormData();
				formData.append("name", this.product.name);
				formData.append("descriptions", this.product.descriptions);
				formData.append("type", this.product.type);
				formData.append("thumbnail", this.product.thumbnail);
				formData.append("image_url", this.product.image_url);
				formData.append("date_from", this.product.date_from);
				formData.append("date_to", this.product.date_to);

                axios.post('/admin/brand/product/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#product-form').modal('hide');
				})
				
            },

			editProduct: function(id) {
                axios.get('/admin/brand/product/'+id)
                .then(response => {
                    var product = response.data.data;
                    this.product.id = id;
                    this.product.name = product.name;
                    this.product.descriptions = product.descriptions;
                    this.product.type = product.type;
                    this.product.date_from = product.date_from;
                    this.product.date_to = product.date_to;
                    this.product.thumbnail = product.thumbnail;
                    this.product.image_url = product.image_url;
                    this.product.active = product.active;
					this.add_record = false;
					this.edit_record = true;
					if(product.thumbnail) {
						this.thumbnail = product.thumbnail_path;
					}
					else {
						this.thumbnail = this.product.thumbnail;
					}

                    if(product.image_url) {
						this.image_url = product.image_url_path;
					}
					else {
						this.image_url = this.product.image_url;
					}

                    this.$refs.thumbnail.value = null;
                    this.$refs.image_url.value = null;
					
                    $('#product-form').modal('show');
                });
            },

            updateProduct: function() {
				let formData = new FormData();
				formData.append("id", this.product.id);
				formData.append("name", this.product.name);
				formData.append("descriptions", this.product.descriptions);
				formData.append("type", this.product.type);
				formData.append("thumbnail", this.product.thumbnail);
				formData.append("image_url", this.product.image_url);
				formData.append("date_from", this.product.date_from);
				formData.append("date_to", this.product.date_to);
				formData.append("active", this.product.active);

                axios.post('/admin/brand/product/update', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
                    $('#product-form').modal('hide');
				})
            },

        },

        components: {
        	Table,
			datePicker,
 	   }
    };
</script> 
