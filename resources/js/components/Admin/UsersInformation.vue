<template>
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card m-3">
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<div class="text-center">
										<img :src="material" class="profile-user-img img-fluid img-circle" />
									</div>
									<div class="text-center">{{ user.email }}</div>
								</div>
								<div class="col-md-3">
									<div class="form-group row mb-0">
										<label for="firstName" class="col-sm-4">Last Name</label>
										<div class="col-sm-8">
											{{ user.last_name }}
										</div>
									</div>
									<div class="form-group row mb-0">
										<label for="firstName" class="col-sm-4">First Name</label>
										<div class="col-sm-8">
											{{ user.first_name }}
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group row mb-0">
										<label for="firstName" class="col-sm-4">Mobile</label>
										<div class="col-sm-8">
											{{ user.mobile }}
										</div>
									</div>
									<div class="form-group row mb-0">
										<label for="firstName" class="col-sm-4">Address</label>
										<div class="col-sm-8">
											{{ user.address }}
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<!-- /.col -->
				<div class="col-md-12">
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#activity_logs"
										data-toggle="tab">Activity
										Logs</a>
								</li>
								<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a>
								</li>
							</ul>
						</div><!-- /.card-header -->
						<div class="card-body">
							<div class="tab-content">
								<div class="active tab-pane" id="activity_logs">
									<div class="card">
										<div class="card-body">
											<Table :dataFields="dataFields" :dataUrl="dataUrl" :primaryKey="primaryKey"
												ref="dataTable">
											</Table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="settings">
									<!-- <form class="form-horizontal"> -->
									<div class="form-group row">

										<div class="col-sm-10">
											<input type="file" accept="image/*" ref="material" @change="materialChange">
											<footer class="blockquote-footer">Max file size is 15MB</footer>
										</div>
									</div>
									<div class="form-group row">
										<label for="firstName" class="col-sm-2 col-form-label">First Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" v-model="user.first_name"
												placeholder="First Name">
										</div>
									</div>
									<div class="form-group row">
										<label for="lastName" class="col-sm-2 col-form-label">Last Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" v-model="user.last_name"
												placeholder="Last Name">
										</div>
									</div>
									<div class="form-group row">
										<label for="mobile" class="col-sm-2 col-form-label">Mobile Number</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" v-model="user.mobile"
												placeholder="Mobile Number">
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-sm-2 col-form-label">Address</label>
										<div class="col-sm-10">
											<textarea class="form-control" rows="4" v-model="user.address"
												placeholder="Address" required></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="lastName" class="col-sm-2 col-form-label">Password</label>
										<div class="col-sm-8">
											<input type="password" class="form-control" v-model="user.password"
												placeholder="Password">
										</div>
									</div>
									<div class="form-group row">
										<label for="passwordConfirmation" class="col-sm-2 col-form-label">Password
											Confirmation</label>
										<div class="col-sm-8">
											<input type="password" class="form-control" v-model="user.password_confirmation"
												placeholder="Password Confirmation">
										</div>
									</div>
									<div class="form-group row">
										<div class="col-sm-2 text-right">
											<button type="button" class="btn btn-primary btn-sm" @click="updateUser">Save
												Changes</button>
										</div>
									</div>
									<!-- </form> -->
								</div>
								<!-- /.tab-pane -->
							</div>
							<!-- /.tab-content -->
						</div><!-- /.card-body -->
					</div>
					<!-- /.card -->
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import Multiselect from 'vue-multiselect';

export default {
	name: "Users_Information",
	data() {
		return {
			helper: new Helpers(),
			user: {
				id: '',
				full_name: '',
				email: '',
				first_name: '',
				last_name: '',
				password: null,
				password_confirmation: null,
				profile_image: '',
				mobile: '',
				address: '',
			},
			material: '/images/user-icon.png',
			dataFields: {
				last_login: "Last Login",
				last_password_reset: "Last Password Reset",
				module_accessed: "Module Accessed",
				query: "Query",
				old_bindings: "(Old) Bindings",
				bindings: "(New) Bindngs",
			},
			primaryKey: "id",
			dataUrl: "/admin/activity-logs/list",
		};


	},
	created() {
		this.editUser();
	},
	methods: {
		materialChange: function (e) {
			const file = e.target.files[0];
			this.material = URL.createObjectURL(file);
			this.user.profile_image = file;
		},
		editUser: function () {
			axios.get('/admin/users-information/details')
				.then(response => {
					var user = response.data.data;
					this.user.id = user.id;
					this.user.full_name = user.full_name;
					this.user.email = user.email;
					this.user.first_name = user.details.first_name;
					this.user.last_name = user.details.last_name;
					this.material = user.profile_image;
					this.user.mobile = user.details.mobile;
					this.user.address = user.details.address;
				});
		},

		updateUser: function () {
			let formData = new FormData();
			formData.append("id", this.user.id);
			formData.append("email", this.user.email);
			formData.append("first_name", this.user.first_name);
			formData.append("last_name", this.user.last_name);
			formData.append("mobile", this.user.mobile);
			formData.append("address", this.user.address);
			formData.append("profile_image", this.user.profile_image);
			formData.append("password", this.user.password);
			formData.append("password_confirmation", this.user.password_confirmation);
			axios.post('/admin/users-information/update-profile', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
				})
		},

	},
	components: {
		Table,
	}

};
</script>