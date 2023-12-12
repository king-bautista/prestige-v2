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
            <input type="hidden" value="30998" class="direction-from" />
            <div style="float: left; border-radius: 18px; box-shadow: 0 3px 6px rgb(0 0 0 / 0.16);">
                <div style="float: left; width: 423px; height: 63px;">
                    <select class="form-control select direction-to d-inline-block translateselect2 select2-hidden-accessible" style="width: 423px; font-family: 'Henry Sans Regular' !important; height: 65px; border-top-left-radius: 15px; border-bottom-left-radius: 15px; border-right: 0px;" tabindex="-1" aria-hidden="true" data-select2-id="537">
                        <option value="0" data-select2-id="539">Input Destination</option>
                        <option value="11772">
                            ACE HARDWARE
                        </option>
                    </select>
                    <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="538" style="width: 100%;">
                        <span class="selection">
                            <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-kds0-container">
                            </span>
                        </span>
                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                    </span>
                </div>
                <div style="float: left;">
                    <button class="btn-prestige-rounded3 btn-pwd btn-prestige-white" data-toggle="button" aria-pressed="false" style="width: 67px; height: 64px; outline-style: none; border-top-right-radius: 15px; border-bottom-right-radius: 15px; border: 1px solid black !important;">
                        <svg
                            class="svg-inline--fa fa-wheelchair fa-w-16"
                            style="font-size: 26px;"
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fa"
                            data-icon="wheelchair"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"
                            data-fa-i2svg=""
                        >
                            <path
                                fill="currentColor"
                                d="M496.101 385.669l14.227 28.663c3.929 7.915.697 17.516-7.218 21.445l-65.465 32.886c-16.049 7.967-35.556 1.194-43.189-15.055L331.679 320H192c-15.925 0-29.426-11.71-31.679-27.475C126.433 55.308 128.38 70.044 128 64c0-36.358 30.318-65.635 67.052-63.929 33.271 1.545 60.048 28.905 60.925 62.201.868 32.933-23.152 60.423-54.608 65.039l4.67 32.69H336c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H215.182l4.572 32H352a32 32 0 0 1 28.962 18.392L438.477 396.8l36.178-18.349c7.915-3.929 17.517-.697 21.446 7.218zM311.358 352h-24.506c-7.788 54.204-54.528 96-110.852 96-61.757 0-112-50.243-112-112 0-41.505 22.694-77.809 56.324-97.156-3.712-25.965-6.844-47.86-9.488-66.333C45.956 198.464 0 261.963 0 336c0 97.047 78.953 176 176 176 71.87 0 133.806-43.308 161.11-105.192L311.358 352z"
                            ></path>
                        </svg>
                        <!-- <span class="fa fa-wheelchair" style="font-size:26px;"></span> Font Awesome fontawesome.com -->
                    </button>
                    <input type="checkbox" id="ispwd" class="d-none" />
                </div>
                <div style="clear: both;"></div>
            </div>

            <div style="float: left; margin-left: 20px; border-radius: 18px; box-shadow: 0 3px 6px rgb(0 0 0 / 0.16);">
                <div style="float: left; width: 161px;">
                    <select class="form-control select floor-select my-auto select2-hidden-accessible" style="height: 65px; border-top-left-radius: 15px; border-bottom-left-radius: 15px;" id="floor-select" data-select2-id="floor-select" tabindex="-1" aria-hidden="true">
                        <option value="168" selected="" data-select2-id="5">GF</option>
                        <option value="169">2F</option>
                    </select>
                    <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="4" style="width: 100%;">
                        <span class="selection">
                            <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-floor-select-container">
                        	</span>
                        </span>
                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                    </span>
                </div>
                <div style="float: left;">
                    <a
                        class="mapminus btn btn-prestige-rounded2 my-auto"
                        style="background-color: #ffffff; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; height: 65px; border-left: 0px; color: #0030ff; width: 67px; height: 64px;"
                    >
                        <svg
                            class="svg-inline--fa fa-minus fa-w-14"
                            style="font-size: 26px;"
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fa"
                            data-icon="minus"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512"
                            data-fa-i2svg=""
                        >
                            <path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                        </svg>
                        <!-- <span class="fa fa-minus" style="font-size: 26px;"></span> Font Awesome fontawesome.com -->
                    </a>
                </div>
                <div style="float: left;">
                    <a
                        class="mapplus btn btn-prestige-rounded2 my-auto"
                        style="background-color: #ffffff; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; height: 65px; border-left: 0px; color: #0030ff; width: 67px; height: 64px;"
                    >
                        <svg
                            class="svg-inline--fa fa-plus fa-w-14"
                            style="font-size: 26px;"
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fa"
                            data-icon="plus"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512"
                            data-fa-i2svg=""
                        >
                            <path
                                fill="currentColor"
                                d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"
                            ></path>
                        </svg>
                        <!-- <span class="fa fa-plus" style="font-size: 26px;"></span> Font Awesome fontawesome.com -->
                    </a>
                </div>
                <div style="float: left;">
                    <a
                        class="mapexpand btn btn-prestige-rounded3 my-auto"
                        style="background-color: #ffffff; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-top-right-radius: 15px; height: 65px; border-bottom-right-radius: 15px; border-bottom: 1px solid #aaa; border-left: 0px; color: #0030ff; width: 67px; height: 64px;"
                    >
                        <svg
                            class="svg-inline--fa fa-expand fa-w-14"
                            style="font-size: 26px;"
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fa"
                            data-icon="expand"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512"
                            data-fa-i2svg=""
                        >
                            <path
                                fill="currentColor"
                                d="M0 180V56c0-13.3 10.7-24 24-24h124c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H64v84c0 6.6-5.4 12-12 12H12c-6.6 0-12-5.4-12-12zM288 44v40c0 6.6 5.4 12 12 12h84v84c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12V56c0-13.3-10.7-24-24-24H300c-6.6 0-12 5.4-12 12zm148 276h-40c-6.6 0-12 5.4-12 12v84h-84c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h124c13.3 0 24-10.7 24-24V332c0-6.6-5.4-12-12-12zM160 468v-40c0-6.6-5.4-12-12-12H64v-84c0-6.6-5.4-12-12-12H12c-6.6 0-12 5.4-12 12v124c0 13.3 10.7 24 24 24h124c6.6 0 12-5.4 12-12z"
                            ></path>
                        </svg>
                        <!-- <span class="fa fa-expand" style="font-size: 26px;"></span> Font Awesome fontawesome.com -->
                    </a>
                </div>
                <div style="float: left;">
                    <a
                        class="maprepeat btn btn-prestige-rounded3 my-auto"
                        style="background-color: #ffffff; border-top: 1px solid #aaa; border-right: 1px solid #aaa; border-bottom: 1px solid #aaa; border-left: 0px; color: #0030ff; width: 67px; height: 64px; display: none;"
                    >
                        <svg
                            class="svg-inline--fa fa-history fa-w-16"
                            style="font-size: 26px;"
                            aria-hidden="true"
                            focusable="false"
                            data-prefix="fa"
                            data-icon="history"
                            role="img"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"
                            data-fa-i2svg=""
                        >
                            <path
                                fill="currentColor"
                                d="M504 255.531c.253 136.64-111.18 248.372-247.82 248.468-59.015.042-113.223-20.53-155.822-54.911-11.077-8.94-11.905-25.541-1.839-35.607l11.267-11.267c8.609-8.609 22.353-9.551 31.891-1.984C173.062 425.135 212.781 440 256 440c101.705 0 184-82.311 184-184 0-101.705-82.311-184-184-184-48.814 0-93.149 18.969-126.068 49.932l50.754 50.754c10.08 10.08 2.941 27.314-11.313 27.314H24c-8.837 0-16-7.163-16-16V38.627c0-14.254 17.234-21.393 27.314-11.314l49.372 49.372C129.209 34.136 189.552 8 256 8c136.81 0 247.747 110.78 248 247.531zm-180.912 78.784l9.823-12.63c8.138-10.463 6.253-25.542-4.21-33.679L288 256.349V152c0-13.255-10.745-24-24-24h-16c-13.255 0-24 10.745-24 24v135.651l65.409 50.874c10.463 8.137 25.541 6.253 33.679-4.21z"
                            ></path>
                        </svg>
                        <!-- <span class="fa fa-history" style="font-size: 26px;"></span> Font Awesome fontawesome.com -->
                    </a>
                </div>
                <div style="float: left;">
                    <button class="btn btn-direction btn-prestige-color btn-prestige-rounded translateme" style="display: none;">Get Directions</button>
                    <button class="btn btn-reloadmap btn-prestige-color btn-prestige-rounded translateme" style="display: none;">Reload Map</button>
                </div>

                <div style="clear: both;"></div>
            </div>

            <div style="clear: both;"></div>
        </div>
    </div>
</div>



