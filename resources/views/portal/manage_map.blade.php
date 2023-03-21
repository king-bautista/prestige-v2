@extends('layout.portal.master')
@section('Page-Title')
<h4>Maps</h4>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="#">Manage Property</a></li>
  <li class="breadcrumb-item"><a href="#">Screens Maps</a></li>
  <li class="breadcrumb-item active">Maps : {{$site_screen->name}}</li>
</ol>
@endsection
@section('content')
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
    <portal-manage-maps :site_id="{{$site_screen->site_id}}" :site_screen_id="{{$site_screen->id}}"></portal-manage-maps>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush