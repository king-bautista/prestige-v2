<!-- back btn -->
<!--Start of the directory back button-->
<?php include('resources/include/common/navigation/back_button.php'); ?>
<!--Start of the directory back button-->

<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Map</div>

<div class="map-canvas" id="canvas">
    <canvas tabindex="0" class="canvas-canvas">
    </canvas>
</div>


<div class="MapBtn">
    <div class="container">
        <div class="row">
            <!-- Add Hidden Value -->
            <input type="hidden" class="direction-from" />
            <div class="d-flex justify-content-start" style="border-radius: 18px; box-shadow: 0 3px 6px rgb(0 0 0 / 0.16);">
                <div style="width: 423px; height: 62px !important;">
                    
                    <select class="form-control select direction-to d-inline-block translateselect2 select2-hidden-accessible" style="width: 423px; height: 63.5px !important; font-family: 'Henry Sans Regular' !important; height: 65px; border-top-left-radius: 18px; border-bottom-left-radius: 18px; border: 0.5px solid #aaa !important;" tabindex="-1" data-select2-id="537">
                        <option value="0" data-select2-id="539">Input Destination</option>
                        <option value="11772">
                            ACE HARDWARE
                        </option>
                        <option value="11772">
                            ACE HARDWARE 1
                        </option>
                        <option value="11772">
                            ACE HARDWARE 2
                        </option>
                        <option value="11772">
                            ACE HARDWARE 3
                        </option>
                        <option value="11772">
                            ACE HARDWARE 4
                        </option>
                        <option value="11772">
                            ACE HARDWARE 5
                        </option>
                        <option value="11772">
                            ACE HARDWARE 6
                        </option>
                        <option value="11772">
                            ACE HARDWARE 7
                        </option>
                        <option value="11772">
                            ACE HARDWARE 8
                        </option>
                    </select>

                    <span class="select2 select2-container select2-container--default">
                        <span class="selection">
                            <span class="select2-selection select2-selection--single">
                            </span>
                        </span>
                        <span class="dropdown-wrapper"></span>
                    </span>

                </div>

                <div>
                    <button class="btn-pwd" id="btnpwdchange">
                        <svg class="svg-inline--fa fa-wheelchair fa-w-16 btn-pwd-icon" focusable="false" data-prefix="fa" data-icon="wheelchair" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M496.101 385.669l14.227 28.663c3.929 7.915.697 17.516-7.218 21.445l-65.465 32.886c-16.049 7.967-35.556 1.194-43.189-15.055L331.679 320H192c-15.925 0-29.426-11.71-31.679-27.475C126.433 55.308 128.38 70.044 128 64c0-36.358 30.318-65.635 67.052-63.929 33.271 1.545 60.048 28.905 60.925 62.201.868 32.933-23.152 60.423-54.608 65.039l4.67 32.69H336c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H215.182l4.572 32H352a32 32 0 0 1 28.962 18.392L438.477 396.8l36.178-18.349c7.915-3.929 17.517-.697 21.446 7.218zM311.358 352h-24.506c-7.788 54.204-54.528 96-110.852 96-61.757 0-112-50.243-112-112 0-41.505 22.694-77.809 56.324-97.156-3.712-25.965-6.844-47.86-9.488-66.333C45.956 198.464 0 261.963 0 336c0 97.047 78.953 176 176 176 71.87 0 133.806-43.308 161.11-105.192L311.358 352z"
                            ></path>
                        </svg>
                    </button>
                </div>

            </div>

            <div class="d-flex justify-content-start" style="margin-left: 20px; border-radius: 18px; box-shadow: 0 3px 6px rgb(0 0 0 / 0.16);">

                <div style="width: 161px;">
                    <select class="form-control select floor-select my-auto select2-hidden-accessible" style="height: 64px; border-top-left-radius: 18px; border-bottom-left-radius: 18px;  border: 0.5px solid #aaa !important;" id="floor-select" data-select2-id="floor-select" tabindex="-1">
                        <option>GF</option>
                        <option>2F</option>
                    </select>
                    <span class="select2 select2-container select2-container--default">
                        <span class="selection">
                            <span class="select2-selection select2-selection--single">
                            </span>
                        </span>
                        <span class="dropdown-wrapper"></span>
                    </span>
                </div>

                <div style="">
                    <a class="mapminus btn btn-prestige-rounded2 my-auto" style="background-color: #ffffff; border-radius: 0px !important; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; height: 65px; border-left: 0px; color: #0030ff; width: 67px; height: 64px;" >
                        <svg class="svg-inline--fa fa-minus fa-w-14" style="font-size: 26px;" focusable="false" data-prefix="fa" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                        </svg>
                    </a>
                </div>

                <div style="">
                    <a class="mapplus btn btn-prestige-rounded2 my-auto" style="background-color: #ffffff; border-radius: 0px !important; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; height: 65px; border-left: 0px; color: #0030ff; width: 67px; height: 64px;" >
                        <svg class="svg-inline--fa fa-plus fa-w-14" style="font-size: 26px;" focusable="false" data-prefix="fa" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" ></path>
                        </svg>
                    </a>
                </div>

                <div style="">
                    <a class="mapexpand btn btn-prestige-rounded3 my-auto" style="background-color: #ffffff; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-top-right-radius: 18px; height: 65px; border-bottom-right-radius: 18px; border-bottom: 1px solid #aaa; border-left: 0px; color: #0030ff; width: 67px; height: 64px;" >
                        <svg class="svg-inline--fa fa-expand fa-w-14" style="font-size: 26px;" focusable="false" data-prefix="fa" data-icon="expand" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path fill="currentColor" d="M0 180V56c0-13.3 10.7-24 24-24h124c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H64v84c0 6.6-5.4 12-12 12H12c-6.6 0-12-5.4-12-12zM288 44v40c0 6.6 5.4 12 12 12h84v84c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12V56c0-13.3-10.7-24-24-24H300c-6.6 0-12 5.4-12 12zm148 276h-40c-6.6 0-12 5.4-12 12v84h-84c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h124c13.3 0 24-10.7 24-24V332c0-6.6-5.4-12-12-12zM160 468v-40c0-6.6-5.4-12-12-12H64v-84c0-6.6-5.4-12-12-12H12c-6.6 0-12 5.4-12 12v124c0 13.3 10.7 24 24 24h124c6.6 0 12-5.4 12-12z" ></path>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>



