<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 v-show="data_list"><i class="nav-icon fa fa-building"></i>&nbsp;&nbsp;View Tickets</h4>
					</div>
					<div class="card-body" v-show="data_list">
						<Table 
                        :dataFields="dataFields"
                        :dataUrl="dataUrl"
                        :primaryKey="primaryKey"
						ref="dataTable">
			          	</Table>
					</div>
				</div>
			</div>
		</div>
		
    </div>
</template>
<script> 
	var schedules = [];
	import Table from '../Helpers/Table';
	// Import this component
    import datePicker from 'vue-bootstrap-datetimepicker';    
    // Import date picker css
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
	
	export default {
        name: "View_Tickets",
        data() {
            return {
				view_ticket: {
					id: '',
					first_name: '',
                    //name: '',
					// descriptions: '',
					// facebook: '',
					// instagram: '',
					// twitter: '',
					// time_from: '',
					// time_to: '',
					// website: '',
					// active: false,
					// operational_hours: [],
					// site_banner_path: '',
				},
                data_list: true,
				data_form: false,
				is_default: '',
				options: {
                    format: 'hh:mm A',
                    useCurrent: false,
                },
            	dataFields: {
					ticket_id:  "Ticket ID",
            		first_name: "First Name", 
            		last_name:  "Last Name",
					ticket_subject:  "Ticket Subject",
					ticket_description:  "Ticket Decription",
					status_id: {
					name: "Status",
					type: "Boolean",
					status: {
						1: '<span class="badge bg-info">Draft</span>',
						2: '<span class="badge bg-primary">New</span>',
						3: '<span class="badge bg-info">Pending approval</span>',
						4: '<span class="badge bg-danger">Disapprove</span>',
						5: '<span class="badge bg-success">Approved</span>',
						6: '<span class="badge bg-secondary">Saved</span>',
						7: '<span class="badge bg-info">For review</span>',
						8: '<span class="badge bg-success">Archive</span>',
					}
				},
                     updated_at: "Last Updated"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/customer-care/list",
            	
            };
        },

        created(){

        },

        methods: {

        },

        components: {
        	Table,
			datePicker
 	   }
    };
	// export default {
    //     name: "View_Tickets",
    //     data() {
    //         return {
	// 			site: {
	// 				id: '',
    //                 name: '',
	// 				descriptions: '',
	// 				facebook: '',
	// 				instagram: '',
	// 				twitter: '',
	// 				time_from: '',
	// 				time_to: '',
	// 				website: '',
	// 				active: false,
	// 				operational_hours: [],
	// 				site_banner_path: '',
	// 			},
    //             data_list: true,
	// 			data_form: false,
	// 			is_default: '',
	// 			options: {
    //                 format: 'hh:mm A',
    //                 useCurrent: false,
    //             },
    //         	dataFields: {
    //         		name: "Name", 
    //         		descriptions_ellipsis: "Descriptions", 
    //                 site_logo_path: {
    //         			name: "Logo", 
    //         			type:"image", 
    //         		},
    //         		active: {
    //         			name: "Status", 
    //         			type:"Boolean", 
    //         			status: { 
    //         				0: '<span class="badge bg-danger">Deactivated</span>', 
    //         				1: '<span class="badge bg-info text-dark">Active</span>'
    //         			}
    //         		},
    //                 updated_at: "Last Updated"
    //         	},
    //         	primaryKey: "id",
    //         	dataUrl: "/portal/property-details/list",
            	
    //         };
    //     },

    //     created(){

    //     },

    //     methods: {

    //     },

    //     components: {
    //     	Table,
	// 		datePicker
 	//    }
    // };
</script> 
