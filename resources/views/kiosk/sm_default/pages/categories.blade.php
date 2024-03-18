<div class="content-holder" id="home-container">
    <div class="text-center">
        <div class="title-page title-page-portrait translateme" data-en="Search your favorite stores">Search your favorite stores</div>
    </div>

    <div class="categories-card-container">
        <div class="row">
            <div class="col-md-12" id="categories-container">
            </div>
        </div>
    </div>
</div>

<div class="content-holder" id="home-cat-contents">
    <!-- TITLE -->
    <div class="p-3 font-weight-bold nav-titles translateme nav-headers" data-en="Store List">Store List</div>

    <div class="tab-content" id="Categories-nav-tab-content">
        <div class="tab-pane show active" id="Tab-Category" role="tabpanel">
            <div class="CatTabCategories cat-cards">
                <!-- MAIN CATEGORY TITLE -->
                <div class="p-2 text-center title-page-content font-weight-bold category-title translateme" data-en="Main Category">Main Category</div>
                <!-- SUB-CATEGORY LIST -->
                <div class="row mt-5 cat-row-card"></div>
            </div>
            <div class="TenantPage">
                <!-- MAIN CATEGORY TITLE -->
                <div class="Category-Container-Banner">
                    <img class="category-img-banner" src="#">
                    <div class="hts-strip-align hts-strip-color category-banner-title translateme" data-en="Sub-Category">Sub-Category</div>
                </div>
                <!-- TENANT LIST PER SUB-CATEGORY -->
                <div class="slideshow-content-container sub-category-tenants"></div>
            </div>
        </div>        
        <div class="tab-pane" id="Tab-Alphabetical" role="tabpanel">
            <!-- MAIN CATEGORY TITLE -->
            <div class="p-2 text-center mx-auto font-weight-bold title-page-container">
                <div class="title-page-content-2 category-title translateme" data-en="Main Category">Main Category</div>
            </div>

            <!-- TENANT LIST PER ALPHABETICAL -->
            <div class="slideshow-content-container alpha-tenants"></div>

            <div class="row container-alphabet">
                <div class="col">
                    <div class="alphabet-content">
                        <div class="alphabet-box">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="tab-pane" id="Tab-Supplemental" role="tabpanel" aria-labelledby="Tab-Supplemental-tab">
            <div class="CatTabCategories">
                <div class="p-2 text-center mx-auto font-weight-bold title-page-container">
                    <div class="title-page-content-2 category-title translateme" data-en="Food">Food</div>
                </div>
                <div class="slideshow-content-container supplemental-list"></div>
            </div>
            <div class="TenantPage">
                <div class="Category-Container-Banner">
                    <img class="category-img-banner" src="#">
                    <div class="hts-strip-align hts-strip-color category-banner-title translateme" data-en="Sub-Category">Sub-Category</div>
                </div>
                <div class="slideshow-content-container sub-category-tenants"></div>
            </div>
            
        </div>
    </div>
    <!-- categories navigation -->
    <div class="row tab-container">
        <div class="col-12">
            <div class="cat-nav-tabs"> 
                <span class="mr-4 nav-tab-title translateme" data-en="View stores by:">View stores by: </span>
                <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Categories-nav-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active nav-tab-pills-btn translateme" id="Tab-Category-Tab" data-toggle="pill" data-target="#Tab-Category" type="button" role="tab" aria-controls="Tab-Category" aria-selected="true" data-en="Category">Category</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link nav-tab-pills-btn translateme" id="Tab-Alphabetical-tab" data-toggle="pill" data-target="#Tab-Alphabetical" type="button" role="tab" aria-controls="Tab-Alphabetical" aria-selected="false" data-en="Alphabetical">Alphabetical</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link nav-tab-pills-btn translateme" id="Tab-Supplemental-tab" data-toggle="pill" data-target="#Tab-Supplemental" type="button" role="tab" aria-controls="Tab-Supplemental" aria-selected="false" data-en="Cravings">Cravings</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var categories = "{{ $categories }}";
    var sub_categories = '';
    var main_category_id = '';
    var main_category = '';
    var supplementals = '';
    var alphabetical = '';
    var tenant_list = '';
    var navigation_letters = ['#'];
    var available_letters = '';

    $(document).ready(function() {
        $('#Tab-Category-Tab').on('click', function() {
            showSubCategories();
        });

        $('#Tab-Alphabetical-tab').on('click', function() {
            showAlphabetical();
        });

        $('#Tab-Supplemental-tab').on('click', function() {
            showSupplementals();
        });
    });

    function showHomeCategories() {
        var my_categories = JSON.parse(helper.decodeEntities(categories));
        $.each(my_categories, function(key,category) {
            var category_element = '';
            category_element = '<div class="home-category-holder '+ category.category_class +' main-'+ category.id +'">';
            category_element += '<img class="category-fashion-img" src="'+ category.kiosk_image_primary_path +'" />';
            category_element += '<div class="ct-food-button-allign translateme" data-en="'+ category.category_name +'">'+ category.category_name +'</div>';
            category_element += '</div>';
            $( "#categories-container" ).append(category_element);
            $('.main-'+category.id).on('click', function() {
                main_category_id = category.category_id;
                main_category = category.category_name;
                sub_categories = (category.sub_categories.length > 0) ? category.sub_categories : null;
                alphabetical = (category.alphabetical.length > 0 ) ? category.alphabetical : null;
                supplementals = (category.supplemental) ? category.supplemental.sub_categories : null;
                if(category.supplemental) {
                    $('#Tab-Supplemental-tab').html(category.supplemental.name);
                    $('#Tab-Supplemental-tab').attr('data-en', category.supplemental.name);
                    $('#Tab-Supplemental-tab').show();
                }
                else {
                    $('#Tab-Supplemental-tab').hide();
                }
                $('#Tab-Category-Tab').click();
                //showSubCategories();
            });
        });
    }

    function showSubCategories() {
        
        if(sub_categories == null || sub_categories == undefined) {
            $('.category-img-banner').attr('src', '');
            $('.sub-category-tenants').html('');
            $('.cat-row-card').html('');
            $('.category-title').html(main_category);
            $('.category-title').attr('data-en', main_category);
            $('.nav-btn-container').removeClass('active');

            $( ".cat-row-card" ).append('<img src="{{ URL::to('themes/sm_default/images/stick-around.png') }}" style="width: 735px; margin: auto;">');
            $( ".cat-row-card" ).addClass('text-center');

            helper.setTranslation();                
            current_location = 'subcategory';
            page_history.push(current_location);

            $('#home-cat-contents').show();
            $('.CatTabCategories').show();
            $('#home-container').hide();
            $('.TenantPage').hide();
            $('.back-img-btn').show();

            return false;
        }
        
        $( ".cat-row-card" ).removeClass('text-center');
        $('.category-img-banner').attr('src', '');
        $('.sub-category-tenants').html('');
        $('.cat-row-card').html('');
        $('.category-title').html(main_category);
        $('.category-title').attr('data-en', main_category);
        $('.nav-btn-container').removeClass('active');
        $.each(sub_categories, function(key,category) {
            var subcategory_element = '';
            subcategory_element = '<div class="col-sm-6 mt-3 show-tenants-'+category.id+'">';
            subcategory_element += '<div class="cat-btn">';
            subcategory_element += '<img class="cat-btn-img" src="'+ category.kiosk_image_primary_path +'" />';
            subcategory_element += '<div class="cat-btn-align">';
            subcategory_element += '<p class="cat-text translateme '+main_category+'_color" data-en="'+ category.category_name +'">'+ category.category_name +'</p>';
            subcategory_element += '</div>';
            subcategory_element += '</div>';
            subcategory_element += '</div>';
            $( ".cat-row-card" ).append(subcategory_element);

            $('.show-tenants-'+category.id).on('click', function() {
                $('.category-img-banner').attr('src', category.kiosk_image_top_path);
                $('.category-banner-title').html(category.category_name);
                $('.category-banner-title').attr('data-en', category.category_name);
                $('.category-banner-title').addClass(main_category + "_color");
                tenant_list = category.tenants;
                // alert("i am here");
                showTenantList();
                current_location = 'tenantcategory_show-tenants-'+category.id;
                page_history.push(current_location);

                $("#container-per-cat").addClass("supplemental-container");
            });
        }); 
        $('#home-cat-contents').show();
        $('.CatTabCategories').show();
        $('#home-container').hide();
        $('.TenantPage').hide();
        $('.back-img-btn').show();
        
        helper.setTranslation();        
        
        current_location = 'subcategory';
        page_history.push(current_location);
    }

    function showTenantList(sub_category) {
        $('.sub-category-tenants').html('');
        $('.sub-category-tenants').html('<div class="owl-carousel owl-theme owl-wrapper-tenant-list"></div>');

        var number_of_tenants = tenant_list.length; 

        if(number_of_tenants > 0){
            $.each(tenant_list, function(key,tenants) {
                var tenant_list_element = '';
                tenant_list_element = '<div class="item">';
                tenant_list_element += '<div class="carousel-content-container-per-food-category mb-4" id="container-per-cat">';
                tenant_list_element += '<div class="row tenants-'+key+'">';
                tenant_list_element += '</div>';
                tenant_list_element += '</div>';
                tenant_list_element += '</div>';
                $( ".owl-wrapper-tenant-list" ).append(tenant_list_element);

                $.each(tenants, function(index,tenant) {
                    var tenant_item = '';
                    var store_status = 'Closed';
                    var store_status_class = 'text-danger';
                    if(tenant.operational_hours.is_open) {
                        store_status = 'Open';
                        store_status_class = 'text-success';
                    }

                    tenant_item = '<div class="col-xl-4 col-lg-4 col-md-4 mt-3">';
                    tenant_item += '<div class="tenant-store-card-container bg-white text-center box-shadowed tenant-item-'+tenant.id+'">';
                    tenant_item += '<div class="tenant-store-contents">';
                    tenant_item += '<img class="img-shop-logo y-auto" src="'+tenant.brand_logo+'"/>';
                    tenant_item += '</div>';
                    tenant_item += '<div class="text-left tenant-store-details">';
                    // tenant_item += '<div class="tenant-store-name">'+helper.convertToProperCase(tenant.brand_name)+'</div>';
                    tenant_item += '<div class="tenant-store-ellipsis">';
                    tenant_item += '<div class="tenant-store-name">'+tenant.brand_name+'</div>';
                    tenant_item += '</div>';

                    tenant_item += '<div class="tenant-store-floor">'+tenant.location+'</div>';
                    tenant_item += '<div class="tenant-store-status">';
                    tenant_item += '<span class="translateme '+store_status_class+'" data-en="'+store_status+'">'+store_status+'</span>';
                    if(tenant.is_subscriber)
                        tenant_item += '<span class="featured_shop">Featured</span>';
                    tenant_item += '</div>';
                    tenant_item += '</div>';
                    tenant_item += '</div>';
                    tenant_item += '</div>';
                    $( ".tenants-"+key ).append(tenant_item);                
                    $('.tenant-item-'+tenant.id).on('click', function() {
                        showTenantDetails(tenant);
                    });

                });
            }); 
        }
        else{
            $( ".owl-wrapper-tenant-list" ).append('<img src="{{ URL::to('themes/sm_default/images/stick-around.png') }}" class="empty-category">');
        }

        var navigation_button = '';
            navigation_button += '<a class="promo-prev per-category-prev">';
            navigation_button += '<div class="left-btn-carousel left-btn-carousel-per-food-alphabetical">';
            navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Left.png') }}">';
            navigation_button += '</div>';
            navigation_button += '</a>';
            navigation_button += '<a class="promo-next per-category-next">';
            navigation_button += '<div class="right-btn-carousel right-btn-carousel-per-food-alphabetical">';
            navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Right.png') }}">';
            navigation_button += '</div>';
            navigation_button += '</a>';

            $('.sub-category-tenants').append(navigation_button);

            owl_tenant = $('.owl-wrapper-tenant-list');
            owl_tenant.on("initialized.owl.carousel", function(e) {
                if(e.item.count > 1) {
                    $('.promo-prev').hide();
                    $('.promo-next').show();
                }
                else {
                    $('.promo-prev').hide();
                    $('.promo-next').hide();
                }
            }).owlCarousel({
                margin: 0,
                nav: false,
                loop: false,
                items: 1,
            });

            $('.promo-next').click(function() {
                owl_tenant.trigger('next.owl.carousel');
            })

            $('.promo-prev').click(function() {
                owl_tenant.trigger('prev.owl.carousel');
            })

            owl_tenant.on('changed.owl.carousel', function(e) {
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

            $('.TenantPage').show();
            $('.CatTabCategories').hide();
            $(".owl-wrapper-tenant-list").find(".owl-dots").addClass('owl-dots-tenant-list');
            helper.setTranslation();
    }

    function showAlphabetical() {
        if(alphabetical == null || alphabetical == undefined) {
            $('.alpha-tenants').html('');
            $('.alpha-tenants').append('<div class="row col-12 text-center" style="margin-left: -36px;"><img src="{{ URL::to('themes/sm_default/images/stick-around.png') }}" style="width: 735px; margin: auto; margin-top: 82px;"></div>');

            generateLetters();
            helper.setTranslation();
            current_location = 'alphabet';
            page_history.push(current_location);

            return false;
        }

        $('.alpha-tenants').html('');
        $('.alpha-tenants').html('<div class="owl-carousel owl-theme owl-wrapper-alpha-tenant-list"></div>');
        $.each(alphabetical, function(key,tenants) {
            var tenant_list_element = '';
            tenant_list_element = '<div class="item">';
            tenant_list_element += '<div class="carousel-content-container-alphabetical mb-4">';
            tenant_list_element += '<div class="row tenants-'+key+'">';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            $( ".owl-wrapper-alpha-tenant-list" ).append(tenant_list_element);

            $.each(tenants, function(index,tenant) {
                var tenant_item = '';
                var store_status = 'Closed';
                var store_status_class = 'text-danger';
                if(tenant.operational_hours.is_open) {
                    store_status = 'Open';
                    store_status_class = 'text-success';
                }

                tenant_item = '<div class="col-xl-4 col-lg-6 col-md-4 mt-3">';
                tenant_item += '<div class="tenant-store-card-container bg-white text-center box-shadowed alpha-tenant-item-'+tenant.id+'">';
                tenant_item += '<div class="tenant-store-contents">';
                tenant_item += '<img class="img-shop-logo y-auto" src="'+tenant.brand_logo+'"/>';
                tenant_item += '</div>';
                tenant_item += '<div class="text-left tenant-store-details">';
                // tenant_item += '<div class="tenant-store-name" parent-index="'+key+'">'+helper.convertToProperCase(tenant.brand_name)+'</div>';
                tenant_item += '<div class="tenant-store-ellipsis">';
                tenant_item += '<div class="tenant-store-name" parent-index="'+key+'">'+tenant.brand_name+'</div>';
                // tenant_item += '<div class="tenant-store-name">'+tenant.brand_name+'</div>';
                tenant_item += '</div>';
                tenant_item += '<div class="tenant-store-floor">'+tenant.location+'</div>';
                tenant_item += '<div class="tenant-store-status">';
                tenant_item += '<span class="translateme '+store_status_class+'" data-en="'+store_status+'">'+store_status+'</span>';
                if(tenant.is_subscriber)
                    tenant_item += '<span class="featured_shop">Featured</span>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                $('.tenants-'+key ).append(tenant_item);                
                $('.alpha-tenant-item-'+tenant.id).on('click', function() {
                    showTenantDetails(tenant);
                });
            });
        }); 

        var navigation_button = '';
        navigation_button += '<a class="promo-prev alpha-prev">';
        navigation_button += '<div class="left-btn-carousel left-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Left.png') }}">';
        navigation_button += '</div>';
        navigation_button += '</a>';
        navigation_button += '<a class="promo-next alpha-next">';
        navigation_button += '<div class="right-btn-carousel right-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Right.png') }}">';
        navigation_button += '</div>';
        navigation_button += '</a>';

        $('.alpha-tenants').append(navigation_button);

        owl_tenant_alpha = $('.owl-wrapper-alpha-tenant-list');
        owl_tenant_alpha.on("initialized.owl.carousel", function(e) {
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
            owl_tenant_alpha.trigger('next.owl.carousel');
        })

        $('.promo-prev').click(function() {
            owl_tenant_alpha.trigger('prev.owl.carousel');
        })

        owl_tenant_alpha.on('changed.owl.carousel', function(e) {
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

        generateLetters();
        helper.setTranslation();
        current_location = 'alphabet';
        page_history.push(current_location);

        $(".owl-wrapper-alpha-tenant-list").find(".owl-dots").addClass('owl-dots-alphabetical');

    }

    function showSupplementals() {
        if(supplementals == null || supplementals == undefined || supplementals.length == 0) {
            $('.supplemental-list').html('');
            $('.supplemental-list').append('<div class="row col-12 text-center" style="margin-left: -36px;"><img src="{{ URL::to('themes/sm_default/images/stick-around.png') }}" style="width: 735px; margin: auto; margin-top: 82px;"></div>');

            generateLetters();
            helper.setTranslation();
            current_location = 'supplemental';
            page_history.push(current_location);

            return false;
        }

        $('.supplemental-list').html('');
        $('.supplemental-list').html('<div class="owl-carousel owl-theme owl-wrapper-supplemental"></div>');
        $.each(supplementals, function(key,supplemental) {
            var supplemental_element = '';
            supplemental_element = '<div class="item">';
            supplemental_element += '<div class="carousel-content-container-per-food-cravings mb-5">';
            supplemental_element += '<div class="row supplemental-'+key+'">';
            supplemental_element += '</div>';
            supplemental_element += '</div>';
            supplemental_element += '</div>';
            $( ".owl-wrapper-supplemental" ).append(supplemental_element);

            $.each(supplemental, function(index,category) {
                var supplemental_item = '';
                supplemental_item += '<div class="col-xl-4 col-lg-6 col-md-4 mt-3">';
                supplemental_item += '<div class="cat-btn-adjustment mx-auto supplemental-item-'+category.id+'">';
                supplemental_item += '<img class="cat-btn-img" src="'+category.kiosk_image_primary_path+'" />';
                supplemental_item += '<div class="cat-btn-align-2">';
                supplemental_item += '<p class="cat-text translateme '+main_category+'_color" data-en="'+ category.category_name +'">'+category.category_name+'</p>';
                supplemental_item += '</div>';
                supplemental_item += '</div>';
                supplemental_item += '</div>';
                $( ".supplemental-"+key ).append(supplemental_item);                
                $('.supplemental-item-'+category.id).on('click', function() {
                    $('.category-img-banner').attr('src', category.kiosk_image_top_path);
                    $('.category-banner-title').html(category.category_name);
                    $('.category-banner-title').attr('data-en', category.category_name);
                    $('.category-banner-title').addClass(main_category + "_color");
                    tenant_list = category.tenants;
                    showTenantList();
                    current_location = 'supplemental_supplemental-item-'+category.id;
                    page_history.push(current_location);

                    $("#container-per-cat").removeClass("supplemental-container");
                });
            });
        }); 

        var navigation_button = '';
        navigation_button += '<a class="promo-prev supplemental-prev">';
        navigation_button += '<div class="left-btn-carousel left-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Left.png') }}">';
        navigation_button += '</div>';
        navigation_button += '</a>';
        navigation_button += '<a class="promo-next supplemental-next">';
        navigation_button += '<div class="right-btn-carousel right-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="{{ URL::to('themes/sm_default/images/Right.png') }}">';
        navigation_button += '</div>';
        navigation_button += '</a>';

        $('.supplemental-list').append(navigation_button);

        owl_tenant_supplemental = $('.owl-wrapper-supplemental');
        owl_tenant_supplemental.on("initialized.owl.carousel", function(e) {
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
            owl_tenant_supplemental.trigger('next.owl.carousel');
        })

        $('.promo-prev').click(function() {
            owl_tenant_supplemental.trigger('prev.owl.carousel');
        })

        owl_tenant_supplemental.on('changed.owl.carousel', function(e) {
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

        $('.TenantPage').hide();
        $('.CatTabCategories').show();
        helper.setTranslation();
        current_location = 'supplemental';
        page_history.push(current_location);

        $(".owl-wrapper-supplemental").find(".owl-dots").addClass('owl-dots-cravings');

    }

    function generateLetters() {
        available_letters = [];
        filterLetterNavigator();

        $('.alphabet-box').html('');
        $('.alphabet-box').append('<a class="link-alpha" onclick="moveTo(0)">#</a>');
        for (let i = 65; i <= 90; i++) {

            var class_name = (available_letters.includes(String.fromCharCode(i))) ? 'link-alpha' : 'link-alpha-disabled';
            $('.alphabet-box').append('<a class="'+class_name+'" onclick="moveTo(\''+String.fromCharCode(i)+'\')">'+String.fromCharCode(i)+'</a>');

        }

    }

    function filterLetterNavigator() {
        let letter_container = [];

        $(".tenant-store-name").each(function(){
            let tenant_name = $(this).html().charAt(0);
            if (tenant_name.match(/^\d/)) {
                letter_container.push("#");
            }else{
                letter_container.push(tenant_name);
            };
        });

        available_letters = [...new Set(letter_container)];
    }

    function moveTo(letter) {
        $(".tenant-store-name").removeClass('letter-selected'); 
        $(".alphabet-box a").removeClass('active');

        let index = 0;
        // GET SLIDE INDEX
        $(".tenant-store-name").each(function(){
            if($(this).html().startsWith(letter, 0)){
                index = $(this).attr('parent-index');
                return false;
            };
            if ($(this).html().match(/^\d/) && letter=="#") {
                index = $(this).attr('parent-index');
                return false;
            };
        });

        // ADD ACTIVE CLASS
        $(".tenant-store-name").each(function(){
            if($(this).html().startsWith(letter, 0)){
                $(this).addClass('letter-selected');
            };

            if($(this).html().startsWith('@', 0) && letter== 0){
                $(this).addClass('letter-selected');
            };

            if ($(this).html().match(/^\d/) && letter==0) {
                $(this).addClass('letter-selected');
            };
        });

        // ADD ACTIVE CLASS
        $(".alphabet-box .link-alpha").each(function(){
            if($(this).html().startsWith(letter, 0)){
                $(this).addClass('active');
            };

            if($(this).html().startsWith('#', 0) && letter==0){
                $(this).addClass('active');
            };

        });

        $('.alpha-tenants').parent("div").find(".owl-dots button").each(function(key){
            if (key == parseInt(index)){
                $(this).trigger('click');
            }
        });

    }

    showHomeCategories();

</script>
@endpush