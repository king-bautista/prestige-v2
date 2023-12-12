<!-- About image -->
<div class="img-about-background-container">
    <img src="{{ $site->site_banner_path }}" />
</div>

<!-- About content -->
<div class="row">
    <div class="col-xl-4 col-lg-6 col-sm-4 p-5">
        <div class="img-about-logo-container text-center">
            <img src="{{ $site->site_logo_path }}">
        </div>
        @if($site->details['facebook'])
        <div class="socmediconcontainer">
            <img src="{{ URL::to('themes/sm_default/images/about-facebook.svg') }}" class="socmedicon mr-2" /> 
            {{ $site->details['facebook'] }}
        </div>
        @endif

        @if($site->details['twitter'])
        <div class="socmediconcontainer">
            <img src="{{ URL::to('themes/sm_default/images/about-twitter.svg') }}" class="socmedicon mr-2" /> 
            {{ $site->details['twitter'] }}
        </div>
        @endif

        @if($site->details['instagram'])
        <div class="socmediconcontainer">
            <img src="{{ URL::to('themes/sm_default/images/about-instagram.svg') }}" class="socmedicon mr-2" /> 
            {{ $site->details['instagram'] }}
        </div>
        @endif

        @if($site->details['website'])
        <div class="socmediconcontainer">
            <img src="{{ URL::to('themes/sm_default/images/about-website.svg') }}" class="socmedicon mr-2" /> 
            {{ $site->details['website'] }}
        </div>
        @endif
    </div>

    <div class="col-xl-8 col-lg-6 col-sm-8 mall-details-container">
        <div class="mall-details">
            <textarea class="text-mall-details" readonly="readonly"> {{ $site->descriptions }} </textarea>
        </div>
    </div>
</div>