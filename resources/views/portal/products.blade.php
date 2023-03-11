@extends('layout.portal.master')
@section('Page-Title')
<h4></h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
  <li class="breadcrumb-item active">Products and Promos - ({{$brand->name}})</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<portal-products></portal-products>
<!-- /.content -->
@stop

@push('scripts')    
@endpush