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
                <div class="p-2 text-center title-page-content font-weight-bold" id="category-title">Food</div>
                <!-- SUB-CATEGORY LIST -->
                <div class="row mt-5 cat-row-card">
                    
                </div>
            </div>
            <div id="TenantPage">
                <?php include('resources/include/common/contents/tenant/tenant_page/tenant_page.php'); ?>
            </div>
        </div>        
        <div class="tab-pane" id="Tab-Alphabetical" role="tabpanel">
            <?php include('resources/include/common/contents/categories/tabs/category_tab_alphabetical.php'); ?>
        </div>        
        <div class="tab-pane" id="Tab-Supplemental" role="tabpanel" aria-labelledby="Tab-Supplemental-tab">
            <?php include('resources/include/common/contents/categories/tabs/category_tab_cravings.php'); ?>
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

    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = categories;
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
                supplementals = category.supplemental.sub_categories;
                $('#Tab-Supplemental-tab').html(category.supplemental.name);
                showSubCategories();
            });
        }); 
    }

    function showSubCategories() {
        $('.cat-row-card').html('');
        $( "#category-title" ).html(main_category);
        $.each(sub_categories, function(key,category) {
            var subcategory_element = '';
            subcategory_element = '<div class="col-sm-6 mt-3">';
            subcategory_element += '<div class="cat-btn">';
            subcategory_element += '<img class="cat-btn-img" src="'+ category.kiosk_image_primary_path +'" />';
            subcategory_element += '<div class="cat-btn-align">';
            subcategory_element += '<p class="cat-text">'+ category.category_name +'</p>';
            subcategory_element += '</div>';
            subcategory_element += '</div>';
            subcategory_element += '</div>';
            $( ".cat-row-card" ).append(subcategory_element);
        });         
        $('#home-cat-contents').show();
        $('#home-container').hide();
    }

    showHomeCategories();

</script>
@endpush