@extends('layout.portal.master')
@section('Page-Title')
<h4>Create Ad</h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Home&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Ads</a></li>
  <li class="breadcrumb-item active">Create Add</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-12">
    @include('layout.portal.company-profile')
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <portal-advertisements></portal-advertisements>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush