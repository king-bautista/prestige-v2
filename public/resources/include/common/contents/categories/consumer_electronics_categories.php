<!-- back btn -->
<div class="back-img-btn" id="Back_btn" type="button">
    <img class="" src="resources/uploads/imagebutton/Back.png">
</div>

<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Store List</div>

<!--Start of directory categories category content-->
<div class="tab-content" id="Electronics-nav-tab-content">

    <div class="tab-pane show active" id="Electronics-Tab-Category" role="tabpanel">
        <div id="ElectronicsTabCategories" class="cat-cards">
            <?php include('resources/include/common/contents/categories/tabs/electronics_tab/electronics_tab_categories.php'); ?>
        </div>

        <div id="TenantPage">
            <?php include('resources/include/common/contents/tenant/tenant_page/tenant_page.php'); ?>
        </div>
    </div>
    
    <div class="tab-pane" id="Electronics-Tab-Alphabetical" role="tabpanel">
        <?php include('resources/include/common/contents/categories/tabs/electronics_tab/electronics_tab_alphabetical.php'); ?>
    </div>
    
    <div class="tab-pane" id="Tab-Latest" role="tabpanel" aria-labelledby="pills-Latest-tab">
        <?php include('resources/include/common/contents/categories/tabs/electronics_tab/electronics_tab_latest.php'); ?>
    </div>

</div>
<!--End of directory categories category content-->

<!-- categories navigation -->
<div class="cat-nav-tabs"> 
    <span class="mr-4 nav-tab-title">View stores by: </span>
    <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Categories-nav-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active nav-tab-pills-btn" id="Tab-Electronics-Tab" data-toggle="pill" data-target="#Electronics-Tab-Category" type="button" role="tab" aria-controls="Tab-Category" aria-selected="true">Category</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Alphabetical-tab" data-toggle="pill" data-target="#Electronics-Tab-Alphabetical" type="button" role="tab" aria-controls="Tab-Alphabetical" aria-selected="false">Alphabetical</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Latest-Tab" data-toggle="pill" data-target="#Tab-Latest" type="button" role="tab" aria-controls="pills-Latest" aria-selected="false">Latest</button>
        </li>
    </ul>
</div>