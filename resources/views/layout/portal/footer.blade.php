<footer class="footer">
    <div class="row">
        @foreach($user->permissions as $permission)
        <div class="col-sm-1">
            <a href="{{ $permission->link }}"></i>{{ $permission->name }}</a>
            @if(count($permission->sub_permissions) > 0)
            <div>
                @foreach($permission->sub_permissions as $sub_menu)
                @if($sub_menu->can_view)
                <a href="{{ $sub_menu->link }}">{{ $sub_menu->name }}</a>
                @endif
                @endforeach
            </div>
            @endif
        </div>
        @endforeach

    </div>
    <div> <strong>Copyright &copy; 2023 <a href="http://www.prestigeinteractive.com.ph/">PRESTIGE INTERACTIVE</a>.</strong> All rights reserved.</div>
</footer>