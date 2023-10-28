<template>
	<div>
		<div class="row mt-2"  v-if="showHeader">
	        <div class="col-md-3 d-flex align-items-center mb-2">
	            Show
	            <select v-model="perPage" @change="fetchData" class="custom-select custom-select-sm form-control form-control-sm" style="margin-left: 0.5em; margin-right: 0.5em;" :disabled="!meta.total">
	                <option value="5" selected>5</option>
	                <option value="10" selected>10</option>
	                <option value="15" selected>15</option>
	                <option value="30">30</option>
	                <option value="50">50</option>
	                <option value="100">100</option>
	            </select>
	            entries
	        </div>
            <div class="col-md-5 mb-2" style="text-align: right;">
                <div v-if="otherButtons" class="other-button">
                    <div v-for="(action, index) in otherButtons" v-show="condition(action, meta.permissions)">
                        <a type="button" :class="action.class" :title="action.title" v-html="action.icon" @click="buttonAction(action)"></a>
                    </div>
                </div>
            </div>
	        <div class="col-md-4 mb-2">
	            <div class="input-group d-flex align-items-center justify-content-end">
	                Search:
	                <input type="text" class="form-control form-control-sm search-box" placeholder="Search" v-on:keyup="onEnterSearch($event)" style="margin-left: 0.5em;"/>
	                <div class="input-group-append">
	                    <button type="button" class="btn btn-info btn-sm" @click="fetchData"><i class="fas fa-search"></i></button>
	                </div>
	            </div>
	        </div>
	    </div>

		<div class="table-responsive mt-2">
            <table class="table table-hover" id="dataTable" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th v-if="checkBox == true">
                            <!-- <input class="form-check-select" type="checkbox" value="" id="selectAll" name="selectAll" @click="checkedAll"> -->
                        </th>
                        <th v-for="(tHeader, index) in dataFields" v-bind:key="index" class="text-nowrap">
	                        <span v-if="tHeader.type == 'Boolean'">
	                        {{ tHeader.name }}
	                        </span>
                            <span v-else-if="tHeader.type == 'image'">
                            {{ tHeader.name }}
                            </span>
                            <span v-else-if="tHeader.type == 'logo'">
                            {{ tHeader.name }}
                            </span>
                            <span v-else-if="tHeader.type == 'icon'">
                            {{ tHeader.name }}
                            </span>
	                        <span v-else>
	                        {{ tHeader }}
	                        </span>
                    	</th>
                    </tr>
                </thead>
                <tbody>
                	<tr v-for="(data, index) in dataTable" v-bind:key="index" class="has-row-actions">
                        <td v-if="checkBox == true">
                            <div v-if="checkedIds.indexOf(data.id) >= 0">
                                <input class="form-check-select" type="checkbox" :value="data.id" name="selected"  checked="checked" @click="getChecked(data.id,$event)">
                            </div>
                            <div v-else>
                                <input class="form-check-select" type="checkbox" :value="data.id" name="selected" @click="getChecked(data.id,$event)">
                            </div>
                        </td>
                		<td v-for="(tHeader, key, index) in dataFields" v-bind:key="key">
                			<span v-if="tHeader.type == 'Boolean'">
                				<span v-html="tHeader.status[data[key]]"></span>
                			</span>
                            <span v-else-if="tHeader.type == 'image' && helper.getFileExtension(data[key]) == 'image'">
                                <img class="img-thumbnail" :src="data[key]" />
                            </span>
                            <span v-else-if="tHeader.type == 'image' && helper.getFileExtension(data[key]) == 'video'">
                                <video muted="muted" class="img-thumbnail">
                                    <source :src="data[key]" type="video/ogg">
                                    Your browser does not support the video tag.
                                </video>
                            </span>
                            <span v-else-if="tHeader.type == 'logo' && helper.getFileExtension(data[key]) == 'image'">
                                <img class="img-logo" :src="data[key]" />
                            </span>
                            <span v-else-if="tHeader.type == 'logo' && helper.getFileExtension(data[key]) == 'video'">
                                <video muted="muted" class="img-logo">
                                    <source :src="data[key]" type="video/ogg">
                                    Your browser does not support the video tag.
                                </video>
                            </span>
                            <span v-else-if="tHeader.type == 'icon'">
                                <i :class="data[key]" aria-hidden="true"></i>
                            </span>
                			<span v-else>
                			{{ data[key] }}
                			</span>
                			<div class="row-actions" v-if="index==0" style="min-width: 150px;">
	                      		<span v-for="(action, index) in actionButtons" v-bind:key="index" v-show="condition(action, meta.permissions, data)">
	                      			<a href="#" @click="doAction(action.method, action.routeName, action.apiUrl, data[primaryKey], data, action)">
	                      				<span v-html="action.button"></span>
	                      			</a>
	                      		</span>
	                      	</div>
                		</td>
                	</tr>
                </tbody>
            </table>
        </div>

        <div v-if="pre_loader" class="d-flex align-items-center justify-content-center">
            <div class="loader"></div>
        </div>
        
        <div v-if="!dataTable.length && !pre_loader" class="d-flex align-items-center justify-content-center">
            <p>No Record Found</p>
        </div>

        <!-- Pagination  -->
        <div class="row" v-if="meta.total > 0">
            <div class="col-md-5 col-12">
                <div v-if="dataTable.length">
                    Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} entries
                </div>
            </div>
            <div class="col-md-7 col-12">
                <ul class="pagination pagination-sm justify-content-end">
                    <li v-bind:class="[ meta.prev_page_url ? 'page-item' : 'page-item disabled']">
                        <a class="page-link" href="javascript:void(0);" @click="prev()" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0);" @click="prev()" v-if="meta.current_page != 1">{{ meta.current_page - 1 }}</a></li>
                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{ meta.current_page }}</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:void(0);" @click="next()" v-if="meta.current_page < meta.last_page">{{ meta.current_page + 1 }}</a></li>
                    <li class="page-item" v-bind:class="[ meta.next_page_url ? 'page-item' : 'page-item disabled']">
                        <a class="page-link" href="javascript:void(0);" @click="next()">Next</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
              </div>
              <div class="modal-body">
                <h6>Do you really want to delete?</h6>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
                <button type="button" class="btn btn-primary" @click="remove">OK</button>
              </div>
            </div>
          </div>
        </div>
	</div>
