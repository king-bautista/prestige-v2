(function ($) {
    var delTimer = 0;
    $.fn.softkeys = function(options) {

        var settings = $.extend({
                layout : [],
                target : '',
                rowSeperator : 'br',
                buttonWrapper : 'li'
            },  options);

        var createRow = function(obj, buttons) {
                for (var i = 0; i < buttons.length; i++) {
                    createButton(obj, buttons[i]);
                }

                obj.append('<'+settings.rowSeperator+'>');
            },

            createButton = function(obj, button) {
                var character = '',
                    type = 'letter',
                    styleClass = '';

                switch(typeof button) {
                    case 'array' :
                    case 'object' :
                        if(typeof button[0] !== 'undefined') {
                            character += '<span>'+button[0]+'</span>';
                        }
                        if(typeof button[1] !== 'undefined') {
                            character += '<span>'+button[1]+'</span>';
                        }
                        type = 'symbol';
                        break;

                    case 'string' :
                        switch(button) {
                            case 'capslock' :
                                character = '<span>caps</span>';
                                type = 'capslock';
                                break;

                            case 'shift' :
                                character = '<span>#+=</span>';
                                type = 'shift';
                                styleClass = 'softkeys__btn--shift';
                                break;

                            case 'return' :
                                character = '<span>return</span>';
                                type = 'return';
                                break;

                            case 'tab' :
                                character = '<span>tab</span>';
                                type = 'tab';
                                break;

                            case 'space' :
                                character = '<span>SPACE</span>';
                                type = 'space';
                                styleClass = 'softkeys__btn--space';
                                break;

                            case 'Search' :
                                character = '<span>Enter</span>';
                                type = 'symbol';
                                styleClass = 'softkeys__btn--search';
                                break;

                            case 'delete' :
                                character = '<span>delete</span>';
                                type = 'delete';
                                styleClass = 'softkeys__btn--delete';
                                break;

                            case '&apos;' :
                                character = '<span>&apos;</span>';
                                type = 'symbol';
                                styleClass = 'softkeys__btn--hidden';
                                break;
                            case '&comma;' :
                                character = '<span>&comma;</span>';
                                type = 'symbol';
                                styleClass = 'softkeys__btn--hidden';
                                break;

                            case '&period;' :
                                character = '<span>&period;</span>';
                                type = 'symbol';
                                styleClass = 'softkeys__btn--hidden';
                                break;

                            default :
                                character = button;
                                type = 'letter';
                                break;
                        }

                        break;
                }

                obj.append('<'+settings.buttonWrapper+' class="softkeys__btn  '+styleClass+'" data-type="'+type+'">'+character+'</'+settings.buttonWrapper+'>');
            },

            bindKeyPress = function(obj) {
                obj.children(settings.buttonWrapper).on('click touchstart', function(event){
                    event.preventDefault();

                    var character = '',
                        type = $(this).data('type'),
                        targetValue = $(settings.target).val();

                    switch(type) {
                        case 'capslock' :
                            toggleCase(obj);
                            break;

                        case 'shift' :
                            toggleCase(obj);
                            toggleAlt(obj);
                            break;

                        case 'return' :
                            character = '\n';
                            break;

                        case 'tab' :
                            character = '\t';
                            break;

                        case 'space' :
                            character = ' ';
                            break;

                        case 'delete' :
                            targetValue = targetValue.substr(0, targetValue.length - 1);
                            break;

                        case 'symbol' :

                            if(obj.hasClass('softkeys--alt')) {
                                if($(this).children('span').eq(1).html() === 'Search') {

                                }
                                else {
                                    character = $(this).children('span').eq(1).html().replace("&amp;", "&");
                                }
                            } else if($(this).children('span').eq(0).html() === 'Enter') {

                            } else if($(this).children('span').eq(0).html() === 'Search') {

                            }
                            else {
                                character = $(this).children('span').eq(0).text();
                            }
                            break;

                        case 'letter' :
                            character = $(this).html();

                            if(obj.hasClass('softkeys--caps')) {
                                character = character.toUpperCase();
                            }

                            break;
                    }

                    var decoded = $('<div/>').html(targetValue + character).text();
                    $(settings.target).focus().val(decoded);

                    $('.softkeys__btn--delete').off('mousedown touchstart').on('mousedown touchstart', function(){
                        var targetValue = $(settings.target).val();
    
                        delTimer = setInterval(() => {
                            targetValue = targetValue.substr(0, targetValue.length - 1);
                            $(settings.target).focus().val(targetValue);
                        }, 300);
                    }).off('mouseup touchend').on('mouseup touchend',function(){
                        clearInterval(delTimer);
                    });

                });                
            },

            toggleCase = function(obj) {
                obj.toggleClass('softkeys--caps');
            },

            toggleAlt = function(obj) {
                obj.toggleClass('softkeys--alt');
            };

        return this.each(function(){
            for (var i = 0; i < settings.layout.length; i++) {
                createRow($(this), settings.layout[i]);
            }

            bindKeyPress($(this));
        });
    };

}(jQuery));