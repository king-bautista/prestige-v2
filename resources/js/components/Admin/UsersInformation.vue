<template>
	<!-- Main content -->
	<section class="content">
		<!-- <div class="container-fluid"> -->
		<div class="row">
			<div class="col-md-4">
				<!-- Profile Image -->
				<div class="card card-primary card-outline">
					<div class="card-body box-profile">
						<div class="text-center">
							<img :src="material" class="profile-user-img img-fluid img-circle" />
						</div>
						<h3 class="profile-username text-center"> {{ user.full_name }}</h3>

						<div class="text-center">{{ user.email }}</div>
						<!-- <div class="form-group row">
									<label for="lastName" class="col-sm-4 col-form-label">Password</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" v-model="user.password"
											placeholder="Password">
									</div>
								</div>
								<div class="form-group row">
									<label for="lastName" class="col-sm-4 col-form-label">Confirm Password</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" v-model="user.password_confirmation"
											placeholder="Confirm Password">
									</div>
								</div> -->

					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header p-2">
						<ul class="nav nav-pills">
							<li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
							</li>
							<li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a>
							</li>
						</ul>
					</div><!-- /.card-header -->
					<div class="card-body">
						<div class="tab-content">
							<div class="active tab-pane" id="settings">
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
										<textarea class="form-control" rows="4" v-model="user.address" placeholder="Address"
											required></textarea>
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
							<div class="tab-pane" id="activity">
								<!-- Post -->
								<div class="post">
									<div class="user-block">
										<!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image"> -->
										<span class="username">
											<a href="#">Jonathan Burke Jr.</a>
											<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
										</span>
										<span class="description">Shared publicly - 7:30 PM today</span>
									</div>
									<!-- /.user-block -->
									<p>
										Lorem ipsum represents a long-held tradition for designers,
										typographers and the like. Some people hate it and argue for
										its demise, but others ignore the hate as they create awesome
										tools to help create filler text for everyone from bacon lovers
										to Charlie Sheen fans.
									</p>

									<p>
										<a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i>
											Share</a>
										<a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i>
											Like</a>
										<span class="float-right">
											<a href="#" class="link-black text-sm">
												<i class="far fa-comments mr-1"></i> Comments (5)
											</a>
										</span>
									</p>

									<input class="form-control form-control-sm" type="text" placeholder="Type a comment">
								</div>
								<!-- /.post -->

								<!-- Post -->
								<div class="post clearfix">
									<div class="user-block">
										<!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
										<span class="username">
											<a href="#">Sarah Ross</a>
											<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
										</span>
										<span class="description">Sent you a message - 3 days ago</span>
									</div>
									<!-- /.user-block -->
									<p>
										Lorem ipsum represents a long-held tradition for designers,
										typographers and the like. Some people hate it and argue for
										its demise, but others ignore the hate as they create awesome
										tools to help create filler text for everyone from bacon lovers
										to Charlie Sheen fans.
									</p>

									<form class="form-horizontal">
										<div class="input-group input-group-sm mb-0">
											<input class="form-control form-control-sm" placeholder="Response">
											<div class="input-group-append">
												<button type="submit" class="btn btn-danger">Send</button>
											</div>
										</div>
									</form>
								</div>
								<!-- /.post -->

								<!-- Post -->
								<div class="post">
									<div class="user-block">
										<!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image"> -->
										<span class="username">
											<a href="#">Adam Jones</a>
											<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
										</span>
										<span class="description">Posted 5 photos - 5 days ago</span>
									</div>
									<!-- /.user-block -->
									<div class="row mb-3">
										<div class="col-sm-6">
											<!-- <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo"> -->
										</div>
										<!-- /.col -->
										<div class="col-sm-6">
											<div class="row">
												<div class="col-sm-6">
													<!-- <img class="img-fluid mb-3" src="../../dist/img/photo2.png" alt="Photo"> -->
													<!-- <img class="img-fluid" src="../../dist/img/photo3.jpg" alt="Photo"> -->
												</div>
												<!-- /.col -->
												<div class="col-sm-6">
													<!-- <img class="img-fluid mb-3" src="../../dist/img/photo4.jpg" alt="Photo"> -->
													<!-- <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo"> -->
												</div>
												<!-- /.col -->
											</div>
											<!-- /.row -->
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->

									<p>
										<a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i>
											Share</a>
										<a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i>
											Like</a>
										<span class="float-right">
											<a href="#" class="link-black text-sm">
												<i class="far fa-comments mr-1"></i> Comments (5)
											</a>
										</span>
									</p>

									<input class="form-control form-control-sm" type="text" placeholder="Type a comment">
								</div>
								<!-- /.post -->
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
		<!-- </div> -->
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</template>
<script>

export default {
	name: "Users Information",
	data() {
		return {
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


};
</script>