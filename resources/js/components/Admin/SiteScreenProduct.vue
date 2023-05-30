<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<Table :dataFields="dataFields" 
								:dataUrl="dataUrl" 
								:actionButtons="actionButtons"
								:otherButtons="otherButtons" 
								:primaryKey="primaryKey" 
								v-on:AddNewProduct="AddNewProduct"
								v-on:editButton="editProduct" 
								v-on:downloadCsv="downloadCsv"
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
								<label for="firstName" class="col-sm-4 col-form-label">Site Screen <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<multiselect v-model="pi_product.site_screen_id" 
										:options="screens" 
										:multiple="false"
										:close-on-select="true" 
										placeholder="Select Screens" 
										label="site_screen_location"
										track-by="site_screen_location"
										@input="screenDetails">
									</multiselect>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Physical Configuration</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.physical_configuration" placeholder="1920x1080" readonly/>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Orientation</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.orientation" placeholder="Orientation" readonly/>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Product Application</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.product_application" placeholder="Product Application" readonly/>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Advertisement Type <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="pi_product.ad_type" @change="adTypeDetails(pi_product.ad_type)">
										<option value="">Select Advertisement Type</option>
										<option v-for="ad_type in ad_types" :value="ad_type"> {{ ad_type.ad_type }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions</label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="pi_product.description" placeholder="Descriptions"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Width <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.width" placeholder="1920" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Height <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.height" placeholder="1080" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Sec/Slot <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.sec_slot" placeholder="10" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Slots <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="pi_product.slots" placeholder="40" required>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="pi_product.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeScreen">Add New
							Product</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateScreen">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->

	</div>
</template>
<script>
	import Table from '../Helpers/Table';
	import Multiselect from 'vue-multiselect';

	export default {
        name: "SiteScreenProduct",
        data() {
            return {
                pi_product: {
                    id: '',
					site_screen_id: '',
                    ad_type: '',
                    description: '',
                    width: '',
                    height: '',
                    sec_slot: '',
					slots: '',
					active: false,
					physical_configuration: '',
					orientation: '',
					product_ppplication: '',
                },
                add_record: true,
                edit_record: false,
				screens: [],
				ad_types: [],
            	dataFields: {
            		site_screen_location: "Screen Location",
                    ad_type: "Advertisement Type",
            		dimension: "Dimension", 
            		sec_slot: "Sec/Slot", 
            		slots: "Slot", 
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
            	dataUrl: "/admin/site/site-screen-product/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Product',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'building.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Product',
            			name: 'Delete',
            			apiUrl: '/admin/site/site-screen-product/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete',
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
					download: {
						title: 'Download',
						v_on: 'downloadCsv',
						icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
			this.getScreens();
			this.getPiProducts();
        },

	methods: {
		getScreens: function (id) {
			axios.get('/admin/site/screen/get-all')
				.then(response => this.screens = response.data.data);
		},

		getPiProducts: function() {
			axios.get('/admin/site/pi-product/get-products')
				.then(response => this.ad_types = response.data.data);
		},

		adTypeDetails: function(ad_type) {
			this.pi_product.sec_slot = ad_type.sec_slot;
			this.pi_product.slots = ad_type.slots;

		},

		screenDetails: function(screen) {
			this.pi_product.physical_configuration = screen.physical_size_width +' x '+screen.physical_size_height;
			this.pi_product.orientation = screen.orientation;
			this.pi_product.product_application = screen.product_application;
			this.ad_types = this.ad_types.filter(option => option.product_application == screen.product_application);
		},

		AddNewProduct: function () {
			this.add_record = true;
			this.edit_record = false;
			this.pi_product.site_screen_id = '';
			this.pi_product.ad_type = '';
			this.pi_product.description = '';
			this.pi_product.width = '';
			this.pi_product.height = '';
			this.pi_product.sec_slot = '';
			this.pi_product.slots = '';
			this.pi_product.active = false;
			this.pi_product.physical_configuration = '';
			this.pi_product.orientation = '';
			this.pi_product.product_ppplication = '';

			$('#product-form').modal('show');
		},

		storeScreen: function () {
			axios.post('/admin/site/site-screen-product/store', this.pi_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})
		},

		editProduct: function (id) {
			axios.get('/admin/site/site-screen-product/' + id)
				.then(response => {
					this.add_record = false;
					this.edit_record = true;

					var pi_product = response.data.data;
					this.pi_product.id = pi_product.id;
					this.pi_product.site_screen_id = pi_product.site_screen_details;
					this.pi_product.ad_type = pi_product.ad_type;
					this.pi_product.description = pi_product.description;
					this.pi_product.width = pi_product.width;
					this.pi_product.height = pi_product.height;
					this.pi_product.sec_slot = pi_product.sec_slot;
					this.pi_product.slots = pi_product.slots;
					this.pi_product.active = pi_product.active;

					this.pi_product.physical_configuration = pi_product.site_screen_details.physical_size_width +' x '+pi_product.site_screen_details.physical_size_height;
					this.pi_product.orientation = pi_product.site_screen_details.orientation;
					this.pi_product.product_application = pi_product.site_screen_details.product_application;

					this.ad_types = this.ad_types.filter(option => option.product_application == this.pi_product.product_application);					

                    $('#product-form').modal('show');
                });
            },

		updateScreen: function () {
			axios.put('/admin/site/site-screen-product/update', this.pi_product)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#product-form').modal('hide');
				})
		},

		downloadCsv: function () {
			axios.get('/admin/site/site-screen-product/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

	},

	components: {
		Table,
		Multiselect
	}
};
</script> 
