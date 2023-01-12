var Helpers = function() {}

Helpers.prototype = {
    getFileExtension: function(fileType) {			
        switch(fileType) {
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
    }
};

// export default class Helpers {
//     static getFileExtension(fileType) {			
//         switch(fileType) {
//             case 'ogg':
//             case 'ogv':
//             case 'mp4':
//             case 'wmv':
//             case 'avi':
//             case 'mkv':
//             case 'video/ogg':
//             case 'video/ogv':
//             case 'video/mp4':
//             case 'video/wmv':
//             case 'video/avi':
//             case 'video/mkv':
//                 return 'video';
//                 break;
//             case 'jpeg':
//             case 'jpg':
//             case 'png':
//             case 'gif':
//             case 'image/jpeg':
//             case 'image/jpg':
//             case 'image/png':
//             case 'image/gif':
//                 return 'image';
//                 break;
//             default:
//                 return false;
//                 break;
//         }
//     }
  
//     // static getCategory(userPoints) {
//     //   return userPoints > 70 ? 'A' : 'B';
//     // }

//   }