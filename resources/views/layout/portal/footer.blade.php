<footer class="footer">
    <div class="row justify-content-md-center footer-background m-0">
        @foreach($user->permissions as $permission)
        <div class="col-sm-1 footer-color">
            <a href="{{ $permission->link }}"><strong>{{ $permission->name }}</strong></a>
            @if(count($permission->sub_permissions) > 0)
            <div class="footer-color">
                @foreach($permission->sub_permissions as $sub_menu)
                @if($sub_menu->can_view)
                <a href="{{ $sub_menu->link }}">{{ $sub_menu->name }}</a>
                @endif
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
        <div class="col-sm-1 offset-sm-2">
            <div class="row">
                <div class="col-md-4">
                    <a href="https://www.linkedin.com/company/13257958/">
                        <img src="{{ URL::to('images/linkedin.png') }}" width="22px" height="22px">
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="https://www.facebook.com/PrestigeInteractivePH/">
                        <img src="{{ URL::to('images/facebook.png') }}" width="22px" height="22px">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-color footer-background" style="padding-top:20px">
        <div class="footer-color"><strong class="footer-color">Copyright &copy; 2023 <a href="http://www.prestigeinteractive.com.ph/">PRESTIGE INTERACTIVE</a>.</strong>
            All rights reserved.</div>
    </div>
    
</footer>