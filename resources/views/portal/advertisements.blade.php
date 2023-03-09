@extends('layout.portal.master')
@section('Page-Title')
<h4>Content Master</h4>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige&nbsp;</a></li>
  <li class="breadcrumb-item"><a href="javascript: void(0);">Interactive</a></li>
  <li class="breadcrumb-item active">Content Master</li>
  </ol>
</nav>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-body">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="tab" href="#online" role="tab" aria-selected="true">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">Online</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#banner" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Banners</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#fullscreen" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Fullscreens</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#pop-up" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Pop-Ups</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#event" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Events</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#promo" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Promos</span>
          </a>
        </li>
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane p-3 active show" id="online" role="tabpanel">
          <portal-advertisements-online :ad_type="'Online'"></portal-advertisements-online>
        </div>
        <div class="tab-pane p-3" id="banner" role="tabpanel">
          <portal-advertisements-banner :ad_type="'Banners'"></portal-advertisements-banner>
        </div>
        <div class="tab-pane p-3" id="fullscreen" role="tabpanel">
          <portal-advertisements-fullscreen :ad_type="'Fullscreen'"></portal-advertisements-fullscreen>
        </div>
        <div class="tab-pane p-3" id="pop-up" role="tabpanel">
          <portal-advertisements-pop-up :ad_type="'Pop-Up'"></portal-advertisements-pop-up>
        </div>
        <div class="tab-pane p-3" id="event" role="tabpanel">
          <portal-advertisements-event :ad_type="'Events'"></portal-advertisements-event>
        </div>
        <div class="tab-pane p-3" id="promo" role="tabpanel">
          <portal-advertisements-promo :ad_type="'Promos'"></portal-advertisements-promo>
        </div>
    </div>
  </div>
</div>
<!-- /.content -->
@stop

@push('scripts')    
@endpush
