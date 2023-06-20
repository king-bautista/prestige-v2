<div class="card card-primary card-outline">
    <div class="card-body">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle" src="{{ URL::to('images/user-icon.png') }}" alt="User profile picture">
        </div>
        <h3 class="profile-username text-center">{{ $user->company->name }}</h3>

        <p class="text-muted text-center">{{ $user->company->classification_name }}</p>

        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <a href="/portal/manage-account/profile"><strong><i class="fa fa-address-card mr-1"></i>&nbsp;&nbsp;Profile</strong></a>
            </li>
            <li class="list-group-item">
                <a href="/portal/manage-account/brands"><strong><i class="fa fa-tags mr-1"></i>&nbsp;&nbsp;Brands</strong></a>
            </li>
            <li class="list-group-item">
                <a href="/portal/property-details"><strong><i class="fa fa-sitemap mr-1"></i>&nbsp;&nbsp;Sites</strong></a>
            </li>
            <li class="list-group-item">
                <a href="/portal/maps"><strong><i class="fas fa-map mr-1"></i>&nbsp;&nbsp;Screens</strong></a>
            </li>
            <li class="list-group-item">
                <a href="#"><strong><i class="fas fa-bell"></i>&nbsp;&nbsp;Notifications</strong></a>
            </li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>