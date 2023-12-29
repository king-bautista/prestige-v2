<div id="home-container">
    <div class="text-center">
        <div class="title-page title-page-portrait">Search your favorite stores</div>
    </div>

    <div class="categories-card-container">
        <div class="row">
            <div class="col-md-12" id="categories-container">
            </div>
        </div>
    </div>
</div>

<div id="home-cat-contents">
    <!-- TITLE -->
    <div class="p-3 font-weight-bold nav-titles">Store List</div>

    <div class="tab-content" id="Categories-nav-tab-content">
        <div class="tab-pane show active" id="Tab-Category" role="tabpanel">
            <div id="CatTabCategories" class="cat-cards">
                <!-- MAIN CATEGORY TITLE -->
                <div class="p-2 text-center title-page-content font-weight-bold category-title">Main Category</div>
                <!-- SUB-CATEGORY LIST -->
                <div class="row mt-5 cat-row-card">
                </div>
            </div>
            <div id="TenantPage">
                <!-- MAIN CATEGORY TITLE -->
                <div class="Category-Container-Banner">
                    <img class="category-img-banner" src="#">
                    <div class="hts-strip-align hts-strip-color category-banner-title">Sub-Category</div>
                </div>
                <!-- TENANT LIST PER SUB-CATEGORY -->
                <div class="slideshow-content-container sub-category-tenants">
                </div>
            </div>
        </div>        
        <div class="tab-pane" id="Tab-Alphabetical" role="tabpanel">
            <!-- MAIN CATEGORY TITLE -->
            <div class="p-2 text-center mx-auto font-weight-bold title-page-container">
                <div class="title-page-content-2 category-title">Main Category</div>
            </div>

            <!-- TENANT LIST PER ALPHABETICAL -->
            <div class="slideshow-content-container alpha-tenants"></div>

            <div class="row container-alphabet">
                <div class="col">
                    <div class="alphabet-content">
                        <div class="alphabet-box">
                            <a class="link-alpha selected">#</a>
                            <a class="link-alpha"> A </a>
                            <a class="link-alpha"> B </a>
                            <a class="link-alpha"> C </a>
                            <a class="link-alpha"> D </a>
                            <span class="link-alpha-disabled">E</span>
                            <a class="link-alpha"> F </a>
                            <a class="link-alpha"> G </a>
                            <span class="link-alpha-disabled">H</span>
                            <a class="link-alpha"> I </a>
                            <a class="link-alpha"> J </a>
                            <a class="link-alpha"> K </a>
                            <a class="link-alpha"> L </a>
                            <a class="link-alpha"> M </a>
                            <a class="link-alpha"> N </a>
                            <span class="link-alpha-disabled">O</span>
                            <a class="link-alpha"> P </a>
                            <span class="link-alpha-disabled">Q</span>
                            <a class="link-alpha"> R </a>
                            <a class="link-alpha"> S </a>
                            <span class="link-alpha-disabled">T</span>
                            <span class="link-alpha-disabled">U</span>
                            <span class="link-alpha-disabled">V</span>
                            <span class="link-alpha-disabled">W</span>
                            <span class="link-alpha-disabled">X</span>
                            <a class="link-alpha"> Y </a>
                            <a class="link-alpha"> Z </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="tab-pane" id="Tab-Supplemental" role="tabpanel" aria-labelledby="Tab-Supplemental-tab">
            <!-- MAIN CATEGORY TITLE -->
            <div class="p-2 text-center mx-auto font-weight-bold title-page-container">
                <div class="title-page-content-2 category-title">Food</div>
            </div>

            <!-- TENANT LIST PER ALPHABETICAL -->
            <div class="slideshow-content-container supplemental-list"></div>
        </div>
    </div>
    <!-- categories navigation -->
    <div class="cat-nav-tabs"> 
        <span class="mr-4 nav-tab-title">View stores by: </span>
        <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Categories-nav-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active nav-tab-pills-btn" id="Tab-Category-Tab" data-toggle="pill" data-target="#Tab-Category" type="button" role="tab" aria-controls="Tab-Category" aria-selected="true">Category</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-tab-pills-btn" id="Tab-Alphabetical-tab" data-toggle="pill" data-target="#Tab-Alphabetical" type="button" role="tab" aria-controls="Tab-Alphabetical" aria-selected="false">Alphabetical</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-tab-pills-btn" id="Tab-Supplemental-tab" data-toggle="pill" data-target="#Tab-Supplemental" type="button" role="tab" aria-controls="Tab-Supplemental" aria-selected="false">Cravings</button>
            </li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
    var categories = "{{ $categories }}";
    var sub_categories = '';
    var main_category = '';
    var supplementals = '';
    var alphabetical = '';
    var tenant_list = '';

    $(document).ready(function() {
        $('#Tab-Alphabetical-tab').on('click', function() {
            showAlphabetical();
        });

        $('#Tab-Supplemental-tab').on('click', function() {
            showSupplementals();
        });
    });

    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }

    function showHomeCategories() {
        var my_categories = JSON.parse(decodeEntities(categories));
        $.each(my_categories, function(key,category) {
            var category_element = '';
            category_element = '<div class="home-category-holder '+ category.category_class +' main-'+ category.id +'">';
            category_element += '<img class="category-fashion-img" src="'+ category.kiosk_image_primary_path +'" />';
            category_element += '<div class="ct-fashion-button-allign">'+ category.category_name +'</div>';
            category_element += '</div>';
            $( "#categories-container" ).append(category_element);
            $('.main-'+category.id).on('click', function() {
                main_category = category.category_name;
                sub_categories = category.sub_categories;
                alphabetical = category.alphabetical;
                supplementals = category.supplemental.sub_categories;
                $('#Tab-Supplemental-tab').html(category.supplemental.name);
                showSubCategories();
            });
        }); 
    }

    function showSubCategories() {
        $('.cat-row-card').html('');
        $( ".category-title" ).html(main_category);
        $.each(sub_categories, function(key,category) {
            var subcategory_element = '';
            subcategory_element = '<div class="col-sm-6 mt-3 show-tenants-'+category.id+'">';
            subcategory_element += '<div class="cat-btn">';
            subcategory_element += '<img class="cat-btn-img" src="'+ category.kiosk_image_primary_path +'" />';
            subcategory_element += '<div class="cat-btn-align">';
            subcategory_element += '<p class="cat-text">'+ category.category_name +'</p>';
            subcategory_element += '</div>';
            subcategory_element += '</div>';
            subcategory_element += '</div>';
            $( ".cat-row-card" ).append(subcategory_element);

            $('.show-tenants-'+category.id).on('click', function() {
                $('.category-img-banner').attr('src', category.kiosk_image_top_path);
                $('.category-banner-title').html(category.category_name);
                tenant_list = category.tenants;
                showTenantList();
            });
        }); 
        $('#home-cat-contents').show();
        $('#CatTabCategories').show();
        $('#home-container').hide();
    }

    function showTenantList() {
        $('.sub-category-tenants').html('');
        $('.sub-category-tenants').html('<div class="owl-carousel owl-theme owl-wrapper-tenant-list"></div>');
        $.each(tenant_list, function(key,tenants) {
            var tenant_list_element = '';
            tenant_list_element = '<div class="item">';
            tenant_list_element += '<div class="carousel-content-container-per-food-category mb-5">';
            tenant_list_element += '<div class="row tenants-'+key+'">';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            $( ".owl-wrapper-tenant-list" ).append(tenant_list_element);

            $.each(tenants, function(index,tenant) {
                var tenant_item = '';
                tenant_item = '<div class="col-xl-4 col-lg-6 col-md-4 mt-3">';
                tenant_item += '<div class="tenant-store-card-container bg-white text-center box-shadowed tenant-item-'+tenant.id+'">';
                tenant_item += '<div class="tenant-store-contents">';
                tenant_item += '<img class="img-shop-logo y-auto" src="'+tenant.brand_logo+'"/>';
                tenant_item += '</div>';
                tenant_item += '<div class="text-left tenant-store-details">';
                tenant_item += '<div class="tenant-store-name">'+tenant.brand_name+'</div>';
                tenant_item += '<div class="tenant-store-floor">'+tenant.location+'</div>';
                tenant_item += '<div class="tenant-store-status">';
                tenant_item += '<span class="text-success">'+tenant.operational_hours+'</span>';
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
        owl_tenant = $('.owl-wrapper-tenant-list');
        owl_tenant.owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });

        $('#TenantPage').show();
        $('#CatTabCategories').hide();
        $('#Tab-Category-Tab').click();
    }

    function showAlphabetical() {
        $('.alpha-tenants').html('');
        $('.alpha-tenants').html('<div class="owl-carousel owl-theme owl-wrapper-alpha-tenant-list"></div>');
        $.each(alphabetical, function(key,tenants) {
            var tenant_list_element = '';
            tenant_list_element = '<div class="item">';
            tenant_list_element += '<div class="carousel-content-container-per-food-category mb-5">';
            tenant_list_element += '<div class="row tenants-'+key+'">';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            tenant_list_element += '</div>';
            $( ".owl-wrapper-alpha-tenant-list" ).append(tenant_list_element);

            $.each(tenants, function(index,tenant) {
                var tenant_item = '';
                tenant_item = '<div class="col-xl-4 col-lg-6 col-md-4 mt-3">';
                tenant_item += '<div class="tenant-store-card-container bg-white text-center box-shadowed alpha-tenant-item-'+tenant.id+'">';
                tenant_item += '<div class="tenant-store-contents">';
                tenant_item += '<img class="img-shop-logo y-auto" src="'+tenant.brand_logo+'"/>';
                tenant_item += '</div>';
                tenant_item += '<div class="text-left tenant-store-details">';
                tenant_item += '<div class="tenant-store-name">'+tenant.brand_name+'</div>';
                tenant_item += '<div class="tenant-store-floor">'+tenant.location+'</div>';
                tenant_item += '<div class="tenant-store-status">';
                tenant_item += '<span class="text-success">'+tenant.operational_hours+'</span>';
                if(tenant.is_subscriber)
                    tenant_item += '<span class="featured_shop">Featured</span>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                tenant_item += '</div>';
                $( ".tenants-"+key ).append(tenant_item);                
                $('.alpha-tenant-item'+tenant.id).on('click', function() {
                    showTenantDetails(tenant);
                });
            });
        }); 
        owl_tenant = $('.owl-wrapper-alpha-tenant-list');
        owl_tenant.owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });
    }

    function showSupplementals() {
        $('.supplemental-list').html('');
        $( ".supplemental-list" ).html('<div class="owl-carousel owl-theme owl-wrapper-supplemental"></div>');
        $.each(supplementals, function(key,supplemental) {
            var supplemental_element = '';
            supplemental_element = '<div class="item">';
            supplemental_element += '<div class="carousel-content-container-per-food-cravings">';
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
                supplemental_item += '<p class="cat-text">'+category.category_name+'</p>';
                supplemental_item += '</div>';
                supplemental_item += '</div>';
                supplemental_item += '</div>';
                $( ".supplemental-"+key ).append(supplemental_item);                
                $('.supplemental-item-'+category.id).on('click', function() {
                    $('.category-img-banner').attr('src', category.kiosk_image_top_path);
                    $('.category-banner-title').html(category.category_name);
                    tenant_list = category.tenants;
                    showTenantList();
                });
            });
        }); 
        owl_tenant = $('.owl-wrapper-supplemental');
        owl_tenant.owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });
    }

    showHomeCategories();

</script>
@endpush