<!-- back btn -->
<div class="back-img-btn" id="Back_btn" type="button">
	<img class="" src="resources/uploads/imagebutton/Back.png">
</div>

<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Store List</div>

<!--Start of directory categories category content-->
<div class="tab-content" id="Fashion-nav-tab-content">

    <div class="tab-pane show active" id="Fashion-Tab-Category" role="tabpanel">
        <div id="FashionTabCategories" class="cat-cards">
            <?php include('resources/include/common/contents/categories/tabs/fashion_tab/fashion_tab_categories.php'); ?>
        </div>

        <div id="TenantPage">
            <?php include('resources/include/common/contents/tenant/tenant_page/tenant_page.php'); ?>
        </div>
    </div>
    
    <div class="tab-pane" id="Fashion-Tab-Alphabetical" role="tabpanel">
        <?php include('resources/include/common/contents/categories/tabs/fashion_tab/fashion_tab_alphabetical.php'); ?>
    </div>
    
    <div class="tab-pane" id="Tab-Trends" role="tabpanel" aria-labelledby="pills-Trends-tab">
        <?php include('resources/include/common/contents/categories/tabs/fashion_tab/fashion_tab_trends.php'); ?>
    </div>

</div>
<!--End of directory categories category content-->

<!-- categories navigation -->
<div class="cat-nav-tabs"> 
    <span class="mr-4 nav-tab-title">View stores by: </span>
    <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Categories-nav-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active nav-tab-pills-btn" id="Tab-Fashion-Tab" data-toggle="pill" data-target="#Fashion-Tab-Category" type="button" role="tab" aria-controls="Tab-Category" aria-selected="true">Category</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Alphabetical-Tab" data-toggle="pill" data-target="#Fashion-Tab-Alphabetical" type="button" role="tab" aria-controls="Tab-Alphabetical" aria-selected="false">Alphabetical</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Trends-Tab" data-toggle="pill" data-target="#Tab-Trends" type="button" role="tab" aria-controls="Tab-Trends" aria-selected="false">Trends</button>
        </li>
    </ul>
</div>