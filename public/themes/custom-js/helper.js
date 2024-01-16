
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

    updateLikeCount: function(id, count) {
        var tenant_count = 0;
        if($(".btn-heart").hasClass("fas")) {
            tenant_count = parseInt(count) - 1;
            $(".btn-heart").removeClass('fas').addClass('far');
        }
        else {
            tenant_count = parseInt(count) + 1;
            $(".btn-heart").removeClass('far').addClass('fas');
        }

        let params = {
            id: id,
            like_count: tenant_count
        }

        $.post( "/api/v1/like-count", params ,function(response) {
            $('.like_counts').html(tenant_count);
            tenant_likes = tenant_count;
        });
    },

    updateViewCount: function(id, count) {

        var view_count = parseInt(count) + 1;
        let params = {
            id: id,
            view_count: view_count
        }

        $.post( "/api/v1/view-count", params ,function(response) {
            $('.view-count').html(view_count);
        });
    },

    setTenantCountDetails: function(id) {
        let params = {
            id: id
        }

        $.post( "/api/v1/tenant-count-details", params ,function(response) {
            $('.view-count').html(response.view_count);
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
    }

};