</template>
<script>  
    export default {
        name: "Table",
        props: {
        	dataFields: {
        		type: Object,
        		required: true
        	},
        	dataUrl: {
        		type: String,
        		required: true
        	},
            dataParams: {
                type: Boolean,
                required: false
            },
        	actionButtons: {
        		type: Object,
        		required: false
        	},
        	primaryKey: {
        		type: String,
        		required: false
        	},
            checkBox: {
                type: Boolean,
                required: false
            },
            checkedIds: {
                type: Array,
                required: false
            },
            otherButtons: {
                type: Object,
                required: false
            },
            showHeader: {
                type: Boolean,
                required: false,
                default: true
            },
            rowPerPage: {
                type: Number,
                required: false,
                default: 15
            }
        },

        data() {
        	return {
            	page: 1,
            	perPage: this.rowPerPage,
        		search: '',
        		dataTable: [],
        		meta: [],
                itemSelected: [],
                deleteUrl: '',
                tobeDeleted: 0,
                filters: [],
                helper: new Helpers(),
                pre_loader: false,
        	}
        },

        created() {
        	this.fetchData();
        },

        methods: {
            condition(action, permission, data) {
                if(!permission)
                    return false;

                if(action.conditions) {
                    var condition = false;
                    for (var keyname in action.conditions) {
                        var key_value = action.conditions[keyname];
                        if(data[keyname] == key_value) {
                            condition = true;
                        }
                        else {
                            return false;
                        }
                    }
                    return condition;
                }

                switch(action.method) {
                    case 'edit':
                        if(permission.can_edit > 0)
                            return true;
                        break;
                    case 'delete':
                        if(permission.can_delete > 0)
                            return true;
                        break;
                    case 'add':
                        if(permission.can_add > 0)
                            return true;
                        break;
                    case 'link':
                        if(permission.can_add > 0)
                            return true;
                        break;
                    case 'custom_delete':
                        if(permission.can_delete > 0)
                            return true;
                        break;
                    case 'view':
                        if(permission.can_add > 0)
                            return true;
                        break;
                }

                return false;
            },

        	fetchData() {
                //var id = this.$route.params.id;
                this.pre_loader = true;
                var data_url = this.dataUrl;
                if(this.dataParams)
                    data_url = this.dataUrl+'/'+id;

        		axios.get(data_url, {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        search: this.search,
                        filters: this.filters,
                    }
                })
                .then(response => {
                    this.dataTable = [];
                    this.dataTable = response.data.data;
                    this.meta = response.data.meta;
                    this.pre_loader = false;
                    window.scrollTo(0,0);
                })
        	},

        	onEnterSearch(e) {
                this.search = e.target.value;
                if (e.keyCode === 13) {
                    this.page = 1;
                    this.fetchData()
                } 
            },

            doAction(method, routeName, apiUrl, id=0, data, object) {

            	switch(method) {

                    case "edit":
                        this.$emit("editButton", id);
                    break;

                    case "delete":
                        this.deleteUrl = apiUrl;
                        this.tobeDeleted = id;
                        $('#deleteModal').modal('show');
                    break;

                    case "custom_delete":
                        this.$emit(object.v_on, data);
                    break;

            		// case "link": // link for SPA only
            		// 	this.$router.push({name: routeName, params: { id: id}});
            		// break;

                    case "link":
                        window.location.href = apiUrl+'/'+id;
            		break;

                    case "view":
                        if(object.v_on) {
                            this.$emit(object.v_on, data);
                        }
                        else {
                            this.$emit("editButton", data);
                        }
                    break;

            	}
            },

            buttonAction(object) {
                if(object.downloadLink) {
                    const link = document.createElement('a')
                    link.href = object.downloadLink
                    link.setAttribute('download', object.file) //or any other extension
                    document.body.appendChild(link)
                    link.click()
                }

                if(object.v_on) {
                    this.$emit(object.v_on);
                }
                    
            },

            remove() {
                axios.get(this.deleteUrl+"/"+this.tobeDeleted)
                .then(response => {
                    this.fetchData();
                    this.deleteUrl = '';
                    this.tobeDeleted = 0;
                    $('#deleteModal').modal('hide');
                });
            },

            close() {
                this.deleteUrl = '';
                this.tobeDeleted = 0;
                $('#deleteModal').modal('hide');
            },

            prev() {
                this.page = this.page -= 1;
                this.fetchData()
            },

            next() {
                this.page = this.page += 1;
                this.fetchData()
            },

            checkedAll() {                
                if($("#selectAll").is(":checked")) {
                    $(":checkbox").attr("checked", true);
                }
                else {
                    $(":checkbox").attr("checked", false);
                }

                var itemSelected = [];
                $(":checkbox").each(function () {

                    var thisVal = (this.checked ? $(this).val() : null);
                    if(thisVal)
                        itemSelected.push(thisVal);
                });
                // this.$emit("getSelectedItems", itemSelected);
            },

            getChecked(id, event) {                
                var checked = 'unchecked';
                if(event) {
                    if(event.target.checked) {
                        checked = 'checked';
                    }
                    else {
                        checked = 'unchecked';
                    }
                }
                this.$emit("getSelectedItems", id, checked);
            },

        }
    };
