
var Helpers = function() {}
var bannerCount = [];

Helpers.prototype = {
    getFileExtension: function(fileType) {	
        if(fileType) {
            fileExt = fileType.split('.').pop();    
        }
        else {
            fileExt = fileType;
        }
        
        switch(fileExt) {
            case 'ogg':
            case 'ogv':
            case 'mp4':
            case 'wmv':
            case 'avi':
            case 'mkv':
            case 'video/ogg':
            case 'video/ogv':
            case 'video/mp4':
            case 'video/wmv':
            case 'video/avi':
            case 'video/mkv':
                return 'video';
                break;
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'gif':
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/png':
            case 'image/gif':
                return 'image';
                break;
            default:
                return false;
                break;
        }
    },

    decodeEntities: function(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    },

    saveLogs: function(params, page = '') {
        params['page'] = page;
        $.post( "/api/v1/save-logs", params ,function(response) {
            console.log(response);
        });
    },

    updateLikeCount: function(id) {
        var action;
        if($(".btn-heart").hasClass("fas")) {
            action = 'minus';
            $(".btn-heart").removeClass('fas').addClass('far');
        }
        else {
            action = 'add';
            $(".btn-heart").removeClass('far').addClass('fas');
        }

        let params = {
            id: id,
            action: action
        }

        $.post( "/api/v1/like-count", params ,function(response) {
            $('.like_counts').html(response.like_count);
        });
    },

    updateViewCount: function(id, count) {

        $.post( "/api/v1/view-count", {id: id} ,function(response) {
            $('.view-count').html(response.view_count);
        });

    },

    setTenantCountDetails: function(id) {
        let params = {
            id: id
        }

        $.post( "/api/v1/tenant-count-details", params ,function(response) {
            $('.like_counts').html(response.like_count);
        });
    },

    removeLoader: function(){
        $( "#loadingDiv" ).fadeOut(500, function() {
            // fadeOut complete. Remove the loading div
            $( "#loadingDiv" ).remove(); //makes page more lightweight 
        });  
    },

    setTranslation: function() {
        var translations_by_language;
        if (content_language != 'english') {
            translations_by_language = my_translations.filter(option => option.language == content_language);
        }else {
            translations_by_language = my_translations;
        }

        $(".translateme").each(function(){
            let data_en = $(this).attr('data-en');
            var translated = translations_by_language.find(option => option.english == data_en);

            if (translated != null) {                         
                if (content_language != 'english') {
                    $(this).html(translated.translated);
                } else {
                    $(this).html(translated.english);
                }
            }

        });

        if (content_language == 'japanese') {
            $('.resize').css({'font-size':'22px'});
            $('.resize').css({'line-height':'19px'});
        }
        else if(content_language == 'korean') {
            $('.resize').css({'font-size':'23px'});
            $('.resize').css({'line-height':''});
        }        
        else {
            $('.resize').css({'font-size':''});
            $('.resize').css({'line-height':''});
        }
    },

    filterAssist: function() {
        current_assistant_messages = my_assistant_message.filter(option => option.location == current_location && option.content_language == content_language);
    },

    screenUpTime: function(screen_id) {
        $.post( "/api/v1/screen-uptime", { site_screen_id: screen_id } , function( data ) {
            console.log(data);
        }); 
    },

    homeBtnClick: function() {
        $('.content-holder, .back-img-btn, .modal').hide();
        $('#home-container, #ImgMallLogo').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-home').addClass("active");
        $('#videocontainer').html('');

        sub_categories = '';
        main_category = '';
        supplementals = '';
        alphabetical = '';
        tenant_list = '';
        current_location = 'home';
        page_history = [];
    },

    searchBtnClick: function() {
        $('.content-holder, .back-img-btn, .modal, .search-for, #searchNone, #searchList').hide();
        $('#search-container, #ImgMallLogo, #keyboard-section, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-search').addClass("active");
        $('#code').val('');
        $('#videocontainer').html('');
        current_location = 'searchbox';
    },

    mapBtnClick: function() {
        $('.content-holder, .modal').hide();
        $('#map-container, #ImgMallLogo, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-map').addClass("active");
        $('#videocontainer').html('');
        $('#btnresetmap').click();
        current_location = 'map';
    },

    promosBtnClick: function() {
        $('.content-holder, .modal').hide();
        $('#promos-container, #ImgMallLogo, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-promo').addClass("active");
        $('#videocontainer').html('');
        current_location = 'promo';
    },

    eventsBtnClick: function() {
        $('.content-holder, .modal').hide();
        $('#events-container, #ImgMallLogo, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-event').addClass("active");
        $('#videocontainer').html('');
        current_location = 'events';
    },
    
    cinemaBtnClick: function() {
        $('.content-holder, .modal').hide();
        $('#cinema-container, #ImgMallLogo, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-cinema').addClass("active");
        $('#videocontainer').html('');
        $('#Tab-Cinema-Tab').click();
        current_location = 'cinema';
    },

    backToTenant: function() {
        $('#search_v4, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo, #keyboard-section').show();
        $('#search-container, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage, .modal, .search-for, #searchNone, #searchList').hide();
        $('#search_txt, #map_txt, #promos_txt, #cinema_txt').removeClass("nav-btn-active");
        $('#home_txt').addClass("nav-btn-active");

        $('#promos-container').hide();
        $('#home-container').hide();
        $('#home-cat-contents').hide();
        $('#search-container').hide();
        $('#tenant-store-content').show();
    },

    backToTenantCategory: function() {
        $('.CatTabCategories').hide();
        $('#tenant-store-content').hide();
        $('#promos-container').hide();
        $('#home-container').hide();
        $('#search-container').hide();
        $('#tenant-store-content').hide();
        $('#home-cat-contents').show();
        $('.TenantPage').show();
        $('#home_v4').show();
        $('#home_v4s').hide();
    },

    backToSupplemental: function() {
        $('#search_v4, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo, #keyboard-section').show();
        $('#search-container, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage, .modal, .search-for, #searchNone, #searchList').hide();
        $('#search_txt, #map_txt, #promos_txt, #cinema_txt').removeClass("nav-btn-active");
        $('#home_txt').addClass("nav-btn-active");
        $('#home-cat-contents').show();
        if($('.TenantPage').is(":visible")) {
            $('.TenantPage').hide();
            $('.CatTabCategories').show();    
        }
    },

    backToAlphabet: function() {
        $('#search_v4, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo, #keyboard-section').show();
        $('#search-container, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage, .modal, .search-for, #searchNone, #searchList').hide();
        $('#search_txt, #map_txt, #promos_txt, #cinema_txt').removeClass("nav-btn-active");
        $('#home_txt').addClass("nav-btn-active");
        $('#home-cat-contents').show();
        if($('.TenantPage').is(":visible")) {
            $('.CatTabCategories').hide();
            $('.TenantPage').show();
            $('#Tab-Alphabetical-tab').click();
            page_history.splice(-1);    
        }
        else {
            $('#Tab-Alphabetical-tab').click();
            page_history.splice(-1);
        }
    },

    backToSubcategory: function() {
        $('#search_v4, #home_v4s, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo, #keyboard-section').show();
        $('#search-container, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage, .modal, .search-for, #searchNone, #searchList').hide();
        $('#search_txt, #map_txt, #promos_txt, #cinema_txt').removeClass("nav-btn-active");
        $('#home_txt').addClass("nav-btn-active");
        $('.CatTabCategories').hide();
        $('.TenantPage').show();
        $('#Tab-Category-Tab').click();
        page_history.splice(-1);
    },

    backToSearchresult: function() {
        $('#home_txt, #map_txt, #promos_txt, #cinema_txt').removeClass("nav-btn-active");
        $('#search_txt').addClass("nav-btn-active");
        $('.TenantPage').hide();
        $('#tenant-store-content').hide();
        $('#keyboard-section').hide();
        $('.search-for').show();
        $('#search-container').show();
        $('#searchList').show();
    },

    backToSearchnoresult: function() {
        $('#search-container, #search_v4s, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo, #keyboard-section, .back-img-btn').show();
        $('#home-container, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage, .modal, .search-for, #searchNone, #searchList').hide();
        $('#home_txt, #map_txt, #promos_txt, #cinema_txt').removeClass("nav-btn-active");
        $('#search_txt').addClass("nav-btn-active");
        $('.search-for').show();
        $('#searchNone').show();
        $('#keyboard-section').hide();
        $('#searchList').hide();
    },

    backToCinemaschedule: function() {
        $('#cinema-container, #cinema_v4s, #home_v4, #search_v4, #promos_v4, #map_v4, #ImgMallLogo, #keyboard-section, .back-img-btn').show();
        $('#home-container, #home-cat-contents, #search-container, #map-container, #promos-container, #cinema_v4, #home_v4s, #search_v4s, #promos_v4s, #map_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage, .modal, .search-for, #searchNone, #searchList').hide();
        $('#home_txt, #map_txt, #promos_txt, #search_txt').removeClass("nav-btn-active");
        $('#cinema_txt').addClass("nav-btn-active");
        $('#videocontainer').html('');
        $('#Tab-Schedule-tab').click();
        page_history.splice(-1);
    }

};