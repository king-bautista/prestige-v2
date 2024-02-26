<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fa fa-tags"></i>&nbsp;&nbsp;User Brands</h4>
					</div>
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

        <!-- Modal Add Brand / Edit Brand -->
        <div class="modal fade" id="brand-form" data-backdrop="static" tabindex="-1" aria-labelledby="brand-form"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="nav-icon fa fa-tags"></i> Add Brand</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="nav-icon fa fa-tags"></i> Edit Brand</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="brands-tab" data-bs-toggle="tab" data-bs-target="#brand-list" type="button" role="tab" aria-controls="brand-list" aria-selected="true"><strong><i class="fas fa-list"></i> From Brands</strong></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="new-brand-tab" data-bs-toggle="tab" data-bs-target="#create-brand" type="button" role="tab" aria-controls="create-brand" aria-selected="false"><strong><i class="fas fa-plus"></i> Create New</strong></button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="brand-list" role="tabpanel" aria-labelledby="brands-tab">
                                <div class="card-body">
                                    <Table :dataFields="brandsDataFields" :dataUrl="brandDataUrl" :primaryKey="brandPrimaryKey"
                                        :actionButtons="brandsActionButtons" v-on:editButton="selectedBrand" :rowPerPage=5
                                        ref="brandsDataTable">
                                    </Table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="create-brand" role="tabpanel" aria-labelledby="new-brand-tab">
                                <div class="card-body mt-3">
                                    <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">Logo</label>
                                        <div class="col-sm-5">
                                            <input type="file" accept="image/*" ref="logo" @change="logoChange">
                                            <footer class="blockquote-footer">image max size is 120 x 120 pixels</footer>
                                        </div>
                                        <div class="col-sm-3 text-center">
                                            <img v-if="logo" :src="logo" class="img-thumbnail" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">Name <span
                                                class="font-italic text-danger"> *</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" v-model="brand.name" placeholder="Brand Name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lastName" class="col-sm-4 col-form-label">Descriptions</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" v-model="brand.descriptions"
                                                placeholder="Descriptions"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lastName" class="col-sm-4 col-form-label">Category <span
                                                class="font-italic text-danger"> *</span></label>
                                        <div class="col-sm-8">
                                            <treeselect v-model="brand.category_id" :options="categories"
                                                placeholder="Select Category" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-4 col-form-label">Supplementals</label>
                                        <div class="col-sm-8">
                                            <multiselect v-model="brand.supplementals" :options="supplementals" :multiple="true"
                                                :close-on-select="true" placeholder="Select Supplementals" label="name"
                                                track-by="name" @select="toggleSelected" @remove="toggleUnSelected">
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-4 col-form-label">Tags</label>
                                        <div class="col-sm-8">
                                            <multiselect v-model="brand.tags" :options="tags" :multiple="true"
                                                :close-on-select="true" placeholder="Select Tags" label="name" track-by="name"
                                                @select="toggleSelectedTags" @remove="toggleUnSelectedTags">
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-show="edit_record">
                                        <label for="active" class="col-sm-4 col-form-label">Active</label>
                                        <div class="col-sm-8">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="active"
                                                    v-model="brand.active">
                                                <label class="custom-control-label" for="active"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary pull-right" v-show="add_record"
                                        @click="storeBrand">Add New Brand</button>
                                    <button type="button" class="btn btn-primary pull-right" v-show="edit_record"
                                        @click="updateBrand">Save Changes</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
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
        name: "UserBrands",
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
                        type: "logo",
                    },
                    name: "Name",
                    category_name: "Category Name",
                    supplemental_names: "Supplementals",
                    active: {
                        name: "Status",
                        type: "Boolean",
                        status: {
                            0: '<span class="badge bg-danger">Inactive</span>',
                            1: '<span class="badge bg-info text-dark">Active</span>'
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
                brandsDataFields: {
                    logo_image_path: {
                        name: "Logo",
                        type: "logo",
                    },
                    name: "Name",
                    active: {
                        name: "Status",
                        type: "Boolean",
                        status: {
                            0: '<span class="badge bg-danger">Inactive</span>',
                            1: '<span class="badge bg-info text-dark">Active</span>'
                        }
                    },
                },
                brandsActionButtons: {
                    edit: {
                        title: 'Add',
                        name: 'Edit',
                        apiUrl: '',
                        routeName: 'content.edit',
                        button: '<i class="fas fa-check-circle"></i> Add',
                        method: 'view'
                    },
                },
                brandPrimaryKey: "id",
                brandDataUrl: "/portal/brand/get-all",
            };
        },

        created(){
            this.GetCategories();
            this.GetSupplementals();
            this.getTags();
        },

        methods: {
            logoChange: function (e) {
                const file = e.target.files[0];
                this.logo = URL.createObjectURL(file);
                this.brand.logo = file;
            },

            GetCategories: function () {
                axios.get('/portal/category/get-parent')
                    .then(response => this.categories = response.data.data);
            },

            GetSupplementals: function () {
                axios.get('/portal/supplemental/get-child')
                    .then(response => this.supplementals = response.data.data);
            },

            getTags: function () {
                axios.get('/portal/brand/get-tags')
                    .then(response => this.tags = response.data.data);
            },

            toggleSelected: function (value, id) {
                this.supplemental_ids.push(value.id);
            },

            toggleUnSelected: function (value, id) {
                const index = this.supplemental_ids.indexOf(value.id);
                if (index > -1) { // only splice array when item is found
                    this.supplemental_ids.splice(index, 1); // 2nd parameter means remove one item only
                }
            },

            toggleSelectedTags: function (value, id) {
                this.tags_ids.push(value.id);
            },

            toggleUnSelectedTags: function (value, id) {
                const index = this.tags_ids.indexOf(value.id);
                if (index > -1) { // only splice array when item is found
                    this.tags_ids.splice(index, 1); // 2nd parameter means remove one item only
                }
            },

            AddNewBrand: function () {
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

            storeBrand: function () {
                let formData = new FormData();
                formData.append("name", this.brand.name);
                formData.append("category_id", this.brand.category_id);
                formData.append("descriptions", this.brand.descriptions);
                formData.append("logo", this.brand.logo);
                formData.append("supplementals", this.supplemental_ids);
                formData.append("tags", this.tags_ids);
                formData.append("active", this.brand.active);

                axios.post('/portal/brand/save', formData, {
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

            editBrand: function (id) {
                axios.get('/portal/brand/' + id)
                .then(response => {
                    var brand = response.data.data;
                    this.brand.id = id;
                    this.brand.category_id = brand.category_id;
                    this.brand.name = brand.name;
                    this.brand.descriptions = brand.descriptions;
                    this.brand.supplementals = brand.brand_details.supplementals;
                    this.brand.tags = brand.tags;
                    this.brand.active = brand.active;
                    this.add_record = false;
                    this.edit_record = true;
                    if (brand.logo) {
                        this.logo = brand.logo_image_path;
                    }
                    else {
                        this.logo = this.brand.logo;
                    }

                    this.$refs.logo.value = null;
                    this.product_view = true;

                    brand.brand_details.supplementals.forEach((value) => {
                        this.supplemental_ids.push(value.id);
                    });

                    brand.brand_details.tags.forEach((value) => {
                        this.tags_ids.push(value.id);
                    });

                    $('#brand-form').modal('show');
                    $('#new-brand-tab').click();
                });
            },

            updateBrand: function () {
                let formData = new FormData();
                formData.append("id", this.brand.id);
                formData.append("name", this.brand.name);
                formData.append("category_id", this.brand.category_id);
                formData.append("descriptions", this.brand.descriptions);
                formData.append("logo", this.brand.logo);
                formData.append("supplementals", this.supplemental_ids);
                formData.append("tags", this.tags_ids);
                formData.append("active", this.brand.active);

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

            selectedBrand: function (data) {
                axios.post('/portal/brand/store', data)
                .then(response => {
                    if (response.data.data.brand_id) {
                        toastr.success('Brand ' + data.name + ' has been added.');
                        this.$refs.dataTable.fetchData();
                    }
                });
            },
            
        },

        components: {
            Table,
            Treeselect,
            Multiselect
        }

    };
</script>