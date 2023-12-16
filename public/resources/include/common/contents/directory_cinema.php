<!-- back btn -->
<!--Start of the directory back button-->
<?php include('resources/include/common/navigation/back_button.php'); ?>
<!--Start of the directory back button-->

<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Cinema</div>

<!--Start of directory Cinema content-->
<div class="tab-content" id="Cinema-nav-tab-content">

    <div class="tab-pane show active" id="Tab-Cinema" role="tabpanel">
        <?php include('resources/include/common/contents/cinema/tabs/cinema_tab_cinema.php'); ?>
    </div>
    
    <div class="tab-pane" id="Tab-Schedule" role="tabpanel">
        <div id="CinemaTabSchedule">
            <?php include('resources/include/common/contents/cinema/tabs/cinema_tab_schedule.php'); ?>
        </div>
        
        <div id="CinemaTabDefault">
            <?php include('resources/include/common/contents/default/cinema_default.php'); ?>
        </div>
    </div>


</div>
<!--End of directory Cinema content-->

<!-- Cinema navigation -->
<div class="cinema-nav-tabs"> 
    <span class="mr-4 nav-tab-title">Select to view: </span>
    <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Cinema-nav-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active nav-tab-pills-btn" id="Tab-Cinema-Tab" data-toggle="pill" data-target="#Tab-Cinema" type="button" role="tab" aria-controls="Tab-Cinema" aria-selected="true">Cinema</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Schedule-tab" data-toggle="pill" data-target="#Tab-Schedule" type="button" role="tab" aria-controls="Tab-Schedule" aria-selected="false">Schedule</button>
        </li>
    </ul>
</div>