</script>
<style lang="scss" scoped>
    .form-check-select {
        margin-top: 0.3rem;
        margin-left:0;
    }

    .other-button div {
        margin-right: 5px;
        color: #ffff;
        display:inline-block;
    }

    .row-actions {
        color: #ddd;
        font-size: 13px;
        padding: 2px 0 0;
        position: relative;
        left: -9999em;
    }

	.row-actions span:not(:first-child)::before {
		content: "|";
		color: #007bff;
		margin-left: 5px;
		margin-right: 5px;
		font-weight: bolder;
	}

	.has-row-actions:hover .row-actions {
	    left: 0;
	}

    .img-thumbnail {
        max-width: 15rem;
    }

    .img-logo {
        max-width: 5rem;
    }

    .loader {
        width: 4vmax;
        height: 4vmax;
        border-right: 2px solid #000;
        border-radius: 100%;
        animation: spinRight-9c7a2d6e 800ms linear infinite;
        
        &:before, &:after {
            content: "";
            width: 3vmax;
            height: 3vmax;
            display: block;
            position: absolute;
            top: calc(50% - 1.5vmax);
            left: calc(50% - 1.5vmax);
            border-left: 2px solid #3498db;
            border-radius: 100%;
            animation: spinLeft-9c7a2d6e 800ms linear infinite;
        }
        
        &:after {
            width: 2vmax;
            height: 2vmax;
            top: calc(50% - 1vmax);
            left: calc(50% - 1vmax);
            border: 0;
            border-right: 2px solid #000;
            animation: none;
        }
    }

    @keyframes spinLeft {
        from {transform:rotate(0deg);}
        to {transform:rotate(720deg);}
    }

    @keyframes spinRight {
        from {transform:rotate(360deg);}
        to {transform:rotate(0deg);}
    }

</style>