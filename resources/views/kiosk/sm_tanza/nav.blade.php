<div>
	<div class="help-img-btn" id="Help_btn" data-container="body" data-toggle="popover" data-placement="left" data-content="" type="button">
	  	<img class="" src="{{ URL::to('themes/sm_default/images/Help.png') }}">
	</div>

	<div class="language-btn-container">
		<div class="btn-group dropup">
		    <button type="button" class="bg-transparent border-0 p-0 language-btn-font font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		        ENGLISH
		    </button>
		    <div class="dropdown-menu dropdown-container-adjustment">
		        <!-- Dropdown menu links -->
		        <a class="dropdown-item dropdown-item-language" href="#" data-language="korean">한국어</a>
		        <a class="dropdown-item dropdown-item-language" href="#" data-language="japanese">日本人</a>
		        <a class="dropdown-item dropdown-item-language" href="#" data-language="chinese">中文</a>
		        <a class="dropdown-item dropdown-item-language" href="#" data-language="filipino">FILIPINO</a>
		        <a class="dropdown-item dropdown-item-language" href="#" data-language="english">ENGLISH</a>
		    </div>
		</div>
		<div class="language-title">LANGUAGE SELECT</div>
	</div>

	<div class="d-flex nav-content-container" id = "NavContentContainer"> 

		<div class="nav-btn-home" id="home_btn">
			<div class="nav-btn-container">
				<img id="home_v4s" class="" src="{{ URL::to('themes/sm_default/images/Homev4s.png') }}">
				<img id="home_v4" class="" src="{{ URL::to('themes/sm_default/images/Homev4.png') }}">
				<div id="home_txt" class="nav-home-button-align nav-btn-active">Home</div>
			</div>
		</div>

		<div class="nav-btn-search" id="search_btn">
			<div class="nav-btn-container">
				<img id="search_v4s" class="" src="{{ URL::to('themes/sm_default/images/Searchv4s.png') }}">
				<img id="search_v4" class="" src="{{ URL::to('themes/sm_default/images/Searchv4.png') }}">
				<div id="search_txt" class="nav-search-button-align">Search</div>
			</div>
		</div>

		<div class="nav-btn-map" id="map_btn">
			<div class="nav-btn-container">
				<img id="map_v4s" class="" src="{{ URL::to('themes/sm_default/images/Mapv4s.png') }}">
				<img id="map_v4" class="" src="{{ URL::to('themes/sm_default/images/Mapv4.png') }}">
				<div id="map_txt" class="nav-map-button-align">Map</div>
			</div>
		</div>

		<div class="nav-btn-promo" id="promos_btn">
			<div class="nav-btn-container">
				<img id="promos_v4s" class="" src="{{ URL::to('themes/sm_default/images/Promosv4s.png') }}">
				<img id="promos_v4" class="" src="{{ URL::to('themes/sm_default/images/Promosv4.png') }}">
				<div id="promos_txt" class="nav-promo-button-align">Promos</div>
			</div>
		</div>

		<div class="nav-btn-cinema" id="cinema_btn">
			<div class="nav-btn-container">
				<img id="cinema_v4s" class="" src="{{ URL::to('themes/sm_default/images/Cinemav4s.png') }}">
				<img id="cinema_v4" class="" src="{{ URL::to('themes/sm_default/images/Cinemav4.png') }}">
				<div id="cinema_txt" class="nav-cinema-button-align">Cinema</div>
			</div>
		</div>

	</div>
</div>

