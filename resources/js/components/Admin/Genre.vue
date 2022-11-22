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
						v-on:AddNewGenre="AddNewGenre"
						v-on:editButton="editGenre"
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
		<div class="modal fade" id="genres-form" tabindex="-1" aria-labelledby="genres-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New Genre</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Genre</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Genre Code <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="genre.genre_code" placeholder="Genre Code" required>
								</div>
							</div>
                            <div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Genre Label <span class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="genre.genre_label" placeholder="Genre Label" required>
								</div>
							</div>
						</div>
					<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeGenre">Add New Genre</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateGenre">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
      <!-- End Modal Add New User -->
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "Genre",
        data() {
            return {
                genre: {
                    id: '',
                    genre_code: '',
                    genre_label: '',           
                },
                add_record: true,
                edit_record: false,
            	dataFields: {
            		genre_code: "Genre Vode", 
            		genre_label: "Genre Label", 
                    updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/cinema/genre/list",
            	actionButtons: {
            		edit: {
            			title: 'Edit this Genre',
            			name: 'Edit',
            			apiUrl: '',
            			routeName: 'genre.edit',
            			button: '<i class="fas fa-edit"></i> Edit',
            			method: 'edit'
            		},
            		delete: {
            			title: 'Delete this Genre',
            			name: 'Delete',
            			apiUrl: '/admin/cinema/genre/delete',
            			routeName: '',
            			button: '<i class="fas fa-trash-alt"></i> Delete',
            			method: 'delete'
            		},
            	},
				otherButtons: {
					addNew: {
						title: 'New Genre',
						v_on: 'AddNewGenre',
						icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Genre',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
        },

        methods: {
			AddNewGenre: function() {
				this.add_record = true;
				this.edit_record = false;
                this.genre.genre_code = '';
                this.genre.genre_label = '';				
              	$('#genres-form').modal('show');
            },

            storeGenre: function() {
                axios.post('/admin/cinema/genre/store', this.genre)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#genres-form').modal('hide');
				})
            },

			editGenre: function(id) {
                axios.get('/admin/cinema/genre/'+id)
                .then(response => {
                    var genre = response.data.data;
                    this.genre.id = id;
                    this.genre.genre_code = genre.genre_code;
                    this.genre.genre_label = genre.genre_label;
					this.add_record = false;
					this.edit_record = true;
                    $('#genres-form').modal('show');
                });
            },

            updateGenre: function() {
                axios.put('/admin/cinema/genre/update', this.genre)
                    .then(response => {
                        toastr.success(response.data.message);
                        this.$refs.dataTable.fetchData();
                        $('#genres-form').modal('hide');
                    })
            },

        },

        components: {
        	Table
 	   }
    };
</script> 
