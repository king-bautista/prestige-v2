@extends('layout.kiosk_default.master-themes')
@section('content')

<div id='loadingDiv'><video muted='muted' autoplay loop><source src="{{ URL::to('themes/sm_default/images/loading_page.ogv') }}">Your browser does not support the video tag.</video></div>
<!--PORTRAIT LOADING VIDEO --> 
<!-- <div id='loadingDiv'><video muted='muted' autoplay loop><source src="{{ URL::to('themes/sm_default/images/loading_page_portrait.ogv') }}">Your browser does not support the video tag.</video></div> -->

<div class="bg-kiosk tenant-main-content" style="background-image: url('{{ $site->site_background_path}}');">
    <div class="row w-100 m-0">
        <div class="col-xl-3 col-lg-12 dca-1">
            <!--Start of the directory banner-->
            @include('kiosk.sm_default.multirotator.banner')
            <!--End of the directory banner-->
        </div>

        <div class="col-xl-9 col-lg-12 directory-container-content">
            @include('kiosk.sm_default.header')

            <!--Start of the directory about page-->
            <div id="DirectoryAboutPage">
                @include('kiosk.sm_default.pages.about')
            </div>
            <!--End of the directory about page-->

            <!--Start of the directory home content categories-->
                @include('kiosk.sm_default.pages.categories')
            <!--End of the directory home content categories-->

            <!--Start of the directory search content categories-->
            <div id="search-container">
                @include('kiosk.sm_default.pages.search')
            </div>
            <!--End of the directory search content categories-->

            <!--Start of the directory map content categories-->
            <div id="map-container">
                @include('kiosk.sm_default.pages.map')
            </div>
            <!--End of the directory map content categories-->

            <!--Start of the directory promos content categories-->
            <div id="promos-container">
                @include('kiosk.sm_default.pages.promos')
            </div>

            <div id="tenant-store-content">
                @include('kiosk.sm_default.pages.tenant')
            </div>
            <!--End of the directory promo content categories-->

            <!--Start of the directory cinema content categories-->
            <div id="cinema-container">
                @include('kiosk.sm_default.pages.cinema')
            </div>
            <!--End of the directory cinema content categories-->

            <!--Start of the directory navigation content navigation-->
            @include('kiosk.sm_default.nav')
            <!--End of the directory navigation content navigation-->
        </div>
    </div>
</div>
@include('kiosk.sm_default.multirotator.fullpage')

<!-- /.content -->
@stop