@push('scripts')
<script>
	/* script for popover */
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();   
    });

    /* script for random content of popover */
    $('[data-toggle="popover"]').on("click", function () {
        const assistant_messages = [
            "Touch a featured store or your desired store to get directions. You may check the latest product and promo offers. Scroll to view more.",
            "Need help? Touch here.",
            "Looking for outfits? Try searching dress or shirt.",
        ];

        var contentIndex = Math.floor(Math.random() * assistant_messages.length);
        var newMessage = assistant_messages[contentIndex];

        // Set the 'data-content' attribute for the popover
        $(this).attr("data-content", newMessage);
    });

	// for navigation positioning if remove another nav
    var NavContentPositioning = $("#NavContentContainer > div").length;
	if(NavContentPositioning == 1){
		$("#NavContentContainer").removeClass("nav-content-container");
		$("#NavContentContainer").addClass("nav-content-container-4");
	}

	else if(NavContentPositioning == 2){
		$("#NavContentContainer").removeClass("nav-content-container");
		$("#NavContentContainer").removeClass("nav-content-container-1");
		$("#NavContentContainer").removeClass("nav-content-container-2");
		$("#NavContentContainer").removeClass("nav-content-container-4");
		$("#NavContentContainer").addClass("nav-content-container-3");
	}

	else if(NavContentPositioning == 3){
		$("#NavContentContainer").removeClass("nav-content-container");
		$("#NavContentContainer").removeClass("nav-content-container-1");
		$("#NavContentContainer").removeClass("nav-content-container-3");
		$("#NavContentContainer").removeClass("nav-content-container-4");
		$("#NavContentContainer").addClass("nav-content-container-2");
	}

	else if(NavContentPositioning == 4){
		$("#NavContentContainer").removeClass("nav-content-container");
		$("#NavContentContainer").removeClass("nav-content-container-2");
		$("#NavContentContainer").removeClass("nav-content-container-3");
		$("#NavContentContainer").removeClass("nav-content-container-4");
		$("#NavContentContainer").addClass("nav-content-container-1");
	}

	else {
		$("#NavContentContainer").removeClass("nav-content-container-1");
		$("#NavContentContainer").removeClass("nav-content-container-2");
		$("#NavContentContainer").removeClass("nav-content-container-3");
		$("#NavContentContainer").removeClass("nav-content-container-4");
		$("#NavContentContainer").addClass("nav-content-container");
	}

	/* for logo button to view about page */
    $("#ImgMallLogo").on('click', function(){
        $('#DirectoryAboutPage, #search_v4, #home_v4, #map_v4, #promos_v4, #cinema_v4').show();
        $('#ImgMallLogo, #home-container, #home-cat-contents, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage').hide();
        $('#home_txt').removeClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
    });

	/* for home button */
    $("#home_btn").on('click', function(){
        $('#home-container, #search_v4, #home_v4s, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-cat-contents, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#home_txt').addClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
        sub_categories = '';
        main_category = '';
        supplementals = '';
        alphabetical = '';
        tenant_list = '';
        $('#videocontainer').html('');
        $('.modal').hide();
        $('#Tab-Category-Tab').click();        
        $('.search-for').hide();
        $('#searchNone').hide();
        $('#searchList').hide();
        $('#keyboard-section').show();
		$('#home-cat-contents,#search-container,#map-container,#promos-container,#cinema-container').hide();
        $('#home-container').show();
    });

    /* for search button */
    $("#search_btn").on('click', function(){
        $('#search-container, #search_v4s, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#search_txt').addClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
        $('#code').val('');
        $('#videocontainer').html('');
        $('.modal').hide();
        $('.search-for').hide();
        $('#searchNone').hide();
        $('#searchList').hide();
        $('#keyboard-section').show();
    });

    /* for map button */
    $("#map_btn").on('click', function(){
        $('#map-container, #map_v4s, #home_v4, #search_v4, #promos_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #search-container, #promos-container, #cinema-container, #map_v4, #home_v4s, #search_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#map_txt').addClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
        $('#videocontainer').html('');
        $('.modal').hide();
        $('.search-for').hide();
        $('#searchNone').hide();
        $('#searchList').hide();
        $('#keyboard-section').show();
    });

    /* for promos button */
    $("#promos_btn").on('click', function(){
        $('#promos-container, #promos_v4s, #home_v4, #search_v4, #map_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #search-container, #map-container, #cinema-container, #promos_v4, #home_v4s, #search_v4s, #cinema_v4s, #map_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#promos_txt').addClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
        $('#videocontainer').html('');
        $('.modal').hide();
        $('.search-for').hide();
        $('#searchNone').hide();
        $('#searchList').hide();
        $('#keyboard-section').show();
        showPromos();
    });

    /* for cinema button */
    $("#cinema_btn").on('click', function(){
        $('#cinema-container, #cinema_v4s, #home_v4, #search_v4, #promos_v4, #map_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #search-container, #map-container, #promos-container, #cinema_v4, #home_v4s, #search_v4s, #promos_v4s, #map_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#cinema_txt').addClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#videocontainer').html('');
        $('.modal').hide();
        $('#Tab-Cinema-Tab').click();
        $('.search-for').hide();
        $('#searchNone').hide();
        $('#searchList').hide();
        $('#keyboard-section').show();
        showCinemas();
    });
	
</script>
@endpush
