<!-- title -->
<div class="p-3 font-weight-bold nav-titles translateme" id="search-title" data-en="Search">Search</div>

<div id="keyboard-section" class="row minus-30">
    <div class="col-md-10 offset-md-1 mt-5 pt-5">
       <form class="row form text-center" id="form_search">
            <div class="input-group mb-5 mt-5" style="width: 70%; margin: auto;"> 
                <input type="text" id="code" name="code" class="form-control input-mg search-box">
                <button class="btn search-box-button translateme" type="button" data-en="Search">Search</button>
                <label class="notification translateme" data-en="Please type at least two (2) letters to search.">Please type at least two (2) letters to search.</label>
            </div>                    
            <div class="softkeys softkeys-search-page mt-5 search-keyboard-height" data-target="input[name='code']"></div>
        </form>
    </div>
</div>

<div class="p-2 text-center mx-auto title-page-container2 search-for">
    <div class="title-page-content-2">
        <span class="translateme" data-en="You searched for">You searched for</span> ‘<span id="search_str"></span>’:
    </div>
</div>

<div id="searchList">
    <div class="slideshow-content-container search-results search-result-container">
    </div>
    <div class="you-might-want-to-try">

    </div>
</div>

<div id="searchNone">
    <div class="searchDefaultMessage text-center translateme" data-en="No results found">
        No results found
    </div>
</div>


