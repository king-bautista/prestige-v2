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
			<div class="modal-dialog modal-dialog-centered modal-lg">
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
								<label for="lastName" class="col-sm-4 col-form-label">Descriptions <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
                                    <textarea class="form-control" v-model="supplemental.descriptions" placeholder="Descriptions"></textarea>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Parent Supplemental</label>
								<div class="col-sm-8">
									<treeselect v-model="supplemental.parent_id" :options="parent_supplementals" placeholder="Select Parent Supplemental"/>
								</div>
							</div>
                            <div class="form-group row">
								<label for="lastName" class="col-sm-4 col-form-label">Category</label>
								<div class="col-sm-8">
									<treeselect v-model="supplemental.category_id" :options="categories" placeholder="Select Category"/>
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
                    parent_id: null,
                    category_id: null,
                    name: '',
                    descriptions: '',                   
                    class_name: '',                   
                    category_type: 2,                   
                    active: false,           
                },
                parent_supplementals: [],
                categories: [],
                add_record: true,
                edit_record: false,
            	dataFields: {
            		name: "Name",          		
            		descriptions: "Descriptions",          		
                    parent_category: "Parent Supplemental", 
                    supplemental_category_name: "Category Name", 
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
			this.GetCategories();
        },

        methods: {
			GetParentSupplemental: function() {
				axios.get('/admin/supplemental/get-parent')
                .then(response => this.parent_supplementals = response.data.data);
			},

			GetCategories: function() {
				axios.get('/admin/category/get-parent')
                .then(response => this.categories = response.data.data);
			},

			AddNewSupplemental: function() {
				this.add_record = true;
				this.edit_record = false;
                this.supplemental.category_id = null;
                this.supplemental.parent_id = null;
                this.supplemental.name = '';
                this.supplemental.descriptions = '';
                this.supplemental.active = false;
              	$('#supplemental-form').modal('show');
            },

            storeSupplemental: function() {
                axios.post('/admin/supplemental/store', this.supplemental)
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
					console.log(supplemental);
                    this.supplemental.id = supplemental.id;
                    this.supplemental.category_id = (supplemental.supplemental_category_id) ? supplemental.supplemental_category_id : null;
                    this.supplemental.parent_id = (supplemental.parent_id) ? supplemental.parent_id : null;
                    this.supplemental.name = supplemental.name;
                    this.supplemental.descriptions = supplemental.descriptions;
                    this.supplemental.active = supplemental.active;
					this.add_record = false;
					this.edit_record = true;
                    $('#supplemental-form').modal('show');
                });
            },

            updateSupplemental: function() {
                axios.post('/admin/supplemental/update', this.supplemental)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					this.GetParentSupplemental();
					$('#supplemental-form').modal('hide');
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