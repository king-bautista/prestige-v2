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
						v-on:AddNewContent="AddNewContent"
						v-on:editButton="editContent"
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
		
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';

	export default {
        name: "PlayList",
		
        data() {
            return {
            	dataFields: {
					material_path: {
            			name: "Preview", 
            			type: "logo", 
            		},
					site_name: "Site",
                    screen_location: "Screen Location",
					company_name: "Company Name",
					display_duration: "Duration(s)",
					start_date: "Start Date",
					end_date: "End Date",
					active: {
            			name: "Status", 
            			type:"Boolean", 
            			status: { 
            				0: '<span class="badge badge-danger">Deactivated</span>', 
            				1: '<span class="badge badge-info">Active</span>'
            			}
            		},
                    duration: "Duration"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/play-list/list",
            };
        },

        components: {
        	Table,
     	}
    };
</script>