@push('scripts')
<script>
    var suggestions =  "{{ $suggestions }}";
    var site_id = "{{ $site->id }}";
    var tenant_searchList = '';

    $(document).ready(function() {
        var suggestion_list = JSON.parse(helper.decodeEntities(suggestions));

        $("#form_search").submit(function(e){
            e.preventDefault();
        });

        $('#code').autocomplete({
            minLength: 1,
            source: function(request, response) {
                var filteredArray = $.map(suggestion_list, function(item) {
                    if( item.value.toLowerCase().startsWith(request.term.toLowerCase())){
                        return item;
                    }
                    else{
                        return null;
                    }
                });
                response(filteredArray);
            },
            select: function(event, ui) {
                if(ui.item.id)
                {
                    onClickSuggest(ui.item.tenant);
                }else{
                    event.preventDefault();
                    this.value = $('<div />').html(ui.item.label).text();
                    $(".search-box-button").trigger('click');
                }
            }
        }).data("uiAutocomplete")._renderItem = function (ul, item) {
            let text = helper.decodeEntities(item.value);

            var newText = String(text).replace(
                    new RegExp("\\b" + $.ui.autocomplete.escapeRegex(this.term), "i"),
                    //new RegExp("\\b" +this.term, "gi"),
                    "<span class='prestige-text-color text-bold'>$&</span>");

            var floor = item.floor_name === null?"": " " + item.floor_name;
            var bldg = item.building_name === null?"": " " + item.building_name;

            var attrib = '';
            
            // if (item.building_name == 'Main Building'){
            //     attrib = floor;
            // }else{
            //     attrib = floor + bldg;
            // }

            // if(item.address !== null || item.address !== 'undefined' || item.address !== 'null' || item.address !== '') {
            //     var attrib = ", " + item.address;
            // }

            // if(attrib === null || attrib === ', null' || attrib === ', undefined')
            //     attrib = '';

            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<div>" + newText + attrib + "</div>")
                .appendTo(ul);
        };

        // KEYBOARD
        $('.softkeys-search-page').softkeys({
            target : $('.softkeys-search-page').data('target'),
            layout : [
                [
                    '1','2','3','4','5','6','7','8','9','0',
                ],
                [
                    ['Q','~'],
                    ['W','!'],
                    ['E','@'],
                    ['R','#'],
                    ['T','$'],
                    ['Y','%'],
                    ['U','^'],
                    ['I','&'],
                    ['O','*'],
                    ['P','('],
                    ['-',')'],
                ],
                [
                    ['A','['],
                    ['S',']'],
                    ['D','-'],
                    ['F','+'],
                    ['G','='],
                    ['H',':'],
                    ['J',';'],
                    ['K','\''],
                    ['L','&#34;'],
                    '&apos;',
                ],
                [
                    'shift',
                    ['Z','{'],
                    ['X','}'],
                    ['C','<'],
                    ['V','>'],
                    ['B','_'],
                    ['N','?'],
                    ['M','/'],
                    'delete',
                ],
                [
                    '&comma;',
                    'space',
                    '&period;',
                    'Search',
                ]
            ]
        });

        $(".softkeys-search-page > .softkeys__btn").on('mousedown',function(){                
        }).on('mouseup',function(){
            $('#code').trigger('keydown');
            $('.notification').hide();
            $('.ui-menu').scrollTop(0);
        }).on('touchend',function(){
            $('.notification').hide();
            $('#code').trigger('keydown');
            $('.ui-menu').scrollTop(0);
        });

        $('.search-box-button, .softkeys__btn--search').on('touchend click', function() {
            var search_key = $('#code').val();
            tenant_searchList = '';
            if (search_key.length >= 2) {
                $("#code").css("border-color", "#6051e3");
                $('.notification').hide();
                $.post( "/api/v1/search", { site_id: site_id, id: null, key_words: search_key } )
                .done(function(responce) {

                    $('#search-title').html('Search Results');
                    $('#search-title').attr("data-en",'Search Results');
                    $('#search_str').html(search_key);
                    $('.search-for').show();

                    current_location = 'searchresult';
                    page_history.push(current_location);

                    if(responce.tenants.length > 0) {
                        showTenantSearch(responce.tenants);
                        showSubscriber(responce.suggest_subscribers)
                        $('#keyboard-section').hide();
                        $('#searchList').show();
                    }
                    else {
                        $('#searchNone').show();
                        $('#keyboard-section').hide();
                        $('#searchList').hide();
                    }
                    helper.setTranslation();
                })
            }
            else {
                $("#code").css("border-color", "#bc5b68");
                $('.notification').show();
            }

        });

        $('.softkeys-search-page > .softkeys__btn--shift').on( 'touchend click', function(){
            if($(this).find('span').text() === '#+=') {
                $(this).find('span').html('ABC');
                $('.softkeys-search-page > .softkeys__btn--hidden').hide();
            }
            else {
                $(this).find('span').html('#+=');
                $('.softkeys-search-page > .softkeys__btn--hidden').show();
            }
        });
    });

    function showTenantSearch(search_results) {
        $('.search-results').html('');
        $('.you-might-want-to-try').html('');
        $('.search-results').html('<div class="owl-carousel owl-theme owl-wrapper-tenant-search-list search-result-dots"></div>');
        
        $.each(search_results, function(key,tenants) {
            var tenant_list_element = '';
            tenant_list_element = '<div class="item">';
            tenant_list_element += '<div class="carousel-content-container-per-food-category mb-4 search-result-height">';
            tenant_list_element += '<div class="row tenants-'+key+'">';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            $( ".owl-wrapper-tenant-search-list" ).append(tenant_list_element);

            $.each(tenants, function(index,tenant) {
                var tenant_item = '';
                var store_status = 'Closed';
                var store_status_class = 'text-danger';
                if(tenant.operational_hours.is_open) {
                    store_status = 'Open';
                    store_status_class = 'text-success';
                }

                store_status = (tenant.amenity_name) ? '' : store_status;

                var store_logo = (tenant.icon_path) ? tenant.icon_path : tenant.brand_logo;
                var store_name = (tenant.amenity_name) ? tenant.amenity_name : tenant.brand_name;
                var store_location = (tenant.amenity_location) ? tenant.amenity_location : tenant.location;
                var item_id = (tenant.id) ? tenant.id : tenant.site_point;

                tenant_item = '<div class="col-xl-4 col-lg-6 col-md-4 mt-3">';
                tenant_item += '<div class="tenant-store-card-container bg-white text-center box-shadowed tenant-item-'+item_id+'">';
                tenant_item += '<div class="tenant-store-contents">';
                tenant_item += '<img class="img-shop-logo y-auto" src="'+store_logo+'"/>';
                tenant_item += '</div>';
                tenant_item += '<div class="text-left tenant-store-details">';
                // tenant_item += '<div class="tenant-store-name">'+helper.convertToProperCase(store_name)+'</div>';
                tenant_item += '<div class="tenant-store-name">'+store_name+'</div>';
                tenant_item += '<div class="tenant-store-floor">'+store_location+'</div>';
                tenant_item += '<div class="tenant-store-status">';
                tenant_item += '<span class="translateme '+store_status_class+'" data-en="'+store_status+'">'+store_status+'</span>';
                if(tenant.is_subscriber)
                    tenant_item += '<span class="featured_shop">Featured</span>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                $( ".tenants-"+key ).append(tenant_item);                
                $('.tenant-item-'+item_id).on('click', function() {
                    if(tenant.amenity_name) {
                        helper.showAmenity(tenant.site_point);
                    }
                    else {
                        showTenantDetails(tenant);
                    }
                });
            });
        }); 

        var navigation_button = '';
        navigation_button += '<a class="promo-prev search-prev">';
        navigation_button += '<div class="left-btn-carousel left-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Left.png') }}">';
        navigation_button += '</div>';
        navigation_button += '</a>';
        navigation_button += '<a class="promo-next search-next">';
        navigation_button += '<div class="right-btn-carousel right-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Right.png') }}">';
        navigation_button += '</div>';
        navigation_button += '</a>';

        $('.search-results').append(navigation_button);

        owl_search = $('.owl-wrapper-tenant-search-list');
        owl_search.on("initialized.owl.carousel", function(e) {
            if(e.item.count == 1) {
                $('.promo-prev').hide();
                $('.promo-next').hide();
            }
            else {
                $('.promo-prev').hide();
                $('.promo-next').show();
            }
        }).owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });

        $('.promo-next').click(function() {
            owl_search.trigger('next.owl.carousel');
        })

        $('.promo-prev').click(function() {
            owl_search.trigger('prev.owl.carousel');
        })

        owl_search.on('changed.owl.carousel', function(e) {
            var first = ( !e.item.index)
            if( first ){
                $('.promo-prev').hide();
            }
            else {
                $('.promo-prev').show();
            }

            var total = e.relatedTarget.items().length - 1;
            var current = e.item.index;
            if(total == current) {
                $('.promo-next').hide();
            }
            else {
                $('.promo-next').show();
            }
            
        });
        $('.search-result-dots').find(".owl-dots").addClass('owl-dots-search-result');
        
    }

    function showSubscriber(subscriber) {
        if(subscriber.length > 0) {
            var want_to_try = '';
            want_to_try += '<div class="want-to-try">';
            want_to_try += '<div class="row">';
            want_to_try += '<div class="col-12 pl-170">';
            want_to_try += '<span class="translateme" data-en="You might want to try">You might want to try</span> :';
            want_to_try += '</div>';
            want_to_try += '</div>';
            want_to_try += '</div>';

            want_to_try += '<div class="row">';
            want_to_try += '<div class="col-12 pl-170">';
            want_to_try += '<div class="owl-carousel subscriber-holder">';
            want_to_try += '</div>';
            want_to_try += '</div>';
            want_to_try += '</div>';

            $('.you-might-want-to-try').append(want_to_try);

            $.each(subscriber, function(index,tenant) {     
                want_to_item = '';   
                want_to_item += '<div class="item">';            
                want_to_item += '<img class="shop-logo tenant-store tenant-try-'+tenant.id+'" src="'+tenant.tenant_details.subscriber_logo+'">';
                want_to_item += '</div>';

                $('.subscriber-holder').append(want_to_item);
                // show tenant
                $('.tenant-try-'+tenant.id).on('click', function() {
                    showTenantDetails(tenant);
                });
            });

            owl_subscriber = $('.subscriber-holder');
            owl_subscriber.owlCarousel({
                margin: 0,
                nav: false,
                loop: false,
                items: 5,
            });
        }
    }

    function onClickSuggest(tenant) {
        // SHOW TENANT
        showTenantDetails(tenant);
    }

</script>
@endpush