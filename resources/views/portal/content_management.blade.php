@extends('layout.portal.master')
@section('Page-Title')
<h4>Content Management</h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
  <li class="breadcrumb-item active">Content Management</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-2">
    @include('layout.portal.company-profile')
  </div>
  <div class="col-md-10">
    <portal-content></portal-content>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush

@push('scripts')    
@endpush