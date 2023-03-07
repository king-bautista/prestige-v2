@extends('layout.portal.master')
@section('Page-Title')
<h4>User</h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
  <li class="breadcrumb-item active">Brands</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<portal-brands></portal-brands>
<!-- /.content -->
@stop

@push('scripts')    
@endpush
