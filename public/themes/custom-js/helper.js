
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

    encodeEntities: function(string) {
        return $('<div>').text(string).html();
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
        console.log(my_translations);
        var translations_by_language;
        if (content_language != 'english') {
            translations_by_language = my_translations.filter(option => option.language == content_language);
        }else {
            translations_by_language = my_translations;
        }

        var obj = this;

        $(".translateme").each(function(){
            let data_en = obj.encodeEntities($(this).attr('data-en'));
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

        if(content_language == 'filipino') {
            $('#events_txt').addClass('nav-event-button-align-custom');
        }
        else {
            $('#events_txt').removeClass('nav-event-button-align-custom');
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

    revertToEnglish: function() {
        var txt= 'ENGLISH';
        content_language = 'english';
        this.filterAssist();
        this.setTranslation();
        $('.dropup button').html(txt);
    },

    homeBtnClick: function() {
        $('.content-holder, .back-img-btn, .modal').hide();
        $('#home-container, #ImgMallLogo').show();
        $('.nav-btn-container, #btnpwdchange').removeClass('active btn-prestige-pwd');
        $('.nav-btn-home').addClass("active");
        $('#videocontainer').html('');
        $('.category-img-banner').attr('src', '');        

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
        $('#search-title').html('Search');
        this.resetKeyBoard();
        current_location = 'searchbox';
    },

    mapBtnClick: function() {
        $('.content-holder, .modal, #mapkeyboardoverlay, #mapkeyboard').hide();
        $('#map-container, #ImgMallLogo, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-map').addClass("active");
        $('#videocontainer').html('');
        $('#btnresetmap').click();
        this.resetKeyBoard();
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
        current_location = 'cinema';
    },

    aboutBtnClick: function() {
        $('.content-holder, #ImgMallLogo, .modal').hide();
        $('#DirectoryAboutPage, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
        $('#videocontainer').html('');
        current_location = 'about';
    },

    backToTenant: function() {
        $('.content-holder, .modal').hide();
        $('#tenant-store-content, #ImgMallLogo, .back-img-btn').show();
        $('.nav-btn-container').removeClass('active');
    },

    backToTenantCategory: function() {
        $('.content-holder, .CatTabCategories, .modal').hide();
        $('#home-cat-contents, #ImgMallLogo, .TenantPage').show();
        $('.nav-btn-container').removeClass('active');
        $('#Tab-Category-Tab').click();
        page_history.splice(-1);
    },

    backToSupplemental: function() {
        $('.content-holder, .modal').hide();
        $('#home-cat-contents, #ImgMallLogo, .TenantPage').show();
        $('.nav-btn-container').removeClass('active');
        if($('.TenantPage').is(":visible")) {
            $('.TenantPage').hide();
            $('.CatTabCategories').show();    
        }        
    },

    backToAlphabet: function() {
        $('.content-holder, .modal').hide();
        $('#home-cat-contents, #ImgMallLogo').show();
        $('.nav-btn-container').removeClass('active');
        if($('.TenantPage').is(":visible")) {
            $('.CatTabCategories').hide();
            $('.TenantPage').show();
            $('#Tab-Alphabetical-tab').click();
        }
        else {
            $('#Tab-Alphabetical-tab').click();
        }        
        page_history.splice(-1);    
    },

    backToSubcategory: function() {
        $('.content-holder, .CatTabCategories, .modal').hide();
        $('#home-cat-contents, .TenantPage, #ImgMallLogo').show();
        $('.nav-btn-container').removeClass('active');
        $('#Tab-Category-Tab').click();
        page_history.splice(-1);
    },

    backToSearchresult: function() {
        $('.content-holder, #keyboard-section .modal').hide();
        $('#search-container, .search-for, #searchList').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-search').addClass("active");
    },

    backToSearchnoresult: function() {
        $('.content-holder, #keyboard-section, #searchList .modal').hide();
        $('#search-container, .search-for, #searchNone').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-search').addClass("active");
    },

    backToCinemaschedule: function() {
        $('.content-holder, .modal').hide();
        $('#cinema-container, #ImgMallLogo').show();
        $('.nav-btn-container').removeClass('active');
        $('.nav-btn-cinema').addClass("active");
        $('#videocontainer').html('');
        // $('#Tab-Schedule-tab').click();
        page_history.splice(-1);
    },

    showAmenity: function(site_point) {
        this.mapBtnClick();
        $('.amenity-point').val(site_point);
        $('.amenity-show-location').click();
    },

    convertToProperCase: function(text) {
        text = text.replace(/(\w+)(?:'(\w+))?/g, function(txt){
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });

        return text;
    },

    resetKeyBoard: function() {
        if($('.softkeys-search-page > .softkeys__btn--shift').find('span').text() === 'ABC') {
            $('.softkeys-search-page > .softkeys__btn--shift').click();
        }

        if($('.softkeys-map-page > .softkeys__btn--shift').find('span').text() === 'ABC') {
            $('.softkeys-map-page > .softkeys__btn--shift').click();
        }
    }

};