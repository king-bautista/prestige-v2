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
						v-on:addNewCategory="addNewCategory"
						v-on:editButton="editCategory"
						v-on:modalLabels="modalLabels"					
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
		<div class="modal fade" id="category-form" tabindex="-1" aria-labelledby="category-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Category</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Category</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Category Name <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="category.name" placeholder="Category Name">
								</div>
							</div>
							<div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="category.descriptions" placeholder="Descriptions"></textarea>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Parent Category</label>
								<div class="col-sm-8">
									<treeselect v-model="category.parent_id" :options="parent_category" placeholder="Select Parent Category"/>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Class Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="category.class_name" placeholder="Class Name">
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive" v-model="category.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeCategory">Add New Category</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateCategory">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
	  	<div class="modal fade" id="label-form" tabindex="-1" aria-labelledby="label-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Site Labels</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">							
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Category <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<label for="firstName" class="col-sm-4 col-form-label">{{ category_label_for }} </label>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Site <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="category_label.site_id">
										<option value="">Select Site</option>
										<option v-for="site in site_list" :value="site.id"> {{ site.name }}</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Label <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="category_label.label" placeholder="Label">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12 text-right">
									<button type="button" class="btn btn-primary btn-sm" @click="saveLabels">+ Add Label</button>
								</div>
							</div>
							<hr/>
							<table class="table table-hover" style="width:100%">
								<thead class="table-dark">
									<tr>
										<th>Site Name</th>
										<th>Category Name</th>
										<th>Label</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="label in category_labels">
										<td>{{label.site_name}}</td>
										<td>{{label.category_name}}</td>
										<td>{{label.name}}</td>
										<td style="text-align: right;"><button type="button" class="btn btn-danger" title="Delete" @click="deleteLabel(label.id, label.category_id)"><i class="fas fa-trash-alt"></i></button></td>
									</tr>
								</tbody>
							</table>	
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        name: "Categories",
        data() {
            return {
                category: {
                    id: '',
                    parent_id: null,
                    name: '',
                    descriptions: '',                   
                    class_name: '',                   
                    category_type: 1,                   
                    active: false,           
                },
                parent_category: [],
				site_list: [],
				category_label: {
					id: '',
					category_id: '',
					site_id: '',
					label: ''
				},
				category_labels: [],
				category_label_for: '',
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name", 
            		descriptions: "Descriptions",          		
                    parent_category: "Parent Category", 
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
            	dataUrl: "/admin/category/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this category',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'category.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this category',
            			name: 'Delete',
            			apiUrl: '/admin/category/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
					modal: {
						title: 'Site Labels',
						name: 'Site Labels',
						apiUrl: '',
						routeName: '',
						button: '<i class="fa fa-tags" aria-hidden="true"></i> Site Label',
						method: 'view',
						v_on: 'modalLabels',
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Category',
						v_on: 'addNewCategory',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Category',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
            this.getParentCategory();
			this.getSites();
        },

        methods: {
			getParentCategory: function() {
				axios.get('/admin/category/get-parent')
                .then(response => this.parent_category = response.data.data);
			},

			addNewCategory: function() {
				this.add_record = true;
				this.edit_record = false;
                this.category.parent_id = null;
                this.category.name = '';
                this.category.descriptions = '';
                this.category.class_name = '';
                this.category.active = false;
              	$('#category-form').modal('show');
            },

            storeCategory: function() {
                axios.post('/admin/category/store', this.category)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.getParentCategory();
					$('#category-form').modal('hide');
				})
            },

			editCategory: function(id) {
                axios.get('/admin/category/'+id)
                .then(response => {
                    var category = response.data.data;
                    this.category.id = category.id;
                    this.category.parent_id = (category.parent_id) ? category.parent_id : null;
                    this.category.name = category.name;
                    this.category.descriptions = category.descriptions;
                    this.category.class_name = category.class_name;
                    this.category.active = category.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#category-form').modal('show');
                });
            },

            updateCategory: function() {
                axios.post('/admin/category/update', this.category)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.getParentCategory();
					$('#category-form').modal('hide');
				})
                    
            },

			getSites: function() {
				axios.get('/admin/site/get-all')
                .then(response => this.site_list = response.data.data);
			},

			deleteLabel: function(id, category_id) {
				if(id) {
					if(confirm("Do you really want to delete?")) { 
						axios.get('/admin/category/label/delete/'+id)
						.then(response => {
							this.getLabels(category_id);
						});
					}        
				}
			},

			getLabels: function(id) {
				axios.get('/admin/category/labels/'+id)
                .then(response => {
                    this.category_labels = response.data.data;
                });
			},

			modalLabels: function(data) {
				this.category_label.category_id = data.id;
				this.category_label.site_id = '';
				this.category_label.label = '';
				this.category_label_for = data.name;
				this.getLabels(data.id);
				$('#label-form').modal('show');
			},

			saveLabels: function() {
				axios.post('/admin/category/label/store', this.category_label)
				.then(response => {
					toastr.success(response.data.message);
					this.category_label.site_id = '';
					this.category_label.label = '';
					this.getLabels(response.data.data.category_id);
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