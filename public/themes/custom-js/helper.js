
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

    saveBannerCount: function(banner_id) {
        
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
            
        });
    },

    updateViewCount: function(id, count) {
        $.post( "/api/v1/view-count", params ,function(response) {});
    },

};