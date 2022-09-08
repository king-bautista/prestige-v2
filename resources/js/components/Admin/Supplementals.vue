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
						v-on:AddNewSupplemental="AddNewSupplemental"
						v-on:editButton="editSupplemental"
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
		<div class="modal fade" id="supplemental-form" tabindex="-1" aria-labelledby="supplemental-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Supplemental</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Supplemental</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Supplemental Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="supplemental.name" placeholder="Supplemental Name">
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Category</label>
								<div class="col-sm-8">
									<treeselect v-model="supplemental.category_id"
										:options="categories"
										:normalizer="normalizer"
										placeholder="Select Category"
										/>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="supplemental.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Kiosk Primary Image <span class="font-italic text-danger"> *</span></label>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
                                    <input type="file" accept="image/*" ref="kiosk_image_primary" @change="kioskPrimaryChange">
									<footer class="blockquote-footer">image max size is 349 x 528 pixels</footer>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-10" id="preview">
											<img v-if="kiosk_primary_url" :src="kiosk_primary_url" />
										</div>
										<div class="col-2" v-if="kiosk_primary_url">
											<button @click="deleteImage('kiosk_image_primary')" type="button" class="btn btn-outline-danger"><i class="nav-icon fas fa-trash-alt"></i></button>											
										</div>
									</div>
                                </div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Kiosk Top Image <span class="font-italic text-danger"> *</span></label>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
                                    <input type="file" accept="image/*" ref="kiosk_image_top" @change="kioskTopChange">
									<footer class="blockquote-footer">image max size is 1463 x 73 pixels</footer>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-10" id="preview">
											<img v-if="kiosk_top_url" :src="kiosk_top_url" />
										</div>
										<div class="col-2" v-if="kiosk_top_url">
											<button @click="deleteImage('kiosk_image_top')" type="button" class="btn btn-outline-danger"><i class="nav-icon fas fa-trash-alt"></i></button>											
										</div>
									</div>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Online Primary Image</label>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
                                    <input type="file" accept="image/*" ref="online_image_primary" @change="onlinePrimaryChange">
									<footer class="blockquote-footer">image max size is 349 x 528 pixels</footer>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-10" id="preview">
											<img v-if="online_primary_url" :src="online_primary_url" />
										</div>
										<div class="col-2" v-if="online_primary_url">
											<button @click="deleteImage('online_image_primary')" type="button" class="btn btn-outline-danger"><i class="nav-icon fas fa-trash-alt"></i></button>											
										</div>
									</div>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Online Top Image</label>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
                                    <input type="file" accept="image/*" ref="online_image_top" @change="onlineTopChange">
									<footer class="blockquote-footer">image max size is 1463 x 73 pixels</footer>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-10" id="preview">
											<img v-if="online_top_url" :src="online_top_url" />
										</div>
										<div class="col-2" v-if="online_top_url">
											<button @click="deleteImage('online_image_top')" type="button" class="btn btn-outline-danger"><i class="nav-icon fas fa-trash-alt"></i></button>											
										</div>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeSupplemental">Add New Supplemental</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateSupplemental">Save Changes</button>
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
	// import the styles
	import '@riophae/vue-treeselect/dist/vue-treeselect.css'

	export default {
        name: "Categories",
        data() {
            return {
                supplemental: {
                    id: '',
                    category_id: null,
                    name: '',                
                    kiosk_image_primary: '',                   
                    kiosk_image_top: '',                   
                    online_image_primary: '',                   
                    online_image_top: '',                   
                    active: false,           
                },
                categories: [],
                add_record: true,
                edit_record: false,
				kiosk_primary_url: '',
				kiosk_top_url: '',
				online_primary_url: '',
				online_top_url: '',
            	dataFields: {
            		name: "Name",          		
                    category: "Category", 
                    kiosk_image_primary_path: {
            			name: "Kiosk Primary Image", 
            			type:"image", 
            		}, 
                    kiosk_image_top_path: {
            			name: "Kiosk Top Image", 
            			type:"image", 
            		}, 
                    online_image_primary_path: {
            			name: "Online Primary Image", 
            			type:"image", 
            		}, 
                    online_image_top_path: {
            			name: "Online Top Image", 
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
                    created_at: "Date Created"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/supplemental/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this supplemental',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'supplemental.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this supplemental',
            			name: 'Delete',
            			apiUrl: '/admin/supplemental/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Supplemental',
						v_on: 'AddNewSupplemental',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Supplemental',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
            this.GetParentSupplemental();
        },

        methods: {
			kioskPrimaryChange: function(e) {
				const file = e.target.files[0];
      			this.kiosk_primary_url = URL.createObjectURL(file);
				this.supplemental.kiosk_image_primary = file;
			},

			kioskTopChange: function(e) {
				const file = e.target.files[0];
				this.supplemental.kiosk_image_top = file;
      			this.kiosk_top_url = URL.createObjectURL(file);
			},

			onlinePrimaryChange: function(e) {
				const file = e.target.files[0];
				this.supplemental.online_image_primary = file;
      			this.online_primary_url = URL.createObjectURL(file);
			},

			onlineTopChange: function(e) {
				const file = e.target.files[0];
				this.supplemental.online_image_top = file;
      			this.online_top_url = URL.createObjectURL(file);
			},

			GetParentSupplemental: function() {
				axios.get('/admin/category/get-all-categories')
                .then(response => this.categories = response.data.data);
			},

			AddNewSupplemental: function() {
				this.add_record = true;
				this.edit_record = false;
                this.supplemental.category_id = null;
                this.supplemental.name = '';
                this.supplemental.kiosk_image_primary = '';
                this.supplemental.kiosk_image_top = '';
                this.supplemental.online_image_primary = '';
                this.supplemental.online_image_top = '';
                this.supplemental.active = false;
				this.kiosk_primary_url = '';
				this.kiosk_top_url = '';
				this.online_primary_url = '';
				this.online_top_url = '';
				this.$refs.kiosk_image_primary.value = null;
				this.$refs.kiosk_image_top.value = null;
				this.$refs.online_image_primary.value = null;
				this.$refs.online_image_top.value = null;
              	$('#supplemental-form').modal('show');
            },

            storeSupplemental: function() {
				let formData = new FormData();
				formData.append("category_id", this.supplemental.category_id);
				formData.append("name", this.supplemental.name);
				formData.append("kiosk_image_primary", this.supplemental.kiosk_image_primary);
				formData.append("kiosk_image_top", this.supplemental.kiosk_image_top);
				formData.append("online_image_primary", this.supplemental.online_image_primary);
				formData.append("online_image_top", this.supplemental.online_image_top);

                axios.post('/admin/supplemental/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentSupplemental();
					$('#supplemental-form').modal('hide');
				})
            },

			editSupplemental: function(id) {
                axios.get('/admin/supplemental/'+id)
                .then(response => {
                    var supplemental = response.data.data;
                    this.supplemental.id = supplemental.id;
                    this.supplemental.category_id = (supplemental.category_id) ? supplemental.category_id : null;
                    this.supplemental.name = supplemental.name;
                    this.kiosk_primary_url = supplemental.kiosk_image_primary_path;
                    this.kiosk_top_url = supplemental.kiosk_image_top_path;
                    this.online_primary_url = supplemental.online_image_primary_path;
                    this.online_top_url = supplemental.online_image_top_path;
                    this.supplemental.active = supplemental.active;
					this.supplemental.kiosk_image_primary = '';
					this.supplemental.kiosk_image_top = '';
					this.supplemental.online_image_primary = '';
					this.supplemental.online_image_top = '';
					this.$refs.kiosk_image_primary.value = null;
					this.$refs.kiosk_image_top.value = null;
					this.$refs.online_image_primary.value = null;
					this.$refs.online_image_top.value = null;

					this.add_record = false;
					this.edit_record = true;
                    $('#supplemental-form').modal('show');
                });
            },

            updateSupplemental: function() {
				let formDataUpdate = new FormData();
				formDataUpdate.append("id", this.supplemental.id);
				formDataUpdate.append("category_id", this.supplemental.category_id);
				formDataUpdate.append("name", this.supplemental.name);
				formDataUpdate.append("kiosk_image_primary", this.supplemental.kiosk_image_primary);
				formDataUpdate.append("kiosk_image_top", this.supplemental.kiosk_image_top);
				formDataUpdate.append("online_image_primary", this.supplemental.online_image_primary);
				formDataUpdate.append("online_image_top", this.supplemental.online_image_top);
				formDataUpdate.append("active", this.supplemental.active);

                axios.post('/admin/supplemental/update', formDataUpdate, {
					headers: {
						'Content-Type': 'multipart/form-data'
					},
				})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentSupplemental();
					$('#supplemental-form').modal('hide');
				})
                    
            },

			deleteImage: function(column) {
				axios.post('/admin/supplemental/delete-image',{
					id: this.supplemental.id,
					column: column
				})
				.then(response => {
					toastr.success(response.data.message);
					this.editSupplemental(this.supplemental.id);
					this.$refs.dataTable.fetchData();
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