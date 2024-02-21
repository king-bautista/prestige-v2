<div>
    <div class="back-img-btn" type="button">
        <img class="" src="{{ URL::to('themes/sm_default/images/Back.png') }}">
    </div>

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
		        <a class="dropdown-item dropdown-item-language" data-language="korean">한국어</a>
		        <a class="dropdown-item dropdown-item-language" data-language="japanese">日本人</a>
		        <a class="dropdown-item dropdown-item-language" data-language="chinese">中文</a>
		        <a class="dropdown-item dropdown-item-language" data-language="filipino">FILIPINO</a>
		        <a class="dropdown-item dropdown-item-language" data-language="english">ENGLISH</a>
		    </div>
		</div>
		<div class="language-title">LANGUAGE SELECT</div>
	</div>

	<div class="d-flex nav-content-container" id="NavContentContainer"> 

		<div id="home_btn">
			<div class="nav-btn-container nav-btn-home active">
				<div id="home_txt" class="nav-home-button-align translateme" data-en="Home">Home</div>
			</div>
		</div>

		<div id="search_btn">
			<div class="nav-btn-container nav-btn-search">
				<div id="search_txt" class="nav-search-button-align translateme" data-en="Search">Search</div>
			</div>
		</div>

		<div id="map_btn">
			<div class="nav-btn-container nav-btn-map">
				<div id="map_txt" class="nav-map-button-align translateme" data-en="Map">Map</div>
			</div>
		</div>

		<div id="promos_btn">
			<div class="nav-btn-container nav-btn-promo">
				<div id="promos_txt" class="nav-promo-button-align translateme resize" data-en="Promos">Promos</div>
			</div>
		</div>

        <div id="events_btn">
			<div class="nav-btn-container nav-btn-event">
				<div id="events_txt" class="nav-event-button-align translateme resize" data-en="Events">Events</div>
			</div>
		</div>

		<div id="cinema_btn">
			<div class="nav-btn-container nav-btn-cinema">
				<div id="cinema_txt" class="nav-cinema-button-align translateme" data-en="Cinema">Cinema</div>
			</div>
		</div>

	</div>
</div>

@push('scripts')
<script>
    var translations = "{{ $translations }}";
    var assistant_message = "{{ $assistant_message }}";
    var current_location = 'home';
    var content_language = 'english';
    var my_assistant_message = [];
    var current_assistant_messages = [];
    var my_translations = '';
    var page_history = [];

	/* script for popover */
    $(document).ready(function(){
        if(current_location == 'home')
            $('.back-img-btn').hide();

        my_assistant_message = JSON.parse(helper.decodeEntities(assistant_message));        
        my_translations = JSON.parse(helper.decodeEntities(translations));
        current_assistant_messages = my_assistant_message.filter(option => option.location == current_location && option.content_language == content_language);
        $('[data-toggle="popover"]').popover();   
    });

    $('.dropdown-menu a').on('click', function () {
        var txt= ($(this).text());
        content_language = $(this).data('language');
        helper.filterAssist();
        helper.setTranslation();
        $('.dropup button').html(txt);
    });

    /* script for random content of popover */
    $('[data-toggle="popover"]').on("click", function () {
        var contentIndex = Math.floor(Math.random() * current_assistant_messages.length);
        var newMessage = helper.decodeEntities(current_assistant_messages[contentIndex].content);
        // Set the 'data-content' attribute for the popover
        $(this).attr("data-content", newMessage);
    });

    $('.back-img-btn').on('click', function() {
        var index = 0;
        page_history.splice(-1);

        if(page_history.length == 0) {
            index = 0;
        }
        else {
            index = page_history.length-1;
        }

        if(page_history[index] == undefined) {
            $("#home_btn").click();
        }
        else {
            switch(page_history[index]) {
                case 'searchbox':
                    helper.searchBtnClick();
                    break;
                case 'map':
                    helper.mapBtnClick();
                    break;
                case 'promo':
                    helper.promosBtnClick();
                    break;
                case 'event':
                    helper.eventsBtnClick();
                    break;
                case 'cinema':
                    helper.cinemaBtnClick();
                    break;
                case 'tenant':
                    helper.backToTenant();
                    break;
                case 'tenantcategory':
                    helper.backToTenantCategory();
                    break;
                case 'supplemental':
                    helper.backToSupplemental();
                    break;
                case 'alphabet':
                    helper.backToAlphabet();
                    break;
                case 'subcategory':
                    helper.backToSubcategory();
                    break;
                case 'searchresult':
                    helper.backToSearchresult();
                    break;
                case 'searchnoresult':
                    helper.backToSearchnoresult();
                    break;
                case 'cinemaschedule':
                    helper.backToCinemaschedule();
                    break;
            }
        }
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
        helper.homeBtnClick();
        helper.filterAssist();
    });

    /* for search button */
    $("#search_btn").on('click', function(){
        helper.searchBtnClick();
        helper.filterAssist();
        page_history.push(current_location);
    });

    /* for map button */
    $("#map_btn").on('click', function(){
        helper.mapBtnClick();
        helper.filterAssist();
        page_history.push(current_location);
    });

    /* for promos button */
    $("#promos_btn").on('click', function(){        
        helper.promosBtnClick();
        helper.filterAssist();
        showPromos();
        page_history.push(current_location);
    });

    $("#events_btn").on('click', function(){        
        helper.eventsBtnClick();
        helper.filterAssist();
        showEvents();
        page_history.push(current_location);
    });

    /* for cinema button */
    $("#cinema_btn").on('click', function(){
        helper.cinemaBtnClick();
        helper.filterAssist();
        showCinemas();
        page_history.push(current_location);
        console.log(page_history);
    });
	
</script>
@endpush
