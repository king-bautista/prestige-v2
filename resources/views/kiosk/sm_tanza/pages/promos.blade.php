<div class="p-3 font-weight-bold nav-titles">Promos</div>

<div class="slideshow-content-container" id="SlideShowContainer">
    <div class="swiper-container">
        <div class="swiper-wrapper">            
        </div>

        <!-- If we need pagination -->
        <div class="swiper-pagination1"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
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

    function showPromos() {
        var my_promos = JSON.parse(decodeEntities(promos));
        $( ".swiper-wrapper" ).html('');
        $.each(my_promos, function(key,promos) {
            var promo_element = '';
            promo_element += '<div class="swiper-slide">';
            promo_element += '<div class="carousel-content-container">';
            promo_element += '<div class="row row-'+key+'">';
            promo_element += '</div>';
            promo_element += '</div>';
            promo_element += '</div>';
            $( ".swiper-wrapper" ).append(promo_element);

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

                $( ".row-"+key ).append(promo_item);
                $('.promo_img_'+promo.promo_id).on('click', function() {
                    $('.promo-modal-img').attr("src", promo.image_url);
                    $("#imgPromoModal").css("display", "block");
                });

                $('.tenants_tenant_store_'+promo.promo_id).on('click', function() {
                    showTenantDetails(promo);
                });

            });

        });
    }

    showPromos();

    var swiper = new Swiper('.swiper-container', {
        pagination: {
            el: '.swiper-pagination1',
            clickable: true,
        },
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>
@endpush