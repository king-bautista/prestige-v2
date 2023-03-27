@extends('layout.portal.master')
@section('Page-Title')
<h4>FAQ's</h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
  <li class="breadcrumb-item active">FAQ's</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<div class="row">
  <div class="col-md-12">
    <portal-faqs></portal-fags>
  </div>
</div>

<!-- /.content -->
@stop

@push('scripts')    
@endpush