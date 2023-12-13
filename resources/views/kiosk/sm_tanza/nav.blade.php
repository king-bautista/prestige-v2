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
