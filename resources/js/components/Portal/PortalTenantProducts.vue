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
						v-on:backTenant="backTenant"
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
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add Product & Promo</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Product & Promo</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="row">
								<div class="col-md-3" v-for="product in brand_products">
									<div class="custom-control custom-checkbox image-checkbox" :class="product.type">
										<input type="checkbox" class="custom-control-input" :id="product.id" @click="productSelected(product)">
										<label class="custom-control-label" :for="product.id">
											<img :src="product.image_url_path" alt="#" class="img-fluid">
											<span>{{ product.name }}</span><br>
											<span>Date From: {{ product.date_from }}</span><br>
											<span>Date To: {{ product.date_to }}</span><br>
											<span style="text-transform: uppercase;">Type: {{ product.type }}</span>
										</label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="storeProduct">Save Changes</button>
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
        name: "TenantProducts",
        props: {
        	brand_id: {
        		type: Number,
        		required: true
        	},
			tenant_id: {
        		type: Number,
        		required: true
        	},
        },
        data() {
            return {
				brand_products: [],
				frm_product: {
					site_tenant_id: this.tenant_id,
					product_ids: []
				},
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
            				0: '<span class="badge bg-danger">Inactive</span>',
            				1: '<span class="badge bg-info">Active</span>'
            			}
            		},
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/site/tenant/product/list/"+this.tenant_id,
            	actionButtons: {
            		delete: {
            			title: 'Delete this Product',
            			name: 'Delete',
            			apiUrl: '/portal/site/tenant/product/delete/'+this.tenant_id,
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					backTenant: {
						title: 'Back to Tenants',
						v_on: 'backTenant',
						icon: '<i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Tenants',
						class: 'btn btn-secondary btn-sm',
						method: 'add'
					},
					addNew: {
						title: 'Add Product',
						v_on: 'AddNewProduct',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> Add Product',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
            };
        },

        created(){
            this.getProductByBrand();
        },

        methods: {
			getProductByBrand() {
				axios.get('/portal/brand/product-by-id/'+this.brand_id)
                .then(response => this.brand_products = response.data.data);
			},

			productSelected: function(value) {
				const index = this.frm_product.product_ids.indexOf(value.id);
				if (index > -1) { // only splice array when item is found
					this.frm_product.product_ids.splice(index, 1); // 2nd parameter means remove one item only
				}
				else {
					this.frm_product.product_ids.push(value.id);
				}
			},

			AddNewProduct: function() {
				this.add_record = true;
              	$('#product-form').modal('show');
            },

            storeProduct: function() {
				axios.post('/portal/site/tenant/store-brand-products', this.frm_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				});				
            },

			backTenant: function() { 
				window.location.assign("../../../../portal/site/tenants");
            },

        },

        components: {
        	Table,
			datePicker,
 	   }
    };
</script>
<style lang="scss" scoped>
	/*!
	* Bootstrap Image Checkbox v0.1.0 (https://iqbalfn.github.io/bootstrap-image-checkbox/)
	* Copyright 2019 Iqbal Fauzi
	* Licensed under MIT (https://github.com/iqbalfnn/bootstrap-image-checkbox/blob/master/LICENSE)
	*/
	.custom-control.image-checkbox {
		position: relative;
		padding-left: 0;
		border: solid 1px;
		border-radius: 10px;
		padding: 5px;
		margin-bottom: 10px;
	}

	.custom-control.image-checkbox .custom-control-input:checked ~ .custom-control-label:after, .custom-control.image-checkbox .custom-control-input:checked ~ .custom-control-label:before {
		opacity: 1;
	}

	.custom-control.image-checkbox label {
		cursor: pointer;
	}

	.custom-control.image-checkbox label:before {
		border-color: #007bff;
		background-color: #007bff;
	}

	.custom-control.image-checkbox label:after, .custom-control.image-checkbox label:before {
		transition: opacity .3s ease;
		opacity: 0;
		left: .25rem;
	}

	.custom-control.image-checkbox label:focus, .custom-control.image-checkbox label:hover {
		opacity: .8;
	}

	.custom-control.image-checkbox label img {
		border-radius: 2.5px;
	}

	.form-group-image-checkbox.is-invalid label {
		color: #dc3545;
	}

	.form-group-image-checkbox.is-invalid .invalid-feedback {
		display: block;
	}

	.product {
		height: 350px;
	}

	.promo {
		height: 455px;
	}
	/*# sourceMappingURL=bootstrap-image-checkbox.css.map */
</style>