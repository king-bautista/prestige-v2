<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><i class="nav-icon fas fa-user-cog"></i>&nbsp;&nbsp;User Profile</h4>
					</div>
					<div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <h5><i class="fas fa-key"></i>&nbsp;&nbsp;<strong>User Credentials</strong></h5>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <label for="firstName" class="col-sm-4 col-form-label">Photo</label>
                                    <div class="col-sm-4">
                                        <input type="file" accept="image/*" ref="material" @change="materialChange">
                                        <footer class="blockquote-footer">Max file size is 15MB</footer>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <img :src="material" class="profile-user-img img-fluid img-circle" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label">Email <span class="font-italic text-danger"> *</span></label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" v-model="user.email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastName" class="col-sm-4 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" v-model="user.password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastName" class="col-sm-4 col-form-label">Confirm Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" v-model="user.password_confirmation" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 border-left">
                                <div class="form-group row">
                                    <h5><i class="fas fa-info"></i>&nbsp;&nbsp;<strong>User Information</strong></h5>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <label for="firstName" class="col-sm-4 col-form-label">First Name <span class="font-italic text-danger"> *</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" v-model="user.first_name" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastName" class="col-sm-4 col-form-label">Last Name <span class="font-italic text-danger"> *</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" v-model="user.last_name" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label">Mobile Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" v-model="user.mobile" placeholder="Mobile Number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label">Address</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" rows="4" v-model="user.address" placeholder="Address" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
						<div class="form-group row">
							<div class="col-sm-12 text-right">
								<button type="button" class="btn btn-primary btn-sm" @click="updateUser">Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

    </div>
</template>
<script> 
	export default {
        name: "Users",
        data() {
            return {
                user: {
                    id: '',
                    email: '',
                    first_name: '',
                    last_name: '',                   
                    password: '',
                    password_confirmation: '',
                    profile_image: '',
                    mobile: '',
                    address: '',
                },
                material: '/images/user-icon.png',
            };
        },

        created(){
            this.editUser();
        },

        methods: {
            materialChange: function(e) {
				const file = e.target.files[0];
      			this.material = URL.createObjectURL(file);
				this.user.profile_image = file;
			},

			editUser: function() {
                axios.get('/portal/manage-account/0')
                .then(response => {
                    var user = response.data.data;
                    this.user.id = user.id;
                    this.user.email = user.email;
                    this.user.first_name = user.details.first_name;
                    this.user.last_name = user.details.last_name;
                    this.material = user.profile_image;
                    this.user.mobile = user.details.mobile;
                    this.user.address = user.details.address;
                });
            },

            updateUser: function() {
                let formData = new FormData();
				formData.append("id", this.user.id);
				formData.append("email", this.user.email);
				formData.append("first_name", this.user.first_name);
				formData.append("last_name", this.user.last_name);
				formData.append("mobile", this.user.mobile);
				formData.append("address", this.user.address);
				formData.append("profile_image", this.user.profile_image);
                axios.post('/portal/manage-account/update-profile', formData, {
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