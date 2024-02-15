<div class="p-3 font-weight-bold nav-titles translateme" data-en="Promos">Promos</div>

<div class="slideshow-content-container promo-list">
</div>

<a class="promo-prev">
    <div class="left-btn-carousel">
        <img src="resources/uploads/imagebutton/Left.png">
    </div>
</a>
<a class="promo-next">
    <div class="right-btn-carousel">
        <img src="resources/uploads/imagebutton/Right.png">
    </div>
</a>

<div class="justify-content-center no-promos-found">
    <img class="ImgPromoDefault" src="{{ URL::to('themes/sm_default/images/stick-around.png') }}">
</div>

<div id="imgPromoModal" class="modal promo-modal-content">
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

    var promos = "{{ $promos }}";

    $(document).ready(function () {
        var promomodal = $("#imgPromoModal");
        var span = $(".close");

        span.on("click", function () {
            promomodal.css("display", "none");
        });
    });

    function showPromos() {
        var my_promos = JSON.parse(helper.decodeEntities(promos));

        if(my_promos.length == 0) {
            $('.promo-list').hide();
            $('.no-promos-found').show();
            return false;
        }

        $('.no-promos-found').hide();
        $('.promo-list').html('');
        $('.promo-list').html('<div class="owl-carousel owl-theme owl-wrapper-promo"></div>');
        $.each(my_promos, function(key,promos) {
            var promo_element = '';
            promo_element += '<div class="item">';
            promo_element += '<div class="carousel-content-container">';
            promo_element += '<div class="row item-row-'+key+'">';
            promo_element += '</div>';
            promo_element += '</div>';
            promo_element += '</div>';
            $( ".owl-wrapper-promo" ).append(promo_element);

            $.each(promos, function(index,promo) {
                var promo_item = '';
                promo_item += '<div class="col-sm-4">';
                promo_item += '<div class="card border-0 bg-transparent img-promo-card">';
                promo_item += '<img type="button" class="promo-img promo_img_'+ promo.promo_id +'" src="'+ promo.image_url +'">';
                promo_item += '<div class="d-block">';
                promo_item += '<p class="promo-store tenants_tenant_store_'+ promo.promo_id +'">'+ promo.brand_name +'</p>';
                promo_item += '<p class="promo-floor">'+ promo.location +'</p>';
                promo_item += '</div>';
                promo_item += '</div>';
                promo_item += '</div>';

                $( ".item-row-"+key ).append(promo_item);
                $('.promo_img_'+promo.promo_id).on('click', function() {
                    $('.promo-modal-img').attr("src", promo.image_url);
                    $("#imgPromoModal").css("display", "block");
                });

                $('.tenants_tenant_store_'+promo.promo_id).on('click', function() {
                    showTenantDetails(promo);
                });
            });
        });

        var promo = $('.owl-wrapper-promo');
        promo.on("initialized.owl.carousel", function(e) {
            if(e.item.count == 1) {
                $('.promo-prev').hide();
                $('.promo-next').hide();
            }
            else {
                $('.promo-prev').hide();
                $('.promo-next').show();
            }
        }).owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });
        
        $('.promo-next').click(function() {
            promo.trigger('next.owl.carousel');
        })

        $('.promo-prev').click(function() {
            promo.trigger('prev.owl.carousel');
        })

        promo.on('changed.owl.carousel', function(e) {

            var first = ( !e.item.index)
            if( first ){
                $('.promo-prev').hide();
            }
            else {
                $('.promo-prev').show();
            }

            var total = e.relatedTarget.items().length - 1;
            var current = e.item.index;
            if(total == current) {
                $('.promo-next').hide();
            }
            else {
                $('.promo-next').show();
            }
            
        });

        $(".promo-list").find(".owl-dots").addClass('owl-dots-promo');

    }
</script>
@endpush