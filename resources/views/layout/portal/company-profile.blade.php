<div class="card card-primary card-outline">
    <div class="card-body">
        <div class="row">
            <div class="col-1 text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{ URL::to('images/user-icon.png') }}" alt="User profile picture">
            </div>
            <div class="col-11">
                <div class="row">
					<div class="col-md-5">
                        <div class="form-group row m-0">
                            <label for="CompanyName" class="col-md-4 col-form-label"><strong>Company Name:</strong></label>
                            <div class="col-md-8 col-form-label">{{ $user->company->name }}</div>
                        </div>
                        <div class="form-group row m-0">
                            <label for="CompanyName" class="col-md-4 col-form-label"><strong>Classification:</strong></label>
                            <div class="col-md-8 col-form-label">{{ $user->company->classification_name }}</div>
                        </div>
                        <div class="form-group row m-0">
                            <label for="CompanyName" class="col-md-4 col-form-label"><strong>Email:</strong></label>
                            <div class="col-md-8 col-form-label">{{ $user->company->email }}</div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group row mb-0">
                            <label for="firstName" class="col-sm-4 col-form-label"><strong>TIN Number</strong></label>
                            <div class="col-sm-8 col-form-label">{{ $user->company->tin }}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label for="firstName" class="col-sm-4 col-form-label"><strong>Contact Number</strong></label>
                            <div class="col-sm-8 col-form-label">{{ $user->company->contact_number }}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <label for="firstName" class="col-sm-4 col-form-label"><strong>Address</strong></label>
                            <div class="col-sm-8 col-form-label">{{ $user->company->address }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>