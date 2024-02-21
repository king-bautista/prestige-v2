<div class="p-3 font-weight-bold nav-titles translateme" data-en="Events">Events</div>

<div class="slideshow-content-container event-list">
</div>

<a class="event-prev">
    <div class="left-btn-carousel">
        <img src="{{ URL::to('themes/sm_default/images/Left.png') }}">
    </div>
</a>
<a class="event-next">
    <div class="right-btn-carousel">
        <img src="{{ URL::to('themes/sm_default/images/Right.png') }}">
    </div>
</a>

<div class="justify-content-center no-events-found text-center">
    <img class="ImgPromoDefault" src="{{ URL::to('themes/sm_default/images/stick-around.png') }}">
</div>

<div id="imgEventModal" class="modal promo-modal-content">
  <!-- Modal content -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 promo-modal-container">
            <div class="modal-body m-0 p-0">
                <span class="close text-white">&times;</span>
                <img class="promo-img promo-modal-img" src ="">
            </div>
        </div>
    </div>    
</div>

@push('scripts')
<script>

    var events = "{{ $events }}";

    $(document).ready(function () {
        var promomodal = $("#imgEventModal");
        var span = $(".close");

        span.on("click", function () {
            promomodal.css("display", "none");
        });
    });

    function showEvents() {
        var my_events = JSON.parse(helper.decodeEntities(events));

        if(my_events.length == 0) {
            $('.event-list').hide();
            $('.no-events-found').show();
            return false;
        }

        $('.no-events-found').hide();
        $('.event-list').html('');
        $('.event-list').html('<div class="owl-carousel owl-theme owl-wrapper-event"></div>');
        $.each(my_events, function(key,events) {
            var event_element = '';
            event_element += '<div class="item">';
            event_element += '<div class="carousel-content-container">';
            event_element += '<div class="row event-item-row-'+key+'">';
            event_element += '</div>';
            event_element += '</div>';
            event_element += '</div>';
            $( ".owl-wrapper-event" ).append(event_element);

            $.each(events, function(index,event) {
                var event_item = '';
                event_item += '<div class="col-sm-4">';
                event_item += '<div class="card border-0 bg-transparent img-promo-card">';
                event_item += '<img type="button" class="promo-img promo_img_'+ event.id +'" src="'+ event.image_url_path +'">';
                event_item += '<div class="d-block">';
                event_item += '<p class="promo-store tenants_tenant_store_'+ event.id +'">'+ event.event_name +'</p>';
                event_item += '<p class="promo-floor">'+ event.event_date +'</p>';
                event_item += '</div>';
                event_item += '</div>';
                event_item += '</div>';

                $( ".event-item-row-"+key ).append(event_item);
                $('.promo_img_'+event.id).on('click', function() {
                    $('.promo-modal-img').attr("src", event.image_url_path);
                    $("#imgEventModal").css("display", "block");
                });
            });
        });

        var event = $('.owl-wrapper-event');
        event.on("initialized.owl.carousel", function(e) {
            if(e.item.count == 1) {
                $('.event-prev').hide();
                $('.event-next').hide();
            }
            else {
                $('.event-prev').hide();
                $('.event-next').show();
            }
        }).owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });
        
        $('.event-next').click(function() {
            event.trigger('next.owl.carousel');
        })

        $('.event-prev').click(function() {
            event.trigger('prev.owl.carousel');
        })

        event.on('changed.owl.carousel', function(e) {

            var first = ( !e.item.index)
            if( first ){
                $('.event-prev').hide();
            }
            else {
                $('.event-prev').show();
            }

            var total = e.relatedTarget.items().length - 1;
            var current = e.item.index;
            if(total == current) {
                $('.event-next').hide();
            }
            else {
                $('.event-next').show();
            }
            
        });

        $(".event-list").find(".owl-dots").addClass('owl-dots-event');

    }
</script>
@endpush