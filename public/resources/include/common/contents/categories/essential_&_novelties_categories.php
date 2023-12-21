<!-- back btn -->
<div class="back-img-btn" id="Back_btn" type="button">
    <img class="" src="resources/uploads/imagebutton/Back.png">
</div>

<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Store List</div>

<!--Start of directory categories category content-->
<div class="tab-content" id="Essentials-nav-tab-content">

    <div class="tab-pane show active" id="Essentials-Tab-Category" role="tabpanel">
        <div id="EssentialsTabCategories" class="cat-cards">
            <?php include('resources/include/common/contents/categories/tabs/essentials_tab/essentials_tab_categories.php'); ?>
        </div>

        <div id="TenantPage">
            <?php include('resources/include/common/contents/tenant/tenant_page/tenant_page.php'); ?>
        </div>
    </div>
    
    <div class="tab-pane" id="Essentials-Tab-Alphabetical" role="tabpanel">
        <?php include('resources/include/common/contents/categories/tabs/essentials_tab/essentials_tab_alphabetical.php'); ?>
    </div>
    
    <div class="tab-pane" id="pills-Goodies" role="tabpanel" aria-labelledby="pills-Goodies-tab">
        <?php include('resources/include/common/contents/categories/tabs/essentials_tab/essentials_tab_goodies.php'); ?>
    </div>

</div>
<!--End of directory categories category content-->

<!-- categories navigation -->
<div class="cat-nav-tabs"> 
    <span class="mr-4 nav-tab-title">View stores by: </span>
    <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Categories-nav-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active nav-tab-pills-btn" id="Tab-Category-Tab" data-toggle="pill" data-target="#Essentials-Tab-Category" type="button" role="tab" aria-controls="Tab-Category" aria-selected="true">Category</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Alphabetical-tab" data-toggle="pill" data-target="#Essentials-Tab-Alphabetical" type="button" role="tab" aria-controls="Tab-Alphabetical" aria-selected="false">Alphabetical</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="pills-Goodies-tab" data-toggle="pill" data-target="#pills-Goodies" type="button" role="tab" aria-controls="pills-Goodies" aria-selected="false">Goodies</button>
        </li>
    </ul>
</div>