var ImageViewer = {
	img: null,
	show: function(src,btn){
		var self  = this;
		if($('body').find('.image-viewer-overlay').length == 0)
		{
			$('<div class="image-viewer-overlay"></div>').appendTo($('body'))
				.css({
				 		'position': 'fixed',
				 		'top': '0px',
				 		'z-index': 1000,
				 		'background-color': '#000000',
				 		'height': '100%',
				 		'width': '100%',
				 		'opacity': '0.80',
				});

			$('<div class="image-viewer-container"></div>').appendTo($('body'))
				.css({
					'z-index': 1001,
					'padding': '0px',
					'border': '0px solid #c0c0c0',
					'border-radius': '5px',
					'position':'fixed',
					'max-width':'80%',
					'text-align': 'center'
				}).append('<img class="image-viewer-image">');

				if(btn)
				{
					$(".image-viewer-container").append('<div class="text-center mt-2 image-viewer-container-btn"></div>');
				}

				if(btn && btn >= 1)
				{
					$(".image-viewer-container-btn").append('<button class="btn btn-light">Reserve this product</button>');
				}

				if(btn && btn == 2)
				{
					$(".image-viewer-container-btn").append('<button class="btn btn-light ml-2"><!--span class="fa fa-qrcode"></span--> Scan QR</button>');
				}
				
			$('<div class="btn-close"></div>').appendTo($(".image-viewer-container"))
			.css({'position':'absolute',
				'right':'0px',
				'top':'0px',
				'width':'26px',
				'height':'26px',
				'color': '#fff',
				'margin-top':'-30px',
				'margin-right':'-30px',
				'border-radius': '13px',
				'text-align': 'center',
				'cursor':'pointer',
				'padding-top': '2px',
				'box-shadow': '0px 0px 9px rgba(0,0,0,0.15)',
			})
			.html('<span class="fa fa-times"></span>')
			.on('click',function(){
				$(".image-viewer-overlay").hide()
				$(this).parent().hide();
			});
			//self.loadImage($('.image-viewer-container').find('.image-viewer-image').get(0),src);
		}
		$('.image-viewer-container').css({'height':'auto'});
		self.positionView();
		self.loadImage($('.image-viewer-container').find('.image-viewer-image').get(0),src);
	},
	loadImage: function(img,src){
		$('.image-viewer-container').css({'height':'auto'});
		var self = this;
		img.src = '';
		var downloadingImage = new Image();
		downloadingImage.onload = function(){
			$(img).css({'padding':'0px','max-width':'100%','max-height':'100%'});
			img.src = downloadingImage.src;
			console.log('before');
			self.positionView();
			console.log('after');
		}
		downloadingImage.src = src;
	},
	showThumbnail: function(img,src){
		var downloadingImage = new Image();
		downloadingImage.onload = function(){
			$(img).css({'padding':'0px','max-width':'100%','max-height':'100%'});
			img.src = downloadingImage.src;
		}
		downloadingImage.src = src;
	},
	positionView:function(){
		if($('.image-viewer-container').height() > $(".image-viewer-overlay").height())
		{
			$('.image-viewer-container').css({'height':$(".image-viewer-overlay").height()*.9});
		}else{
			$('.image-viewer-container').css({'height':'auto'});
		}

		//$('.image-viewer-container').css("top", Math.max(0, (($(window).height() - $('.image-viewer-container').outerHeight()) / 2) + 
        //                           $(window).scrollTop()) + "px");
       	$('.image-viewer-container').css("top", Math.max(0, (($(window).height() - $('.image-viewer-container').outerHeight()) / 2)) + "px");
		$('.image-viewer-container').css("left", Math.max(0, (($(window).width() - $('.image-viewer-container').outerWidth()) / 2)) + "px");
		$('.image-viewer-container').show();
		$('.image-viewer-overlay').show();
	}
}